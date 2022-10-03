<?php

namespace App\Jobs;

use App\Repositories\ClubRepository;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NraClubsAndAssociationsFeedImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var string  */
    private const FEED_URL = 'https://home.nra.org/umbraco/surface/NRAAPI/CLUBS/?location=60714&distance=4000';

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
        $clubRepository = app()->make(ClubRepository::class);

        $items = json_decode(
            $httpClient->get(self::FEED_URL)->getBody(),
            1
        );
        Log::info('IMPORT | Start Importing Nra clubs and associations Feed');
        $counter=0;
        foreach ($items as $item) {
            $data = [
                'type' => (isset($item['OrgType']) && $item['OrgType']) ? $item['OrgType'] : null,
                'org_desc' => (isset($item['OrgDesc']) && $item['OrgDesc']) ? $item['OrgDesc'] : null,
                'club_name' => (isset($item['ClubName']) && $item['ClubName']) ? $item['ClubName'] : null,
                'origination_order_date' => (isset($item['OriginationOrderDate']) && $item['OriginationOrderDate']) ? $item['OriginationOrderDate'] : null,
                'address_type_desc' => (isset($item['AddressTypeDesc']) && $item['AddressTypeDesc']) ? $item['AddressTypeDesc'] : null,
                'phone' => (isset($item['Phone']) && $item['Phone']) ? $item['Phone'] : null,
                'name' => (isset($item['Name']) && $item['Name']) ? $item['Name'] : null,
                'address1' => (isset($item['Address1']) && $item['Address1']) ? $item['Address1'] : null,
                'address2' => (isset($item['Address2']) && $item['Address2']) ? $item['Address2'] : null,
                'city' => (isset($item['City']) && $item['City']) ? $item['City'] : null,
                'state' => (isset($item['State']) && $item['State']) ? $item['State'] : null,
                'zip' => (isset($item['ZIPCode']) && $item['ZIPCode']) ? $item['ZIPCode'] : null,
                'country_id' => (isset($item['CountryID']) && $item['CountryID']) ? $item['CountryID'] : null,
                'country' => (isset($item['County']) && $item['County']) ? $item['County'] : null,
                'email' => (isset($item['Email']) && $item['Email']) ? $item['Email'] : null,
                'web' => (isset($item['Web']) && $item['Web']) ? $item['Web'] : null,
                'mailing_flag' => (isset($item['MailingFlag']) && $item['MailingFlag']) ? $item['MailingFlag'] : null,
                'latitude' => isset($item['Latitude']) ? (float)$item['Latitude'] : null,
                'longitude' => isset($item['Longitude']) ? (float)$item['Longitude'] : null,
                'distance' => isset($item['Distance']) ? (float)$item['Distance'] : null,
            ];

            $data['hash_index'] = $this->makeHashIndexForClubItem($item);

            if ($clubRepository->findOneByField('hash_index', $data['hash_index']) === null) {
                $clubRepository->create($data);
                $counter+=1;
            }
        }
        Log::info('IMPORT | '.$counter.' Records has benn importer to  Nra clubs and associations Feed');
        Log::info('IMPORT | Finish Importing Nra clubs and associations Feed');
    }

    /**
     * @param array $item
     * @return string
     */
    private function makeHashIndexForClubItem(array $item)
    {
        return hash('sha512', ($item['ClubName'] ?? '') . ($item['Address1'] ?? '') . ($item['Email'] ?? ''));
    }
}
