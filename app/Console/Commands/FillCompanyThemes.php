<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class FillCompanyThemes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:company-themes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill database with relations beetwen default themes and companies';

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
        $companies = DB::select('select * from companies');

        $themes = DB::select('select * from themes as t where t.default = true');

        if (empty($themes)) {
            $this->line('Default themes is not exists - please run "php artisan fill:themes"');
            return;
        }

        foreach ($companies as $company) {
            foreach ($themes as $theme) {
                if (empty($check)) {
                    DB::table('company_themes')->insert([
                        'theme_id' => $theme->id,
                        'company_id' => $company->id,
                        'is_active' => 1
                    ]);
                    $this->line('Theme - ' . $theme->name . ' is saved for company with id: ' . $company->id);
                } else {
                    $this->line('Theme - ' . $theme->name . ' already exists for company with id: ' . $company->id);
                }
            }
        }
    }
}
