<?php

namespace Webkul\Product\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Webkul\Product\Http\Requests\ProductForm;
use Webkul\Product\Helpers\ProductType;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CategoryRepository object
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * ProductRepository object
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ProductDownloadableLinkRepository object
     *
     * @var ProductDownloadableLinkRepository
     */
    protected $productDownloadableLinkRepository;

    /**
     * ProductDownloadableSampleRepository object
     *
     * @var ProductDownloadableSampleRepository
     */
    protected $productDownloadableSampleRepository;

    /**
     * AttributeFamilyRepository object
     *
     * @var AttributeFamilyRepository
     */
    protected $attributeFamilyRepository;


    /**
     * Create a new controller instance.
     *
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     * @param ProductDownloadableLinkRepository $productDownloadableLinkRepository
     * @param ProductDownloadableSampleRepository $productDownloadableSampleRepository
     * @param AttributeFamilyRepository $attributeFamilyRepository
     * @return void
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        AttributeFamilyRepository $attributeFamilyRepository
    )
    {
        $this->_config = request('_config');

        $this->categoryRepository = $categoryRepository;

        $this->productRepository = $productRepository;

        $this->productDownloadableLinkRepository = $productDownloadableLinkRepository;

        $this->productDownloadableSampleRepository = $productDownloadableSampleRepository;

        $this->attributeFamilyRepository = $attributeFamilyRepository;


    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $families = $this->attributeFamilyRepository->all();

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamilyRepository->find($familyId);
        }

        return view($this->_config['view'], compact('families', 'configurableFamily'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Application|RedirectResponse|Response|Redirector
     * @throws ValidationException
     */
    public function store()
    {


        if (! request()->get('family')
            && ProductType::hasVariants(request()->input('type'))
            && request()->input('sku') != ''
        ) {
            return redirect(url()->current() . '?type=' . request()->input('type') . '&family=' . request()->input('attribute_family_id') . '&sku=' . request()->input('sku'));
        }

        if (ProductType::hasVariants(request()->input('type'))
            && (! request()->has('super_attributes')
            || ! count(request()->get('super_attributes')))
        ) {
            session()->flash('error', trans('admin::app.catalog.products.configurable-error'));

            return back();
        }

        $this->validate(request(), [
            'type'                => 'required',
            'attribute_family_id' => 'required',
            'sku'                 => ['required', 'unique:products,sku', new \Webkul\Core\Contracts\Validations\Slug],
        ]);



        $product = $this->productRepository->create(request()->all());


        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Product']));

        return redirect()->route($this->_config['redirect'], ['id' => $product->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $product = $this->productRepository->with(['variants'])->findOrFail($id);

        $categories = $this->categoryRepository->getCategoryTree();

        return view($this->_config['view'], compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductForm $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(ProductForm $request, $id)
    {

        $data = $request->all();

        if (!isset($data['show_on_marketplace']) || !isset($data['marketplaceCategories'])) {
            $data['marketplaceCategories'] = [];
        }
        if (!isset($data['guest_checkout'])) {
            $data['guest_checkout'] = 0;
        }
        if (!isset($data['categories'])) $data['categories'] = [];
        $data['categories'] = array_merge($data['categories'], $data['marketplaceCategories']);

        $this->productRepository->update($data, $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Product']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Uploads downloadable file
     *
     * @param $id
     * @return JsonResponse
     */
    public function uploadLink($id)
    {
        return response()->json(
            $this->productDownloadableLinkRepository->upload(request()->all(), $id)
        );
    }

    /**
     * Uploads downloadable sample file
     *
     * @param $id
     * @return JsonResponse
     */
    public function uploadSample($id)
    {
        return response()->json(
            $this->productDownloadableSampleRepository->upload(request()->all(), $id)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $allow_delete=true;
        try {
            //check if the product is ordered from a customer before delete , id yes don't allow to delete
            $orders_items=app('Webkul\Sales\Repositories\OrderItemRepository')->getOrdersItemsByProduct($id);
            if($orders_items){
                foreach ($orders_items as $orders_item){
                    $order=app('Webkul\Sales\Repositories\OrderRepository')->getOrderByOrderItem($orders_item->order_id);
                    if($order){

                        if($order->status=='processing'){$allow_delete=false;}
                    }
                }
            }

            if($allow_delete){
                $this->productRepository->delete($id);

                session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Product']));

                return response()->json(['message' => true], 200);
            }else{
                session()->flash('error', 'Product cannot be deleted because it is found in an in-process order');
                return '';
            }

        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Product']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function view($id)
    {
        $product = $this->productRepository->findOrFail($id);
        if($product->url_key){
            return redirect()->route('shop.product.index', $product->url_key);
        }else{
            if($product->parent_id){
                $parentProduct = $this->productRepository->findOrFail($product->parent_id);
                return redirect()->route('shop.product.index', $parentProduct->url_key);
            }
        }

    }

    /**
     * Mass Delete the products
     *
     * @return RedirectResponse
     */
    public function massDestroy()
    {
        $productIds = explode(',', request()->input('indexes'));

        foreach ($productIds as $productId) {
            $product = $this->productRepository->find($productId);

            if (isset($product)) {
                $this->productRepository->delete($productId);
            }
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Mass updates the products
     *
     * @return RedirectResponse|Response
     */
    public function massUpdate()
    {
        $data = request()->all();

        if (! isset($data['massaction-type'])) {
            return redirect()->back();
        }

        if (! $data['massaction-type'] == 'update') {
            return redirect()->back();
        }

        $productIds = explode(',', $data['indexes']);

        foreach ($productIds as $productId) {
            $this->productRepository->update([
                'channel' => null,
                'locale'  => null,
                'status'  => $data['update-options'],
            ], $productId);
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To be manually invoked when data is seeded into products
     *
     * @return RedirectResponse
     */
    public function sync()
    {
        Event::dispatch('products.datagrid.sync', true);

        return redirect()->route('admin.catalog.products.index');
    }

    /**
     * Result of search product.
     *
     * @return JsonResponse|View
     */
    public function productLinkSearch()
    {
        if (request()->ajax()) {
            $results = [];

            foreach ($this->productRepository->searchProductByAttribute(request()->input('query')) as $row) {
                $results[] = [
                    'id'   => $row->product_id,
                    'sku'  => $row->sku,
                    'name' => $row->name,
                ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view']);
        }
    }

     /**
     * Download image or file
     *
     * @param $productId
     * @param $attributeId
     * @return Response
     */
    public function download($productId, $attributeId)
    {
        $productAttribute = $this->productAttributeValue->findOneWhere([
            'product_id'   => $productId,
            'attribute_id' => $attributeId,
        ]);

        return Storage::download($productAttribute['text_value']);
    }

    /**
     * Search simple products
     *
     * @return JsonResponse
     */
    public function searchSimpleProducts()
    {
        return response()->json(
            $this->productRepository->searchSimpleProducts(request()->input('query'))
        );
    }
    public function getProductBaseImage($product_id){

        $product = app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['product_id'=>$product_id])->first();
        return app('Webkul\Product\Helpers\ProductImage')->getProductBaseImage($product);
    }

    public function sellerProductAdvancedSearch($seller_id){
        return app('Webkul\Product\Repositories\ProductFlatRepository')->sellerProductAdvancedSearch($seller_id);
    }

}