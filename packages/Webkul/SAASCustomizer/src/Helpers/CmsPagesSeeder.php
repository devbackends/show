<?php

namespace Webkul\SAASCustomizer\Helpers;

use Webkul\Core\Repositories\LocaleRepository;
use Webkul\CMS\Repositories\CmsRepository;
use Webkul\Velocity\Repositories\VelocityMetadataRepository;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use DB;
use Company;

/**
 * Class meant for preparing functional and sample data required for functioning of a new seller
 */
class CmsPagesSeeder
{
    use AttributesList;

    /**
     * CompanyRepository instance
     */
    protected $companyRepository;

    /**
     * LocaleRepository instance
     */
    protected $localeRepository;

    /**
     * CmsRepository instance
     */
    protected $cmsRepository;

    /**
     * VelocityMetadataRepository instance
     */
    protected $velocityMetadataRepository;



    public function __construct(
        LocaleRepository $localeRepository,
        CmsRepository $cmsRepository,
        velocityMetadataRepository $velocityMetadataRepository
    ) {


        $this->localeRepository = $localeRepository;
        $this->cmsRepository = $cmsRepository;
        $this->velocityMetadataRepository = $velocityMetadataRepository;
    }
    /**
     * To prepare the cms pages data for the seller's shop
     */
    public function prepareCMSPagesData()
    {
        $companyRepository = Company::getCurrent();
        $tables_prefix = config('pagebuilder.storage.database.prefix');

        $localeRepository = $this->localeRepository->findOneWhere([
            'company_id' => $companyRepository->id
        ]);

        // About Us - PageBuilder
        DB::table($tables_prefix . 'pages')->insert([
            [
                'name' => 'about us',
                'layout' => 'merchant_master',
                'meta' => '{"type":"page"}',
                'data' => null,
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);
        $pbPage = DB::table($tables_prefix . 'pages')
            ->where(['name' => 'about us', 'multi_saas_id' => $companyRepository->id])
            ->limit(1)
            ->get()
            ->first();

        DB::table($tables_prefix . 'page_translations')->insert([
            [
                'page_id' => $pbPage->id,
                'locale' => 'en',
                'title' => $pbPage->name,
                'route' => 'about-us',
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);

        // About US
        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
        $stop = null;
        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id',
            'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'pb_page_id' => $pbPage->id,
                'url_key' => 'about-us',
                'html_content' => null,
                'page_title' => 'About Us',
                'meta_title' => 'about us',
                'meta_description' => '',
                'meta_keywords' => 'aboutus'
            ]
        ]);

        // Return Policy - PageBuilder
        DB::table($tables_prefix . 'pages')->insert([
            [
                'name' => 'return policy',
                'layout' => 'merchant_master',
                'meta' => '{"type":"page"}',
                'data' => null,
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);
        $pbPage = DB::table($tables_prefix . 'pages')
            ->where(['name' => 'return policy', 'multi_saas_id' => $companyRepository->id])
            ->limit(1)
            ->get()
            ->first();

        DB::table($tables_prefix . 'page_translations')->insert([
            [
                'page_id' => $pbPage->id,
                'locale' => 'en',
                'title' => $pbPage->name,
                'route' => 'return-policy',
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);

        // Return Policy:
        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id',
            'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'pb_page_id' => $pbPage->id,
                'url_key' => 'return-policy',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Return policy page content</div>
                                   </div>',
                'page_title' => 'Return Policy',
                'meta_title' => 'return policy',
                'meta_description' => '',
                'meta_keywords' => 'return, policy'
            ]
        ]);

        // Refund Policy - PageBuilder
        DB::table($tables_prefix . 'pages')->insert([
            [
                'name' => 'refund policy',
                'layout' => 'merchant_master',
                'meta' => '{"type":"page"}',
                'data' => null,
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);
        $pbPage = DB::table($tables_prefix . 'pages')
            ->where(['name' => 'refund policy', 'multi_saas_id' => $companyRepository->id])
            ->limit(1)
            ->get()
            ->first();

        DB::table($tables_prefix . 'page_translations')->insert([
            [
                'page_id' => $pbPage->id,
                'locale' => 'en',
                'title' => $pbPage->name,
                'route' => 'refund-policy',
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);

        // Refund Policy:

        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id',
            'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'pb_page_id' => $pbPage->id,
                'url_key' => 'refund-policy',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Refund policy page content</div>
                                   </div>',
                'page_title' => 'Refund Policy',
                'meta_title' => 'Refund policy',
                'meta_description' => '',
                'meta_keywords' => 'refund, policy'
            ]
        ]);

        // Terms and Conditions - PageBuilder
        DB::table($tables_prefix . 'pages')->insert([
            [
                'name' => 'terms and conditions',
                'layout' => 'merchant_master',
                'meta' => '{"type":"page"}',
                'data' => null,
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);
        $pbPage = DB::table($tables_prefix . 'pages')
            ->where(['name' => 'terms and conditions', 'multi_saas_id' => $companyRepository->id])
            ->limit(1)
            ->get()
            ->first();

        DB::table($tables_prefix . 'page_translations')->insert([
            [
                'page_id' => $pbPage->id,
                'locale' => 'en',
                'title' => $pbPage->name,
                'route' => 'terms-conditions',
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);

        // Terms and Conditions
        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id',
            'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'pb_page_id' => $pbPage->id,
                'url_key' => 'terms-conditions',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Terms & conditions page content</div>
                                   </div>',
                'page_title' => 'Terms & Conditions',
                'meta_title' => 'Terms & Conditions',
                'meta_description' => '',
                'meta_keywords' => 'term, conditions'
            ]
        ]);


        // Terms of Use - PageBuilder
        DB::table($tables_prefix . 'pages')->insert([
            [
                'name' => 'terms of use',
                'layout' => 'merchant_master',
                'meta' => '{"type":"page"}',
                'data' => null,
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);
        $pbPage = DB::table($tables_prefix . 'pages')
            ->where(['name' => 'terms of use', 'multi_saas_id' => $companyRepository->id])
            ->limit(1)
            ->get()
            ->first();

        DB::table($tables_prefix . 'page_translations')->insert([
            [
                'page_id' => $pbPage->id,
                'locale' => 'en',
                'title' => $pbPage->name,
                'route' => 'terms-of-use',
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);

        // Terms of Use:
        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id',
            'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'pb_page_id' => $pbPage->id,
                'url_key' => 'terms-of-use',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Terms of use page content</div>
                                   </div>',
                'page_title' => 'Terms of use',
                'meta_title' => 'Terms of use',
                'meta_description' => '',
                'meta_keywords' => 'term, use'
            ]
        ]);

        // Contact Us - PageBuilder
        DB::table($tables_prefix . 'pages')->insert([
            [
                'name' => 'contact us',
                'layout' => 'merchant_master',
                'meta' => '{"type":"page"}',
                'data' => null,
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);
        $pbPage = DB::table($tables_prefix . 'pages')
            ->where(['name' => 'contact us', 'multi_saas_id' => $companyRepository->id])
            ->limit(1)
            ->get()
            ->first();

        DB::table($tables_prefix . 'page_translations')->insert([
            [
                'page_id' => $pbPage->id,
                'locale' => 'en',
                'title' => $pbPage->name,
                'route' => 'contact-us',
                'multi_saas_id' => $companyRepository->id,
            ]
        ]);

        // Contact Us
        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id',
            'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'pb_page_id' => $pbPage->id,
                'url_key' => 'contact-us',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Contact us page content</div>
                                   </div>',
                'page_title' => 'Contact Us',
                'meta_title' => 'Contact Us',
                'meta_description' => '',
                'meta_keywords' => 'contact, us'
            ]
        ]);

        Log::info("Info:- prepareCMSPagesData() created for company " . $companyRepository->domain . ".");

        return true;
    }

    /**
     * To prepare the Velocity Theme data for the tenant's shop
     */
    public function prepareVelocityData()
    {
        $companyRepository = Company::getCurrent();
        /*  @include('shop::home.advertisements.advertisement-three')    //   add ads to home_page_content */
        $data = [
            'company_id' => $companyRepository->id,
            'path_hero_image' => 'default',
            'home_page_content' => "<!-- wp:custom-blocks/featured-products -->[featured_products]<!-- /wp:custom-blocks/featured-products -->",

            'footer_left_content' => trans('velocity::app.admin.meta-data.footer-left-raw-content'),

            'footer_middle_content' => '',

            'slider' => 0,

            'subscription_bar_content' => '',
            'product_policy' => '',
        ];

        Log::info("Info:- prepareVelocityData() created for company " . $companyRepository->domain . ".");

        return $this->velocityMetadataRepository->create($data);

    }



    public function addHomePageAndFooterData(){
        $json = file_get_contents(__DIR__ .'/../../../CMS/src/Database/Seeders/home_page.json');
        $home_page_content = json_decode($json, true);
        $json = file_get_contents(__DIR__ .'/../../../CMS/src/Database/Seeders/footer.json');
        $footer_content = json_decode($json, true);
        $tables_prefix = config('pagebuilder.storage.database.prefix');
        $company = Company::getCurrent();
        DB::table($tables_prefix . 'pages')->insert([
            [
                'name' => 'home',
                'layout' => 'merchant_home',
                'meta' => '{"type":"home"}',
                'data' => json_encode($home_page_content),
                'multi_saas_id' => $company->id,
            ],
            [
                'name' => 'footer',
                'layout' => 'merchant_footer',
                'data' => json_encode($footer_content),
                'meta' => '{"type":"footer"}',
                'multi_saas_id' => $company->id,
            ]
        ]);

        $homePage = DB::table($tables_prefix . 'pages')
            ->where(['name' => 'home', 'multi_saas_id' => $company->id])
            ->limit(1)
            ->get()
            ->first();
        $footer = DB::table($tables_prefix . 'pages')
            ->where(['name' => 'footer', 'multi_saas_id' => $company->id])
            ->limit(1)
            ->get()
            ->first();

        DB::table($tables_prefix . 'page_translations')->insert([
            [
                'page_id' => $homePage->id,
                'locale' => 'en',
                'title' => $homePage->name,
                'route' => $homePage->name,
                'multi_saas_id' => $company->id,
            ],
            [
                'page_id' => $footer->id,
                'locale' => 'en',
                'title' => $footer->name,
                'route' => $footer->name,
                'multi_saas_id' => $company->id,
            ]
        ]);
    }
}