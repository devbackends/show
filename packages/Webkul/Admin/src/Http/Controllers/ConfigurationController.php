<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Core\Tree;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\Http\Requests\ConfigurationForm;

class ConfigurationController extends Controller
{
    const URLS_FOR_MENU = [
        '/admin/configuration/general/design',
        '/admin/configuration/megaheaderfooter/general',
        '/admin/configuration/general/general',
        '/admin/configuration/emails/general',
        '/admin/configuration/megasmsnotifications/general',
        '/admin/configuration/catalog/inventory',
        '/admin/configuration/catalog/products',
        '/admin/configuration/sales/rma',
        '/admin/configuration/sales/paymentmethods',
        '/admin/configuration/sales/orderSettings',
        '/admin/configuration/customer/settings',
        '/admin/configuration/megaPhoneLogin/general',
        '/admin/configuration/sales/shipping',
        '/admin/configuration/general/general',
        '/admin/configuration/sales/carriers',
        '/admin/configuration/pos/configuration',
        '/admin/configuration/pwa/settings',
        '/admin/configuration/sales/ffl'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * CoreConfigRepository object
     *
     * @var \Webkul\Core\Repositories\CoreConfigRepository
     */
    protected $coreConfigRepository;

    /**
     *
     * @var array
     */
    protected $configTree;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\CoreConfigRepository  $coreConfigRepository
     * @return void
     */
    public function __construct(CoreConfigRepository $coreConfigRepository)
    {
        $this->middleware('admin');

        $this->coreConfigRepository = $coreConfigRepository;

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

        foreach (config('core') as $item) {

            if (!isset($_GET['showall']) || $_GET['showall'] !== 'true') {
                if (strpos($item['key'], 'sales.carriers') > -1) {
                    if (core()->getCurrentChannelCode() == config('app.defaultChannel')) {
                        $marketplaceAllowedCarriers = [
                            //'sales.carriers.mpmultishipping',
                            'sales.carriers.mpups',
                            'sales.carriers.mpfedex',
                            'sales.carriers.mpusps',
                            'sales.carriers.tablerate',
                            'sales.carriers.flatrate',
                            'sales.carriers'
                        ];

                        if (!in_array($item['key'], $marketplaceAllowedCarriers)) continue;
                    } else {
                        $marketplaceDisabledCarriers = [
                            'sales.carriers.mpmultishipping',
                            'sales.carriers.mpups',
                            'sales.carriers.mpfedex',
                            'sales.carriers.mpusps',
                        ];

                        if (in_array($item['key'], $marketplaceDisabledCarriers)) continue;
                    }
                }
            }

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
        $slugs = $this->getDefaultConfigSlugs();

        if (count($slugs)) {
            return redirect()->route('admin.configuration.index', $slugs);
        }

        $pathInfo = request()->getPathInfo();
        $flag = in_array($pathInfo, self::URLS_FOR_MENU) ? 1 : 0;

        return view($this->_config['view'], ['config' => $this->configTree, 'flag' => $flag, 'pathInfo' => $pathInfo]);
    }

    /**
     * Returns slugs
     *
     * @return array
     */
    public function getDefaultConfigSlugs()
    {
        if (! request()->route('slug')) {
            $firstItem = current($this->configTree->items);
            $secondItem = current($firstItem['children']);

            return $this->getSlugs($secondItem);
        }

        if (! request()->route('slug2')) {
            $secondItem = current($this->configTree->items[request()->route('slug')]['children']);

            return $this->getSlugs($secondItem);
        }

        return [];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Webkul\Admin\Http\Requests\ConfigurationForm  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConfigurationForm $request)
    {
        Event::dispatch('core.configuration.save.before');

        $this->coreConfigRepository->create(request()->all());

        Event::dispatch('core.configuration.save.after');

        session()->flash('success', trans('admin::app.configuration.save-message'));

        return redirect()->back();
    }

    /**
     * download the file for the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function download()
    {
        $path = request()->route()->parameters()['path'];

        $fileName = 'configuration/'. $path;

        $config = $this->coreConfigRepository->findOneByField('value', $fileName);

        return Storage::download($config['value']);
    }

    /**
     * @param  string  $secondItem
     * @return array
     */
    private function getSlugs($secondItem): array
    {
        $temp = explode('.', $secondItem['key']);

        return ['slug' => current($temp), 'slug2' => end($temp)];
    }
}
