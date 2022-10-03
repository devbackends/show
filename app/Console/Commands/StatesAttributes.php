<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class StatesAttributes extends Command
{
    /**
     * Holds the execution signature of the command needed
     * to be executed for generating super user
     */
    protected $signature = 'refresh-attributes:states';

    /**
     * Will inhibit the description related to this
     * command's role
     */
    protected $description = 'Sets states options to each company';

    public function __construct()   {
        parent::__construct();
    }

    /**
     * Does the all sought of lifting required to be performed for
     * generating a super user
     */
    public function handle()
    {
        $states = DB::select('select default_name from country_states');
        $attribute = DB::select('select * from attributes where code="unavailable_states"');

        $this->warn('Step: setting states as attributes (will take a while)...');

        $i = 1;
        foreach ($states as $state) {
            $checkState = DB::select('select * from attribute_options where admin_name="' . $state->default_name . '"');
            if (!empty($checkState)) {
                DB::table('attribute_options')->insert([
                    [
                        'admin_name' => $state->default_name,
                        'sort_order' => $i,
                        'attribute_id' => $attribute[0]->id,
                    ]
                ]);

                $this->comment('Set: state - ' . $state->default_name);
                $i++;
            }
        }

        $this->comment('All states attributes is set');
    }
}