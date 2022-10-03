<?php


namespace Devvly\Blog\Http\Controllers\Front;

use Devvly\Blog\Repositories\PostCategoryRepository;
use Devvly\Blog\Repositories\PostRepository;
use Devvly\Blog\Repositories\TagRepository;
use HansSchouten\LaravelPageBuilder\NativePageBuilderWrapper;
use HansSchouten\LaravelPageBuilder\Repositories\PageRepository;
use Illuminate\Support\Facades\URL;
use Webkul\Shop\Http\Controllers\Controller;
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
     */
    public function __construct(PostRepository $postRepository, PostCategoryRepository $postCategoryRepository, TagRepository $tagRepository)
    {
        $this->postRepository = $postRepository;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->tagRepository = $tagRepository;
        parent::__construct();
    }

    /**
     * Loads the index page showing the static posts resources
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $posts = $this->postRepository->published()->orderByDesc('created_at')->paginate(10);
        $categories = $this->postCategoryRepository->all();
        return view($this->_config['view'], compact('posts', 'categories'));
    }

    /**
     * Loads the index page showing the static posts resources
     *
     * @return \Illuminate\View\View
     */
    public function view($slug)
    {
        $post = $this->postRepository->findByUrlKeyOrFail($slug);
        $name = app('router')->getRoutes()->match(app('request')->create(URL::previous()))->getName();
        $display_back = strpos($name,'blog.front.') !== FALSE;
        // get page builder page:
        $repo = new PageRepository();
        $locale = request()->get('locale') ?: app()->getLocale();
        $pb_page_id = $post->translate($locale)['pb_page_id'];
        $page = $repo->findWithId($pb_page_id);
        $page->setVariables(['post' => $post, 'display_back' => $display_back]);
        $builder = new NativePageBuilderWrapper();
        $content = $builder->renderPage($page);
        return $content;
    }

    public function filterByCategory($slug){
        $category = $this->postCategoryRepository->findByUrlKeyOrFail($slug);
        $posts = $category->posts()->published()->orderByDesc('created_at')->paginate(10);
        $categories = $this->postCategoryRepository->all();
        return view($this->_config['view'], compact('posts', 'categories'));
    }
    public function filterByTag($slug){
        $tag = $this->tagRepository->findByUrlKeyOrFail($slug);
        $posts = $tag->posts()->published()->orderByDesc('created_at')->paginate(10);
        $categories = $this->postCategoryRepository->all();
        return view($this->_config['view'], compact('posts', 'categories'));
    }
}