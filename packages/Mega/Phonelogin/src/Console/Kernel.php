<?php

namespace Mega\Phonelogin\Console;

use App\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        parent::schedule($schedule);
        $schedule->command('phonelogin:clear-codes')->daily();
    }

}