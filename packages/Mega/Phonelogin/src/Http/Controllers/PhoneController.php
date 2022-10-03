<?php

namespace Mega\Phonelogin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Mega\Phonelogin\Helper\PhoneloginHelper;
use Mega\Phonelogin\Repositories\OtpLogRepository;
use Mega\Phonelogin\Helper\MessageHelper;

class PhoneController extends BaseController
{
    protected $megaHelper;
    protected $messageHelper;
    protected $otpRepo;

    public function __construct(
        PhoneloginHelper $phoneloginHelper,
        MessageHelper $messageHelper,
        OtpLogRepository $otpLogRepository
    )
    {

        $this->megaHelper = $phoneloginHelper;
        $this->messageHelper = $messageHelper;
        $this->otpRepo = $otpLogRepository;
    }

    public function sendOtp(Request $request){
        $phoneNumber = $request->input('phone',false);
        if(!$this->megaHelper->phoneIsUnique($phoneNumber)){
            $data = ['status' => false,'message' => trans("megaPhoneLogin::app.customer.account-exist")];
            return response()->json($data);
        }
        $code = $this->megaHelper->generateVerificationCode();
        $status = $this->messageHelper->sendMessage($phoneNumber,$code);
        if($status)
            $data = ['status' => $status,'phone' => $phoneNumber,'message' => trans("megaPhoneLogin::app.customer.code-sent")];
        else
            $data = ['status' => $status,'phone' => $phoneNumber,'message' => trans("megaPhoneLogin::app.customer.error-sending-code")];
        return response()->json($data);
    }

    public function verifyOtp(Request $request){
        $phone = $request->input('phone',false);
        $otp = $request->input('otp',false);
        if(!$phone || !$otp){
            $data = ['status' => true,'message' => trans("megaPhoneLogin::app.customer.invalid-code")];
            return response()->json($data);
        }

        $row = $this->otpRepo->findByField('phone_number',$phone)->last();
        if(!$row){
            $data = ['status' => false,'message' => trans("megaPhoneLogin::app.customer.invalid-code")];
            return response()->json($data);
        }
        if($row->verification_code == $otp){
            $user = auth()->guard('customer')->user();
            if($user){
                $user->phone = $phone;
                $user->mega_phone_verified = 1;
                $user->save();
            }
            $data = ['status' => true,'message' => trans("megaPhoneLogin::app.customer.phone-verified")];
            return response()->json($data);
        }else{
            $data = ['status' => false,'message' => trans("megaPhoneLogin::app.customer.invalid-code")];
            return response()->json($data);
        }

    }


    public function verifyPhone(){
        $user = auth()->guard('customer')->user();
        if($this->megaHelper->isEnabled())
            return view('megaPhoneLogin::shop.customer.verifyPhone')->with('customer',$user);
        else
            return redirect()->route('customer.profile.index');
    }

}