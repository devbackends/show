<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Admin;

use Webkul\Shop\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Repositories\ChannelRepository as Channel;

use DB;

/**
 * Channel controller
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @author Vivek Sharma <viveksh047@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ChannelController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ChannelRepository object
     *
     * @var Object
     */
    protected $channel;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository $channel
     * @return void
     */
    public function __construct(Channel $channel)
    {
        $this->channel = $channel;

        $this->_config = request('_config');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pathInfo = request()->getPathInfo();
        $flag = 1;

        return view($this->_config['view'], compact('flag', 'pathInfo'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:channels,code', new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required',
            'locales' => 'required|array|min:1',
            'hostname' => ['required', 'unique:channels,hostname', new \Webkul\SAASCustomizer\Contracts\Validations\Host],
            'default_locale_id' => 'required',
            'currencies' => 'required|array|min:1',
            'base_currency_id' => 'required',
            'root_category_id' => 'required',
            'logo.*' => 'mimes:jpeg,jpg,bmp,png',
            'favicon.*' => 'mimes:jpeg,jpg,bmp,png',
            'seo_title' => 'required|string',
            'seo_description' => 'required|string',
            'seo_keywords' => 'required|string'
        ]);

        $data = request()->all();

        $data['seo']['meta_title'] = $data['seo_title'];
        $data['seo']['meta_description'] = $data['seo_description'];
        $data['seo']['meta_keywords'] = $data['seo_keywords'];

        unset($data['seo_title']);
        unset($data['seo_description']);
        unset($data['seo_keywords']);

        $data['home_seo'] = json_encode($data['seo']);

        unset($data['seo']);

        Event::dispatch('core.channel.create.before');

        $channel = $this->channel->create($data);
        $this->channel->createFooter($channel->id);
        Event::dispatch('core.channel.create.after', $channel);

        session()->flash('success', trans('admin::app.settings.channels.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $channel = $this->channel->with(['locales', 'currencies'],['icons'])->findOrFail($id);
        $pathInfo = request()->getPathInfo();
        $flag = 1;

        return view($this->_config['view'], compact('channel', 'flag', 'pathInfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate(request(), [
            'code' => ['required', 'unique:channels,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required',
            'locales' => 'required|array|min:1',
            'hostname' => ['required', 'unique:channels,hostname,' . $id, new \Webkul\SAASCustomizer\Contracts\Validations\Host],
            'inventory_sources' => 'required|array|min:1',
            'default_locale_id' => 'required',
            'currencies' => 'required|array|min:1',
            'base_currency_id' => 'required',
            'root_category_id' => 'required',
            'logo.*' => 'mimes:jpeg,jpg,bmp,png',
            'favicon.*' => 'mimes:jpeg,jpg,bmp,png'
        ]);

        $data = request()->all();

        $data['seo']['meta_title'] = $data['seo_title'];
        $data['seo']['meta_description'] = $data['seo_description'];
        $data['seo']['meta_keywords'] = $data['seo_keywords'];

        unset($data['seo_title']);
        unset($data['seo_description']);
        unset($data['seo_keywords']);

        $data['home_seo'] = json_encode($data['seo']);

        Event::dispatch('core.channel.update.before', $id);

        $channel = $this->channel->update($data, $id);

        Event::dispatch('core.channel.update.after', $channel);

        session()->flash('success', trans('admin::app.settings.channels.update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $channel = $this->channel->findOrFail($id);

        if ($channel->code == config('app.channel')) {
            session()->flash('error', trans('admin::app.settings.channels.last-delete-error'));
        } else {
            try {
                Event::dispatch('core.channel.delete.before', $id);

                $this->channel->delete($id);

                Event::dispatch('core.channel.delete.after', $id);

                session()->flash('success', trans('admin::app.settings.channels.delete-success'));

                return response()->json(['message' => true], 200);
            } catch(\Exception $e) {
                session()->flash('error', trans('saas::app.tenant.custom-errors.cannot-delete-default'));
            }
        }

        return response()->json(['message' => false], 400);
    }
    public function getFooterIcons(Request $request){
        return $icons = $this->channel->getFooterIcons($request->request->get('channel_id'));
    }
    public function destroyIcon(Request $request){
        $icon_id=$request->request->get('icon_id');
       return $delete_icon = $this->channel->deleteIcon($icon_id);
    }
    public function addIcon(Request $request){
        return $add_icon = $this->channel->addIcon($request);
    }

    public function updateIconUrl(Request $request)
    {
        return $update_icon = $this->channel->updateIconUrl($request);
    }
    public function updateFooterIcon(Request $request){
        return $update_icon = $this->channel->updateFooterIcon($request);
    }
}