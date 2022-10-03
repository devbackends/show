<?php


namespace Webkul\Marketplace\Http\Controllers\Shop;

use App\Jobs\Scraper;
use App\Repositories\ShowRepository;
use App\Repositories\ShowsPromoterRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Webkul\Core\Repositories\CountryStateRepository;
use Webkul\Marketplace\Mail\MarketplaceContactNotification;

class ShowController extends Controller
{
    /** @var array|Request|string  */
    protected $_config;

    /** @var mixed  */
    protected $showRepository;
    /** @var mixed  */
    protected $promoterRepository;

    /**
     * ShowController constructor.
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->_config = request('_config');
        $this->showRepository = app()->make(ShowRepository::class);
        $this->promoterRepository = app()->make(ShowsPromoterRepository::class);
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
        $shows = $this->showRepository->with('stateRelation')->whereState($stateCode ?? 'FL')->paginate(6);
        $states = app()->make(CountryStateRepository::class)->findByField('country_code', 'US');

        return view($this->_config['view'], compact('shows', 'states'));
    }

    /**
     * @param string $state
     * @param string $title
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function get(string $state, string $title)
    {
        $title = ucwords(str_replace('-', ' ', $title));
        $stateCode = app()->make(CountryStateRepository::class)->where([
            'default_name' => ucfirst($state)
        ])->first();
        if (isset($stateCode->code) && $stateCode->code !== null) {
            $show = $this->showRepository->where([
                'state' => $stateCode->code,
                'title' => $title
            ])->first();
        } else {
            $show = $this->showRepository->where([
                'title' => $title
            ])->first();
        }

        $show->dates = (isset($show->dates)) ? !empty($show->dates) ? implode(',', $show->dates) : null : null;
        $promoter = $show->promoter()->first();
        $promoterShows = $promoter->shows()->with('stateRelation')->paginate(6);
        $show->map_location = urlencode($show->location);

        return view($this->_config['view'], compact('show', 'promoter', 'promoterShows'));
    }

    /**
     * @param string $name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPromoter(string $name)
    {
        $promoter = $this->promoterRepository->findOneByField('name', ucwords(implode(' ',explode('-', $name))));

        $regex = '/https?\:\/\/[^\" \n]+/i';
        $contacts = collect(array_filter($promoter->contact))->map(function ($item) use ($regex) {
            preg_match_all($regex, $item, $matches);
            foreach ($matches[0] as $url) {
                $replace = '<a href="' . $url . '" target="_blank">' . $url . '</a>';
                $item = str_replace($url, $replace, $item);
            }

            return $item;
        })->toArray();

        $promoter->contact = implode(', ', $contacts);
        $promoterShows = $promoter->shows()->with('stateRelation')->paginate(6);

        return view($this->_config['view'], compact('promoter', 'promoterShows'));
    }

    public function contact(int $id)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'query' => 'required',
        ]);

        $promoter = $this->promoterRepository->find($id);
        if (!$promoter) {
            return response()->json([
                'success' => false,
                'message' => ''
            ]);
        }

        $data = [
            'to' => [
                'email' => $promoter->email,
                'name' => $promoter->name
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
    public function apiShows(Request $request)
    {
        $request = $request->all();
        $builder = $this->showRepository;

        if ($request['state'] !== 'all') {
            $builder = $builder->whereState($request['state']);
        }

        if ($request['address'] && $request['radius']) {
            $shows = $builder->with('stateRelation')->get();
            $address = $request['address'] . ($request['state'] !== 'all' ? (', ' . $request['state']) : '');
            list($originLatitude, $originLongitude) = Scraper::findCoordinatesForLocation($address);

            $shows = array_filter($shows->all(), function ($show) use ($originLatitude, $originLongitude, $request) {
                $distance = Scraper::distance($originLatitude, $originLongitude, $show->latitude, $show->longitude);
                return (int)$distance < $request['radius'];
            });
            $page = Paginator::resolveCurrentPage() ?: 1;
            $items = collect(array_values($shows));

            return new LengthAwarePaginator(array_values($items->forPage($page, 6)->all()), $items->count(), 6, $page);
        }

        return response()->json($builder->with('stateRelation')->paginate(6));
    }
}