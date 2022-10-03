<?php

namespace Webkul\CMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageBuilderSeeder extends Seeder
{
    public function run()
    {
        $home_page_content = file_get_contents(__DIR__ .'/home_page.json');
        $home_page_content = json_decode($home_page_content, true);
        $footer_content = file_get_contents(__DIR__ .'/footer.json');
        $footer_content = json_decode($footer_content, true);
        $tables_prefix = config('pagebuilder.storage.database.prefix');
        $is_multi_saas = phpb_config('general.is_multi_saas');
        $pages = [];
        if ($is_multi_saas) {
            $companies = DB::table('companies')->get();
            foreach ($companies as $company) {
                $pages[] = [
                    'name' => 'home',
                    'layout' =>'merchant_home',
                    'meta' => '{"type":"home"}',
                    'data' => json_encode($home_page_content),
                    'multi_saas_id' => $company->id,
                ];
                $pages[] = [
                    'name' => 'footer',
                    'layout' => 'merchant_footer',
                    'data' => json_encode($footer_content),
                    'meta' => '{"type":"footer"}',
                    'multi_saas_id' => $company->id,
                ];
            }
        }
        else {
            $pages[] = [
                'name' => 'home',
                'layout' => 'home',
                'meta' => '{"type":"home"}',
                'data' => json_encode($home_page_content),
                'multi_saas_id' => null,
            ];
            $pages[] = [
                'name' => 'footer',
                'layout' => 'footer',
                'data' => json_encode($footer_content),
                'meta' => '{"type":"footer"}',
                'multi_saas_id' => null,
            ];
        }
        DB::table($tables_prefix . 'pages')->insert($pages);

        // create a page builder page for each of the default pages:
        if($is_multi_saas){
            $companies = DB::table('companies')->get();
            foreach ($companies as $company) {
                $defaultPages = DB::table('cms_page_translations')->where(['company_id' => $company->id])->get();
                foreach ($defaultPages as $defaultPage) {
                    $page = [
                        'name' => $defaultPage->page_title,
                        'layout' => $company->is_marketplace? 'master': 'merchant_master',
                        'data' => null,
                        'meta' => '{"type":"page"}',
                        'multi_saas_id' => $company->id,
                    ];
                    // create the page:
                    DB::table($tables_prefix . 'pages')->insert([$page]);
                    $page = DB::table($tables_prefix . 'pages')
                        ->where('multi_saas_id', $company->id)
                        ->orderBy('id', 'desc')->limit(1)->get()->first();

                    // associate the cms translation with PageBuilder page:
                    DB::table('cms_page_translations')
                        ->where(['id' => $defaultPage->id])
                        ->update(['pb_page_id' => $page->id]);

                }
            }
        }
        else {
            $defaultPages = DB::table('cms_page_translations')->get();
            foreach ($defaultPages as $defaultPage) {
                $page = [
                    'name' => $defaultPage->page_title,
                    'layout' => 'master',
                    'data' => null,
                    'meta' => '{"type":"page"}',
                    'multi_saas_id' => null,
                ];
                // create the page:
                DB::table($tables_prefix . 'pages')->insert([$page]);
                $page = DB::table($tables_prefix . 'pages')
                    ->orderBy('id', 'desc')->limit(1)->get()->first();

                // associate the cms translation with PageBuilder page:
                DB::table('cms_page_translations')
                    ->where(['id' => $defaultPage->id])
                    ->update(['pb_page_id' => $page->id]);

            }
        }
        // create translation for each PageBuilder page
        $pages = DB::table($tables_prefix . 'pages')->get();
        $translations = [];
        foreach ($pages as $page) {
            $route = str_replace(' ', '-', $page->name);
            $route = strtolower($route);
            $translations[] = [
                'page_id' => $page->id,
                'locale' => 'en',
                'title' => $page->name,
                'route' => $route,
                'multi_saas_id' => $page->multi_saas_id,
            ];
        }
        DB::table($tables_prefix . 'page_translations')->insert($translations);
    }

    protected function getMultiSaasId(){
        $multi_saas_id = phpb_config('general.multi_saas_id');
        if(function_exists($multi_saas_id)){
            $multi_saas_id = call_user_func($multi_saas_id);
        }
        return $multi_saas_id;
    }
}