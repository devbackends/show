<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use App\Jobs\Scraper;
use App\Repositories\GunRangeRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Webkul\Core\Repositories\CountryStateRepository;
use Webkul\Marketplace\Mail\MarketplaceContactNotification;

class GunRangeController extends Controller
{
    /** @var array|Request|string  */
    protected $_config;
    /** @var GunRangeRepository  */
    protected $gunRangeRepository;

    /**
     * GunRangeController constructor.
     * @param GunRangeRepository $gunRangeRepository
     */
    public function __construct(GunRangeRepository $gunRangeRepository)
    {
        $this->_config = request('_config');
        $this->gunRangeRepository = $gunRangeRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function index()
    {
        if ($clientState = $this->getClientLocationData()) {
            if ($state = app()->make(CountryStateRepository::class)->findByField('default_name', $clientState)->first()) {
                $stateCode = $state->code;
            }
        }
        $gunRanges = $this->gunRangeRepository->with('stateRelation')->whereState($stateCode ?? 'FL')->paginate(6);
        $states = app()->make(CountryStateRepository::class)->findByField('country_code', 'US');

        return view($this->_config['view'], compact('gunRanges', 'states'));
    }

    public function get(string $state, string $gunRange)
    {
        $gunRange = ucwords(str_replace('-', ' ', $gunRange));
        $stateCode = app()->make(CountryStateRepository::class)->where([
            'default_name' => ucfirst($state)
        ])->first();

        if (isset($stateCode->code) && $stateCode->code !== null) {
            $gunRangeData = $this->gunRangeRepository->where([
                'state' => $stateCode->code,
                'name' => strtoupper($gunRange)
            ])->first();
        } else {
            $gunRangeData = $this->gunRangeRepository->where([
                'name' => strtoupper($gunRange)
            ])->first();
        }

        $gunRangeData['location'] = ($gunRangeData['address1'] ?? '') . ' ' .  ($gunRangeData['address2'] ?? '') . ' '. ($gunRangeData['address3'] ?? '');
        $gunRangeData['map_location'] = urlencode($gunRangeData['state'] ?? '' . ' ' . $gunRangeData['city'] . ' ' . $gunRangeData['zip_code']) . ' ' . $gunRangeData['address1'];

        if ($gunRangeData['web']) {
            $gunRangeData['web'] = strpos($gunRangeData['web'], 'http') !== false ? $gunRangeData['web'] : ('http://' . $gunRangeData['web']);
        }

        return view($this->_config['view'], compact('gunRangeData'));
    }

    public function contact(int $id)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'query' => 'required',
        ]);

        $range = $this->gunRangeRepository->find($id);
        if (!$range) {
            return response()->json([
                'success' => false,
                'message' => ''
            ]);
        }

        $data = [
            'to' => [
                'email' => $range->email,
                'name' => $range->name
            ],
            'from' => [
                'email' => request()->get('email'),
                'name' => request()->get('name'),
            ],
            'subject' => request()->get('subject'),
            'query' => request()->get('query')
        ];

        try {
            Mail::send(new MarketplaceContactNotification($data));
            return response()->json([
                'success' => true,
                'message' => 'Email has been sent successfully. Instructor will contact you as soon as possible.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|LengthAwarePaginator
     */
    public function apiGunRanges(Request $request)
    {
        $request = $request->all();
        $builder = $this->gunRangeRepository;

        if ($request['state'] !== 'all') {
            $builder = $builder->whereState($request['state']);
        }

        if ($request['address'] && $request['radius']) {
            $gunRanges = $builder->with('stateRelation')->get();
            $address = $request['address'] . ($request['state'] !== 'all' ? (', ' . $request['state']) : '');
            list($originLatitude, $originLongitude) = Scraper::findCoordinatesForLocation($address);

            $gunRanges = array_filter($gunRanges->all(), function ($gunRange) use ($originLatitude, $originLongitude, $request) {
                $distance = Scraper::distance($originLatitude, $originLongitude, $gunRange->latitude, $gunRange->longitude);
                return (int)$distance < $request['radius'];
            });
            $page = Paginator::resolveCurrentPage() ?: 1;
            $items = collect(array_values($gunRanges));

            return new LengthAwarePaginator(array_values($items->forPage($page, 6)->all()), $items->count(), 6, $page);
        }

        return response()->json($builder->with('stateRelation')->paginate(6));
    }
}