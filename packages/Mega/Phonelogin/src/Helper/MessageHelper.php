<?php

namespace Mega\Phonelogin\Helper;

use Mega\Phonelogin\Models\OtpLog;
use Illuminate\Support\Facades\Log;

class MessageHelper
{
    protected $logModel;

    public function __construct(OtpLog $otpLog)
    {
        $this->logModel = $otpLog;
    }

    public function sendMessage($phone,$code){
        if(strpos($phone,'+') !== false){}
        else{
            $phone = '+'.$phone;
        }
        $message = $this->getverificationMessage($code);
        $activeAPI = $this->getConfigData('megaPhoneLogin.general.general.api');
        $resp = false;
        switch ($activeAPI){
            case $this->logModel::API_NO_API:
                Log::debug('API Is Not Selected to Send SMS');
                break;

            case $this->logModel::API_SPRING_EDGE:
                $resp = $this->sendSpringEdgeSms($phone,$message);
                break;
            case $this->logModel::API_MSG91:
                $resp = $this->sendMsg91($phone,$message);
                break;
            case $this->logModel::API_JAWALBSMS:
                $resp = $this->sendJawalbSms($phone,$message);
                break;
            case $this->logModel::API_TWILIO:
                $resp =  $this->sendTwilioSms($phone,$message);
                break;
            case $this->logModel::API_TEXTLOCAL:
                $resp = $this->sendTextLocalSms($phone,$message);
                break;
        }

        $this->saveMessageData($phone,$code,$resp);
        return $resp;
    }

    public function saveMessageData($phone,$message,$resp){
        $otpLog = new OtpLog([
            'phone_number' => $phone,
            'verification_code' => $message,
            'status' => $resp
        ]);
        $otpLog->save();
    }

    public function sendSpringEdgeSms($phone,$message){
        $url = 'https://instantalerts.co/api/web/send/';
        $senderId = $this->getConfigData('megaPhoneLogin.general.general.sender_id');
        $apiKey  = $this->getConfigData('megaPhoneLogin.general.general.api_key');
        $url .= '?apikey='.$apiKey.'&sender='.$senderId;
        $url .= '&to='.$phone.'&message='.urlencode($message).'&format=json';
        Log::debug('Spring Edge URL '.$url);
        $resp = $this->sendRequest($url);
        Log::debug('Spring Edge Response '.json_encode($resp));
        if(!$resp['status'])
            return false;
        else{
            $apiResponse = isset($resp['res']) ? $resp['res'] : false;
            if(!$apiResponse)
                return false;
            $requestStatus = isset($apiResponse['status']) ? $apiResponse['status'] : false;
            if(!$requestStatus)
                return false;
            return true;
        }

    }

    public function sendJawalbSms($phone,$message){

        $username = $this->getConfigData('megaPhoneLogin.general.general.api_key');
        $password = $this->getConfigData('megaPhoneLogin.general.general.api_password');
        $sender = $this->getConfigData('megaPhoneLogin.general.general.sender_id');
        $message = urlencode($message);
        $url = "http://www.jawalbsms.ws/api.php/sendsms?user=$username&pass=$password&to=$phone&message=$message&sender=$sender&unicode=u";
        Log::debug('jawalbsms URL '.$url);
        $resp = $this->sendRequest($url);
        $responseData = explode("|",$resp);
        $msgId = $responseData[0];
        $status = $responseData[1];
        $cost = $responseData[3];
        $find = 'STATUS:';
        $replace = '';
        $arr = $status;
        $status = str_replace($find,$replace,$arr);
        $match = 'Success';
        $sentmsg = true;
        if(!strpos($status, $match)) {
            $sentmsg = false;
        }
        return $sentmsg;
    }

    public function sendTwilioSms($phone,$message){
        $id = $this->getConfigData('megaPhoneLogin.general.general.api_key');
        $token = $this->getConfigData('megaPhoneLogin.general.general.api_password');
        $url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages";
        Log::debug('twilio '.$url);
        $from = $this->getConfigData('megaPhoneLogin.general.general.sender_id');
        $to = "+$phone";
        $body = $message;
        $data = array (
            'From' => $from,
            'To' => $to,
            'Body' => $body,
        );
        $post = http_build_query($data);
        $ch = curl_init($url );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$id:$token");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $resp = curl_exec($ch);
        curl_close($ch);
        $resp = json_decode($resp,true);
        if(isset($resp['status']) && $resp['status'])
            return true;
        return false;
    }

    public function sendTextLocalSms($phone,$message){
        $url = 'https://api.textlocal.in/send/?';
        $senderId = $this->getConfigData('megaPhoneLogin.general.general.sender_id');
        $apiKey  = $this->getConfigData('megaPhoneLogin.general.general.api_key');
        $apiKey = urlencode($apiKey);
        $numbers = urlencode($phone);
        $sender = urlencode($senderId);
        $message = rawurlencode($message);
        $url .= 'apikey=' . $apiKey . '&numbers=' . $numbers . "&sender=" . $sender . "&message=" . $message;
        Log::debug('textlocal SMS'.$url);
        $resp = $this->sendRequest($url);
        //$resp = json_decode($resp,true);
        if(isset($resp['status']) && $resp['status'])
            return true;
        return false;

    }

    public function sendMsg91($phone,$message){

        $countryCode = substr($phone,0,3);
        $phone = substr($phone,-10);
        $route = 4;
        $senderId = $this->getConfigData('megaPhoneLogin.general.general.sender_id');
        $apiKey  = $this->getConfigData('megaPhoneLogin.general.general.api_key');
        //$url = 'https://api.msg91.com/api/sendhttp.php?campaign=&response=&afterminutes=&schtime=&flash=&unicode=&';
        $url = 'https://api.msg91.com/api/sendhttp.php?campaign=&response=&';
        $url .= 'mobiles='.$phone.'&response=json&authkey='.$apiKey.'&route='.$route.'&sender='.$senderId.'&message='.urlencode($message).'&country='.$countryCode;
        $resp = $this->sendRequest($url);
        //$resp = json_decode($resp,true);
        Log::debug('msg91 resp '.json_encode($url));
        if(isset($resp['status']) && $resp['status'])
            return true;
        return false;
    }

    public function sendRequest($url){
        $resp = [];
        $resp['status'] = true;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::debug("cURL Error #:" . $err) ;
            $resp['status'] = false;

        } else {
            $resp['res'] = $response;
        }
        Log::debug('API REsponse '.$response);
        return $resp;
    }

    public function getverificationMessage($code){
        $rawMessage = $this->getConfigData('megaPhoneLogin.general.template.verification-code-template');
        $find = ['__verification_code__'];
        $rep = [$code];
        $message = str_replace($find,$rep,$rawMessage);
        return $message;
    }

    public function getConfigData($config){
        return core()->getConfigData($config);
    }
}