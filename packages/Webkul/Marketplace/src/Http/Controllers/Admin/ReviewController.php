<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * ReviewRepository object
     *
     * @var array
    */
    protected $reviewRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\ReviewRepository $reviewRepository
     * @return void
     */
    public function __construct(
        ReviewRepository $reviewRepository
    )
    {
        $this->reviewRepository = $reviewRepository;

        $this->_config = request('_config');
    }

    /**
     * Method to populate the seller review page which will be populated.
     *
     * @return Mixed
     */
    public function index($url)
    {
        return view($this->_config['view']);
    }

    /**
     * Mass updates the products
     *
     * @return response
     */
    public function massUpdate()
    {
        $data = request()->all();

        if (! isset($data['massaction-type']) || !$data['massaction-type'] == 'update') {
            return redirect()->back();
        }

        $reviewIds = explode(',', $data['indexes']);

        foreach ($reviewIds as $reviewId) {
            $this->reviewRepository->update([
                    'status' => $data['update-options']
                ], $reviewId);
        }

        session()->flash('success', trans('marketplace::app.admin.reviews.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }
}