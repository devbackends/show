<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use App\Jobs\Scraper;
use App\Repositories\FirearmTrainingRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Webkul\Core\Repositories\CountryStateRepository;
use Webkul\Marketplace\Mail\MarketplaceContactNotification;

class InstructorController extends Controller
{
    /** @var array|Request|string  */
    protected $_config;
    /** @var FirearmTrainingRepository  */
    protected $instructorRepository;

    /**
     * InstructorController constructor.
     * @param FirearmTrainingRepository $firearmTrainingRepository
     */
    public function __construct(FirearmTrainingRepository $firearmTrainingRepository)
    {
        $this->_config = request('_config');
        $this->instructorRepository = $firearmTrainingRepository;
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

        $instructors = $this->instructorRepository
            ->select('firearm_trainings.*', DB::raw('GROUP_CONCAT(class_time SEPARATOR \',\') as dates'))
            ->groupBy('title', 'instructor')
            ->with('stateRelation')
            ->where('class_date', '>=', date('Y-m-d H:i:s'))
            ->whereState($stateCode ?? 'FL')
            ->paginate(6);

        return view($this->_config['view'], compact('instructors'));
    }

    /**
     * @param string $state
     * @param string $instructor
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function get(string $state, string $instructor)
    {
        $instructor = ucwords(str_replace('-', ' ', $instructor));
        $stateCode = app()->make(CountryStateRepository::class)->where([
            'default_name' => ucfirst($state)
        ])->first();

        if (isset($stateCode->code) && $stateCode->code !== null) {
            $instructorData = $this->instructorRepository->where([
                'state' => $stateCode->code,
                'instructor' => strtoupper($instructor)
            ])->first();
        } else {
            $instructorData = $this->instructorRepository->where([
                'instructor' => strtoupper($instructor)
            ])->first();
        }
        $instructorData['location'] =  $instructorData['city_name']. ', '.$instructorData['state'];
        $instructorData['map_location'] = urlencode($instructorData['state'] . ' ' . $instructorData['city_name']);

        $instructorData['dates'] = explode(
            ',',
            $this->instructorRepository
                ->select('firearm_trainings.*', DB::raw('GROUP_CONCAT(class_time SEPARATOR \',\') as dates'))
                ->where('title', $instructorData['title'])
                ->where('instructor', $instructorData['instructor'])
                ->groupBy('title', 'instructor')
                ->first()['dates']
        );

        return view($this->_config['view'], compact('instructorData'));
    }

    public function contact(int $instructorId)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'query' => 'required',
        ]);
        $instructor = $this->instructorRepository->find($instructorId);
        if (!$instructor) {
            return response()->json([
                'success' => false,
                'message' => ''
            ]);
        }

        $data = [
            'to' => [
                'email' => $instructor['contact_email'],
                'name' => $instructor['instructor']
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
    public function apiInstructors(Request $request)
    {
        $request = $request->all();
        $builder = $this->instructorRepository;

        if ($request['state'] !== 'all') {
            $builder = $builder->whereState($request['state']);
        }

        if ($request['address'] && $request['radius']) {
            $instructors = $this->instructorRepository
                ->select('firearm_trainings.*', DB::raw('GROUP_CONCAT(class_time SEPARATOR \',\') as dates'))
                ->groupBy('title', 'instructor')
                ->with('stateRelation')
                ->get();
            $address = $request['address'] . ($request['state'] !== 'all' ? (', ' . $request['state']) : '');
            list($originLatitude, $originLongitude) = Scraper::findCoordinatesForLocation($address);

            $instructors = array_filter($instructors->all(), function ($instructor) use ($originLatitude, $originLongitude, $request) {
                $distance = Scraper::distance($originLatitude, $originLongitude, $instructor->latitude, $instructor->longitude);
                return (int)$distance < $request['radius'];
            });
            $page = Paginator::resolveCurrentPage() ?: 1;
            $items = collect(array_values($instructors));

            return new LengthAwarePaginator(array_values($items->forPage($page, 6)->all()), $items->count(), 6, $page);
        }

        return response()->json($builder->with('stateRelation')->paginate(6));
    }
}