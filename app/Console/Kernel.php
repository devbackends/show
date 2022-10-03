<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'Devvly\DistributorImport\Console\Commands\UpdateInventory',
        'Devvly\DistributorImport\Console\Commands\Import',
        'Devvly\DistributorImport\Console\Commands\RsrDeleteProducts',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ffl:ffl_ez_check')
                  ->monthlyOn();

        $schedule->command('rsr-delete-products')
            ->dailyAt('01:00');


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
