<?php

namespace App\Jobs;

use App\Repositories\GunRangeRepository;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NraGunRangeFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const FEED_URL = 'https://home.nra.org/umbraco/surface/NRAAPI/PTS/?location=Chicago,%20IL&distance=4000';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
    {
        $httpClient = new Client();
        $gunRangeRepository = app()->make(GunRangeRepository::class);

        $response = $httpClient->get(self::FEED_URL);
        $items = json_decode($response->getBody(), 1);
        Log::info('IMPORT | Start Importing Nra Range Feed');
        $counter=0;
        foreach ($items as $item) {

            $data = [
                'name' => (isset($item['Name']) && $item['Name']) ? $item['Name'] : null,
                'address1' => (isset($item['Address1']) && $item['Address1']) ? $item['Address1'] : null,
                'address2' => (isset($item['Address2']) && $item['Address2']) ? $item['Address2'] : null,
                'address3' => (isset($item['Address3']) && $item['Address3']) ? $item['Address3'] : null,
                'city' => (isset($item['City']) && $item['City']) ? $item['City'] : null,
                'state' => (isset($item['State']) && $item['State']) ? $item['State'] : null,
                'zip_code' => (isset($item['ZIPCode']) && $item['ZIPCode']) ? $item['ZIPCode'] : null,
                'zip_code_2' => (isset($item['Zip2']) && $item['Zip2']) ? $item['Zip2'] : null,
                'phone' => (isset($item['Phone']) && $item['Phone']) ? $item['Phone'] : null,
                'email' => (isset($item['Email']) && $item['Email']) ? $item['Email'] : null,
                'web' => (isset($item['Web']) && $item['Web']) ? $item['Web'] : null,
                'hours' => (isset($item['Hours']) && $item['Hours']) ? $item['Hours'] : null,
                'contact_name' => (isset($item['ContactName']) && $item['ContactName']) ? $item['ContactName'] : null,
                'contact_phone' => (isset($item['ContactPhone']) && $item['ContactPhone']) ? $item['ContactPhone'] : null,
                'contact_email' => (isset($item['ContactEmail']) && $item['ContactEmail']) ? $item['ContactEmail'] : null,
                'range_category' => (isset($item['RangeCategory']) && $item['RangeCategory']) ? $item['RangeCategory'] : null,
                'range_access' => (isset($item['RangeAccess']) && $item['RangeAccess']) ? $item['RangeAccess'] : null,
                'club_number' => (isset($item['ClubNumber']) && $item['ClubNumber']) ? $item['ClubNumber'] : null,
                'comments' => (isset($item['Comments']) && $item['Comments']) ? $item['Comments'] : null,
                'facilities' => (isset($item['Facilities']) && $item['Facilities']) ? $item['Facilities'] : null,
                'latitude' => (isset($item['Latitude']) && $item['Latitude']) ? (float)$item['Latitude'] : null,
                'longitude' => (isset($item['Longitude']) && $item['Longitude']) ? (float)$item['Longitude'] : null,
                'distance' => (isset($item['Distance']) && $item['Distance']) ? (float)$item['Distance'] : null,
            ];

            $data['hash_index'] = $this->makeHashIndexForGunRangeItem($item);

            if ($gunRangeRepository->findOneByField('hash_index', $data['hash_index']) === null) {
                $gunRangeRepository->create($data);
                $counter+=1;
            }
        }
        Log::info('IMPORT | '.$counter.' Records has been imported to  Nra Range Feed');
        Log::info('IMPORT | Finish Importing Nra Range Feed');
    }

    /**
     * @param array $item
     * @return string
     */
    private function makeHashIndexForGunRangeItem(array $item)
    {
        return hash('sha512', ($item['Name'] ?? '') . ($item['Phone'] ?? '') . ($item['Latitude'] ?? ''));
    }
}
