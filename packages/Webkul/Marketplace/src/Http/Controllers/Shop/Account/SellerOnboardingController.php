<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Service\SellerType;

class SellerOnboardingController extends Controller
{
    public function start()
    {
        $seller = Seller::query()->firstWhere('customer_id', auth()->guard('customer')->user()->id);

        if ($seller && $seller->is_approved) {
            return redirect()->route('marketplace.account.seller.edit');
        }

        return view($this->_config['view'], compact('seller'));
    }

    /**
     * @return JsonResponse
     */
    public function getShippingMethods(): JsonResponse
    {
        $carriers = [];
        foreach (config('carriers') as $carrier) {
            if (strpos($carrier['code'], 'mp') == false && $carrier['code'] != 'mpmultishipping' && $carrier['code'] != 'tablerate') {
                if (core()->getConfigData('sales.carriers.'.$carrier['code'].'.active') == 1) {
                    $carriers[] = $carrier;
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'shippingMethods' => $carriers,
            ],
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getSellerPlans(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'plans' => config('seller-types'),
            ],
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getTokenizerInfo(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'url' => config('services.2acommerce.gateway_url'),
                'public_key' => core()->getConfigData('sales.paymentmethods.fluid.public_key')
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeSellerShopInfo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required',
            'url' => 'required',
            'phone' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'address2' => '',
        ]);

        $seller = session()->get('seller') ?? [];
        $seller['shopInfo'] = $data;
        session()->put('seller', $seller);

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeSellerShippingInfo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'shippingMethods' => 'required|array'
        ]);

        // Get active shipping methods
        $activeShippingMethods = [];
        foreach (config('carriers') as $carrier) {
            if (strpos($carrier['code'], 'mp') == false && $carrier['code'] != 'mpmultishipping' && $carrier['code'] != 'tablerate') {
                if (core()->getConfigData('sales.carriers.'.$carrier['code'].'.active') == 1) {
                    $activeShippingMethods[] = $carrier['code'];
                }
            }
        }

        $errors = [];
        foreach ($data['shippingMethods'] as $shippingMethod) {
            if (!in_array($shippingMethod, $activeShippingMethods)) {
                $errors[] = "Shipping method {$shippingMethod} is not available";
            }
        }
        if (empty($errors)) {
            $seller = session()->get('seller') ?? [];
            $seller['shippingMethods'] = $data['shippingMethods'];
            session()->put('seller', $seller);

            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => [
                    'errors' => $errors
                ],
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeSellerPlan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'plan' => ['required', Rule::in(['basic', 'plus'])],
        ]);

        $seller = session()->get('seller') ?? [];
        $seller['plan'] = $data['plan'];
        session()->put('seller', $seller);

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeSellerPayments(Request $request): JsonResponse
    {
        $data = $request->validate([
            'paymentInfo' => 'array',
            'billingInfo' => 'array',
        ]);

        $seller = session()->get('seller') ?? [];
        $seller['paymentInfo'] = $data['paymentInfo'];
        $seller['paymentInfo']['billingInfo'] = $data['billingInfo'];
        session()->put('seller', $seller);

        return response()->json([
            'status' => 'success'
        ]);

    }

    /**
     * @return RedirectResponse|JsonResponse
     * @throws Exception
     */
    public function storeSeller()
    {
        $sellerInfo = session()->get('seller');

        DB::beginTransaction();

        // Register seller
        $seller = $this->registerSeller($sellerInfo);

        // Check payment
        $subscription = (new SellerType($seller))->init([
            'token' => $sellerInfo['paymentInfo']['token'],
            'billingInfo' => $sellerInfo['paymentInfo']['billingInfo']
        ]);

        if ($subscription) {
            DB::commit();
            session()->forget('seller');
            session()->flash('success', 'Your seller has been successfully registered');

            return response()->json([
                'status' => 'success',
                'data' => [
                    'redirectUrl' => $seller->type === 'basic'
                        ? route('marketplace.account.dashboard.index')
                        : route('marketplace.account.seller.onboarding.success')
                ],
            ]);
        } else {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
            ]);
        }
    }


    /**
     * @param array $options
     * @return Seller
     */
    protected function registerSeller(array $options): Seller
    {
        $customer = auth()->guard('customer')->user();

        $options = [
            'url' => $options['shopInfo']['url'],
            'type' => $options['plan'],
            'is_approved' => '1',
            'shop_title' => $options['shopInfo']['title'],
            'phone' => $options['shopInfo']['phone'],
            'address1' => $options['shopInfo']['address1'],
            'address2' => $options['shopInfo']['address2'],
            'city' => $options['shopInfo']['city'],
            'state' => $options['shopInfo']['state'],
            'postcode' => $options['shopInfo']['zipcode'],
            'customer_id' => $customer->id,
            'shipping_methods' => implode(',', $options['shippingMethods']),
            'payment_methods' => 'cashsale',
        ];

        return app(SellerRepository::class)->create($options);
    }

}