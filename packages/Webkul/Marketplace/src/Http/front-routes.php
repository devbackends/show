<?php

use Illuminate\Support\Facades\Route;
use Webkul\Marketplace\Http\Controllers\Shop\Account\DashboardController;
use Webkul\Marketplace\Http\Controllers\Shop\Account\SellerController;
use Webkul\Marketplace\Http\Controllers\Shop\Account\SellerOnboardingController;
use Webkul\Marketplace\Http\Controllers\Shop\Account\SellerUpgradeController;
use Webkul\Marketplace\Http\Controllers\Shop\Account\SettingsController;
use Webkul\Marketplace\Http\Controllers\Shop\CartController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {

    Route::get('/{url}', 'Webkul\Marketplace\Http\Controllers\Shop\SellerController@show')->defaults('_config', [
        'view' => 'marketplace::shop.sellers.profile'
    ])->name('marketplace.seller.show');

    //Marketplace routes starts here
    Route::prefix('marketplace')->group(function () {

        // Comprassion
        Route::prefix('/compare')->group(function () {
            Route::get('/count', 'Webkul\Marketplace\Http\Controllers\Shop\ComprassionController@getCount')
                ->name('marketplace.compare.count');

            Route::post('/list', 'Webkul\Marketplace\Http\Controllers\Shop\ComprassionController@getList')
                ->name('marketplace.compare.list');

            Route::post('/add', 'Webkul\Marketplace\Http\Controllers\Shop\ComprassionController@addProduct')
                ->name('marketplace.compare.add');

            Route::post('/delete', 'Webkul\Marketplace\Http\Controllers\Shop\ComprassionController@deleteProduct')
                ->name('marketplace.compare.delete');
        });

        // Wishlist
        Route::prefix('/wishlist')->group(function () {
            Route::get('/count', 'Webkul\Marketplace\Http\Controllers\Shop\WishlistController@getCount')
                ->name('marketplace.wishlist.count');

            Route::post('/list', 'Webkul\Marketplace\Http\Controllers\Shop\WishlistController@getList')
                ->name('marketplace.wishlist.list');

            Route::post('/add', 'Webkul\Marketplace\Http\Controllers\Shop\WishlistController@addProduct')
                ->name('marketplace.wishlist.add');

            Route::post('/delete', 'Webkul\Marketplace\Http\Controllers\Shop\WishlistController@deleteProduct')
                ->name('marketplace.wishlist.delete');
        });

        // Cart
        Route::prefix('/cart')->group(function () {
            Route::get('/', [CartController::class, 'get'])
                ->name('marketplace.cart.get');

            Route::post('/', [CartController::class, 'add'])
                ->name('marketplace.cart.add');

            Route::delete('/{sellerId}/{itemId}', [CartController::class, 'remove'])
                ->name('marketplace.cart.remove');

            Route::post('/update', [CartController::class, 'update'])
                ->name('marketplace.cart.update');

            Route::view('/view', 'marketplace::checkout.cart.index')
                ->name('marketplace.cart.view');

            Route::post('/add-customer-to-cart', [CartController::class, 'addCustomerToCart'])
                ->name('marketplace.cart.add-customer-to-cart');

        });

        Route::get('/start-selling', 'Webkul\Marketplace\Http\Controllers\Shop\MarketplaceController@startSelling')->defaults('_config', [
                'view' => 'marketplace::shop.home.start-selling'
            ])->name('marketplace.start-selling');

        Route::get('/category-details', 'Webkul\Marketplace\Http\Controllers\Shop\MarketplaceController@categoryDetails')
            ->name('marketplace.category.details');

        Route::post('/user-help-form', 'Webkul\Marketplace\Http\Controllers\Shop\MarketplaceController@userHelpForm')
            ->name('marketplace.userHelpForm');

        Route::view('/contact-us', 'marketplace::shop.contact-us')->name('marketplace.contact-us');

        Route::get('/', 'Webkul\Marketplace\Http\Controllers\Shop\MarketplaceController@index')->defaults('_config', [
            'view' => 'marketplace::shop.seller-central.index'
        ])->name('marketplace.seller_central.index');

        Route::get('/gun-shows', 'Webkul\Marketplace\Http\Controllers\Shop\ShowController@index')->defaults('_config', [
            'view' => 'marketplace::shop.shows.index'
        ])->name('marketplace.shows.index');

        Route::get('/shows/api', 'Webkul\Marketplace\Http\Controllers\Shop\ShowController@apiShows')->defaults('_config', [])->name('marketplace.shows.api');

        Route::get('/gun-show/{state}/{title}', 'Webkul\Marketplace\Http\Controllers\Shop\ShowController@get')->defaults('_config', [
            'view' => 'marketplace::shop.shows.show'
        ])->name('marketplace.shows.get');

        Route::get('/gun-show-promoter/{name}', 'Webkul\Marketplace\Http\Controllers\Shop\ShowController@getPromoter')->defaults('_config', [
            'view' => 'marketplace::shop.shows.promoter'
        ])->name('marketplace.shows.promoters.get');

        Route::post('/gun-show-promoter/contact/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\ShowController@contact')
            ->name('marketplace.shows.promoters.contact');

        Route::get('/community', 'Webkul\Marketplace\Http\Controllers\Shop\MarketplaceController@communityIndex')->name('marketplace.community');

        Route::get('/certified-firearm-instructors', 'Webkul\Marketplace\Http\Controllers\Shop\InstructorController@index')->defaults('_config', [
            'view' => 'marketplace::shop.instructors.index'
        ])->name('marketplace.instructors.index');

        Route::get('/instructors', 'Webkul\Marketplace\Http\Controllers\Shop\InstructorController@index')->defaults('_config', [
            'view' => 'marketplace::shop.instructors.instructors'
        ])->name('marketplace.instructors.instructors');

        Route::get('/clubs-and-associations', 'Webkul\Marketplace\Http\Controllers\Shop\ClubController@index')->defaults('_config', [
            'view' => 'marketplace::shop.clubs.index'
        ])->name('marketplace.clubs.index');

        Route::get('/find-ffl', 'Webkul\Marketplace\Http\Controllers\Shop\FflController@index')->defaults('_config', [
            'view' => 'marketplace::shop.ffl.index'
        ])->name('marketplace.ffl.index');

        Route::get('/ffls/api', 'Webkul\Marketplace\Http\Controllers\Shop\FflController@api')
            ->defaults('_config', [])
            ->name('marketplace.ffls.api');

        Route::get('/ffl/{state}/{name}', 'Webkul\Marketplace\Http\Controllers\Shop\FflController@getByName')->defaults('_config', [
            'view' => 'marketplace::shop.ffl.show'
        ])->name('marketplace.ffl.get');

        Route::get('/ffl/{state}/{name}/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\FflController@get')->defaults('_config', [
            'view' => 'marketplace::shop.ffl.show'
        ])->name('marketplace.ffl.get-id');

        Route::get('/clubs/api', 'Webkul\Marketplace\Http\Controllers\Shop\ClubController@api')
            ->defaults('_config', [])
            ->name('marketplace.clubs.api');

        Route::get('/clubs-and-associations/{state}/{title}', 'Webkul\Marketplace\Http\Controllers\Shop\ClubController@get')->defaults('_config', [
            'view' => 'marketplace::shop.clubs.show'
        ])->name('marketplace.clubs.get');

        Route::post('clubs/contact/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\ClubController@contact')->name('marketplace.club.contact');

        Route::get('/gun-ranges', 'Webkul\Marketplace\Http\Controllers\Shop\GunRangeController@index')->defaults('_config', [
            'view' => 'marketplace::shop.gun-ranges.index'
        ])->name('marketplace.gun-ranges.index');

        Route::get('/gun-range/{state}/{gunrange}', 'Webkul\Marketplace\Http\Controllers\Shop\GunRangeController@get')->defaults('_config', [
            'view' => 'marketplace::shop.gun-ranges.gun-range'
        ])->name('marketplace.gun-ranges.get');

        Route::post('/gun-range/contact/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\GunRangeController@contact')
            ->name('marketplace.gun-ranges.contact');

        Route::get('/gun-ranges/api', 'Webkul\Marketplace\Http\Controllers\Shop\GunRangeController@apiGunRanges')->defaults('_config', [])->name('marketplace.gun-ranges.api');

        Route::get('/instructors/api', 'Webkul\Marketplace\Http\Controllers\Shop\InstructorController@apiInstructors')->defaults('_config', [])->name('marketplace.instructors.api');

        Route::get('/certified-firearm-instructor/{state}/{instructor}', 'Webkul\Marketplace\Http\Controllers\Shop\InstructorController@get')->defaults('_config', [
            'view' => 'marketplace::shop.instructors.instructor'
        ])->name('marketplace.instructors.get');

        Route::post('/certified-firearm-instructor/contact/{instructorId}', 'Webkul\Marketplace\Http\Controllers\Shop\InstructorController@contact')
            ->name('marketplace.instructors.contact');

        Route::post('seller/url', 'Webkul\Marketplace\Http\Controllers\Shop\SellerController@checkShopUrl')->name('marketplace.seller.url');

        Route::post('seller/{url}/contact', 'Webkul\Marketplace\Http\Controllers\Shop\SellerController@contact')->name('marketplace.seller.contact');


        //Seller Review routes
        Route::get('seller/{url}/reviews', 'Webkul\Marketplace\Http\Controllers\Shop\ReviewController@index')->defaults('_config', [
            'view' => 'marketplace::shop.sellers.reviews.index'
        ])->name('marketplace.reviews.index');


        // Auth Routes
        Route::group(['middleware' => ['customer']], function () {

            //Seller Review routes
            Route::get('seller/{url}/reviews/orders/create', 'Webkul\Marketplace\Http\Controllers\Shop\ReviewController@create')->defaults('_config', [
                'view' => 'marketplace::shop.sellers.reviews.create'
            ])->name('marketplace.reviews.create');

            Route::post('seller/{url}/reviews/create', 'Webkul\Marketplace\Http\Controllers\Shop\ReviewController@store')->defaults('_config', [
                'redirect' => 'marketplace.seller.show'
            ])->name('marketplace.reviews.store');


            //Marketplace seller account
            Route::prefix('account')->group(function () {

                // Seller OnBoarding
                Route::prefix('/onboarding')->group(function () {
                    Route::get('/start', [SellerOnboardingController::class, 'start'])->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.profile.create'
                    ])->name('marketplace.account.seller.create');

                    Route::get('/shipping-methods', [SellerOnboardingController::class, 'getShippingMethods']);
                    Route::get('/plan', [SellerOnboardingController::class, 'getSellerPlans']);
                    Route::get('/tokenizer-info', [SellerOnboardingController::class, 'getTokenizerInfo']);

                    Route::prefix('/store')->group(function () {
                        Route::post('/shop-info', [SellerOnboardingController::class, 'storeSellerShopInfo']);
                        Route::post('/shipping-info', [SellerOnboardingController::class, 'storeSellerShippingInfo']);
                        Route::post('/plan', [SellerOnboardingController::class, 'storeSellerPlan']);
                        Route::post('/payments', [SellerOnboardingController::class, 'storeSellerPayments']);
                        Route::get('/', [SellerOnboardingController::class, 'storeSeller'])
                            ->name('marketplace.account.seller.onboarding.submit');
                    });

                    Route::view('/success', 'marketplace::shop.sellers.account.profile.onboarding-success')
                        ->name('marketplace.account.seller.onboarding.success');
                });

                Route::prefix('upgrade')->group(function () {
                    Route::get('/', [SellerUpgradeController::class, 'index'])->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.profile.upgrade'
                    ])->name('marketplace.account.seller.upgrade.index');
                    Route::get('/submit', [SellerUpgradeController::class, 'submit'])->name('marketplace.account.seller.upgrade.submit');
                });

                Route::get('edit', [SellerController::class, 'edit'])->defaults('_config', [
                    'view' => 'marketplace::shop.sellers.account.profile.edit'
                ])->name('marketplace.account.seller.edit');

                Route::put('edit/{id}', [SellerController::class, 'update'])->defaults('_config', [
                    'redirect' => 'marketplace.account.seller.edit'
                ])->name('marketplace.account.seller.update');

                // Dashboard route
                Route::get('dashboard', [DashboardController::class, 'index'])->defaults('_config', [
                    'view' => 'marketplace::shop.sellers.account.dashboard.index'
                ])->name('marketplace.account.dashboard.index');

                // Catalog Routes
                Route::prefix('catalog')->group(function () {


                    // Catalog Product Routes
                    Route::get('/products', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@index')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.catalog.products.index'
                    ])->name('marketplace.account.products.index');

                    Route::get('/products/create', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@create')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.catalog.products.create'
                    ])->name('marketplace.account.products.create');

                    Route::post('/products/create', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@store')->defaults('_config', [
                        'redirect' => 'marketplace.account.products.edit'
                    ])->name('marketplace.account.products.store');

                    Route::get('/products/edit/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@edit')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.catalog.products.edit'
                    ])->name('marketplace.account.products.edit');

                    Route::put('/products/edit/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@update')->defaults('_config', [
                        'redirect' => 'marketplace.account.products.index'
                    ])->name('marketplace.account.products.update');


                    Route::get('/products/delete/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@destroy')->name('marketplace.account.products.delete');

                    Route::post('products/massdelete', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@massDestroy')->defaults('_config', [
                        'redirect' => 'marketplace.account.products.index'
                    ])->name('marketplace.account.products.massdelete');

                    Route::post('/products/add-super-attributes/{product_id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@addSuperAttributes')->defaults('_config', [
                        'redirect' => 'marketplace.account.products.edit'
                    ])->name('marketplace.account.products.add-super-attributes');

                    Route::post('/products/generate-variants/{product_id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@generateVariants')->defaults('_config', [
                        'redirect' => 'marketplace.account.products.edit'
                    ])->name('marketplace.account.products.generate-variants');

                    Route::get('/products/get-variants/{product_id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@getVariants')->defaults('_config', [
                    ])->name('marketplace.account.products.get-variants');

                    Route::post('/products/remove-image', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@removeProductImage')->name('marketplace.product.remove-image');

                    Route::post('/products/add-image', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@addProductImage')->name('marketplace.product.add-image');

                    Route::post('/products/sort-images-order', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@sortImagesOrder')->name('marketplace.product.sort-images-order');

                    Route::get('/products/get-attribute-options/{attribute_id}', 'Webkul\Attribute\Http\Controllers\AttributeController@getAttributeOptions')->name('marketplace.attributes.get-attribute-options');

                    Route::get('/products/validate-url-key', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@validateProductUrl')->name('marketplace.product.validate-product-url');

                    Route::post('/products/suggest-by-seller', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ProductController@suggestBySeller')->name('marketplace.product.suggest-by-seller');

                });


                // Sales routes
                Route::prefix('sales')->group(function () {
                    Route::get('orders', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\OrderController@index')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.orders.index'
                    ])->name('marketplace.account.orders.index');

                    Route::get('orders/view/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\OrderController@view')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.orders.view'
                    ])->name('marketplace.account.orders.view');

                    Route::get('orders/get/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\OrderController@get')
                        ->name('marketplace.account.orders.get');

                    Route::get('/orders/cancel/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\OrderController@cancel')->name('marketplace.account.orders.cancel');

                    Route::get('/orders/pay-cashsale/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\OrderController@payCashsale')
                        ->defaults('_config', [
                            'view' => 'marketplace::shop.sellers.account.sales.orders.pay-cashsale'
                        ])
                        ->name('marketplace.account.orders.pay-cashsale.create');

                    Route::post('/orders/pay-cashsale/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\OrderController@payCashsaleStore')
                        ->name('marketplace.account.orders.pay-cashsale.store');

                    // Sales Invoices Routes
                    Route::get('invoices/create/{order_id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\InvoiceController@create')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.invoices.create'
                    ])->name('marketplace.account.invoices.create');

                    Route::post('invoices/create/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\InvoiceController@store')->defaults('_config', [
                        'redirect' => 'marketplace.account.orders.view'
                    ])->name('marketplace.account.invoices.store');

                    // Sales Refunds Routes
                    Route::get('refund/create/{order_id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\RefundController@create')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.refunds.create'
                    ])->name('marketplace.account.refunds.create');

                    Route::post('refund/create/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\RefundController@store')->defaults('_config', [
                        'redirect' => 'marketplace.account.orders.view'
                    ])->name('marketplace.account.refunds.store');


                    Route::post('refund/update-qty/{order_id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\RefundController@updateQty')->defaults('_config', [
                        'redirect' => 'marketplace.account.orders.view'
                    ])->name('marketplace.account.refunds.update_qty');

                    Route::get('refunds', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\RefundController@index')
                        ->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.refunds.index'
                    ])->name('marketplace.account.refunds.index');

                    Route::get('refund/view/{order_id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\RefundController@view')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.refunds.view'
                    ])->name('marketplace.account.refunds.view');

                    //

                    //Prints invoice
                    Route::get('invoices/print/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\InvoiceController@print')
                        ->name('marketplace.account.invoices.print');


                    // Sales Shipments Routes
                    Route::get('shipments/create/{order_id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\ShipmentController@create')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.shipments.create'
                    ])->name('marketplace.account.shipments.create');

                    Route::post('shipments/create/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\ShipmentController@store')->defaults('_config', [
                        'redirect' => 'marketplace.account.orders.view'
                    ])->name('marketplace.account.shipments.store');


                    // Sales Transactions Routes
                    Route::get('transactions', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\TransactionController@index')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.transactions.index'
                    ])->name('marketplace.account.transactions.index');

                    Route::get('transactions/view/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\TransactionController@view')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.transactions.view'
                    ])->name('marketplace.account.transactions.view');

                    Route::get('coupons', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\CartRuleController@index')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.coupons.index'
                    ])->name('marketplace.account.coupons.index');

                    Route::get('coupons/create', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\CartRuleController@create')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.coupons.create'
                    ])->name('marketplace.account.coupons.create');

                    Route::post('coupons/create', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\CartRuleController@store')->defaults('_config', [
                        'redirect' => 'marketplace.account.coupons.index'
                    ])->name('marketplace.account.coupons.store');

                    Route::get('coupons/edit/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\CartRuleController@edit')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.sales.coupons.edit'
                    ])->name('marketplace.account.coupons.edit');

                    Route::post('coupons/edit/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\CartRuleController@update')->defaults('_config', [
                        'redirect' => 'marketplace.account.coupons.index'
                    ])->name('marketplace.account.coupons.update');

                    Route::post('coupons/delete/{id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\Sales\CartRuleController@destroy')->name('marketplace.account.coupons.delete');

                });


                // Seller review routes
                Route::get('reviews', 'Webkul\Marketplace\Http\Controllers\Shop\Account\ReviewController@index')->defaults('_config', [
                    'view' => 'marketplace::shop.sellers.account.reviews.index'
                ])->name('marketplace.account.reviews.index');

                // Store Settings route
                Route::get('/settings', [SettingsController::class, 'get'])->defaults('_config', [
                    'view' => 'marketplace::shop.sellers.account.settings.index'
                ])->name('marketplace.account.settings.index');
                Route::post('/settings', [SettingsController::class, 'store'])->name('marketplace.account.settings.store');

                //Seller messages routes
                Route::get('messages', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@index')->defaults('_config', [
                    'view' => 'marketplace::shop.sellers.account.messages.index'
                ])->name('marketplace.account.messages.index');
                // inbox messages
                Route::get('messages/inbox', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@getInboxMessages')
                    ->name('marketplace.account.messages.inbox');
                // sent messages
                Route::get('messages/sent', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@getSentMessages')
                    ->name('marketplace.account.messages.sent');
                //all messages
                Route::get('messages/all', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@getAllMessages')
                    ->name('marketplace.account.messages.all');
                //Spam messages
                Route::get('messages/spam', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@getSpamMessages')
                    ->name('marketplace.account.messages.spam');
                //Trash messages
                Route::get('messages/trash', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@getTrashMessages')
                    ->name('marketplace.account.messages.trash');
                //Unread messages
                Route::get('messages/unread', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@getUnreadMessages')
                    ->name('marketplace.account.messages.unread');
                //add-to-trash
                Route::post('messages/add-to-trash', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@addToTrash')
                    ->name('marketplace.account.messages.add-to-trash');
                //mark-as-unread
                Route::post('messages/mark-as-unread', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@markAsUnread')
                    ->name('marketplace.account.messages.mark-as-unread');
                //mark-as-read
                Route::post('messages/mark-as-read', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@markAsRead')
                    ->name('marketplace.account.messages.mark-as-read');
                //mark-as-spam
                Route::post('messages/mark-as-spam', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@markAsSpam')
                    ->name('marketplace.account.messages.mark-as-spam');
                Route::get('messages/get-message-details/{message_id}', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@getMessageDetails')
                    ->name('marketplace.account.messages.message-details');
                Route::get('messages/send-message-mail', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@sendMessageMail')->defaults('_config', [
                        'view' => 'marketplace::shop.sellers.account.messages.index'
                    ])->name('marketplace.account.messages.send-message-mail');
                Route::post('messages/send-message', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@sendMessage')
                    ->name('marketplace.account.messages.send-message');
                Route::post('messages/upload-photos', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@uploadPhotos')
                    ->name('marketplace.account.messages.upload-photos');
                Route::post('messages/report', 'Webkul\Marketplace\Http\Controllers\Shop\Account\MessageController@reportMessages')
                    ->name('marketplace.account.messages.report-messages');
                Route::view('messages-details', 'marketplace::shop.sellers.account.messages.message-item');
            });
        });

    });
    //Marketplace routes end here

 /*   Route::get('checkConfigurableProduct/{productId}', 'Webkul\Marketplace\Http\Controllers\Shop\ProductController@checkConfigurableProduct')->name('marketplace.product.checkConfigurableProduct')*/;

    //Seller review routes
    Route::get('products/{id}/offers', 'Webkul\Marketplace\Http\Controllers\Shop\ProductController@offers')->defaults('_config', [
        'view' => 'marketplace::shop.products.offers'
    ])->name('marketplace.product.offers.index');

    //error page
    Route::get('error', 'Webkul\Marketplace\Http\Controllers\Shop\MarketplaceController@error')->name('marketplace.error');

});