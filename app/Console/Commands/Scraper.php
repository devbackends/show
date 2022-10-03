<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Scraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper {scraperName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('scraperName') === 'gunshowtrader') {
            dispatch(new \App\Jobs\Scraper());
        } elseif ($this->argument('scraperName') === 'nragunshow') {
            dispatch(new \App\Jobs\NraGunShowFeedImporter());
        } elseif ($this->argument('scraperName') === 'nragunrange') {
            dispatch(new \App\Jobs\NraGunRangeFeed());
        } elseif ($this->argument('scraperName') === 'nrafirearmtraining') {
            dispatch(new \App\Jobs\NraFirearmTrainingListingFeedImporter());
        } elseif ($this->argument('scraperName') === 'nraclubs') {
            dispatch(new \App\Jobs\NraClubsAndAssociationsFeedImporter());
        }

        $this->info('Done');

        return;
    }
}
