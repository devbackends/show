<?php

namespace Webkul\MarketplaceSaaS\Repositories;

use DB;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;
use Webkul\SAASCustomizer\Models\Product\ProductFlat;

/**
 * Seller Product Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;


    /**
     * ProductImageRepository object
     *
     * @var Object
     */
    protected $productImageRepository;

    /**
     * SellerRepository object
     *
     * @var Object
     */
    protected $sellerRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository      $attribute
     * @param  Webkul\Product\Repositories\ProductRepository          $productRepository
     * @param  Webkul\Marketplace\Repositories\ProductImageRepository $productImageRepository
     * @param  Webkul\Marketplace\Repositories\SellerRepository       $sellerRepository
     * @param  Illuminate\Container\Container                         $app
     * @return void
     */
    public function __construct(
        AttributeRepository $attribute,
        BaseProductRepository $productRepository,
        ProductImageRepository $productImageRepository,
        SellerRepository $sellerRepository,
        App $app
    )
    {
        $this->attribute = $attribute;

        $this->productRepository = $productRepository;

        $this->productImageRepository = $productImageRepository;

        $this->sellerRepository = $sellerRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\Product';
    }

    /**
     * @return mixed
     */
    public function create(array $data)
    {
        Event::dispatch('marketplace.catalog.product.create.before');

        $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);

        $sellerProduct = parent::create(array_merge($data, [
                'marketplace_seller_id' => $seller->id,
                'is_approved' => core()->getConfigData('marketplace.settings.general.product_approval_required') ? 0 : 1
            ]));

        foreach ($sellerProduct->product->variants as $baseVariant) {
            parent::create([
                    'parent_id' => $sellerProduct->id,
                    'product_id' => $baseVariant->id,
                    'is_owner' => 1,
                    'marketplace_seller_id' => $seller->id,
                    'is_approved' => core()->getConfigData('marketplace.settings.general.product_approval_required') ? 0 : 1
                ]);
        }

        Event::dispatch('marketplace.catalog.product.create.after', $sellerProduct);

        return $sellerProduct;
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        Event::dispatch('marketplace.catalog.product.update.before', $id);

        $sellerProduct = $this->find($id);

        foreach ($sellerProduct->product->variants as $baseVariant) {

            if (! $sellerChildProduct = $this->getMarketplaceProductByProduct($baseVariant->id)) {
                parent::create([
                        'parent_id' => $sellerProduct->id,
                        'product_id' => $baseVariant->id,
                        'is_owner' => 1,
                        'marketplace_seller_id' => $sellerProduct->seller->id,
                        'is_approved' => $sellerProduct->is_approved
                    ]);
            }
        }

        Event::dispatch('marketplace.catalog.product.update.after', $sellerProduct);

        return $sellerProduct;
    }



    /**
     * @param integer $sellerId
     * @return Collection
     */
    public function getPopularProducts($sellerId, $pageTotal = 4)
    {
        return app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($sellerId) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                $qb = $query->distinct()
                        ->addSelect('product_flat.*')
                        ->where('product_flat.visible_individually', 1)
                        ->where('product_flat.status', 1)
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key')
                        ->where('product_flat.marketplace_seller_id', $sellerId)
                        ->where('product_flat.is_seller_approved', 1)
                        ->whereIn('product_flat.product_id', $this->getTopSellingProducts($sellerId))
                        ->orderBy('id', 'desc');

                return $qb;
            })->paginate($pageTotal);
    }

    /**
     * Returns top selling products
     *
     * @param integer $sellerId
     * @return mixed
     */
    public function getTopSellingProducts($sellerId)
    {
        $seller = $this->sellerRepository->find($sellerId);

        $result = app('Webkul\Marketplace\Repositories\OrderItemRepository')->getModel()
            ->leftJoin('order_items', 'marketplace_order_items.order_item_id', 'order_items.id')
            ->Join('product_flat', 'marketplace_order_items.product_id', 'product_flat.product_id')
            ->leftJoin('marketplace_orders', 'marketplace_order_items.marketplace_order_id', 'marketplace_orders.id')
            ->select(DB::raw('SUM(qty_ordered) as total_qty_ordered'))
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->where('product_flat.is_seller_approved', 1)
            ->whereNull('order_items.parent_id')
            ->groupBy('product_flat.product_id')
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit(4)
            ->get();

        return $result->pluck('product_id')->toArray();
    }

    /**
     * Returns the total products of the seller
     *
     * @param Seller $seller
     * @return integer
     */
    public function getTotalProducts($seller)
    {
        return $seller->products()->where('is_approved', 1)->where('parent_id', NULL)->count();
    }

    /**
     * Returns the all products of the seller
     *
     * @param integer $seller
     * @return Collection
     */
    public function findAllBySeller($seller)
    {
        $company = \Company::getCurrent();

        $params = request()->input();

        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($seller, $params, $company) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                $qb = DB::table('product_flat');

                $qb = $qb->newQuery()->from('product_flat')->distinct()
                        ->addSelect('product_flat.*')
                        ->addSelect(DB::raw('IF( product_flat.special_price_from IS NOT NULL
                            AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
                            AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS price'))
                        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                        ->where('product_flat.visible_individually', 1)
                        ->where('product_flat.status', 1)
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key')
                        ->where('product_flat.marketplace_seller_id', $seller->id)
                        ->where('product_flat.is_seller_approved', 1);

                        $qb->addSelect(DB::raw(' product_flat.price AS price'));

                $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                    $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                        ->where('flat_variants.channel', $channel)
                        ->where('flat_variants.locale', $locale);
                });

                if (isset($params['sort'])) {
                    $attribute = $this->attribute->findOneByField('code', $params['sort']);

                    if ($params['sort'] == 'price') {
                        $qb->orderBy($attribute->code, $params['order']);
                    } else {
                        $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                    }
                }

                $qb = $qb->where(function($query1) use($company) {

                    foreach (['product_flat', 'flat_variants'] as $alias) {
                        $query1 = $query1->orWhere(function($query2) use($alias, $company) {

                            $attributes = $this->attribute->getProductDefaultAttributes(array_keys(request()->input()));

                            foreach ($attributes as $attribute) {
                                $column = $alias . '.' . $attribute->code;

                                $queryParams = explode(',', request()->get($attribute->code));

                                if ($attribute->type != 'price') {
                                    $query2 = $query2->where(function($query3) use($column, $queryParams) {
                                        foreach ($queryParams as $filterValue) {
                                            $query3 = $query3->orWhere($column, $filterValue);
                                        }
                                    });
                                    $query2 = $query2->where($alias.'.company_id', $company->id);

                                } else {
                                    $query2 = $query2->where($column, '>=', core()->convertToBasePrice(current($queryParams)))->where($column, '<=',  core()->convertToBasePrice(end($queryParams)));
                                }
                            }
                        });
                    }
                });


                return $qb->groupBy('product_flat.id');
        })->paginate(isset($params['limit']) ? $params['limit'] : 12);

        foreach ($results as $key => $result) {
            $results[$key] = ProductFlat::where('id', $result->id)->get()->first();
        }

        return $results;
    }

    /**
     * Search Product by Attribute
     *
     * @return Collection
     */
    public function searchProducts($term)
    {
        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($term) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                return $query->distinct()
                        ->addSelect('product_flat.*')
                        ->where('product_flat.status', 1)
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key')
                        ->where('product_flat.name', 'like', '%' . $term . '%')
                        ->orderBy('product_id', 'desc');
            })->paginate(16);

        return $results;
    }

    /**
     * Returns seller by product
     *
     * @param integer $productId
     * @return boolean
     */
    public function getSellerByProductId($productId)
    {
        $product = parent::findOneWhere([
                'product_id' => $productId,
                'is_owner' => 1
            ]);

        if (! $product) {
            return;
        }

        return $product->seller;
    }

    /**
     * Returns count of seller that selling the same product
     *
     * @param Product $product
     * @return integer
     */
    public function getSellerCount($product)
    {
        return 1;
    }

    /**
     * Returns the seller products of the product
     *
     * @param Product $product
     * @return Collection
     */
    public function getSellerProducts($product)
    {
        return $this->findWhere([
                'product_id' => $product->id,
                'is_owner' => 0,
                'is_approved' => 1
            ]);
    }

    /**
     * Returns the seller products of the product id
     *
     * @param integer $productId
     * @param integer $sellerId
     * @return Collection
     */
    public function getMarketplaceProductByProduct($productId, $sellerId = null)
    {
        if ($sellerId) {
            $seller = $this->sellerRepository->find($sellerId);
        } else {
            if (auth()->guard('customer')->check()) {
                $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);
            } else {
                return;
            }
        }

        return $this->findOneWhere([
                'product_id' => $productId,
                // 'is_owner' => 1,
                'marketplace_seller_id' => $seller->id,
            ]);
    }
}