<?php

namespace Webkul\MarketplaceSaaS\Listeners;

use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Core\Repositories\LocaleRepository;
use Illuminate\Support\Facades\Log;

/**
 * MarketplaceDataSeeder events handler
 *
 * @author  Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MarketplaceDataSeeder
{
    /**
     * CoreConfigRepository Object
     *
     * @var object
     */
    protected $coreConfigRepository;

    /**
     * LocaleRepository Object
     *
     * @var object
     */
    protected $localeRepository;

    /**
     * Create a new listener instance.
     *
     * @param  Webkul\Core\Repositories\CoreConfigRepository $coreConfigRepository
     * @param  Webkul\Core\Repositories\LocaleRepository $localeRepository
     * @return void
     */
    public function __construct(
        CoreConfigRepository $coreConfigRepository,
        LocaleRepository $localeRepository
    )
    {
        $this->coreConfigRepository = $coreConfigRepository;

        $this->localeRepository = $localeRepository;
    }

    public function handle()
    {
        $localeRepository = $this->localeRepository->first();

        $data = [
            'channel'   => core()->getCurrentChannel()->name,
            'locale'    => $localeRepository->code,
            'marketplace'    => [
                'settings'   => [
                    'general' => [
                        'seller_approval_required'  => '1',
                        'product_approval_required' => '1',
                        'commission_per_unit'       => '10',
                        'can_create_invoice'        => '0',
                        'can_create_shipment'       => '0',
                    ],
                    'landing_page' =>[
                        'page_title'            => 'Turn Your Passion Into a Business',
                        'show_banner'           => 1,
                        'banner_content'        => 'Shake hand with the most reported company known for eCommerce and the marketplace. We reached around all the corners of the world. We serve the customer with our best service experiences.',
                        'show_features'         => 1,
                        'feature_heading'       => 'Attracting Features',
                        'feature_info'          => 'Want to start an online business? Before any decision, please check our unbeatable features.',
                        'feature_icon_label_1'  => 'Generate Revenue',
                        'feature_icon_label_2'  => 'Sell Unlimited Products',
                        'feature_icon_label_3'  => 'Offer for Sellers',
                        'feature_icon_label_4'  => 'Seller Dashboard',
                        'feature_icon_label_5'  => 'Seller Order Managment',
                        'feature_icon_label_6'  => 'Seller Branding',
                        'feature_icon_label_7'  => 'Connect with Social',
                        'feature_icon_label_8'  => 'Buyer Seller Communication',
                        'show_popular_sellers'  => 1,
                        'open_shop_button_label'=> 'Open Shop Now',
                        'show_open_shop_block'  => 1,
                        'open_shop_info'        => 'Open your online shop with us and get explore the new world with more then millions of shoppers.',
                        'banner'                => 'configuration/9OGztMGb6nKUCBbF58xpNA1EShskKjoj9iUvJCrD.png',
                        'feature_icon_1'        => 'configuration/3npLBJCCEnvjtescuelWsENPEm0FzhvElzmFRWIe.png',
                        'feature_icon_2'        => 'configuration/sGtL2WxTxjFypyRMioRth0y4FRJUW6pZEYKfQXq2.png',
                        'feature_icon_3'        => 'configuration/kZZ5OSziGW3aQNVGkq4r4GL2VNTsQhVWLt62C0wb.png',
                        'feature_icon_4'        => 'configuration/cN1NGisKLyVpsn1AldCEQg8ZZCJtSbbd5zTjZGwX.png',
                        'feature_icon_5'        => 'configuration/eSHFNPfIWrw7gLffadeR4FgOgBMeQtxWWxfmB45o.png',
                        'feature_icon_6'        => 'configuration/9Iggsyrd6OElGvYHg27LKfgvgLHx3hBKTXgESxYC.png',
                        'feature_icon_7'        => 'configuration/YvJHOSJLldKpgi0MrgDNy0ookuAyXbYuAtQQI9am.png',
                        'feature_icon_8'        => 'configuration/i7dgjt2Hw5xhUdmploHWMoV0aNml3W4GjEAyZm5e.png',
                        'about_marketplace'     => '<div style="width: 100%; display: inline-block; padding-bottom: 30px;"><h1 style="text-align: center; font-size: 24px; color: rgb(36, 36, 36); margin-bottom: 40px;">Why to sell with us</h1><div style="width: 28%; float: left; padding-right: 20px;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-customize-seller-profile.png" alt="" style="width: 100%;"></div> <div style="width: 70%; float: left; text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Easily Customize your seller profile</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 70%; float: left; padding-right: 20px; text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Add Unlimited Products</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div> <div style="width: 28%; float: left;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-add-unlimited-products.png" alt="" style="width: 100%;"></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 28%; float: left; padding-right: 20px;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-connect-to-your-social-profile.png" alt="" style="width: 100%;"></div> <div style="width: 70%; float: left;text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Connect to your social profile</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 70%; float: left; padding-right: 20px; text-align: justify;"><h3 style="color: rgb(99, 99, 99); margin: 0px;">Buyer can ask you a question</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p> <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div> <div style="width: 28%; float: left;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-buyers-can-ask-a-question.png" alt="" style="width: 100%;"></div></div>',
                    ],
                    'velocity'  => [
                        'page_title'            => 'Turn Your Passion Into a Business',
                        'show_banner'           => 1,
                        'banner_content'        => 'Shake hand with the most reported company known for eCommerce and the marketplace. We reached around all the corners of the world. We serve the customer with our best service experiences.',
                        'show_features'         => 1,
                        'feature_heading'       => 'Attracting Features',
                        'feature_info'          => 'Want to start an online business? Before any decision, please check our unbeatable features.',
                        'feature_icon_label_1'  => 'Generate Revenue',
                        'feature_icon_label_2'  => 'Sell Unlimited Products',
                        'feature_icon_label_3'  => 'Offer for Sellers',
                        'feature_icon_label_4'  => 'Seller Dashboard',
                        'feature_icon_label_5'  => 'Seller Order Managment',
                        'feature_icon_label_6'  => 'Seller Branding',
                        'feature_icon_label_7'  => 'Connect with Social',
                        'feature_icon_label_8'  => 'Buyer Seller Communication',
                        'show_popular_sellers'  => 1,
                        'open_shop_button_label'=> 'Open Shop Now',
                        'show_open_shop_block'  => 1,
                        'open_shop_info'        => 'Open your online shop with us and get explore the new world with more then millions of shoppers.',
                        'banner'                => 'configuration/ftpR2CDQNERkQHzY90Rty5B66WIIQyAkRIZLaxPh.png',
                        'feature_icon_1'        => 'configuration/JKms7R5AeMr4xMpdYMFh6lY97O1c8uJxrOOYrJh2.png',
                        'feature_icon_2'        => 'configuration/EiPYH1PP8tjqHZJAGqXwePS8sqrgQu44BAnLw7Hr.png',
                        'feature_icon_3'        => 'configuration/XqCFcOKK5R7ldPWPUPaXYluQKYA63yXd9GXOAT8B.png',
                        'feature_icon_4'        => 'configuration/PiAzZru2AJ31ahCPQUBmC0ubiBijVDqPLC4agxX0.png',
                        'feature_icon_5'        => 'configuration/tY9AYRKXZaKyE1VMCpWlBALKcdzia7nCHcl3D7U0.png',
                        'feature_icon_6'        => 'configuration/gDQelR5VoHfRL4WWznGYz4ppU2rgF6UWsNhCQGsi.png',
                        'feature_icon_7'        => 'configuration/YdfbYPo8aIdup1UWaNBrQHwtOYupvknPM4UuhRDM.png',
                        'feature_icon_8'        => 'configuration/ji3oMmcj5xenj5EKyCgmiUrkRwPkF5JG3oCrNqde.png',
                        'about_marketplace'     => '<div style="width: 100%; display: inline-block; padding-bottom: 30px;"><h1 style="text-align: center; font-size: 24px; color: rgb(36, 36, 36); margin-bottom: 40px;">Why to sell with us</h1><div style="width: 28%; float: left; padding-right: 20px;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-customize-seller-profile.png" alt="" style="width: 100%;"></div> <div style="width: 70%; float: left; text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Easily Customize your seller profile</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 70%; float: left; padding-right: 20px; text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Add Unlimited Products</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div> <div style="width: 28%; float: left;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-add-unlimited-products.png" alt="" style="width: 100%;"></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 28%; float: left; padding-right: 20px;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-connect-to-your-social-profile.png" alt="" style="width: 100%;"></div> <div style="width: 70%; float: left;text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Connect to your social profile</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 70%; float: left; padding-right: 20px; text-align: justify;"><h3 style="color: rgb(99, 99, 99); margin: 0px;">Buyer can ask you a question</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p> <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div> <div style="width: 28%; float: left;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-buyers-can-ask-a-question.png" alt="" style="width: 100%;"></div></div>',
                        'setup_icon_1'          => 'configuration/HcNKzA9bPzfMtnN31ku98lvo42ijDfPGGkauO1Uv.png',
                        'setup_icon_2'          => 'configuration/LoxpyCBvnFold4XeD8c4JGScwIGtxLwOjfiyyXxU.png',
                        'setup_icon_3'          => 'configuration/7HOB5iER96qFtCpdP8JmWy6lw3QoeCY2jRKAzC6U.png',
                        'setup_icon_4'          => 'configuration/KU4TBnxTcbME3ZAwfmVRSqM1mQWuMmIdyhS17toX.png',
                        'setup_icon_5'          => 'configuration/lgj6ftKJCeCceXtjwK7k668CTnkjshKka0K5mO3w.png',
                    ]
                ]
            ]
        ];

        return $this->coreConfigRepository->create($data);
    }
}