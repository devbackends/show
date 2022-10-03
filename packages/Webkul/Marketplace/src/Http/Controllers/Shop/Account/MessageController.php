<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\MessageRepository;
use Webkul\Marketplace\Repositories\MessageDetailRepository;
use Webkul\Marketplace\Repositories\SellerRepository;


/**
 * Marketplace review controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MessageController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     *
     * @var mixed
     */
    protected $messageRepository;
    protected $messageDetailRepository;
    protected $sellerRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\MessageRepository $messageRepository
     * @param  Webkul\Marketplace\Repositories\MessageDetailRepository $messageDetailRepository
     * @param  Webkul\Marketplace\Repositories\SellerRepository $sellerRepository
     * @return void
     */
    public function __construct(
        MessageRepository $messageRepository,
        MessageDetailRepository $messageDetailRepository,
        SellerRepository $sellerRepository
    )
    {
        $this->messageRepository = $messageRepository;
        $this->messageDetailRepository= $messageDetailRepository;
        $this->sellerRepository=$sellerRepository;
        $this->_config = request('_config');
    }

    /**
     * Method to populate the seller review page which will be populated.
     *
     * @return Mixed
     */
    public function index()
    {
    /*    $isSeller = $this->sellerRepository->isSeller(auth()->guard('customer')->user()->id);

        if (! $isSeller) {
            return redirect()->route('marketplace.account.seller.create');
        }*/
        $data = [
            'temp_message' => null,
            'base_url' => route('marketplace.account.messages.index'),
        ];
       if(isset(request()->query()['m'])){
           $message_id=request()->query()['m'];
           $data['message_id'] = $message_id;
       }
        return view($this->_config['view'], $data);
    }

    public function getInboxMessages(){
        $customer_id=auth()->guard('customer')->user()->id;
        $messages= $this->messageDetailRepository->findWhere(['to'=>$customer_id])->groupBy('message_id','from')->sortByDesc('created_at')->toArray();
        $messages_detail_ids=[];
        foreach ($messages as $message){
            foreach ($message as $messageDetail){
                array_push($messages_detail_ids,$messageDetail['message_id']);
            }
        }
        $messages= $this->messageRepository->getInboxMessages($messages_detail_ids);
        return $messages;
    }
    public function getSentMessages(){
        return $this->messageRepository->getSentMessages();
    }
    public function getAllMessages(){
        return $this->messageRepository->getAllMessages();
    }
    public function getSpamMessages(){
        return $this->messageRepository->getSpamMessages();
    }
    public function getTrashMessages(){
        return $this->messageRepository->getTrashMessages();
    }
    public function getUnreadMessages(){
        return $this->messageRepository->getUnreadMessages();
    }
    public function addToTrash(){
        return $this->messageRepository->addToTrash();
    }
    public function markAsUnread(){
        return $this->messageRepository->markAsUnread();
    }
    public function markAsRead(){
        return $this->messageRepository->markAsRead();
    }
    public function markAsSpam(){
        return $this->messageRepository->markAsSpam();
    }
    public function reportMessages(){
        return $this->messageRepository->reportMessages();
    }
    public function getMessageDetails($message_id){

        return $this->messageDetailRepository->getMessageDetails($message_id);
    }
    public function sendMessage(){
        return  $this->messageRepository->createNewMessage(request());
    }

    public function sendMessageMail(Request $request){
        $seller = (int) $request->query('seller');
        $buyer = (int) $request->query('buyer');
        $order = (int) $request->query('order');
        $temp_message = [
            'from' => $buyer,
            'to' => $seller,
            'subject' => trans('shop::app.common.general_inquiry'),
        ];
        $temp_message = $this->messageRepository->getOrCreateMainMessage($temp_message);
        if($temp_message){
            $temp_message = $temp_message->toArray();
        }
        $temp_message['temp'] = true;
        $temp_message['diff'] = "1 minute";
        $temp_message['customer_id'] = $seller;
        $temp_message['query'] = trans('shop::app.mail.shipment.costumer_auto_message', ['order_id' => $order]);
        $base_url = route('marketplace.account.messages.index');
        return view($this->_config['view'], compact('base_url', 'temp_message'));
    }
    public function uploadPhotos(){
        return  $this->messageRepository->uploadPhotos(request());
    }


}