<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\Core\Models\ChannelFooterIcons;
use Session;
class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_config = request('_config');
        $currentChannel = core()->getCurrentChannel();
        if(isset($currentChannel->id)){
            $ChannelFooterIcons=ChannelFooterIcons::where('channel_id', $currentChannel->id)->get();
            Session::put('ChannelFooterIcons', $ChannelFooterIcons);
        }

    }
}
