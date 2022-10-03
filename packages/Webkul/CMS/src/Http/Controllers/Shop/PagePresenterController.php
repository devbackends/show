<?php

namespace Webkul\CMS\Http\Controllers\Shop;

use HansSchouten\LaravelPageBuilder\LaravelPageBuilder;
use HansSchouten\LaravelPageBuilder\NativePageBuilderWrapper;
use HansSchouten\LaravelPageBuilder\Repositories\PageRepository;
use PHPageBuilder\PHPageBuilder;
use Webkul\CMS\Http\Controllers\Controller;
use Webkul\CMS\Models\CmsPage;
use Webkul\CMS\Repositories\CmsRepository;

class PagePresenterController extends Controller
{
    /**
     * CmsRepository object
     *
     * @var \Webkul\CMS\Repositories\CmsRepository
     */
    protected $cmsRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\CMS\Repositories\CmsRepository  $cmsRepository
     * @return void
     */
    public function __construct(CmsRepository $cmsRepository)
    {
        $this->cmsRepository = $cmsRepository;
    }

    public function presenter($urlkey){
        // 1. load the page by $urlkey
        /** @var CmsPage $cmsPage */
        $cmsPage = $this->cmsRepository->findByUrlKeyOrFail($urlkey);
        if($cmsPage->is_login_required){
            if(!auth()->guard('customer')->check()){
                $uri = request()->path();
                session()->put('page_after_login', $uri);
                return redirect()->route('customer.session.index');
            }
        }
        // 2. get the associated builder page
        $locale = request()->get('locale') ?: app()->getLocale();
        $pb_page_id = $cmsPage->translate($locale)['pb_page_id'];
        $pageRepository = new PageRepository();
        $page = $pageRepository->findWithId($pb_page_id);

        // pass any variables that should be available to the view:
        $page->setVariables([ 'cmsPage' => $cmsPage ]);
        $builder = new NativePageBuilderWrapper();
        $content = $builder->renderPage($page);
        return $content;
    }
}