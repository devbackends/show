<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use App\Jobs\Scraper;
use Devvly\Ffl\Models\Ffl;
use Devvly\Ffl\Repositories\FflRepository;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use phpDocumentor\Reflection\Types\Integer;
use Webkul\Core\Repositories\CountryStateRepository;

class FflController extends Controller
{
    /** @var array|Request|string  */
    protected $_config;
    /** @var FflRepository  */
    protected $fflRepository;

    /**
     * FflController constructor.
     * @param FflRepository $fflRepository
     */
    public function __construct(FflRepository $fflRepository)
    {
        $this->_config = request('_config');
        $this->fflRepository = $fflRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $stateCode = 'FL';
        if ($clientState = $this->getClientLocationData()) {
            if ($state = app()->make(CountryStateRepository::class)->findByField('default_name', $clientState)->first()) {
                $stateCode = $state->code;
            }
        }

        $ffls = Ffl::query()->with(['businessInfo.countryState', 'license'])
            ->where('is_approved', '=', 1)
            ->whereHas('businessInfo.countryState', function($q) use($stateCode) {
                $q->where('code', '=', $stateCode);
            })
            ->paginate(6);

        return view($this->_config['view'], compact('ffls'));
    }
    public function getByName(string $state, string $name){
       return $this->get($state,$name);
    }
    public function get(string $state, string $name,string $id = null)
    {
        if(!is_null($id)){
            $builder = $this->fflRepository;
            $builder->whereHas('businessInfo', function($query) use ($id) {
                $query->where('ffl_id', '=', $id);
            });
            $item = $builder->with(['businessInfo'])->get();
        }else{
            $name = ucwords(str_replace('-', ' ', $name));

            $builder = $this->fflRepository;
            $builder->whereHas('businessInfo', function($query) use ($name) {
                $query->where('company_name', '=', $name);
            });
            $item = $builder->with(['businessInfo'])->get();

            if ($item->isEmpty()) {
                $builder = $this->fflRepository;
                $builder->whereHas('license', function($query) use ($name) {
                    $query->where('license_name', '=', $name);
                });
                $item = $builder->with(['businessInfo'])->get();
            }

        }

        $item['location'] = '';
        $item['map_location'] = '';
        $item['map_location'] = '';

        $item = $item->first();

        if($item){
            $item['location'] = ($item->businessInfo->street_address ?? '');
            $item['map_location'] = urlencode(($item->businessInfo->zip_code ?? '') . ' ' . ($item->businessInfo->city ?? '') . ' ' . $item->businessInfo->street_address);
            $item['map_location'] = urlencode($item->businessInfo->zip_code . ' ' . $item->businessInfo->city);
        }


        return view($this->_config['view'], compact('item'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|LengthAwarePaginator
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function api(Request $request)
    {

        $request = $request->all();
        $builder = Ffl::query()->where('is_approved', '=', '1');

        if ($request['state'] !== 'all') {

            $stateId = app()->make(CountryStateRepository::class)->findByField('code', $request['state'])->first()->id;

            $builder->whereHas('businessInfo.countryState', function($query) use ($stateId) {
                $query->where('id', '=', $stateId);
            });
        }


        if ($request['address'] && $request['radius']) {

            $zip_code = $request['address'];
            $builder->whereHas('businessInfo', function ($query) use ($zip_code) {
                $query->where('zip_code', 'like',substr($zip_code, 0, 2).'%');
            });
            $ffls = $builder->with(['businessInfo.countryState', 'license'])->get();

            $address = $request['address'] . ($request['state'] !== 'all' ? (', ' . $request['state']) : '');

            list($originLatitude, $originLongitude) = Scraper::findCoordinatesForLocation($address);
            $ffls = array_filter($ffls->all(), function ($ffl) use ($originLatitude, $originLongitude, $request) {
                $distance = Scraper::distance($originLatitude, $originLongitude, $ffl->businessInfo->latitude, $ffl->businessInfo->longitude);
                return (int)$distance < $request['radius'];
            });

            $page = Paginator::resolveCurrentPage() ?: 1;
            $items = collect(array_values($ffls));

            return new LengthAwarePaginator(array_values($items->forPage($page, 6)->all()), $items->count(), 6, $page);
        }

        return response()->json($builder->with(['businessInfo.countryState', 'license'])->paginate(6));
    }
}