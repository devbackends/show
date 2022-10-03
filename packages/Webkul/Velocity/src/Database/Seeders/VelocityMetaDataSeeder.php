<?php

namespace Webkul\Velocity\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class VelocityMetaDataSeeder extends Seeder
{
    public function run()
    {
        DB::table('velocity_meta_data')->delete();
       //  @include('shop::home.advertisements.advertisement-three')  add ads to  home_page_content
        DB::table('velocity_meta_data')->insert([
            'id'                       => 1,

            'home_page_content'        => "<p>@include('shop::home.featured-products') @include('shop::home.product-policy') @include('shop::home.advertisements.advertisement-four') @include('shop::home.new-products') @include('shop::home.advertisements.advertisement-two')</p>",
            'footer_left_content'      => trans('velocity::app.admin.meta-data.footer-left-raw-content'),

            'footer_middle_content'    => '',
            'slider'                   => 1,

            'subscription_bar_content' => '<div class="social-icons col-lg-6"><a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-facebook" title="facebook"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-twitter" title="twitter"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-linked-in" title="linkedin"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-pintrest" title="Pinterest"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-youtube" title="Youtube"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-instagram" title="instagram"></i></a></div>',

            'product_policy'           => '<div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><span class="icon vun-ship-icon"></span></div> <div class="right"><span class="regular-font font-setting fs20">Free Shipping on Order $20 or More</span></div></div></div></div> <div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><span class="icon exchange-ship-icon"></span></div> <div class="right"><span class="regular-font font-setting fs20">Product Replace &amp; Return Available </span></div></div></div></div> <div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><span class="icon exchange-ship-icon"></span></div> <div class="right"><span class="regular-font font-setting fs20">Product Exchange and EMI Available </span></div></div></div></div>',
        ]);
    }
}
