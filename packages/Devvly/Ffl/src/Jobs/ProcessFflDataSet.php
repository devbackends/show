<?php

namespace Devvly\Ffl\Jobs;

use Devvly\Ffl\DTO\FflDataSetDto;
use Devvly\Ffl\Models\Ffl;
use Devvly\Ffl\Repositories\FflRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use LimitIterator;
use SplFileObject;
use Webkul\Core\Repositories\CountryStateRepository;

class ProcessFflDataSet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var FflDataSetDto
     */
    private $fflDataSetFile;

    /**
     * Create a new job instance.
     *
     * @param FflDataSetDto $fflDataSet
     */
    public function __construct(FflDataSetDto $fflDataSet)
    {
        $this->fflDataSetFile = $fflDataSet;
    }

    /**
     * Execute the job.
     *
     * @param FflRepository $fflRepository
     * @param CountryStateRepository $countryStateRepository
     * @return void
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function handle(FflRepository $fflRepository, CountryStateRepository $countryStateRepository)
    {
        $file = new SplFileObject(Storage::disk('local')->path(FflDataSetDto::BASE_PATH . $this->fflDataSetFile->getFileName()));
        $file->setFlags(SplFileObject::READ_CSV);
        $rows = new LimitIterator($file, 2);
        foreach ($rows as $row) {
            /** skip last empty line */
            if (empty($row[0])) {
                continue;
            }
            $licenseNumber = implode('-', [$row[0], $row[1], $row[2], $row[3], $row[4], $row[5]]);
            /** @var Ffl $ffl */
            $ffl = $fflRepository->findByLicenseNumber($licenseNumber);
            if (!$ffl) {
                $fflRepository->create(
                    array_merge(
                        $this->getFflAttributes($row),
                        $this->getBusinessInfoAttributes($row, $countryStateRepository),
                        $this->getLicenseAttributes($row, $licenseNumber)
                    )
                );
            } else {
                $fflRepository->update(
                    [
                        'ffl'          => $this->getFflAttributes($row),
                        'businessInfo' => $this->getBusinessInfoAttributes($row, $countryStateRepository),
                        'license'      => $this->getLicenseAttributes($row, $licenseNumber),
                    ], $ffl->id
                );
            }
        }
        return;
    }

    private function getFflAttributes($row): array
    {
        return [
            'is_approved' => true,
            'source'      => $row[28],
        ];
    }

    private function getBusinessInfoAttributes($row, CountryStateRepository $countryStateRepository): array
    {
        $stateId = $countryStateRepository->findByCountryAndState($row[27], $row[14]);
        $stateId = $stateId->id ?? null;
        return [
            'company_name'   => $row[7],
            'contact_name'   => $row[7],
            'street_address' => $row[8],
            'city'           => $row[9],
            'state'          => $stateId,
            'zip_code'       => $row[15],
            'phone'          => $row[16],
            'latitude'       => $row[17],
            'longitude'      => $row[18],
        ];
    }

    private function getLicenseAttributes(array $row, string $licenseNumber)
    {
        return [
            'license_number'      => $licenseNumber,
            'license_name'        => $row[6],
            'license_region'      => $row[0],
            'license_district'    => $row[1],
            'license_fips'        => $row[2],
            'license_type'        => $row[3],
            'license_expire_date' => $row[4],
            'license_sequence'    => $row[5],
        ];
    }
}
