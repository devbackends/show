<?php

namespace Webkul\Marketplace\Repositories;

use DB;
use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Marketplace\Helpers\Marketplace;
use Event;

/**
 * Seller Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SellerRepository extends Repository
{
    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItemRepository;


    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Marketplace\Repositories\OrderItemRepository    $orderItemRepository
     * @param  Illuminate\Container\Container                         $app
     * @return void
     */
    public function __construct(
        OrderItemRepository $orderItemRepository,
        App $app
    )
    {
        $this->orderItemRepository = $orderItemRepository;


        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\Seller';
    }

    public function search(array $term, $sortOptions = [])
    {
        return (new Marketplace())->paginate(collect([]));
    }

    /**
     * Retrive seller from url
     *
     * @param string $url
     * @return mixed
     */
    public function findByUrlOrFail($url, $columns = null)
    {
        if ($seller = $this->findOneByField('url', $url)) {
            return $seller;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $url
        );
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        Event::dispatch('marketplace.seller.profile.update.before', $id);

        $seller = $this->find($id);
        if(!isset($data['nra_certified'])){ $data['nra_certified']=0; }
        if(!isset($data['uscca_certified'])){ $data['uscca_certified']=0;}
        if(!isset($data['general_events_certified'])){ $data['general_events_certified']=0; }

        if(!isset($data['retailer_badge'])){ $data['retailer_badge']=0; }
        if(!isset($data['competition_shooter_badge'])){ $data['competition_shooter_badge']=0;}
        if(!isset($data['promotor_badge'])){ $data['promotor_badge']=0; }
        if(!isset($data['veteran_badge'])){ $data['veteran_badge']=0;}
        if(!isset($data['influencer_badge'])){ $data['influencer_badge']=0; }


        parent::update($data, $id);
        if (isset($data['logo'])) {
            $this->uploadImages($data, $seller);
        }
        if (isset($data['banner'])) {
            $this->uploadImages($data, $seller, 'banner');
        }
        if (isset($data['certification'])) {
            $this->uploadImages($data, $seller, 'certification');
        }
        Event::dispatch('marketplace.seller.profile.update.after', $seller);

        return $seller;
    }

    /**
     * Checks if customer is registered as seller or not
     *
     * @param integer $customerId
     * @return boolean
     */
    public function isSeller($customerId)
    {
        $isSeller = $this->getModel()->where('customer_id', $customerId)
            ->limit(1)
            ->select(\DB::raw(1))
            ->exists();

        return $isSeller ? $this->isSellerApproved($customerId) : false;
    }

    /**
     * Checks if seller is approved or not
     *
     * @param $customerId
     * @return boolean
     */
    public function isSellerApproved($customerId)
    {
        $isSellerApproved = $this->getModel()->where('customer_id', $customerId)
            ->where('is_approved', 1)
            ->limit(1)
            ->select(\DB::raw(1))
            ->exists();

        return $isSellerApproved ? true : false;
    }

    /**
     * @param array $data
     * @param mixed $seller
     * @return void
     */
    public function uploadImages($data, $seller, $type = "logo")
    {
        $dir = 'seller/' . $seller->id;
        if($type!='certification'){
            if (isset($data[$type])) {
                foreach ($data[$type] as $imageId => $image) {
                    $file = $type . '.' . $imageId;
                    if($type=='certification'){

                    }
                    if (request()->hasFile($file)) {
                        if ($seller->{$type}) {
                            Storage::delete($seller->{$type});
                        }

                        $seller->{$type} = request()->file($file)->store($dir);

                        $seller->save();
                    }
                }
            } else {
                if ($seller->{$type}) {
                    Storage::delete($seller->{$type});
                }

                $seller->{$type} = null;
                $seller->save();
            }
        }else{
            if (request()->hasFile('certification')) {
                if ($seller->certification) {
                    Storage::delete($seller->certification);
                }
                $seller->{$type} = request()->file('certification')->store($dir);
                $seller->save();
            }
        }

    }

    /**
     * Returns top 4 popular sellers
     *
     * @return Collection
     */
    public function getPopularSellers()
    {
        $result = $this->getModel()
            ->leftJoin('marketplace_orders', 'marketplace_sellers.id', 'marketplace_orders.marketplace_seller_id')
            ->leftJoin('marketplace_order_items', 'marketplace_orders.id', 'marketplace_order_items.marketplace_order_id')
            ->leftJoin('order_items', 'marketplace_order_items.order_item_id', 'order_items.id')
            ->addSelect('marketplace_sellers.*')
            ->addSelect(DB::raw('SUM(qty_ordered) as total_qty_ordered'))
            ->groupBy('marketplace_sellers.id')
            ->where('marketplace_sellers.shop_title', '<>', NULL)
            // ->where('marketplace_sellers.is_approved', 0)
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit(4)
            ->get();

        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        Event::dispatch('marketplace.seller.delete.before', $id);

        parent::delete($id);

        Event::dispatch('marketplace.seller.delete.after', $id);
    }



    public function getVendorBySellerId($customerId)
    {
        $vendor = $this->getModel()->where('customer_id', $customerId)->get()->first();

        if ($vendor) {
            return $vendor->id;
        }

        return 0;
    }
}