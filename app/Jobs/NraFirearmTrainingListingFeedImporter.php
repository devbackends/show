<?php

namespace App\Jobs;

use App\Repositories\FirearmTrainingRepository;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NraFirearmTrainingListingFeedImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const FEED_URL = 'https://home.nra.org/umbraco/surface/NRAAPI/BFT/?location=60714&distance=4000';

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
        $firearmRepository = app()->make(FirearmTrainingRepository::class);

        $response = $httpClient->get(self::FEED_URL);
        $items = json_decode($response->getBody(), 1);
        Log::info('IMPORT | Start Importing Training Listing Feed');
        $counter=0;
        foreach ($items as $item) {
            $data = [
                'external_course_id' => (isset($item['CourseID']) && $item['CourseID']) ? $item['CourseID'] : null,
                'title' => (isset($item['Title']) && $item['Title']) ? $item['Title'] : null,
                'email' => (isset($item['Email']) && $item['Email']) ? $item['Email'] : null,
                'instructor' => (isset($item['Instructor']) && $item['Instructor']) ? $item['Instructor'] : null,
                'external_instructor_id' => (isset($item['InstructorID']) && $item['InstructorID']) ? $item['InstructorID'] : null,
                'address1' => (isset($item['Address1']) && $item['Address1']) ? $item['Address1'] : null,
                'address2' => (isset($item['Address2']) && $item['Address2']) ? $item['Address2'] : null,
                'address3' => (isset($item['Address3']) && $item['Address3']) ? $item['Address3'] : null,
                'city' => (isset($item['City']) && $item['City']) ? $item['City'] : null,
                'state' => (isset($item['State']) && $item['State']) ? $item['State'] : null,
                'zip' => (isset($item['Zip']) && $item['Zip']) ? $item['Zip'] : null,
                'contact_phone' => (isset($item['ContactPhone']) && $item['ContactPhone']) ? $item['ContactPhone'] : null,
                'contact_email' => (isset($item['ContactEmail']) && $item['ContactEmail']) ? $item['ContactEmail'] : null,
                'cost' => isset($item['Cost']) ? (float)$item['Cost'] : null,
                'idw_cost' => (isset($item['IDWCost']) && $item['IDWCost']) ? $item['IDWCost'] : null,
                'hours' => (isset($item['Hours']) && $item['Hours']) ? $item['Hours'] : null,
                'class_time' => (isset($item['ClassTime']) && $item['ClassTime']) ? $item['ClassTime'] : null,
                'gender' => (isset($item['Gender']) && $item['Gender']) ? $item['Gender'] : null,
                'offering' => (isset($item['Offering']) && $item['Offering']) ? $item['Offering'] : null,
                'spans' => (isset($item['Spans']) && $item['Spans']) ? $item['Spans'] : null,
                'granted' => (isset($item['Granted']) && $item['Granted']) ? $item['Granted'] : null,
                'publish_notes' => (isset($item['PublishNotes']) && $item['PublishNotes']) ? $item['PublishNotes'] : null,
                'date_stamp' => (isset($item['DateStamp']) && $item['DateStamp']) ? $item['DateStamp'] : null,
                'class_date' => (isset($item['ClassDate']) && $item['ClassDate']) ? $item['ClassDate'] : null,
                'approved' => (isset($item['Approved']) && $item['Approved'] && $item['Approved'] === 'Y') ? true : false,
                'zip_code' => (isset($item['ZIPCode']) && $item['ZIPCode']) ? $item['ZIPCode'] : null,
                'city_name' => (isset($item['CityName']) && $item['CityName']) ? $item['CityName'] : null,
                'latitude' => isset($item['Latitude']) ? (float)$item['Latitude'] : null,
                'longitude' => isset($item['Longitude']) ? (float)$item['Longitude'] : null,
                'is_course_blended' => (isset($item['IsCourseBlended']) && $item['IsCourseBlended'] && $item['IsCourseBlended'] === 'Y') ? true : false,
                'distance' => isset($item['Distance']) ? (float)$item['Distance'] : null,
            ];

            $data['hash_index'] = $this->makeHashIndexForFirearmTrainingItem($item);

            if ($firearmRepository->findOneByField('hash_index', $data['hash_index']) === null) {
                $firearmRepository->create($data);
                $counter+=1;
            }
        }
        Log::info('IMPORT | '.$counter.' Records has been imported to Training Listing Feed');
        Log::info('IMPORT | Finish Importing Training Listing Feed');
    }

    /**
     * @param array $item
     * @return string
     */
    private function makeHashIndexForFirearmTrainingItem(array $item)
    {
        return hash('sha512', ($item['CourseID'] ?? '') . ($item['Zip'] ?? '') . ($item['DateStamp'] ?? ''));
    }
}
