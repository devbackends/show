<?php

namespace Webkul\RMA\Http\Controllers\Customer;

use Illuminate\Support\Facades\Mail;
use Webkul\RMA\Repositories\RMARepository;
use Webkul\RMA\Http\Controllers\Controller;
use Webkul\RMA\Mail\CustomerRmaCreationEmail;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\RMA\Mail\CustomerConversationEmail;
use Webkul\RMA\Repositories\RMAItemsRepository;
use Webkul\RMA\Repositories\RMAImagesRepository;
use Webkul\RMA\Repositories\RMAReasonsRepository;
use Webkul\RMA\Repositories\RMAMessagesRepository;
use Webkul\Sales\Repositories\OrderItemRepository;

class CustomerController extends Controller
{
    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

    /**
     * RMARepository object
     *
     * @var object
     */
    protected $rmaRepository;

    /**
     * RMAItemsRepository object
     *
     * @var object
     */
    protected $rmaItemsRepository;

    /**
     * RMAReasonsRepository object
     *
     * @var object
     */
    protected $rmaReasonRepository;

    /**
     * OrderItemRepository object
     *
     * @var object
     */
    protected $orderItemRepository;

    /**
     * RMAMessagesRepository object
     *
     * @var object
     */
    protected $rmaMessagesRepository;

    /**
     * RMAImagesRepository object
     *
     * @var object
     */
    protected $rmaImagesRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Sales\Repositories\OrderRepository  $orderRepository
     *
     * @return void
     */

    public function __construct(
        RMARepository $rmaRepository,
        OrderRepository $orderRepository,
        RMAItemsRepository $rmaItemsRepository,
        OrderItemRepository $orderItemRepository,
        RMAImagesRepository $rmaImagesRepository,
        RMAReasonsRepository $rmaReasonRepository,
        RMAMessagesRepository $rmaMessagesRepository
    ) {
        $this->isGuest = 1;

        if (auth()->guard('customer')->user()) {
            $this->isGuest = 0;
            $this->middleware('customer');
        }

        $this->_config = request('_config');

        $this->rmaRepository = $rmaRepository;
        $this->orderRepository = $orderRepository;
        $this->rmaItemsRepository = $rmaItemsRepository;
        $this->rmaReasonRepository = $rmaReasonRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->rmaImagesRepository = $rmaImagesRepository;
        $this->rmaMessagesRepository = $rmaMessagesRepository;
    }

    /**
     * Method to populate the Customer RMA index page which will be populated.
     *
     * @return Mixed
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * get details of RMA
     *
     * @param integer
     * return $value
     */
    public function view($id)
    {
        if (auth()->guard('customer')->user()) {
            $isGuest = 0;
            $rmaOrder = $this->rmaRepository->find($id)->order_id;

            $guestDetails = $this->orderRepository->findOneWhere([
                'is_guest'      => 0,
                'id'            => $rmaOrder,
                'customer_id'   => auth()->guard('customer')->user()->id,
            ]);

            if ($guestDetails) {
                $customerLastName = $guestDetails->customer_last_name;
                $customerFirstName = $guestDetails->customer_first_name;
            } else {
                return redirect()->route('rma.customers.allrma');
            }

            session()->forget('guestEmailId');
        } else {
            $isGuest = 1;

            if (! $this->rmaRepository->find($id)) {
                return redirect()->route('customer.session.index');
            }

            $guestDetails = $this->orderRepository->findOneWhere(['id' => session()->get('guestOrderId'),'customer_email' => session()->get('guestEmailId'),'is_guest'=>1]);

            $rmaOrder = $this->rmaRepository->find($id)->order_id;

            $guestDetails = $this->orderRepository->findWhere(['id' => $rmaOrder,'customer_email' => session()->get('guestEmailId'),'is_guest' => 1])->first();

            if (is_null($guestDetails)) {
                return redirect()->route('customer.session.index');
            }

            $customerLastName = $guestDetails->customer_last_name;
            $customerFirstName = $guestDetails->customer_first_name;

            if(! isset($guestDetails))  {
                $guestDetails = NULL;
            }
        }

        if (is_null($guestDetails)) {
            abort('404');
        }

        $rmaData = $this->rmaRepository->with('orderItem')->findOneWhere([ 'id'=> $id ]);

        $rmaImages = $this->rmaImagesRepository->findWhere(['rma_id' => $id]);

        $customer = auth()->guard('customer')->user();

        $reasons = $this->rmaItemsRepository->with('getReasons')->findWhere(['rma_id' => $id]);

        $productDetails = $this->rmaItemsRepository->with('getOrderItem')->findWhere(['rma_id' => $id]);

        $orderItemsRMA = $this->rmaItemsRepository->findWhere(['rma_id' => $rmaData['id']]);

        $order = $this->orderRepository->findOrFail($rmaData['order_id']);

        $ordersItem = $order->items;

        foreach ($orderItemsRMA as $orderItemRMA) {
            $orderItem[] = $orderItemRMA->id;
        }

        $orderData = $ordersItem->whereIn('id',$orderItem);

        foreach($order->items as $key => $configurableProducts) {
            if ($configurableProducts['type'] == 'configurable'){
                $skus[] = $configurableProducts['child'];
            } else {
                $skus[] = $configurableProducts['sku'];
            }
        }

        if (! is_null($id)) {
            $messages = $this->rmaMessagesRepository
                        ->where('rma_id', $id)
                        ->get();
        }

        // get the price of each product
        foreach ($productDetails as $key => $orderItemsDetails) {
            foreach ($orderItemsDetails->getOrderItem as $productPrice) {
                $value[] = $productPrice['price'];
            }
        }

        return view($this->_config['view'], compact(
            'skus',
            'rmaData',
            'reasons',
            'isGuest',
            'messages',
            'customer',
            'rmaImages',
            'productDetails',
            'customerLastName',
            'customerFirstName'
        ));
    }

    /**
     *
     * create the RMA for tha specific Order
     *
     */
    public function create()
    {
        $guestOrderId = session()->get('guestOrderId');
        $guestEmailId = session()->get('guestEmailId');

        if (isset($guestEmailId) && isset($guestOrderId) || $this->isGuest == 1) {
            $allOrderItems = $this->orderRepository->orderBy('id', 'desc')->with('items')->findWhere([
                'id' => $guestOrderId,
                'customer_email' => $guestEmailId,
            ]);

            $orderData = $this->orderRepository->findOneWhere(
                ['id' => $guestOrderId ,
                ['status', '<>', 'canceled']]
            );

            if( ! $orderData){
                return redirect()->route('customer.session.index');
            }

            $customerEmail = $orderData->customer_email;

            $customerName = $orderData->customer_first_name." ".$orderData->customer_last_name;
        } else {
            $customer = auth()->guard('customer')->user();
            $customerEmail = $customer->email;
            $customerName = $customer->first_name . " " . $customer->last_name;

            $allOrderItems = $this->orderRepository
                ->orderBy('id','desc')
                ->with('items')
                ->findWhere([
                    'customer_id' => $customer->id,
                    ['status', '<>', 'canceled']
                ]);
        }

        $orderItems = collect();

        $defaultAllowedDays = core()->getConfigData('sales.rma.setting.default_allow_days');

        foreach ($allOrderItems as $orderItem) {
            if (!$defaultAllowedDays || ($orderItem->created_at->addDays($defaultAllowedDays)->format('Y-m-d') >= now()->format('Y-m-d'))) {
                $orderItems->push($orderItem);
            }
        }

        $reasons = $this->rmaReasonRepository->findWhere(['status'=> '1']);

        return view($this->_config['view'],compact('orderItems','customerEmail','customerName','reasons'));
    }

    /**
     * fetch the order from the specific order id
     */
    public function getProducts($orderId, $resolution)
    {
        if (gettype($resolution) == 'array') {
            //check the orderItems by selected $order_id
            $invoiceItems = app('Webkul\Sales\Repositories\InvoiceItemRepository');
            $shippmentItems = app('Webkul\Sales\Repositories\ShipmentItemRepository');
            $productImageRepository = app('Webkul\Product\Repositories\ProductImageRepository');

            $order = $this->orderRepository->findOrFail($orderId);
            $orderItems = $order->items;

            //check the product's shipment and invoice is generated or not
            foreach ($orderItems as $orderItem) {
                $itemsId[] = $orderItem['id'];
                $productId[] = $orderItem['product_id'];
            }

            $invoiceCreatedItems = $invoiceItems->findWhereIn('order_item_id', $itemsId);
            $shippedOrderItems = $shippmentItems->findWhereIn('order_item_id', $itemsId);
            $productImageCounts = $productImageRepository->findWhereIn('product_id', $productId)->count();

            foreach ($invoiceCreatedItems as $invoiceCreatedItem) {
                $invoiceCreatedItemId[] = $invoiceCreatedItem->order_item_id;
            }
            foreach ($shippedOrderItems as $shippedOrderItem) {
                $shippedOrderItemId[] = $shippedOrderItem->order_item_id;
            }

            $resolutions = ['Cancel Items'];

            if (isset($shippedOrderItemId)) {
                $resolutions = ['Return','Exchange'];

                if ( count($shippedOrderItemId) != count($itemsId)) {
                    array_push($resolutions, 'Cancel Items');
                }
            }

            if (isset($invoiceCreatedItemId)) {
                $resolutions = ['Return','Exchange'];

                if ( count($invoiceCreatedItemId) != count($itemsId)) {
                    array_push($resolutions, 'Cancel Items');
                }
            }

            return response()->json([
                'orderId'               => $orderId,
                'orderItems'            => $orderItems,
                'resolutions'           => $resolutions,
                'productImageCounts'    => $productImageCounts,
            ]);
        } else {
            $response = $this->fetchOrderDetails($orderId, $resolution);

            return $response;
        }
    }

    /**
     * fetch order details
     */
    private function fetchOrderDetails($orderId, $resolution)
    {
        $productImagesHelper = app('Webkul\Product\Helpers\ProductImage');
        $invoiceItems = app('Webkul\Sales\Repositories\InvoiceItemRepository');
        $shippmentItems = app('Webkul\Sales\Repositories\ShipmentItemRepository');
        $productrepository = app('Webkul\Product\Repositories\ProductRepository');
        $productImageRepository = app('Webkul\Product\Repositories\ProductImageRepository');

        $order = $this->orderRepository->findOrFail($orderId);

        $allOrderItems = $order->items;

        $rmaDataByOrderId = $this->rmaRepository->findWhere([
            'order_id' => $orderId,
        ]);

        $rmaId = [];
        if (count($rmaDataByOrderId) > 0) {
            foreach ($rmaDataByOrderId as $rmaDataId) {
                $rmaId[] = $rmaDataId->id;
            }
        }

        $rmaOrderItems = $this->rmaItemsRepository->findWhereIn('rma_id', $rmaId);

        $rmaOrderItemId = [];
        $countRmaOrderItems = [];

        if (! empty($rmaOrderItems)) {
            foreach ($rmaOrderItems as $key => $rmaOrderItems) {
                $rmaOrderItemId[$rmaOrderItems['order_item_id']] = $rmaOrderItems['order_item_id'];
                $countRmaOrderItems[] = $rmaOrderItems['order_item_id'];
            }
        }

        foreach ($allOrderItems as $key => $item) {
            $itemsId[] = $item->id;
        }

        foreach ($itemsId as $key => $orderItemId) {
            $orderItems[] = $this->orderItemRepository->findWhere([
                'id'        => $orderItemId,
                'order_id'  => $orderId,
            ]);
        }

        foreach ($orderItems as &$val) {
            $val = $val[0];
            unset($val[0]);
        }

        $filteredData = $orderItems;
        $checkRmaCreatedOrNot = $this->rmaItemsRepository->findWhereIn('order_item_id', $itemsId)->count();

        if ($checkRmaCreatedOrNot > 0) {
            $rmaItems = $this->rmaItemsRepository->findWhereIn('order_item_id', $itemsId);
            if (count($rmaItems) > 0) {
                foreach ($rmaItems as $rmaItem) {
                    $rmaOrderItemQty[$rmaItem->order_item_id][$rmaItem->id] = $rmaItem->quantity;
                }

                foreach ($rmaOrderItemQty as $key => $itemQty) {
                    $qtyAddedrma[$key] = array_sum($itemQty);
                    $rmaItemsId[] = $key;
                }

                foreach ($orderItems as $key => $orderItem) {
                    if (in_array ($orderItem->id,$rmaItemsId)) {
                        $qty[$orderItem->id] = $orderItem->qty_ordered - $qtyAddedrma[$orderItem->id];
                    } else {
                        $qty[$orderItem->id] = $orderItem->qty_ordered;
                    }
                }

                foreach ($orderItems as $orderItem) {
                    if ($qty[$orderItem->id] != 0) {
                        $filteredData[] = $orderItem;
                    }
                }
            }
        } else {
            foreach ($orderItems as $orderItem) {
                $qty[$orderItem->id] = $orderItem->qty_ordered;
            }
        }


        $orderItemsData = array_unique($filteredData);
        foreach ($orderItemsData as $productImages) {
            $productId[] = $productImages->product_id;
        }

        foreach ($orderItemsData as $productsImage) {
            $allProducts[] = $productrepository->find($productsImage->product_id);
        }

        foreach ($allProducts as $image) {
            $productImage[$image->id] = $productImagesHelper->getProductBaseImage($image);
        }

        $orderItemsByItemId = $this->orderItemRepository->findWhereIn('id',$itemsId);

        $html = [];
        foreach($orderItemsByItemId as $key => $configurableProducts) {
            if ($configurableProducts->type == 'configurable'){
                $additional = '';
                // $additional = $configurableProducts->getOptionDetailHtml();
                $html[$configurableProducts->id] = str_replace(',', '<br>',  $additional);

                $attributeValue = app('Webkul\RMA\Helpers\Helper')->getOptionDetailHtml($configurableProducts->additional['attributes'] ?? []);

                $child[$configurableProducts->id] = [
                    'attribute' => $attributeValue,
                    'sku'       => $configurableProducts->child->sku,
                    'name'      => $configurableProducts->child->name,
                ];
            } else {
                $child[$configurableProducts->id] = $configurableProducts->sku;
            }
        }

        $productImageCounts = $productImageRepository->findWhereIn('product_id',$productId)->count();


        $invoiceCreatedItems = $invoiceItems->findWhereIn('order_item_id',$itemsId);
        $shippedOrderItems = $shippmentItems->findWhereIn('order_item_id',$itemsId);

        $invoiceCreatedItemId = [];
        foreach ($invoiceCreatedItems as $invoiceCreatedItem) {
            $invoiceCreatedItemId[] = $invoiceCreatedItem->order_item_id;
        }

        $shippedOrderItemId = [];
        foreach ($shippedOrderItems as $shippedOrderItem) {
            $shippedOrderItemId[] = $shippedOrderItem->order_item_id;
        }

        $resolutionResponse = ['Cancel Items'];
        $orderStatus = ['Not Delivered'];

        if (! empty($shippedOrderItemId)) {
            if ( count(array_unique($shippedOrderItemId)) == count($itemsId)) {
                $resolutionResponse = ['Cancel Items'];
                $orderStatus = ['Not Delivered'];
            }
        }

        if (! empty($invoiceCreatedItemId)) {
            if ( count(array_unique($invoiceCreatedItemId)) == count($itemsId)) {
                $resolutionResponse = ['Return','Exchange'];
                $orderStatus = ['Not Delivered'];
            }
            if ( count(array_unique($invoiceCreatedItemId)) != count($itemsId)) {
                $resolutionResponse = ['Return','Exchange','Cancel Items'];
                $orderStatus = ['Not Delivered'];
            }
        }

        if (! empty($invoiceCreatedItemId) && ! empty($shippedOrderItemId)) {
            if ( count(array_unique($invoiceCreatedItemId)) == count($itemsId)) {
                $resolutionResponse = ['Return','Exchange'];
                $orderStatus = ['Not Delivered'];
            }

            if ( count(array_unique($invoiceCreatedItemId)) == count($itemsId) && count($shippedOrderItemId) == count($itemsId )) {
                $resolutionResponse = ['Return','Exchange'];
                $orderStatus = ['Not Delivered','Delivered'];
            }
        }

        $items = [];
        foreach ($orderItemsData  as $orderItemData) {
            if ($qty[$orderItemData->id] != 0) {
                $items[] =  $orderItemData;
            }
        }

        $orderData = [];
        if (! empty($invoiceCreatedItemId) || ! empty($shippedOrderItemId)) {
            if (
                (isset($resolution) && $resolution == 'Exchange')
                || ('Return' && $resolution != 'Cancel Items')
            ) {
                foreach ($items as $item) {
                    $isExisting = false;
                    foreach ($orderData as $existingData) {
                        if ($item->id == $existingData->id) {
                            $isExisting = true;
                        }
                    }

                    if (! $isExisting && in_array($item->id, $invoiceCreatedItemId)) {
                        if ($item->product->type == 'configurable') {
                            $orderData[] = $item;
                        } else if (! in_array($item->id,$shippedOrderItemId)) {
                            $orderData[] = $item;
                        } else {
                            $orderData[] = $item;
                            $orderStatus = ['Not Delivered','Delivered'];
                        }
                    }
                }
            }
        }

        if (isset($resolution) &&  $resolution == 'Cancel Items') {
            foreach ($items as $item) {
                if (! in_array($item->id, $invoiceCreatedItemId))  {

                    $isExisting = false;
                    foreach ($orderData as $existingData) {
                        if ($item->id == $existingData->id) {
                            $isExisting = true;
                        }
                    }
                    if ($item->product->type == 'configurable') {
                        $orderData[] = $item;
                    } else if (! $isExisting) {
                        $orderData[] = $item;
                    }
                }
            }

            $orderStatus = ['Not Delivered'];
        }

        if (is_null($resolution)) {
            $orderData = [];
        }

        return response()->json([
            'quantity'           => $qty,
            'html'               => $html,
            'child'              => $child,
            'itemsId'            => $itemsId,
            'orderItems'         => $orderData,
            'orderStatus'        => $orderStatus,
            'productImage'       => $productImage,
            'rmaOrderItemId'     => $rmaOrderItemId,
            'resolutions'        => $resolutionResponse,
            'productImageCounts' => $productImageCounts,
            'countRmaOrderItems' => $countRmaOrderItems,
        ]);
    }

    /**
     * Store a newly created rma.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'quantity' => 'required',
            'resolution' => 'required',
            'order_status' => 'required',
            'order_item_id' => 'required',
        ]);

        $items = [];
        $data = request()->all();

        $data['order_items'] = [];

        foreach ($data['order_item_id'] as $orderItemId) {
            $orderItem = $this->orderItemRepository
                        ->getModel()
                        ->where('id', $orderItemId)
                        ->get();

            if (isset($orderItem[0])) {
                $orderItem = $orderItem[0];

                array_push($data['order_items'], $orderItem);

                array_push($items, [
                    'order_id' => $orderItem->order_id,
                    'order_item_id' => $orderItem->id,
                ]);
            }
        }

        $orderRMAData = [
            'status'        => '',
            'order_id'      => $data['order_id'],
            'resolution'    => $data['resolution'],
            'information'   => $data['information'],
            'order_status'  => $data['order_status'],
        ];

        $rma = $this->rmaRepository->create($orderRMAData);

        $lastInsertId = \DB::getPdo()->lastInsertId();

        $imageCheck = implode(",", $data['images']);

        $data['rma_id'] = $lastInsertId;

        // insert images
        if ( ! empty($imageCheck)) {
            foreach ($data['images'] as $itemImg) {
                $this->rmaImagesRepository->create([
                    'rma_id' => $lastInsertId,
                    'path' => $itemImg->getClientOriginalName(),
                ]);
            }
        }

        // insert orderItems
        foreach ($items as $itemId) {
            $orderItemRMA = [
                'rma_id' => $lastInsertId,
                'order_item_id' => $itemId['order_item_id'],
                'quantity' => $data['quantity'][$itemId['order_item_id']],
                'rma_reason_id' => $data['rma_reason_id'][$itemId['order_item_id']]
            ];

            $rmaOrderItem = $this->rmaItemsRepository->create($orderItemRMA);
        }

        $data['reasonsData'] =  $this->rmaReasonRepository->findWhereIn('id', $data['rma_reason_id']);

        foreach($data['reasonsData'] as $reasons) {
            $data['reasons'][] = $reasons->title;
        }

        // save the images in the public path
        $this->rmaImagesRepository->uploadImages($data, $rma);

        $orderItemsRMA = $this->rmaItemsRepository->findWhere(['rma_id' => $lastInsertId]);

        $orderId = $this->rmaRepository->find($lastInsertId)->order_id;

        $order = $this->orderRepository->findOrFail($orderId);

        $ordersItem = $order->items;

        $orderItem = [];
        foreach ($orderItemsRMA as $orderItemRMA) {
            $orderItem[] = $orderItemRMA->order_item_id;
        }

        $orderData = $ordersItem->whereIn('id', $orderItem);

        foreach($orderData as $key => $configurableProducts) {
            if ($configurableProducts['type'] == 'configurable'){
                $data['skus'][] = $configurableProducts['child'];
            }
        }

        if ($rmaOrderItem) {
            try {
                Mail::queue(new CustomerRmaCreationEmail($data));
                session()->flash('success', trans('shop::app.customer.signup-form.success-verify'));
            } catch (\Exception $e) {
                session()->flash('info', trans('shop::app.customer.signup-form.success-verify-email-unsent'));
            }

            session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Request']));

            if ($this->isGuest == 1) {
                return redirect()->route('rma.customers.guestallrma');
            }

            return redirect()->route('rma.customers.allrma');
        } else {
            session()->flash('error', trans('shop::app.customer.signup-form.failed'));

            return redirect()->route('rma.customer.view');
        }
    }

    /**
     * save rma status by customer
     *
     * @param $id
     *
     * return $value
     */
    public function savestatus()
    {
        $data = request()->all();
        if (isset($data['close_rma'])) {
            $status = 1;

           $rma= $this->rmaRepository->find($data['rma_id']);
           $rma->status=$status;
            $rma->rma_status='Solved';
           $rma->save();
        }

        session()->flash('success', trans('rma::app.response.update-status', ['name' => 'Status']));

        return back();
    }

    /**
     * send message by Email
     * @param $request
     *
     * return $value
     */
    public function sendmessage()
    {
        $data = request()->all();

        $conversationDetails['adminName'] = 'Admin';
        $conversationDetails['message'] = $data['message'];
        $conversationDetails['adminEmail'] = core()->getConfigData('general.general.email_settings.admin_email');

        if (isset($this->isGuest) && $this->isGuest == 1) {
            $conversationDetails['customerEmail'] = $this->orderRepository->find(session()->get('guestOrderId'))->customer_email;
        } else {
            $conversationDetails['customerEmail'] = auth()->guard('customer')->user()->email;
        }

        $storeMessage =  $this->rmaMessagesRepository->create($data);

        if ($storeMessage) {

            try {
                Mail::queue(new CustomerConversationEmail($conversationDetails));

                session()->flash('success', trans('shop::app.customer.signup-form.success-verify'));

            } catch (\Exception $e) {

                session()->flash('info', trans('rma::app.response.send-message', ['name' => 'Message']));
            }

                session()->flash('success', trans('rma::app.response.send-message', ['name' => 'Message']));

                return redirect()->back();

        } else {
            session()->flash('error', trans('shop::app.customer.signup-form.failed'));

            return redirect()->back();
        }
        session()->flash('success', trans('rma::app.response.send-message', ['name' => 'Message']));

        return redirect()->back();
    }

    public function reopenRMA($id)
    {
        $rma = $this->rmaRepository->find($id);

        if ($rma)
            $rma->update(['rma_status' => null]);

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * login for the Guest user's
     * which is not logged in the system
     */
    public function guestLogin()
    {
        $isGuest = $this->isGuest;
        if ($isGuest == 1) {
            return view($this->_config['view']);
        } else if($isGuest == 0) {
           return redirect()->route('rma.customers.allrma');
        }
    }

    /**
     * get the requested data from the Guest
     */
    public function guestLoginCreate()
    {
        $guestUserData = request()->all();
        $checkData = $this->orderRepository->findWhere(['id' => $guestUserData['order_id'],'customer_email' => $guestUserData['email'],'is_guest'=>1])->first();

        if (isset($checkData)) {
            session()->put('guestOrderId',$guestUserData['order_id']);
            session()->put('guestEmailId',$guestUserData['email']);

            return redirect()->route('rma.customers.guestallrma')->with('guestUserData');
        } else {
            return redirect()->back()->with('error','Invalid details for guest');
        }

    }

    /**
     *
     * create the RMA for tha specific Order
     *
     */
    public function guestRMACreate()
    {
        $guestOrderId = session()->get('guestOrderId');

        $guestEmailId = session()->get('guestEmailId');

        if (isset($guestEmailId) && isset($guestOrderId) || $this->isGuest == 1) {
            $allOrderItems = $this->orderRepository->orderBy('id', 'desc')->with('items')->findWhere([ 'customer_email'=> $guestEmailId,'id' => $guestOrderId]);

            $orderData = $this->orderRepository->findOneWhere(
                ['id' => $guestOrderId ,
                ['status', '<>', 'canceled']]
            );

            if( ! $orderData){
                return redirect()->route('customer.session.index');
            }

            $customerEmail = $orderData->customer_email;

            $customerName = $orderData->customer_first_name." ".$orderData->customer_last_name;
        } else {
            $customer = auth()->guard('customer')->user();

            $customerEmail = $customer->email;

            $customerName = $customer->first_name ." ". $customer->last_name;

            $allOrderItems = $this->orderRepository
                ->orderBy('id','desc')
                ->with('items')
                ->findWhere([
                    'customer_id' => $customer->id,
                    ['status', '<>', 'canceled']
                ]);
        }

        $orderItems = collect();

        $defaultAllowedDays = core()->getConfigData('sales.rma.setting.default_allow_days');

        foreach ($allOrderItems as $orderItem) {
            if (!$defaultAllowedDays || ($orderItem->created_at->addDays($defaultAllowedDays)->format('Y-m-d') >= now()->format('Y-m-d'))) {
                $orderItems->push($orderItem);
            }
        }

        $reasons = $this->rmaReasonRepository->findWhere(['status'=> '1']);

        return view(
            $this->_config['view'],
            compact(
                'reasons',
                'orderItems',
                'customerName',
                'customerEmail'
            )
        );
    }
}
