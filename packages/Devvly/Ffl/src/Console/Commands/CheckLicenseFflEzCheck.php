<?php

namespace Devvly\Ffl\Console\Commands;

use Devvly\Ffl\Models\Ffl;
use Devvly\Ffl\Repositories\FflRepository;
use Devvly\Ffl\Services\FflEzCheck\FflEzCheck;
use Illuminate\Console\Command;

class CheckLicenseFflEzCheck extends Command
{
    protected $signature = 'ffl:ffl_ez_check';

    protected $description = 'Check all FFL\'s with ffl-ez-check';

    public function handle(FflRepository $fflRepository, FflEzCheck $fflEzCheck)
    {
        foreach ($fflRepository->all() as $ffl) {
            /** @var Ffl $ffl */
            echo $ffl->id . PHP_EOL;
            if (!$fflEzCheck->isLicenseValid($ffl->license->license_region, $ffl->license->license_district, $ffl->license->license_sequence)) {
                $ffl->update(['is_approved' => false]);
            }
        }
    }
}
