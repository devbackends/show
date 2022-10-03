<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class FillRefreshThemes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:refresh-themes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add to database themes from configs';

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
        foreach (themes()->all() as $theme) {
            $check = DB::select('select * from themes as t where t.key = "' . $theme->code . '"');
            if (empty($check)) {
                DB::table('themes')->insert([
                    'key' => $theme->code,
                    'name' => $theme->name,
                    'default' => 0
                ]);
                $this->line('Theme - ' . $theme->name . ' is saved');
            } else {
                $this->line('Theme - ' . $theme->name . ' already exists');
            }
        }
    }
}
