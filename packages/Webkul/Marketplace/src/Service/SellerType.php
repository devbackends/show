<?php

namespace Webkul\Marketplace\Service;

use Devvly\FluidPayment\Models\FluidCustomer;
use Devvly\FluidPayment\Models\FluidCustomer as FluidCustomerModel;
use Devvly\FluidPayment\Services\FluidCharge;
use Devvly\FluidPayment\Services\FluidSubscription;
use Webkul\Marketplace\Models\Order;
use Webkul\Marketplace\Models\Seller;
use Exception;

class SellerType
{
    const DEFAULT_TYPE = 'basic';

    /**
     * @var Seller
     */
    protected $seller;

    /**
     * @var FluidCharge
     */
    protected $chargeService;

    /**
     * @var FluidCustomer
     */
    protected $subscriptionService;

    protected $customerService;

    /**
     * @var array
     */
    protected $config = [];
    protected $baseConfig = [];

    /**
     * AbstractType constructor.
     * @param Seller $seller
     * @throws Exception
     */
    public function __construct(Seller $seller)
    {
        $this->seller = $seller;

        $this->chargeService = new FluidCharge();

        $this->subscriptionService = new FluidSubscription();

        $this->customerService = new \Devvly\FluidPayment\Services\FluidCustomer();

        // Set configs
        $configs = config('seller-types');
        if (!is_array($configs)) throw new Exception('Needed config file with seller types have not been generated');
        if (isset($configs[$this->seller->type])) {
            $this->config = $configs[$this->seller->type];
        } else {
            $this->config = $configs[self::DEFAULT_TYPE];
        }
        $this->baseConfig = $configs[self::DEFAULT_TYPE];
    }

    /**
     * Do needed actions for seller type on it registration
     *
     * @param array $options
     * @return bool
     */
    public function init(array $options = []): bool
    {
        $customer = $this->customerService->getCustomer(
            $options['token'] ?? '',
            $this->seller->id ?? -1,
            $options['billingInfo'] ?? []
        );
        if (!$customer) return false;

        if ($this->config['subscription']['enabled']) { // Logic needed for plus seller

            // Update options
            $options['sellerId'] = $this->seller->id;
            $options['amount'] = $this->config['subscription']['amount'];
            $options['ip_address']= isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] :  $this->getRealIpAddr();
            $options['subscriptionType'] = $this->config['subscription']['type'];

            // Create subscription on fluid side
            return $this->subscriptionService->createSubscription($customer, $options);

        } else if ($this->config['listingFee']['enabled']) { // Logic needed for basic seller

            // Basic seller is always approved
            $customer->is_approved = 1;
            $customer->save();

            // Charge first listing fee
            return $this->listingFee(true);

        } else {

          // Always charge at least the basic initial listing fee to validate seller
          $baseFee = $this->getOptionsCharge($this->baseConfig['listingFee']['amount']);
          return $this->chargeService->charge($baseFee);
        }

        return true;
    }
    function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    /**
     * @param bool $initialCharge On customer registration we want to check if the provided card is valid,
     * thats why we want to make initial charge, and for basic seller it will be listing fee for first product,
     * so we don't need to charge listing fee of first product.
     * @param Product|null $productFlat
     * @return bool
     */
    public function listingFee($initialCharge = false, $productFlat = null): bool
    {
        if (!$this->config['listingFee']['enabled']) return true;

        if (!$initialCharge) {

            // If it is first product set it to charged and return
            $productsCount= app('Webkul\Product\Repositories\ProductFlatRepository')->findwhere(['marketplace_seller_id' => $this->seller->id,
                'is_listing_fee_charged' => 1,])->first()->count();

            // Update seller product
            $productFlat->is_listing_fee_charged = 1;
            $productFlat->save();

            if (!$productsCount) {
                return true;
            }
        }

        $options = $this->getOptionsCharge($this->config['listingFee']['amount']);

        return $this->chargeService->charge($options);
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function orderCommission(Order $order,$paymentType = ''): bool
    {

        if (!$this->config['orderComission']['enabled']) return true;

        // Calculate amount
        $percentage = $this->config['orderComission']['percentage'];
        if($paymentType){
            if($paymentType == 'fluid' || $paymentType == 'seller-fluid' || $paymentType == 'bluedog'){
                $percentage = 2;
            }else{
                $percentage = 3;
            }
        }

        $amount = round(($order->base_seller_total - $order->base_shipping_amount) / 100 * $percentage, 2);

        // Collect options
        $options = $this->getOptionsCharge($amount);

        return $this->chargeService->charge($options);
    }


    /**
     * @param $chargeAmount
     * @return array
     */
    protected function getOptionsCharge($chargeAmount): array
    {
        $fluidCustomer = FluidCustomerModel::query()->where('seller_id', '=', $this->seller->id)->first();
        if (!$fluidCustomer) return [];

        return [
            'amount' => $chargeAmount,
            'sellerPaymentInfo' => [
                'customer' => [
                    'id' => $fluidCustomer->customer_id,
                    'paymentMethodId' => $fluidCustomer->payment_method_id,
                ],
            ],
        ];
    }
}