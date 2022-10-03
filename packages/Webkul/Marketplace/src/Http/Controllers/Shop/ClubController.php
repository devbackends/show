<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use App\Jobs\Scraper;
use App\Repositories\ClubRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Webkul\Core\Repositories\CountryStateRepository;
use Webkul\Marketplace\Mail\ContactClubNotification;

class ClubController extends Controller
{
    /** @var array|Request|string  */
    protected $_config;
    /** @var ClubRepository  */
    protected $clubRepository;

    /**
     * ClubController constructor.
     * @param ClubRepository $clubRepository
     */
    public function __construct(ClubRepository $clubRepository)
    {
        $this->_config = request('_config');
        $this->clubRepository = $clubRepository;
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

        $clubs = $this->clubRepository
            ->with('stateRelation')
            ->whereState($stateCode ?? 'FL')
            ->paginate(6);

        return view($this->_config['view'], compact('clubs'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|LengthAwarePaginator
     */
    public function api(Request $request)
    {
        $request = $request->all();
        $builder = $this->clubRepository;

        if ($request['state'] !== 'all') {
            $builder = $builder->whereState($request['state']);
        }

        if ($request['address'] && $request['radius']) {
            $clubs = $builder->with('stateRelation')->get();
            $address = $request['address'] . ($request['state'] !== 'all' ? (', ' . $request['state']) : '');
            list($originLatitude, $originLongitude) = Scraper::findCoordinatesForLocation($address);

            $clubs = array_filter($clubs->all(), function ($club) use ($originLatitude, $originLongitude, $request) {
                $distance = Scraper::distance($originLatitude, $originLongitude, $club->latitude, $club->longitude);
                return (int)$distance < $request['radius'];
            });
            $page = Paginator::resolveCurrentPage() ?: 1;
            $items = collect(array_values($clubs));

            return new LengthAwarePaginator(array_values($items->forPage($page, 6)->all()), $items->count(), 6, $page);
        }

        return response()->json($builder->with('stateRelation')->paginate(6));
    }

    /**
     * @param string $state
     * @param string $title
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function get(string $state, string $title)
    {
        $club = ucwords(str_replace('-', ' ', $title));
        $stateCode = app()->make(CountryStateRepository::class)->where([
            'default_name' => ucfirst($state)
        ])->first();

        if (isset($stateCode->code) && $stateCode->code !== null) {
            $clubData = $this->clubRepository->where([
                'state' => $stateCode->code,
                'club_name' => strtoupper($club)
            ])->first();
        } else {
            $clubData = $this->clubRepository->where([
                'club_name' => strtoupper($club)
            ])->first();
        }

        $clubData['location'] = ($clubData['address1'] ?? '') . ' ' .  ($clubData['address2'] ?? ' ' . $clubData['state'] ?? ' ' . $clubData['city'] ?? ' ' . $clubData['zip'] ?? '');
        $clubData['map_location'] = urlencode($clubData['state'] ?? '' . ' ' . ($clubData['city'] ?? '') . ' ' . ($clubData['zip'] ?? '') . ' ' . ($clubData['address1'] ?? ''));

        if (isset($clubData['web']) && $clubData['web']) {
            $clubData['web'] = strpos($clubData['web'], 'http') !== false ? $clubData['web'] : ('http://' . $clubData['web']);
        }

        return view($this->_config['view'], compact('clubData'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function contact($id)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'query' => 'required',
        ]);

        $club = $this->clubRepository->whereId($id)->first();

        try {
            Mail::send(new ContactClubNotification($club, request()->all()));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Email has been sent successfully.'
        ]);
    }
}