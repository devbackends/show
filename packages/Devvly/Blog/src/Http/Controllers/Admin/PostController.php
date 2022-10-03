<?php


namespace Devvly\Blog\Http\Controllers\Admin;

use Devvly\Blog\Http\Controllers\Controller;
use Devvly\Blog\Repositories\PostCategoryRepository;
use Devvly\Blog\Repositories\PostRepository;
use Devvly\Blog\Repositories\TagRepository;
use HansSchouten\LaravelPageBuilder\Repositories\PageRepository;
use HansSchouten\LaravelPageBuilder\Repositories\PageTranslationRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Contracts\Validations\Slug;

class PostController extends Controller
{
    /**
     * To hold the request variables from route file
     *
     * @var array
     */
    protected $_config;

    /**
     * @var PostRepository
     */
    protected $postRepository;

    protected $postCategoryRepository;
    protected $tagRepository;

    /**
     * PostController constructor.
     * @param PostRepository $postRepository
     * @param PostCategoryRepository $postCategoryRepository
     * @param TagRepository $tagRepository
     */
    public function __construct(PostRepository $postRepository, PostCategoryRepository $postCategoryRepository, TagRepository $tagRepository)
    {
        $this->middleware('admin');
        $this->_config = request('_config');
        $this->postRepository = $postRepository;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Loads the index page showing the static posts resources
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * To create a new Post
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = $this->postCategoryRepository->all();
        $tags = $this->tagRepository->all();
        return view($this->_config['view'], compact('categories','tags'));
    }

    /**
     * To store a new Post in storage
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->all();

        $this->validate(request(), [
            'url_key' => ['required', 'unique:post_translations,url_key', new Slug],
            'title' => 'required',
            'channels' => 'required',
            'image' => 'url',
        ]);
        $updated_data = [
            'title' => $data['title'],
            'url_key' => $data['url_key'],
            'published' => isset($data['published'])  && $data['published'] === 'on',
        ];

        if (isset($data['tags']) && !empty($data['tags'])) {
            $updated_data['tags'] = $data['tags'];
        }
        if (isset($data['post_category_id']) && !empty($data['post_category_id'])) {
            $updated_data['post_category_id'] = $data['post_category_id'];
        }
        else {
            $updated_data['post_category_id'] = null;
        }
        // create PHP Page Builder Page:
        $pb_route = $data['url_key'];
        $pb_data = [
            'name' => $data['title'],
            'layout' => 'post',
            'title' => ['en' => $data['title']],
            'route' => ['en' => $pb_route],
            'meta' => json_encode(['type' => 'post']),
        ];
        $pb_page_tr_repo = new PageTranslationRepository();
        $pb_page = $pb_page_tr_repo->findWhere('route', $pb_route);
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
        $pb_page = $pb_page_tr_repo->findWhere('route', $pb_route);
        if(!$pb_page){
            session()->flash('error', $m);
            return redirect()->back()->withInput(request()->all());
        }
        $pb_page_id = $pb_page[0]->page_id;
        $updated_data['pb_page_id'] = $pb_page_id;

        $files = request()->allFiles();
        $image = isset($files['uploaded_image']) && count($files['uploaded_image'])? $this->uploadImage(array_first($files['uploaded_image'])): null;
        if ($image) {
            $updated_data['image'] = $image;
        }
        request()->merge($updated_data);

        $post = $this->postRepository->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'post']));

        return redirect()->route('admin.page_builder.edit', ['page_id' => $pb_page_id]);
    }

    /**
     * To edit a previously created Post
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $post = $this->postRepository->with(['postCategory'])->findOrFail($id);
        $categories = $this->postCategoryRepository->all();
        $tags = $this->tagRepository->all();
        return view($this->_config['view'], compact('post', 'categories','tags'));
    }

    /**
     * To update the previously created Post in storage
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        $locale = request()->get('locale') ?: app()->getLocale();

        $validation = [
            $locale . '.title' => 'required',
            'channels' => 'required',
        ];

        if (request()->request->get($locale)['url_key'] != request()->request->get($locale)['old_url_key']) {
            $validator = function ($attribute, $value, $fail) use ($id) {
                if (! $this->postRepository->isUrlKeyUnique($id, $value)) {
                    $fail(trans('admin::app.response.already-taken', ['name' => 'Post']));
                }
            };
            $validation[ $locale . '.url_key'] = ['required', new Slug, $validator];
        }

        $this->validate(request(), $validation);

        $data = request()->all();
        $updated_data = [
            $locale => [
                'title' => $data[$locale]['title'],
                'url_key' => $data[$locale]['url_key'],
            ],
            'published' => isset($data['published'])  && $data['published'] === 'on',
        ];
        if (isset($data[$locale]['tags']) && !empty($data[$locale]['tags'])) {
            $updated_data[$locale]['tags'] = $data[$locale]['tags'];
        }
        if (isset($data['post_category_id']) && !empty($data['post_category_id'])) {
            $updated_data['post_category_id'] = $data['post_category_id'];
        }
        else {
            $updated_data['post_category_id'] = null;
        }
        $post = $this->postRepository->find($id);
        $deleted = $this->deleteOldImage($id, $post);
        $files = request()->allFiles();
        $image = isset($files[$locale]['uploaded_image']) && count($files[$locale]['uploaded_image'])? $this->uploadImage(array_first($files[$locale]['uploaded_image'])): null;
        if ($image || $deleted) {
            $updated_data[$locale]['image'] = $image;
        }

        request()->merge($updated_data);
        $data = request()->all();
        $this->postRepository->update($data, $id);
        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Post']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To delete the previously create Post
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $page = $this->postRepository->findOrFail($id);

        if ($page->delete()) {
            $pb_repo = new PageRepository();
            $pb_repo->destroy($page->pb_page_id);
            session()->flash('success', trans('blog::app.post.delete-success'));
            return response()->json(['message' => true], 200);
        } else {
            session()->flash('success', trans('blog::app.post.delete-failure'));

            return response()->json(['message' => false], 200);
        }
    }

    /**
     * To mass delete the Post resource from storage
     *
     * @return \Illuminate\Http\Response
     */
    public function massDelete()
    {
        $data = request()->all();
        $pb_repo = new PageRepository();
        if ($data['indexes']) {
            $pageIDs = explode(',', $data['indexes']);

            $count = 0;

            foreach ($pageIDs as $pageId) {
                $page = $this->postRepository->find($pageId);

                if ($page) {
                    $page->delete();
                    $pb_repo->destroy($page->pb_page_id);
                    $count++;
                }
            }

            if (count($pageIDs) == $count) {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', [
                    'resource' => 'Posts',
                ]));
            } else {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.partial-action', [
                    'resource' => 'Posts',
                ]));
            }
        } else {
            session()->flash('warning', trans('admin::app.datagrid.mass-ops.no-resource'));
        }

        return redirect()->route('blog.admin.post.index');
    }

    public function deleteOldImage($id, $post){
        $locale = request()->get('locale') ?: app()->getLocale();
        $files = request()->allFiles();
        $remove = false;
        // if request has new image, delete the old
        if(isset($files[$locale]['uploaded_image'])){
            $remove = true;
        }
        if($remove){
            Storage::delete($post->image);
            return true;
        }
        return false;
    }

    /**
     * @param array $data
     * @param int $index
     * @return mixed
     */
    public function uploadImage(UploadedFile $file)
    {

        return Storage::put('post_image', $file);
    }

    public function deleteImage(){

    }
}