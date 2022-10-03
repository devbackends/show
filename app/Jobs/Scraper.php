<?php

namespace App\Jobs;

use App\Repositories\ShowRepository;
use App\Repositories\ShowsPromoterRepository;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class Scraper implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const TARGET_URL = 'https://gunshowtrader.com/gun-shows/page/';

    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new \Goutte\Client();
        $showsPromoterRepository = app()->make(ShowsPromoterRepository::class);
        $showRepository = app()->make(ShowRepository::class);
        $eventsPages = [];

        Log::info('IMPORT | Fetch Show data');
        $i = 1;
        while ($client->request('GET', self::TARGET_URL . $i)->filter('.event-link')->count() !== 0) {

            $crawler = $client->request('GET', 'https://gunshowtrader.com/gun-shows/page/' . $i);

            $eventsPages[$i] = $crawler->filter('.event-link')->each(function ($node) {
                return [
                    'url' => $node->attr('href'),
                ];
            });
            ++$i;
        }
        Log::info('IMPORT | Start importing Show data');
        $counter=0;
        foreach ($eventsPages as $eventsPage) {
            foreach ($eventsPage as $link) {
                $showPage = $client->request('GET', $link['url']);
                $show = [
                    'city' => null,
                    'state' => null,
                    'title' => null,
                    'dates' => null,
                    'location' => null,
                    'hours' => null,
                    'admission' => null,
                    'promoter' => null,
                    'is_cancelled' => false
                ];

                if ($showPage->filter('.cancelled')->count() !== 0) {
                    $show['is_cancelled'] = true;
                }


                $needed = 0;
                $cityState = $showPage->filter(".event-wrap > div")->each(function ($node) use (&$needed) {
                    if ($node->text() === 'City/State' || $node->text() === 'City/Country') {
                        $needed = 100;
                    }

                    // get next element after caption element
                    if ($needed === 101) {
                        $needed++;
                        return $node->text();
                    }
                    $needed++;
                    return;
                });

                $cityState = array_first(array_filter($cityState));
                $cityStateArray = explode(',', $cityState);

                $show['city'] = $cityStateArray[0];
                $show['state'] = trim($cityStateArray[1]);

                if ($showPage->filter('.entry-title')->count() !== 0) {
                    $show['title'] = array_first($showPage->filter('.entry-title')->each(function ($node) {
                        return $node->text();
                    }));
                }

                if ($showPage->filter('.date-display')->count() !== 0) {
                    $show['dates'] = $showPage->filter('.date-display')->each(function ($node) {
                        return $node->text();
                    });
                }

                if (count($show['dates']) > 1) {
                    $show['dates'] = array_filter($show['dates'], function ($item) {
                        $isFutureYear = false;

                        if (
                            strpos($item, '2020') !== false ||
                            strpos($item, '2021') !== false ||
                            strpos($item, '2022') !== false
                        ) {
                            $isFutureYear = true;
                        }
                        return $isFutureYear;
                    });
                }

                if ($showPage->filter('.location') !== 0) {
                    $show['location'] = array_unique($showPage->filter('.location')->each(function ($node) {
                        return $node->text();
                    }));
                    $show['location'] = array_filter($show['location'], function ($item) {
                        return $item !== 'Location';
                    });

                    if (is_array($show['location'])) {
                        $show['location'] = array_first($show['location']);
                    }
                }

                list($show['latitude'], $show['longitude']) = self::findCoordinatesForLocation($show['location']);

                if ($showPage->filter('.hours') !== 0) {
                    $show['hours'] = $showPage->filter('.text.hours')->each(function ($node) {
                        return $node->text();
                    });
                }

                if ($showPage->filter('.text.admission') !== 0) {
                    $show['admission'] = $showPage->filter('.text.admission')->each(function ($node) {
                        return $node->text();
                    });

                    if (is_array($show['admission'])) {
                        $show['admission'] = array_first($show['admission']);
                    }
                }

                $promoter = array_first($showPage->filter('.organization-contact')->each(function ($node) {
                    return $node->text();
                }));

               $show['promoter']['name'] = array_first($showPage->filter('.organization-name')->each(function ($node) {
                    return $node->text();
                }));

                if (isset(explode('Phone:', $promoter)[1])) {
                    $show['promoter']['phone'] = trim(array_first(explode('Email:', explode('Phone:', $promoter)[1])));
                }

                $promoterEncodedEmail = null;
                if ($showPage->filter('.__cf_email__')->count() !== 0) {
                    $promoterEncodedEmail = $showPage->filter('.__cf_email__')->first()->attr('data-cfemail');
                }

                $show['promoter']['email'] = $promoterEncodedEmail ? $this->decodeEmail($promoterEncodedEmail) : null;

                $show['promoter']['contact'] = [];
                if (isset(explode('Contact:', $promoter)[1])) {
                    $show['promoter']['contact'] = [
                        trim(array_first(explode('Phone:', explode('Contact:', $promoter)[1])))
                    ];
                }

                if ($showPage->filter('.organization-contact > li')->count() !== 0) {
                    $contactLinks = $showPage->filter('.organization-contact > li')->each(function ($node) {
                        $item = $node->text();
                        return strpos($item, 'http') !== false ? $item : '';
                    });

                    $show['promoter']['contact'] = array_merge($show['promoter']['contact'], $contactLinks);
                }

                if (empty($show['promoter']['contact'])) {
                    $show['promoter']['contact'] = null;
                }

                if (isset($show['promoter']['phone']) && strpos($show['promoter']['phone'], 'Contact:') !== false) {
                    $show['promoter']['phone'] = trim(array_first(explode('Phone:', explode('Contact:', $show['promoter']['phone'])[0])));
                }

                if (isset($show['promoter']['phone']) && strpos($show['promoter']['phone'], 'http') !== false) {
                    $show['promoter']['phone'] = trim(array_first(explode('http', $show['promoter']['phone'])));
                }

                //save data
                $promoterHash = $this->makeHashIndexForShowsPromoter($show['promoter']);
                $show['promoter']['hash_index'] = $promoterHash;

                try {

                    if ($show['promoter']['email'] !== null) {
                        if (!$showsPromoterRepository->findOneByField('email', $show['promoter']['email']) ) {
                            $promoter = $showsPromoterRepository->firstOrCreate($show['promoter']);
                        } else {
                            $promoter = $showsPromoterRepository->findOneByField('email', $show['promoter']['email']);
                        }
                    } else {
                        if (!$showsPromoterRepository->findOneByField('hash_index', $this->makeHashIndexForShowsPromoter($show['promoter']))) {
                            $promoter = $showsPromoterRepository->firstOrCreate($show['promoter']);
                        } else {
                            $promoter = $showsPromoterRepository->findOneByField('hash_index', $this->makeHashIndexForShowsPromoter($show['promoter']));
                        }
                    }
                } catch (\Exception $exception) {
                    Log::error('show_scrape_error', array_merge($show, $link, [$exception->getMessage()]));
                }

                $show['hash_index'] = $this->makeHashIndexForShow($show);

                if ($showRepository->findOneByField('hash_index', $show['hash_index']) === null) {
                    unset($show['promoter']);
                    $show['promoter_id'] = $promoter->id;
                    $showRepository->create($show);
                    $counter+=1;
;
                }
            }
        }
        Log::info('IMPORT | '.$counter.' Records has been imported to Shows');
        Log::info('IMPORT | Finish Importing Shows');
    }

    /**
     * @param $encodedEmail
     * @return string
     */
    private function decodeEmail($encodedEmail)
    {
        $k = hexdec(substr($encodedEmail, 0, 2));

        for ($i = 2, $email = ''; $i < strlen($encodedEmail) - 1; $i += 2) {
            $email .= chr(hexdec(substr($encodedEmail, $i, 2)) ^ $k);
        }
        return $email;
    }

    /**
     * @param array $promoter
     * @return string
     */
    private function makeHashIndexForShowsPromoter(array $promoter)
    {
        return hash('sha512', $promoter['name'] . ($promoter['phone'] ?? ''));
    }

    /**
     * @param array $show
     * @return string
     */
    private function makeHashIndexForShow(array $show)
    {
        return hash('sha512', $show['title'] . $show['city'] ?? '');
    }

    /**
     * @param string $location
     * @return array
     */
    public static function findCoordinatesForLocation(string $location): array
    {
        $httpClient = new Client();
        $options = [
            'address' => $location,
            'key' => 'AIzaSyDnUKjS6BAjGhL8XL8SacAYmDQMpWEchXs'
        ];

        $response = $httpClient->get('https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query($options));
        $result = json_decode($response->getBody(), 1);
        if ($result['status'] === 'ZERO_RESULTS') {
            return [null, null];
        }

        return [
            array_first($result['results'])['geometry']['location']['lat'],
            array_first($result['results'])['geometry']['location']['lng']
        ];
    }

    /**
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @return float|int
     */
    public static function distance($lat1, $lon1, $lat2, $lon2)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);

            return ($dist * 60 * 1.1515);
        }
    }
}
