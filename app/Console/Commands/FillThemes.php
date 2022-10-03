<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class FillThemes extends Command
{
    const FREE_THEMES = [
        'default' => 'Default',
        'velocity' => 'Velocity'
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:themes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill database with default themes data and relations';

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
        foreach (self::FREE_THEMES as $key => $theme) {
            $check = DB::select('select * from themes as t where t.key = "' . $key . '"');
            if (empty($check)) {
                DB::table('themes')->insert([
                    'key' => $key,
                    'name' => $theme,
                    'default' => 1
                ]);
                $this->line('Theme - ' . $theme . ' is saved');
            } else {
                $this->line('Theme - ' . $theme . ' already exists');
            }
        }
    }
}
