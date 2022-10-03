<?php

namespace Webkul\PWA\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\PWA\Repositories\PushNotificationRepository as PushNotificationRepository;

/**
 * Push Notification controller
 *
 * @author    Vivek Sharma <viveksh047@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * PushNotificationRepository object
     *
     * @var array
     */
    protected $pushNotificationRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\CoreConfigRepository  $coreConfig
     * @return void
     */
    public function __construct(PushNotificationRepository $pushNotificationRepository)
    {
        $this->pushNotificationRepository = $pushNotificationRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $flag = 1;
        $pathInfo = request()->getPathInfo();

        return view($this->_config['view'], compact('flag', 'pathInfo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'title' => 'required',
            'description' => 'required',
            'targeturl' => 'required',
            'image.*' => 'mimes:jpeg,jpg,bmp,png'
        ]);

        // call the repository
        $this->pushNotificationRepository->create(request()->all());

         // flash message
         session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Push Notification']));

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
        $pushnotification = $this->pushNotificationRepository->findOrFail($id);
        return view($this->_config['view'], compact('pushnotification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate(request(), [
                'title' => 'required',
                'description' => 'required',
                'targeturl' => 'required',
                'image.*' => 'mimes:jpeg,jpg,bmp,png'
            ]);

            $this->pushNotificationRepository->update(request()->all(), $id);

            session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Push Notification']));

            return redirect()->route($this->_config['redirect']);
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pushnotification = $this->pushNotificationRepository->findOrFail($id);

        $this->pushNotificationRepository->delete($id);

        session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Push Notification']));

        return redirect()->back();
    }

    public function pushtofirebase($id) // send push notification to multiple devices
    {
        $pushnotification = $this->pushNotificationRepository->findOrFail($id);

        $title = $pushnotification->title;
        $body = $pushnotification->description;
        $badge = $pushnotification->imageurl;
        $targeturl = $pushnotification->targeturl;

        $url = 'https://fcm.googleapis.com/fcm/send';

        $topic = 'bagisto';

        $fields = array(
            'to' => '/topics/' . $topic,
            'data' => [
                'body' => $body,
                'title' => $title,
                'click_action' => $targeturl
            ]
        );

        $server_key = "AIzaSyBjbet3YzHEAp-YEkRN50zWx3asw0d07MA";

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key,
        );

        // Open connection
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );

        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        // Disabling SSL Certificate support temporarly
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );

        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
        // Execute post
        $result = curl_exec( $ch );

        if ( $result === false ) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close( $ch );

        // Close connection
        return redirect()->back();
    }
}