#Fluid Payment

Fluid Payment - is service, that provies API for payments.
It is quite simple, there is a package `\Devvly\FluidPayment` and all fluid logic stored there.
Also, there is only one place in whole application, where requests to fluid api exists - 
`\Devvly\FluidPayment\Services\FluidApi`.

###Fluid Payment in checkout
1) There is a class registered as Bagisto Payment Method - `\Devvly\FluidPayment\Payment\FluidPayment`. This class has 
   method `getRedirectUrl` and it returns a route on which user will be redirected, if fluid is selected as checkout 
   payment method, after pressing "Save Order" button.
2) The route from `getRedirectUrl` will lead user to this action 
   `\Devvly\FluidPayment\Http\Controllers\PaymentController@createTransaction`
    - Collect options, that we need for fluid transaction request. The main thing at this step is
      `payment_method` parameter, it can be token or customer. 
        - Token. We generate via Tokenizer Service provided by Fluid, you can read about it 
          [here](https://sandbox.fluidpay.com/docs/tokenizer/)
        - Customer. To create transaction via customer we need `customer_id` and `payment_method_id`, we are storing
          them in `fluid_customers` table. How and where? Pation =)
    - Call FluidApi with seller api key
        - Make request with already collected options
        - If transaction was made with token, we will create FluidCard instance with needed parameters
    - If everything is okay, create order and invoice
    - Response...

###Fluid Payment Subscription and Charge
There are three services in `\Devvly\FluidPayment\Services` namespace: `FluidCustomer`, `FluidCharge` and 
`FluidSubscription`. Seller Types logic using this services to handle seller plan configuration.

If subscription is enabled on seller plan - we will use FluidSubscription service.
FluidSubscription service has `createSubscription` method which will be executed on subscription registration
1) Initial Charge. We need to check card credentials provided by user first, to avoid not needed actions later, if user
   will have low balance or something like this. So we are getting the subscription one cycle amount and creating
   transaction, and if everything is okay - we will proceed.
2) Subscription Registration. We already charged user for first month, that is why we are setting next bulling day
   to next month. Then we are making simple HTTP Request to FLuid Api to create subscription for specific customer