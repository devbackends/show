<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Repositories\SellerRepository;

use Webkul\Marketplace\Mail\ContactSellerNotification;

/**
 * Marketplace seller page controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SellerController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SellerRepository object
     *
     * @var array
    */
    protected $seller;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository $seller
     * @return void
     */
    public function __construct(SellerRepository $seller)
    {
        $this->_config = request('_config');

        $this->seller = $seller;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function show($url)
    {



        if($url=='sitemap.xml'){
            $posts='';

            //fetch categories
            $pages = DB::table('cms_pages')
                ->select('cms_pages.id','cms_pages.published','cms_page_translations.pb_page_id','cms_pages.updated_at', 'cms_page_translations.page_title',DB::raw("CONCAT('page/',cms_page_translations.url_key) AS url_key"))
                ->leftJoin('cms_page_translations', function($leftJoin) {
                    $leftJoin->on('cms_pages.id', '=', 'cms_page_translations.cms_page_id')
                        ->where('cms_page_translations.locale', app()->getLocale());
                })->get();

            $categories= app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);
            $sellers=app('Webkul\Marketplace\Repositories\SellerRepository')->where([['is_approved', 1],['logo', '<>', '']])->whereNotNull('logo')->orderBy('id')->get();
            $products=app('Webkul\SAASCustomizer\Repositories\ProductRepository')->getProducts(['limit'=>1000,'instock'=>1],[66,74])->items();
            return response()->view('marketplace::shop.sitemap.index', [
                'products' => $products,
                'categories' =>$categories,
                'sellers' => $sellers,
                'pages' => $pages
            ])->header('Content-Type', 'text/xml');
        }elseif($url=='uscca') {
            return redirect('/page/uscca');
        }else{
            $seller = $this->seller->findByUrlOrFail($url);
            return view($this->_config['view'], compact('seller'));
        }

    }

    /**
     * Send query email to seller
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function contact($url)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'query' => 'required',
        ]);

        $seller = $this->seller->findByUrlOrFail($url);
        request()->merge(['to'=>$seller->customer_id,'from'=>auth()->guard('customer')->user()->id]);
        try {
            $message=app('Webkul\Marketplace\Repositories\MessageRepository')->createNewMessage(request());
            if(isset($message['status']) ){
                if($message['status']=='success'){
                    session()->flash('success', 'Your message has been sent successfully. Seller will contact you as soon as possible.');
                    return response()->json([
                        'success' => true,
                        'message' => 'Your message has been sent successfully. Seller will contact you as soon as possible.'
                    ]);
                }
            }
            return response()->json([
                'success' => false,
                'message' =>'Your message has not been sent.Please contact our support team'
            ]);
           // Mail::send(new ContactSellerNotification($seller, request()->all()));

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully. Seller will contact you as soon as possible.'
            ]);
        } catch (\Exception $e) {}

        return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
    }

    /**
     * Check if shop url available or not
     *
     * @return \Illuminate\Http\Response
     */
    public function checkShopUrl()
    {
        $seller = $this->seller->findOneByField([
                'url' => trim(request()->input('url'))
            ]);

        return response()->json([
                'available' => $seller ? false : true
            ]);
    }
}