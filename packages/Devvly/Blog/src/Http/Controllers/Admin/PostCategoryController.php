<?php


namespace Devvly\Blog\Http\Controllers\Admin;

use Devvly\Blog\Http\Controllers\Controller;
use Devvly\Blog\Repositories\PostCategoryRepository;
use Webkul\Core\Contracts\Validations\Slug;

class PostCategoryController extends Controller
{
    /**
     * To hold the request variables from route file
     *
     * @var array
     */
    protected $_config;

    /**
     * @var PostCategoryRepository
     */
    protected $postCategoryRepository;

    /**
     * PostController constructor.
     * @param PostCategoryRepository $postCategoryRepository
     */
    public function __construct(PostCategoryRepository $postCategoryRepository)
    {
        $this->middleware('admin');
        $this->_config = request('_config');
        $this->postCategoryRepository = $postCategoryRepository;
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
        return view($this->_config['view']);
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
            'name' => 'required',
            'channels' => 'required',
        ]);
        $updated_data = [
            'name' => $data['name'],
            'url_key' => $data['url_key'],
        ];
        request()->merge($updated_data);
        $data = request()->all();
        if(isset($data['_token'])){
            unset($data['_token']);
        }
        $category = $this->postCategoryRepository->create($data);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'category']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To edit a previously created Post
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $category = $this->postCategoryRepository->findOrFail($id);
        return view($this->_config['view'], compact('category'));
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
        $url =request()->request->get('en');

        $validation = [
            $locale . '.name' => 'required',
            'channels' => 'required',
        ];

        if (request()->request->get('en')['url_key'] != request()->request->get('en')['old_url_key']) {
            $validator = function ($attribute, $value, $fail) use ($id) {
                if (! $this->postCategoryRepository->isUrlKeyUnique($id, $value)) {
                    $fail(trans('admin::app.response.already-taken', ['name' => 'Category']));
                }
            };
            $validation[ $locale . '.url_key'] = ['required', new Slug, $validator];
        }

        $this->validate(request(), $validation);

        $files = request()->allFiles();
        $data = request()->all();
        $updated_data = [
            $locale => [
                'name' => $data[$locale]['name'],
                'url_key' => $data[$locale]['url_key'],
            ],
        ];

        request()->merge($updated_data);
        $data = request()->all();
        $this->postCategoryRepository->update($data, $id);
        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Category']));
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
        $category = $this->postCategoryRepository->findOrFail($id);

        if ($category->delete()) {
            session()->flash('success', trans('blog::app.post_category.delete-success'));

            return response()->json(['message' => true], 200);
        } else {
            session()->flash('success', trans('blog::app.post_category.delete-failure'));

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

        if ($data['indexes']) {
            $categoryIDs = explode(',', $data['indexes']);

            $count = 0;

            foreach ($categoryIDs as $categoryID) {
                $category = $this->postCategoryRepository->find($categoryID);

                if ($category) {
                    $category->delete();

                    $count++;
                }
            }

            if (count($categoryIDs) == $count) {
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

        return redirect()->route('blog.admin.post_category.index');
    }
}