<?php

namespace Devvly\Ffl\Console\Commands;

use Devvly\Ffl\Models\Ffl;
use Devvly\Ffl\Repositories\FflRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddZerosToLicenseNumbers extends Command
{
    /** @var int 2th part of a license */
    const DISTRICT_LENGTH = 2;

    /** @var int 3rd part of a license */
    const FIPS_LENGTH = 3;

    /** @var int 4th part of a license */
    const TYPE_LENGTH = 2;

    /** @var int 5th part of a license */
    const SEQUENCE_LENGTH = 5;

    protected $signature = 'ffl:add_zero_to_licenses';

    protected $description = 'Filling with 0\'s FFL licenses for missed numbers';

    public function handle(FflRepository $fflRepository)
    {
        foreach ($fflRepository->all() as $ffl) {
            /** @var Ffl $ffl */
            $isUpdated = false;
            if ($this->isNeedToBeUpdated($ffl->license->license_district, self::DISTRICT_LENGTH)) {
                $isUpdated = true;
                $ffl->license->update([
                    'license_district' => $this->addZerosToLicensePart($ffl->license->license_district, self::DISTRICT_LENGTH),
                ]);
            }
            if ($this->isNeedToBeUpdated($ffl->license->license_fips, self::FIPS_LENGTH)) {
                $isUpdated = true;
                $ffl->license->update([
                    'license_fips' => $this->addZerosToLicensePart($ffl->license->license_fips, self::FIPS_LENGTH),
                ]);
            }
            if ($this->isNeedToBeUpdated($ffl->license->license_type, self::TYPE_LENGTH)) {
                $isUpdated = true;
                $ffl->license->update([
                    'license_type' => $this->addZerosToLicensePart($ffl->license->license_type, self::TYPE_LENGTH),
                ]);
            }
            if ($this->isNeedToBeUpdated($ffl->license->license_sequence, self::SEQUENCE_LENGTH)) {
                $isUpdated = true;
                $ffl->license->update([
                    'license_sequence' => $this->addZerosToLicensePart($ffl->license->license_sequence, self::SEQUENCE_LENGTH),
                ]);
            }
            if ($isUpdated) {
                $number = [
                    $ffl->license->license_region,
                    $ffl->license->license_district,
                    $ffl->license->license_fips,
                    $ffl->license->license_type,
                    $ffl->license->license_expire_date,
                    $ffl->license->license_sequence,
                ];
                $ffl->license->update([
                    'license_number' => implode('-', $number),
                ]);
            }
        }
        return 1;
    }

    public function addZerosToLicensePart(string $part, int $requiredLength): string
    {
        $amount = $requiredLength - strlen($part);
        $prepend = null;
        while ($amount > 0) {
            $amount--;
            $prepend .= '0';
        }
        return $prepend . $part;
    }

    public function isNeedToBeUpdated(string $part, string $requiredLength): bool
    {
        return strlen($part) !== $requiredLength;
    }
}