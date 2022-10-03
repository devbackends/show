<?php

namespace Webkul\API\Http\Controllers\Shop\Marketplace;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Webkul\API\Http\Controllers\Shop\Controller as BaseController;
use Webkul\SAASCustomizer\Repositories\ProductRepository;
class ProductController extends BaseController
{
    /**
     * ProductRepository Object
     */
    protected $productRepository;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return the cache if exists
        $exist = Storage::disk('wassabi_private')->exists('wikiarms_feed.json');
        $disable_cache = request()->get('cache') === 'false';
        if($exist && !$disable_cache){
            $file = Storage::disk('wassabi_private')->get('wikiarms_feed.json');
            return response($file)->header('Content-Type', 'application/json');
        }
        // otherwise run the command to get the products and cache them:
        Artisan::call('wikiarmsfeed:cache');
        $file = Storage::disk('wassabi_private')->get('wikiarms_feed.json');
        return response($file)->header('Content-Type', 'application/json');
    }
}
