<?php


namespace Devvly\Blog\Http\Controllers\Admin;

use Devvly\Blog\Http\Controllers\Controller;
use Devvly\Blog\Repositories\TagRepository;
use Webkul\Core\Contracts\Validations\Slug;

class TagController extends Controller
{
    /**
     * To hold the request variables from route file
     *
     * @var array
     */
    protected $_config;

    /**
     * @var TagRepository
     */
    protected $tagRepository;

    /**
     * PostController constructor.
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->middleware('admin');
        $this->_config = request('_config');
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
            'url_key' => ['required', 'unique:tag_translations,url_key', new Slug],
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
        $tag = $this->tagRepository->create($data);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'tag']));

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
        $tag = $this->tagRepository->findOrFail($id);
        return view($this->_config['view'], compact('tag'));
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
                if (! $this->tagRepository->isUrlKeyUnique($id, $value)) {
                    $fail(trans('admin::app.response.already-taken', ['name' => 'Tag']));
                }
            };
            $validation[ $locale . '.url_key'] = ['required', new Slug, $validator];
        }

        $this->validate(request(), $validation);

        $data = request()->all();
        $updated_data = [
            $locale => [
                'name' => $data[$locale]['name'],
                'url_key' => $data[$locale]['url_key'],
            ],
        ];

        request()->merge($updated_data);
        $data = request()->all();
        $this->tagRepository->update($data, $id);
        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Tag']));
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
        $tag = $this->tagRepository->findOrFail($id);

        if ($tag->delete()) {
            session()->flash('success', trans('blog::app.tag.delete-success'));

            return response()->json(['message' => true], 200);
        } else {
            session()->flash('success', trans('blog::app.tag.delete-failure'));

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
            $tagIDs = explode(',', $data['indexes']);

            $count = 0;

            foreach ($tagIDs as $tagID) {
                $tag = $this->tagRepository->find($tagID);

                if ($tag) {
                    $tag->delete();

                    $count++;
                }
            }

            if (count($tagIDs) == $count) {
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

        return redirect()->route('blog.admin.tag.index');
    }
}