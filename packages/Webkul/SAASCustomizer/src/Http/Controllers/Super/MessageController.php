<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Illuminate\Support\Facades\Event;
use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Repositories\Super\SuperConfigRepository;
use Webkul\SAASCustomizer\Http\Requests\ConfigurationForm;
use Webkul\Core\Tree;
use Illuminate\Support\Facades\Storage;
use Webkul\Marketplace\Models\MessageReport;


/**
 * Super Configuration controller
 *
 * @author    Mohamad Kabalan <mohamad@devvly.com>
 */
class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * SuperConfigRepository object
     *
     * @var array
     */
    protected $superConfigRepository;

    /**
     *
     * @var array
     */
    protected $configTree;


    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\SAASCustomizer\Repositories\Super\SuperConfigRepository $superConfigRepository
     * @return void
     */
    public function __construct(SuperConfigRepository $superConfigRepository)
    {
        $this->middleware('super-admin');

        $this->superConfigRepository = $superConfigRepository;

        $this->_config = request('_config');

        $this->prepareConfigTree();

    }

    /**
     * Prepares config tree
     *
     * @return void
     */
    public function prepareConfigTree()
    {
        $tree = Tree::create();

        foreach (config('company') as $item) {
            $tree->add($item);
        }

        $tree->items = core()->sortItems($tree->items);

        $this->configTree = $tree;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view'], ['config' => $this->configTree]);
    }
    public function messageDetails($message_id){
        $message=app('Webkul\Marketplace\Repositories\MessageRepository')->getMessageById($message_id);
        return view($this->_config['view'], ['message' => $message]);
    }
    public function reportedMessages ()
    {
        $reportedMessages=MessageReport::all()->sortByDesc('created_at');

        return view($this->_config['view'], ['messages' => $reportedMessages]);
    }



}