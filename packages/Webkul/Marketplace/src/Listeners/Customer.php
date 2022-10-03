<?php

namespace Webkul\Marketplace\Listeners;

use Exception;
use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\User\Repositories\AdminRepository;
use Webkul\Marketplace\Mail\SellerWelcomeNotification;
use Webkul\Marketplace\Mail\SellerApprovalNotification;
use Webkul\Marketplace\Mail\NewSellerNotification;

/**
 * Customer event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Customer
{
    /**
     * SellerRepository object
     * @var Seller
     */
    protected $seller;

    /**
     * AdminRepository object
     * @var Seller
     */
    protected $admin;

    /**
     * Create a new customer event listener instance.
     * @param  Webkul\Marketplace\Repositories\SellerRepository  $seller
     * @param  Webkul\User\Repositories\AdminRepository  $admin
     * @return void
     */
    public function __construct(SellerRepository $seller, AdminRepository $admin)
    {
        $this->seller = $seller;

        $this->admin = $admin;
    }

}
