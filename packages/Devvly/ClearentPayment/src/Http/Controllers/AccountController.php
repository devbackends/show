<?php

namespace Devvly\ClearentPayment\Http\Controllers;

use Devvly\Clearent\Models\CustomerOptions;
use Devvly\Clearent\Models\Error;
use Devvly\Clearent\Resources\Resources;
use Devvly\ClearentPayment\Repositories\ClearentCardRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    /**
     * authorizeRepository object
     *
     * @var array
     */
    protected $cardRepository;


    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Clearent resources.
     *
     * @var Resources
     */
    protected $resources;

    public function __construct(
        ClearentCardRepository $cardRepository,
        Resources $resources
    ) {

        $this->_config = request('_config');

        $this->cardRepository = $cardRepository;
        $this->resources = $resources;
    }

    public function cards()
    {
        $cards = [];
        if (auth()->guard('customer')->check()) {
            $customer_id = auth()->guard('customer')->user()->id;
            $cards = $this->cardRepository
                ->orderBy('id', 'DESC')
                ->findWhere(['customers_id' => $customer_id]);
        }
        return new JsonResponse($cards);
    }

    public function storeCard(Request $request)
    {
        $request->validate(
            [
                'jwt_token' => 'required',
                'card_type' => 'required',
                'last_four' => 'required',
                'save' => 'required|boolean',
            ]
        );
        $jwtToken = $request->get('jwt_token');
        $cardType = $request->get('card_type');
        $last4 = $request->get('last_four');
        $save = $request->get('save');
        $card = [
            'jwt_token' => $jwtToken,
            'card_type' => $cardType,
            'last_four' => $last4,
            'save' => $save,
        ];
        if (auth()->guard('customer')->check()) {
            $site_customer = auth()->guard('customer')->user();
            if ($card['save']) {
                /** @var \Devvly\ClearentPayment\Payment\ClearentPayment $payment */
                $payment = app('Devvly\ClearentPayment\Payment\ClearentPayment');
                $billing_address = $payment->getCart()->billing_address;
                $firstName = $billing_address->first_name;
                $lastName = $billing_address->last_name;
                // create a card token:
                $cOptions = new CustomerOptions($firstName, $lastName);
                $customer = $this->resources->customers()->create($cOptions);
                if ($customer instanceof Error) {
                    throw new \Exception($customer->getErrorMessage());
                }
                // 2. create credit card token
                $token = $this->resources->tokens()->create($jwtToken);
                if ($token instanceof Error) {
                    throw new \Exception($token->getErrorMessage());
                }
                // 3. add that token to the created customer:
                $response = $this->resources->customers()->addTokenToCustomer(
                    $token,
                    $customer->getCustomerKey()
                );
                $payload = $response->getPayload();
                if ($payload instanceof Error) {
                    throw new \Exception($payload->getErrorMessage());
                }
                $customerCard = [
                    'customers_id' => $site_customer->id,
                    'exp_date' => $token->getExpDate(),
                    'card_token' => $token->getTokenId(),
                ];
            }
            else {
                $customerCard = [
                    'customers_id' => $site_customer->id,
                ];
            }
            $card = array_merge($card, $customerCard);
        }
        $result = $this->cardRepository->create($card);
        if ($result) {
            return response()->json($result);
        } else {
            return response()->json(['success' => 'false'], 400);
        }
    }


    public function cardDefault()
    {

        $updateIfalreadyDefault = $this->cardRepository->findOneWhere(
            [
                'is_default' => '1',
                'customers_id' => auth()->guard('customer')->user()->id,
            ]
        );

        if ($updateIfalreadyDefault) {

            $updateIfalreadyDefault->update(['is_default' => '0']);
        }
        $updateIfFound = $this->cardRepository->findOneWhere(
            [
                'id' => request()->input('id'),
                'customers_id' => auth()->guard('customer')->user()->id,
            ]
        );

        $result = $updateIfFound->update(['is_default' => '1']);

        if ($result) {
            return response()->json(['success' => 'true']);
        } else {
            return response()->json(['success' => 'false'], 400);
        }

    }

}
