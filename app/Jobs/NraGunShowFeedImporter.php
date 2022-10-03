<?php

namespace App\Jobs;

use App\Repositories\ShowRepository;
use App\Repositories\ShowsPromoterRepository;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NraGunShowFeedImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const FEED_URL = 'https://home.nra.org/umbraco/surface/NRAAPI/GSC/?location=60714&distance=4000';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
    {
        $httpClient = new Client();
        $showsPromoterRepository = app()->make(ShowsPromoterRepository::class);
        $showRepository = app()->make(ShowRepository::class);

        $response = $httpClient->get(self::FEED_URL);
        $nraShows = json_decode($response->getBody(), 1);
        Log::info('IMPORT | Start Importing Nra Gunshow Feed');
        $counter=0;
        foreach ($nraShows as $show) {
            $promoter = $showsPromoterRepository->findOneByField('name', $show['Sponsor']);

            if ($promoter !== null) {

                try {
                    $showHash = $this->makeHashIndexForShow($show);

                    if ($showRepository->findOneByField('hash_index', $showHash) === null) {
                        $showData['title'] = $promoter->name;
                        $showData['dates'] = [
                            Carbon::parse($show['StartDate'])->format('d-m-Y h:i:s'),
                            Carbon::parse($show['EndDate'])->format('d-m-Y')
                        ];
                        $showData['state'] = $show['State'] ?? '';
                        $showData['city'] = trim($show['City']) ?? '';
                        $showData['location'] = trim(implode(' ', [$show['Address2'], $show['Address1']]));
                        $showData['hours'] = [
                            Carbon::parse($show['StartDate'])->format('h:i'),
                            Carbon::parse($show['EndDate'])->format('h:i')
                        ];
                        $showData['promoter_id'] = $promoter->id;
                        $showData['hash_index'] = $showHash;
                        $showRepository->create($showData);
                        $counter+=1;
                    }
                } catch (\Exception $exception) {
                    Log::error('nra_gun_show_importer', [$exception->getMessage()]);
                }
            }
        }
        Log::info('IMPORT | '.$counter.' Records has been imported to Nra Gunshow Feed');
        Log::info('IMPORT | Finish Importing Nra Gunshow Feed');
    }

    /**
     * @param array $show
     * @return string
     */
    private function makeHashIndexForShow(array $show)
    {
        return hash('sha512', $show['Sponsor'] . ($show['ShowID'] ?? ''));
    }
}
