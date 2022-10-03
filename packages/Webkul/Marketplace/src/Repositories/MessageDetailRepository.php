<?php

namespace Webkul\Marketplace\Repositories;

use Composer\Package\Package;
use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Webkul\Marketplace\Mail\NewMessageNotification;
use Illuminate\Support\Facades\Mail;
/**
 * Seller Invoice Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MessageDetailRepository extends Repository
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
        return 'Webkul\Marketplace\Contracts\MessageDetail';
    }
    public function getInboxMessages($customer_id){

        $messgaes=$this->model->findWhere(['to'=>$customer_id]);

    }
    public function getMessageDetails($message_id){
        $query = $this->model->where('message_id', '=', $message_id);

        $query->orderBy('created_at', 'asc');

        return $query->get()->toArray();

    }
    public function createNewMessageDetail($data,$message_id){
        $messageDetail=new $this->model;

        $messageDetail->body=$data['query'];
        $messageDetail->from=$data['from'];
        $messageDetail->to=$data['to'];
        if(isset($data['message_type'])){
            $messageDetail->message_type=$data['message_type'];
        }
        $messageDetail->message_id=$message_id;
        $messageDetail->save();

        if($messageDetail){
            $sender = app('Webkul\Customer\Repositories\CustomerRepository')->find($data['from']);
            $sender_name=     $sender->first_name.' '.$sender->last_name;
            $receiver = app('Webkul\Customer\Repositories\CustomerRepository')->find($data['to']);
            $receiver_name=$receiver->first_name.' '.$receiver->last_name;
            $receiver_email=$receiver->email;
            $email_data['sender_name']=$sender_name;
            $email_data['receiver_name']=$receiver_name;
            $email_data['receiver_email']=$receiver_email;
            $email_data['message']=$data['query'];
            $email_data['message_id']=$message_id;
            try{
                Mail::queue(new NewMessageNotification($email_data));
            }catch (\Exception $e){
            }

            return array('status'=>'success');

        }else{
            return array('status'=>'error');
        }
    }


}