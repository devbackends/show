<?php

namespace Devvly\Ffl\Http\Controllers\Admin;

use Devvly\Ffl\Http\Controllers\Controller;
use Devvly\Ffl\Models\Ffl;
use Devvly\Ffl\Repositories\FflRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Devvly\Ffl\Mail\NewFflConfirmation;

class Review extends Controller
{
    /**
     * @var array|\Illuminate\Contracts\Foundation\Application|Request|string
     */
    private $_config;

    /**
     * @var FflRepository
     */
    private $fflRepository;

    public function __construct(FflRepository $fflRepository)
    {
        $this->_config = request('_config');
        $this->fflRepository = $fflRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(int $id)
    {
        /** @var Ffl $ffl */
        $token=md5(uniqid(rand(), true)); //create token to use it in the email
        $ffl = $this->fflRepository->find($id);
        if (!$ffl) {
            abort(404);
        }
        //check if there is another ffl with same license number and deactivate it
        try{
           $license_number=$ffl->license()->get()[0]->license_number;
            $ffls=$this->fflRepository->findAllByLicenseNumber($license_number)->get();
            foreach ($ffls as $ffl_item){
                $ffl_item->is_approved = 0;
                $ffl_item->save();
            }

        }catch (\Exception $e){

        }
        //approve ffl
        $ffl->is_approved = 1;
        $ffl->token=$token;
        $ffl->save();
        try {
        }catch (\Exception $e){
            Mail::queue(new NewFflConfirmation($ffl));
        }

        return redirect()->route('ffl.review.list')->with('success', 'Approved');
    }

    public function show(int $id)
    {
        /** @var Ffl $ffl */
        $ffl = $this->fflRepository->find($id);
        if (!$ffl) {
            return redirect()->back()->with('error', 'Not found');
        }
        $licenseUrl = Storage::disk('wassabi_private')->temporaryUrl($ffl->license->license_file, now()->addMinutes(5));
        $paymentMap = [
            'cash'    => 'Cash',
            'cc_cash' => 'Both credit card and cash',
            'cc'      => 'Credit card',
        ];
        return view($this->_config['view'], [
                'ffl'        => $ffl,
                'licenseUrl' => $licenseUrl,
                'payment'    => $paymentMap[$ffl->transferFees->payment],
            ]
        );
    }
    public function ConfirmPreferredFflForm($token = null){
        if(!is_null($token)){
            $ffl=$this->fflRepository->findWhere(['token'=>$token])->first();
            if($ffl){
                $ffl->is_confirmed=1;
                $ffl->save();
                return view($this->_config['view']);
            }
        }
    }
}
