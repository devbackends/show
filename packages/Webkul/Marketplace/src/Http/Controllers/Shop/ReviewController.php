<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Repositories\ReviewRepository;

/**
 * Marketplace review controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewController extends Controller
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
     * ReviewRepository object
     *
     * @var array
    */
    protected $review;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository $seller
     * @param  Webkul\Marketplace\Repositories\ReviewRepository $review
     * @return void
     */
    public function __construct(
        ReviewRepository $review,
        SellerRepository $seller
    )
    {
        $this->_config = request('_config');

        $this->seller = $seller;

        $this->review = $review;
    }

    /**
     * Method to populate the seller review page which will be populated.
     *
     * @param  string  $url
     * @return Mixed
     */
    public function index($url)
    {
        $seller = $this->seller->findByUrlOrFail($url);

        return view($this->_config['view'], compact('seller'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function create($url)
    {
        $seller = $this->seller->findByUrlOrFail($url);

        return view($this->_config['view'], compact('seller'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function store($url)
    {
        $seller = $this->seller->findByUrlOrFail($url);

        $this->validate(request(), [
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required'
        ]);

        $data = request()->all();

        $data['status'] = 'approved';

        $data['marketplace_seller_id'] = $seller->id;
        $data['customer_id'] = auth()->guard('customer')->user()->id;

        $this->review->create($data);

        session()->flash('success', 'Seller review submitted successfully.');

        return redirect()->route($this->_config['redirect'], ['url' => $url]);
    }
}