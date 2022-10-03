<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Jobs\ProcessTmpQueue;
use Illuminate\Console\Command;

class TmpQueue extends Command
{
    protected $signature = 'tmp:queue';

    public function handle()
    {
        ProcessTmpQueue::dispatch()->onConnection('database')->onQueue('tmp_queue');
    }
}