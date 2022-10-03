<?php

namespace Webkul\Attribute\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChangeBrandAttributeSeeder extends Seeder
{
    public function run()
    {
        $domain = "http://devvlystore.app.dev-54ta5gq-vnqu3mp36aa6a.us-2.platformsh.site";
        $_SERVER['SERVER_NAME'] = $domain;

        DB::table('attributes')->where(['code' => 'brand'])->update([
            'is_user_defined' => '1'
        ]);
    }
}