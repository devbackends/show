<?php

namespace Webkul\Shop\Http\Controllers;

use HansSchouten\LaravelPageBuilder\NativePageBuilderWrapper;
use HansSchouten\LaravelPageBuilder\Repositories\PageTranslationRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Webkul\CMS\Helper\BlockParser;
use Webkul\Customer\Mail\VerificationEmail;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Core\Repositories\SliderRepository;
use Webkul\Velocity\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
use Intervention\Image\Facades\Image;

class HomeController extends Controller
{
    /**
     * SliderRepository object
     *
     * @var \Webkul\Core\Repositories\SliderRepository
     */
    protected $sliderRepository;

    /**
     * BlockParser object
     *
     * @var BlockParser
     */
    protected $blockParser;

    /**
     * Helper object
     *
     * @var Helper
     */
    protected $velocityHelper;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\Core\Repositories\SliderRepository $sliderRepository
     * @param BlockParser $blockParser
     * @param Helper $velocityHelper
     * @return void
     */
    public function __construct(SliderRepository $sliderRepository, BlockParser $blockParser, Helper $velocityHelper)
    {
        $this->sliderRepository = $sliderRepository;
        $this->blockParser = $blockParser;
        $this->velocityHelper = $velocityHelper;
        parent::__construct();
    }

    /**
     * loads the home page for the storefront
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return $this->indexT();
        $currentChannel = core()->getCurrentChannel();

        $sliderData = $this->sliderRepository->findByField('channel_id', $currentChannel->id)->toArray();
        $metaData = $this->velocityHelper->getVelocityMetaData();
        $home_page_content = $this->blockParser->parse($metaData->home_page_content);
        $metaData->home_page_content = $home_page_content;
        $vars = [
            'sliderData' => $sliderData,
            'metaData' => $metaData
        ];
        return view($this->_config['view'])->with($vars);
    }

    public function indexT(){
        // get page builder page:
        $pageTranslationRepo = new PageTranslationRepository();
        $result = $pageTranslationRepo->findWhere('route', 'home');
        if(!$result){
            abort(404);
        }
        // init home variables:
        $pageTranslation = $result[0];
        $page = $pageTranslation->getPage();

        // pass any variables that should be available to the view:
        $page->setVariables([]);

        // build the page:
        $builder = new NativePageBuilderWrapper();
        $content = $builder->renderPage($page);
        return $content;
    }

    public function becomeSeller()
    {
        return view($this->_config['view']);
    }

    /**
     * loads the home page for the storefront
     *
     * @return \Exception
     */
    public function notFound()
    {
        abort(404);
    }

    public function uploadCustomerProfilePicture(Request $request)
    {
        if (auth()->guard('customer')->check()) {
            $customer_id = auth()->guard('customer')->user()->id;
            if ($request->hasFile('image')) {
                //  Let's do everything here
                if ($request->file('image')->isValid()) {
                    //
                    $validated = $request->validate([
                        'image' => 'mimes:jpeg,jpg,gif,png|max:1014',
                    ]);


                    // start resizing
                    $image = $request->file('image');
                    $input['imagename'] = time().'.'.$image->extension();
                    $destinationPath = public_path('/storage/customers');
                    $img = Image::make($image->path())->fit(200,200);
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['imagename']);
                    //end resizing


                    $cover = $request->file('image');
                    $extension = $cover->getClientOriginalExtension();
                    $destination = "customers/profile/".$customer_id.'/'.$cover->getFilename() . '.' . $extension;
                    Storage::put($destination, File::get($destinationPath.'/'.$input['imagename']));
                    $customer = app('Webkul\Customer\Repositories\CustomerRepository')->where('id', $customer_id)->first();
                    $customer->image = $destination;
                    $customer->save();
                    unlink($destinationPath.'/'.$input['imagename']);
                    return json_encode(array('status'=>'success'));
                }
            }
        }
        return json_encode(array('status'=>'fail'));
    }
    public function gunGiveAway(){
        return view($this->_config['view']);
    }

}
