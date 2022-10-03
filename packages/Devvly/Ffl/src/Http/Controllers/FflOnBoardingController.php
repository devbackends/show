<?php

namespace Devvly\Ffl\Http\Controllers;

use Devvly\Ffl\Http\Requests\StoreFflForm;
use Devvly\Ffl\Models\Ffl;
use Devvly\Ffl\Models\FflLicense;
use Devvly\Ffl\Repositories\FflRepository;
use Devvly\Ffl\Services\NutShell\Api;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use stringEncode\Exception;
use Webkul\Core\Models\CountryState;
use Webkul\Core\Repositories\CountryStateRepository;
use Illuminate\Support\Facades\Mail;
use Devvly\Ffl\Mail\NewFflNotification;


class FflOnBoardingController extends Controller
{
    /**
     * @var array|\Illuminate\Contracts\Foundation\Application|Request|string
     */
    protected $_config;

    /**
     * @var Api
     */
    private $api;

    /**
     * @var FflRepository
     */
    private $fflRepository;

    /**
     * @var CountryStateRepository
     */
    private $countryStateRepository;

    /**
     * FflOnBoardingController constructor.
     * @param Api $api
     * @param FflRepository $fflRepository
     * @param CountryStateRepository $countryStateRepository
     */
    public function __construct(Api $api, FflRepository $fflRepository, CountryStateRepository $countryStateRepository)
    {
        $this->_config = request('_config');
        $this->api = $api;
        $this->fflRepository = $fflRepository;
        $this->countryStateRepository = $countryStateRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish()
    {
        return view($this->_config['view']);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form()
    {
        /** @var Collection $state */
        $states = CountryState::where('country_id', CountryState::USA_COUNTRY_ID)->get();
        return view($this->_config['view'], ['states' => $states]);
    }

    /**
     * @param StoreFflForm $request
     * @return JsonResponse
     */
    public function store(StoreFflForm $request)
    {
        /** saving license image */
        $licensePath = FflLicense::STORAGE_FOLDER . microtime() . $request->input('license_image.name');
        Storage::disk('wassabi_private')->put(
            $licensePath,
            base64_decode($request->input('license_image.file'))
        );
       /* dump($request);
        dump($this->mapRequestToAttributes($request, $licensePath));exit;*/
        $ffl=$this->fflRepository->create($this->mapRequestToAttributes($request, $licensePath));
        //internal email is sent her which includes a time stamp, date, and contact information for follow up.
        $request->merge(['ffl_id' => $ffl->id]);
        try{
            Mail::queue(new NewFflNotification($request->all()));
        }catch (\Exception $e){

        }


        $this->sendLead($request);
        return new JsonResponse([]);
    }

    /**
     * @param StoreFflForm $request
     * @throws \Devvly\Ffl\Services\NutShell\NutshellApiException
     */
    private function sendLead(StoreFflForm $request)
    {
        $state = $this->countryStateRepository->find($request->input('mailing_state'));;
        try {
            $newContact = $this->api->call(Api::NEW_CONTACT_METHOD, [
                'contact' => [
                    'name'        => $request->get('company_name'),
                    'phone'       => [
                        $request->get('phone'),
                    ],
                    'address'     => [
                        $request->get('street_address'),
                        'city'        => $request->get('city'),
                        'state'       => $state->default_name ?? '',
                        'postal_code' => $request->get('city'),
                        'country'     => 'US',
                    ],
                    'description' => 'Payment info ' . $request['payment'],
                    'email'       => $request->get('email'),
                ],
            ]);

            if (!$newContact) {
                return;
            }

            $this->api->call(Api::NEW_LEAD_METHOD, [
                'lead' => [
                    'contacts' => [
                        [
                            'id' => $newContact->id,
                        ],
                    ],
                ],
            ]);
        } catch (GuzzleException $exception) {
            Log::error('Lead sending failed. Request data ' . var_export($request->all(), true));
        }
    }

    /**
     * @param StoreFflForm $request
     * @param string $licensePath
     * @return array
     */
    private function mapRequestToAttributes(StoreFflForm $request, string $licensePath): array
    {
        $social_links=[];
        if($request->input('social_link')){
            array_push($social_links,$request->input('social_link'));
        }
        if($request->input('social_link1')){
            array_push($social_links,$request->input('social_link1'));
        }
        if($request->input('social_link2')){
            array_push($social_links,$request->input('social_link2'));
        }
        if($request->input('social_link3')){
            array_push($social_links,$request->input('social_link3'));
        }
        return [
            /** Base ffl  */
            'is_approved'          => false,
            'source'               => Ffl::ON_BOARDING_ADMIN,
            /** Business info */
            'company_name'         => $request->input('company_name'),
            'contact_name'         => $request->input('contact_name'),
            'retail_store_front'   => $request->input('retail_store'),
            'importer_exporter'    => $request->input('importer_exporter'),
            'website'              => $request->input('website_link'),
            'social'               => json_encode($social_links),
            'street_address'       => $request->input('street_address'),
            'city'                 => $request->input('city'),
            'mailing_state'        => $request->input('mailing_state'),
            'zip_code'             => $request->input('zip_code'),
            'phone'                => $request->input('phone'),
            'email'                => $request->input('email'),
            'business_hours'       => $request->input('business_hours'),
            'state'                => $request->input('mailing_state'),
            'latitude'             => $request->input('position.lat'),
            'longitude'            => $request->input('position.lng'),
            /** License */
            'license_name'       => $request->input('license_name'),
            'license_number'       => $request->input('license_number'),
            'license_file'         => $licensePath,
            'license_region'       => $request->input('license_number_parts.first'),
            'license_district'     => $request->input('license_number_parts.second'),
            'license_fips'         => $request->input('license_number_parts.third'),
            'license_type'         => $request->input('license_number_parts.fourth'),
            'license_expire_date'  => $request->input('license_number_parts.fifth'),
            'license_sequence'     => $request->input('license_number_parts.sixth'),
            /** Transfer fees */
            'long_gun'             => $request->input('long_gun'),
            'long_gun_description' => $request->input('long_gun_description'),
            'hand_gun'             => $request->input('hand_gun'),
            'hand_gun_description' => $request->input('hand_gun_description'),
            'nics'                 => $request->input('nics'),
            'nics_description'     => $request->input('nics_description'),
            'other'                => $request->input('other'),
            'other_description'    => $request->input('other_description'),
            'payment'              => $request->input('payment'),
            'comments'             => $request->input('comments'),
        ];
    }
}
