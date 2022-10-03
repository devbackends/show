<?php

namespace Webkul\API\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class CacheProductsFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        // todo: use the queue with db driver, run queue worker with supervisor
        // clear the products cache and recreate it
        // delete the cache if exists
        $exist = Storage::disk('wassabi_private')->exists('wikiarms_feed.json');
        if($exist){
            $file = Storage::disk('wassabi_private')->delete('wikiarms_feed.json');
        }
        // run the command to get the products and cache them:
        #Artisan::call('wikiarmsfeed:cache');
    }
}
