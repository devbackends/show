<?php

namespace Webkul\CMS\Http\Controllers\Admin;

use Carbon\Carbon;
use HansSchouten\LaravelPageBuilder\Repositories\PageRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use HansSchouten\LaravelPageBuilder\Repositories\PageTranslationRepository;
use Webkul\CMS\Http\Controllers\Controller;
use Webkul\CMS\Repositories\CmsRepository;
use Webkul\Core\Contracts\Validations\Slug;

class PageController extends Controller
{
    /**
     * To hold the request variables from route file
     *
     * @var array
     */
    protected $_config;

    /**
     * To hold the CMSRepository instance
     *
     * @var \Webkul\CMS\Repositories\CmsRepository
     */
    protected $cmsRepository;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\CMS\Repositories\CmsRepository $cmsRepository
     * @return void
     */
    public function __construct(CmsRepository $cmsRepository)
    {
        $this->middleware('admin');

        $this->cmsRepository = $cmsRepository;

        $this->_config = request('_config');
    }

    /**
     * Loads the index page showing the static pages resources
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * To create a new CMS page
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * To store a new CMS page in storage
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->all();

        $this->validate(request(), [
            'url_key' => ['required', 'unique:cms_page_translations,url_key', new Slug],
            'page_title' => 'required',
            'channels' => 'required',
        ]);
        $updated_data = [
            'page_title' => $data['page_title'],
            'meta_title' => $data['meta_title'],
            'url_key' => $data['url_key'],
            'meta_keywords' => $data['meta_keywords'],
            'meta_description' => $data['meta_description'],
            'published' => isset($data['published'])? 1: 0,
        ];
        // create PHP Page Builder Page:
        $pb_data = [
            'name' => $data['page_title'],
            'layout' => 'master',
            'title' => ['en' => $data['page_title']],
            'route' => ['en' => $data['url_key']],
            'meta' => json_encode(['type' => 'page']),
        ];
        $pb_page_tr_repo = new PageTranslationRepository;
        $pb_page = $pb_page_tr_repo->findWhere('route', $data['url_key']);
        if($pb_page){
            session()->flash('error', 'URL Key must be unique');
            return redirect()->back()->withInput(request()->all());
        }
        $repo = new PageRepository();
        $pb_page = $repo->create($pb_data);
        $m = 'Something went wrong, failed to create page';
        if(!$pb_page){
            session()->flash('error', $m);
            return redirect()->back()->withInput(request()->all());
        }
        $pb_page = $pb_page_tr_repo->findWhere('route', $data['url_key']);
        if(!$pb_page){
            session()->flash('error', $m);
            return redirect()->back()->withInput(request()->all());
        }
        $pb_page_id = $pb_page[0]->page_id;
        $updated_data['pb_page_id'] = $pb_page_id;
        request()->merge($updated_data);

        $page = $this->cmsRepository->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'page']));
        return redirect()->route('admin.page_builder.edit', ['page_id' => $pb_page_id]);
    }

    /**
     * To edit a previously created CMS page
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $page = $this->cmsRepository->findOrFail($id);
        return view($this->_config['view'], compact('page'));
    }

    /**
     * To update the previously created CMS page in storage
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        $locale = request()->get('locale') ?: app()->getLocale();
        $url=request()->request->get('en');

        $validation=[$locale . '.page_title' => 'required'
            , 'channels' => 'required'
            , $locale . '.hero_image_link' => 'url'
        ];

        if(request()->request->get('en')['url_key']!=request()->request->get('en')['old_url_key']) {
                $validation[ $locale . '.url_key']      = ['required', new Slug, function ($attribute, $value, $fail) use ($id) {
                        if (! $this->cmsRepository->isUrlKeyUnique($id, $value)) {
                            $fail(trans('admin::app.response.already-taken', ['name' => 'Page']));
                        }
                    }];
        }

        $this->validate(request(), $validation);

        $data = request()->all();

        $updated_data = [$locale => [
            'page_title' => $data[$locale]['page_title'],
            'meta_title' => $data[$locale]['meta_title'],
            'url_key' => $data[$locale]['url_key'],
            'meta_keywords' => $data[$locale]['meta_keywords'],
            'meta_description' => $data[$locale]['meta_description'],
            ]
        ];
        $now = Carbon::now();
        $updated_data['updated_at'] = $now ;
        $updated_data['published'] = isset($data['published'])? 1: 0;
        $updated_data['is_login_required'] = isset($data['is_login_required'])? 1: 0;
        request()->merge($updated_data);
        $this->cmsRepository->update(request()->all(), $id);
        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Page']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To delete the previously create CMS page
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $page = $this->cmsRepository->findOrFail($id);

        if ($page->delete()) {
            $pb_repo = new PageRepository();
            $pb_repo->destroy($page->pb_page_id);
            session()->flash('success', trans('admin::app.cms.pages.delete-success'));

            return response()->json(['message' => true], 200);
        } else {
            session()->flash('success', trans('admin::app.cms.pages.delete-failure'));

            return response()->json(['message' => false], 200);
        }
    }

    /**
     * To mass delete the CMS resource from storage
     *
     * @return \Illuminate\Http\Response
     */
    public function massDelete()
    {
        $data = request()->all();

        if ($data['indexes']) {
            $pageIDs = explode(',', $data['indexes']);

            $count = 0;

            foreach ($pageIDs as $pageId) {
                $page = $this->cmsRepository->find($pageId);

                if ($page) {
                    $page->delete();

                    $count++;
                }
            }

            if (count($pageIDs) == $count) {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', [
                    'resource' => 'CMS Pages',
                ]));
            } else {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.partial-action', [
                    'resource' => 'CMS Pages',
                ]));
            }
        } else {
            session()->flash('warning', trans('admin::app.datagrid.mass-ops.no-resource'));
        }

        return redirect()->route('admin.cms.index');
    }

    public function deleteOldHeroImage($id, $page){
        $locale = request()->get('locale') ?: app()->getLocale();
        $data = request()->all();
        $data = $data[$locale];
        $image = isset($data['hero_image']) ? $data['hero_image']: null;
        $remove = true;
        $files = request()->allFiles();

        if(($image && count($image) && empty(array_first($image))) || isset($files[$locale]['hero_image'])){
            $remove = false;
        }
        if($remove){
            Storage::delete($page->path_hero_image);
            return true;
        }
        return false;
    }

    /**
     * @param array $data
     * @param int $index
     * @return mixed
     */
    public function uploadHeroImage(UploadedFile $file)
    {

        return Storage::put('hero_image', $file);
    }

    public function deleteImage(){

    }

    /**
     * To delete the previously create CMS page
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate($id){
        $stop = null;
        try {
            $cms_page = $this->cmsRepository->find($id);
            $new_page = $this->cmsRepository->duplicate($cms_page);
            $stop = null;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
        session()->flash('success', trans('admin::app.cms.pages.duplicate-success'));
    }
}