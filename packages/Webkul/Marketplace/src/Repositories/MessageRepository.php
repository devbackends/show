<?php

namespace Webkul\Marketplace\Repositories;

use http\Env\Request;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;
use File;
use Webkul\Marketplace\Models\MessageReport;

/**
 * Seller Invoice Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MessageRepository extends Repository
{

    /*    public function __construct(
            App $app
        )
        {
            $this->_config = request('_config');
            parent::__construct($app);
        }*/

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\Message';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {

    }
    protected function findExistingMessage($data){
        $message = $this->model->where('from',$data['from'])->where('to',$data['to'])->get()->first();
        if(!$message){
            $message = $this->model->where('to',$data['from'])->where('from',$data['to'])->get()->first();
        }
        return $message;
    }
    protected function createMessageDetail($data, $message){
        $message_detail = app('Webkul\Marketplace\Repositories\MessageDetailRepository')->createNewMessageDetail($data,$message->id);
        if(isset($message_detail['status']) && $message_detail['status'] == 'success'){
            $message->updated_at=Carbon::now()->format('Y-m-d');
            $message->save();
            return array('status'=>'success');
        }
        return null;
    }
    public function createMainMessage($data){
        $res = null;
        try {
            $message=new $this->model;
            $message->subject=$data['subject'];
            $message->from=$data['from'];
            $message->to=$data['to'];
            $message->created_at=Carbon::now()->format('Y-m-d');
            $message->updated_at=Carbon::now()->format('Y-m-d');
            $message->save();
            $res = $message;
        }
        catch(\Exception $e){
            // do task when error
            $stop = null;
        }
        return $res;
    }
    public function getOrCreateMainMessage($data)
    {
        $message = $this->findExistingMessage($data);
        if(!$message){
            $message = $this->createMainMessage($data);
        }
        return $message;
    }

    public function createNewMessage($data){
        $current_user = auth()->guard('customer')->user()->id;
        $to = (int) $data['to'];
        if($current_user === $to){
            return array('status' => 'success');
        }
        $message = $this->findExistingMessage($data);
        if (!$message) {
            $message = $this->createMainMessage($data);
        }
        else {
            $message->read = 0;
            $message->trash = 0;
            $message->subject = $data['subject'];
            $message->save();
        }
        if($message && isset($message->id)){
            $res = $this->createMessageDetail($data, $message);
            if($res){
                return $res;
            }
        }
      return array('status'=>'error');
    }
    public function uploadPhotos(){
        $request = request();
        $customer_id=auth()->guard('customer')->user()->id;
        for ($x = 0; $x <= $request['number-of-images']; $x++) {
            if ($request->hasFile('photo-'.$x)) {
                //  Let's do everything here
                if ($request->file('photo-'.$x)->isValid()) {
                    //
                    $validated = $request->validate([
                        'image' => 'mimes:jpeg,jpg,gif,png|max:1014',
                    ]);
                    $cover = $request->file('photo-'.$x);
                    $extension = $cover->getClientOriginalExtension();
                    $destination = "messages/".$customer_id.'/'.$cover->getFilename() . '.' . $extension;
                    Storage::put($destination, File::get($cover));
                    /*insert image as message*/
                    $data=array('subject'=>'','query'=>$destination ,'from'=>auth()->guard('customer')->user()->id,'to'=>$request['to'],'message_type'=>'image');
                    $this->createNewMessage($data);
                }
            }
        }
        return json_encode(array('status'=>'success'));
    }
    public function getMessageById($message_id)
    {

        $query = $this->model->where(function ($query) use ($message_id) {
            $query->where('id', '=', $message_id);
        });
        $message = $query->get()->first()->toArray();

        return $message;
    }
    public function getInboxMessages($messages_detail_ids)
    {
        $customer = auth()->guard('customer')->user();
      //  $messages = $this->model->whereIn('id','in', $messages_detail_ids)->get()->toArray();
      //  $messages = $this->model->find($messages_detail_ids)->toArray();
        $query = $this->model->where(function ($query) use ($messages_detail_ids) {
            $query->where('trash', '=', 0)
                ->whereIn('id',  $messages_detail_ids);
        });
        $query->orderBy('updated_at', 'desc');
        $messages = $query->get()->toArray();
        $data = [];
        $unread=0;
        $data['messages']=[];

        foreach ($messages as $message) {

            if($message['read']==0 && $message['message_details'][sizeof($message['message_details']) - 1]['to']==$customer->id ){$unread+=1;}
            //userInformation is the customer information that should appear
            $user_id = ($customer->id == $message['from']) ? $message['to'] : $message['from'];
            $user_information = app('Webkul\Customer\Repositories\CustomerRepository')->find($user_id);
            $diff = $this->getDiffTime($message['updated_at']);

            $message = array_merge($message, ['customer_id' => $user_information->id,'customer_name' => $user_information->first_name . ' ' . $user_information->last_name, 'customer_image' => $user_information->image, 'diff' => $diff]);
            array_push($data['messages'], $message);
        }
        $data['unread']=$unread;

        return $data;
    }

    public function getSentMessages()
    {
        $customer = auth()->guard('customer')->user();

        $messages = $this->findWhere(['from' => $customer->id,'trash'=>0])->sortByDesc('updated_at')->toArray();
        $data = [];
        foreach ($messages as $message) {
            //userInformation is the customer information that should appear
            $user_id = ($customer->id == $message['from']) ? $message['to'] : $message['from'];
            $user_information = app('Webkul\Customer\Repositories\CustomerRepository')->find($user_id);
            $diff = $this->getDiffTime($message['updated_at']);
            $message = array_merge($message, ['customer_id' => $user_information->id,'customer_name' => $user_information->first_name . ' ' . $user_information->last_name, 'customer_image' => $user_information->image, 'diff' => $diff]);
            array_push($data, $message);
        }
        return $data;
    }

    public function getAllMessages()
    {
        $customer = auth()->guard('customer')->user();
        $query = $this->model->where(function ($query) use ($customer) {
            $query->where('trash', '=', 0)
                ->Where('from', '=', $customer->id);
        })->orWhere(function ($query) use ($customer) {
            $query->where('trash', '=', 0)
                ->Where('to', '=', $customer->id);
        });
        $query->orderBy('updated_at', 'desc');
        $messages = $query->get()->toArray();
        $data = [];
        foreach ($messages as $message) {
            //userInformation is the customer information that should appear
            $user_id = ($customer->id == $message['from']) ? $message['to'] : $message['from'];
            $user_information = app('Webkul\Customer\Repositories\CustomerRepository')->find($user_id);
            $diff = $this->getDiffTime($message['updated_at']);
            $message = array_merge($message, ['customer_id' => $user_information->id,'customer_name' => $user_information->first_name . ' ' . $user_information->last_name, 'customer_image' => $user_information->image, 'diff' => $diff]);
            array_push($data, $message);
        }
        return $data;
    }

    public function getSpamMessages()
    {
        $customer = auth()->guard('customer')->user();
        $query = $this->model->where(function ($query) use ($customer) {
            $query->where('spam', '=', 1)
                ->Where('from', '=', $customer->id);
        })->orWhere(function ($query) use ($customer) {
            $query->where('spam', '=', 1)
                ->Where('to', '=', $customer->id);
        });
        $query->orderBy('updated_at', 'desc');
        $messages = $query->get()->toArray();
        $data = [];
        foreach ($messages as $message) {
            //userInformation is the customer information that should appear
            $user_id = ($customer->id == $message['from']) ? $message['to'] : $message['from'];
            $user_information = app('Webkul\Customer\Repositories\CustomerRepository')->find($user_id);
            $diff = $this->getDiffTime($message['updated_at']);
            $message = array_merge($message, ['customer_id' => $user_information->id,'customer_name' => $user_information->first_name . ' ' . $user_information->last_name, 'customer_image' => $user_information->image, 'diff' => $diff]);
            array_push($data, $message);
        }
        return $data;
    }


    public function getTrashMessages()
    {
        $customer = auth()->guard('customer')->user();
        $query = $this->model->where(function ($query) use ($customer) {
            $query->where('trash', '=', 1)
                ->Where('from', '=', $customer->id);
        })->orWhere(function ($query) use ($customer) {
            $query->where('trash', '=', 1)
                ->Where('to', '=', $customer->id);
        });
        $query->orderBy('updated_at', 'desc');
        $messages = $query->get()->toArray();
        $data = [];
        foreach ($messages as $message) {
            //userInformation is the customer information that should appear
            $user_id = ($customer->id == $message['from']) ? $message['to'] : $message['from'];
            $user_information = app('Webkul\Customer\Repositories\CustomerRepository')->find($user_id);
            $diff = $this->getDiffTime($message['updated_at']);
            $message = array_merge($message, ['customer_id' => $user_information->id,'customer_name' => $user_information->first_name . ' ' . $user_information->last_name, 'customer_image' => $user_information->image, 'diff' => $diff]);
            array_push($data, $message);
        }
        return $data;
    }
    public function getUnreadMessages(){

        $customer = auth()->guard('customer')->user();
        $query = $this->model->where(function ($query) use ($customer) {
            $query->where('read', '=', 0)
                ->Where('from', '=', $customer->id);
        })->orWhere(function ($query) use ($customer) {
            $query->where('read', '=', 0)
                ->Where('to', '=', $customer->id);
        });
        $query->orderBy('updated_at', 'desc');
        $messages = $query->get()->toArray();
        $data = [];
        $data['messages']=[];
        $unread=0;
        foreach ($messages as $message) {
            if(sizeof($message['message_details']) > 0){
                if($message['read']==0 && $message['message_details'][sizeof($message['message_details']) - 1]['to']==$customer->id ){$unread+=1;
                    //userInformation is the customer information that should appear
                    $user_id = ($customer->id == $message['from']) ? $message['to'] : $message['from'];
                    $user_information = app('Webkul\Customer\Repositories\CustomerRepository')->find($user_id);
                    $diff = $this->getDiffTime($message['updated_at']);
                    $message = array_merge($message, ['customer_id' => $user_information->id,'customer_name' => $user_information->first_name . ' ' . $user_information->last_name, 'customer_image' => $user_information->image, 'diff' => $diff]);
                    array_push($data['messages'], $message);
                }
            }
        }
        $data['unread']=$unread;
        return $data;
    }
    public function addToTrash(){
        $selected_messages=request()->input('selected_messages');
        foreach ($selected_messages as $selected_message){
            $message=$this->model->find($selected_message);
            $message->trash=1;
            $message->save();
        }
        return array('status'=>'success');
    }
    public function markAsUnread(){
        $selected_messages=request()->input('selected_messages');
        foreach ($selected_messages as $selected_message){
            $message=$this->model->find($selected_message);
            $message->read=0;
            $message->save();
        }
        return array('status'=>'success');
    }
    public function markAsRead(){
        $selected_messages=request()->input('selected_messages');
        foreach ($selected_messages as $selected_message){
            $message=$this->model->find($selected_message);
            $message->read=1;
            $message->save();
        }
        return array('status'=>'success');
    }
    public function markAsSpam(){
        $selected_messages=request()->input('selected_messages');
        foreach ($selected_messages as $selected_message){
            $message=$this->model->find($selected_message);
            $message->spam=1;
            $message->save();
        }
        return array('status'=>'success');
    }
    public function reportMessages(){
        $selected_messages=request()->input('selected_messages');

        foreach ($selected_messages as $selected_message){
            $messageReport= new MessageReport;
            $messageReport->reason=request()->input('reportReason');
            $messageReport->details=request()->input('reportDetail');
            $messageReport->message_id=$selected_message;
            $messageReport->save();
        }
        return array('status'=>'success');
    }
    public function getDiffTime($time)
    {
        $current_date = $today = Carbon::now()->format("Y-m-d h:i:s");
        $diff = abs(strtotime($current_date) - strtotime($time));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours =  floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / (60));

        if ($years > 0) {
            if ($years > 1) {
                $diff = $years . ' years';
            } else {
                $diff = $years . ' year';
            }
        } elseif ($months > 0) {
            if ($months > 1) {
                $diff = $months . ' months';
            } else {
                $diff = $months . ' month';
            }
        } elseif ($days > 0) {
            if ($days > 1) {
                $diff = $days . ' days';
            } else {
                $diff = $days . ' day';
            }
        } elseif ($hours > 0) {
            if ($hours > 1) {
                $diff = $hours . ' hours';
            } else {
                $diff = $hours . ' hour';
            }
        } elseif ($minutes > 0) {
            if ($minutes > 1) {
                $diff = $minutes . ' minutes';
            } else {
                $diff = $minutes . ' minute';
            }
        }else{
            $diff='Just now';
        }
        return $diff;
    }


}