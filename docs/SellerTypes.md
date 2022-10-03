#Seller Types

Currently, there are four possible types of seller
1) Basic
2) Plus
3) Pro (it is not available now, this means that seller has merchant store)
4) God (it is available only for 10 vet, and setted manually in db, we need this to disable all charges for 10 vet)

There is a config file `packages/Webkul/Marketplace/src/Config/seller-types.php` and in this file we have configuration for
all types (sometimes we call thme plans) of seller. There is three possible actions or configuration items:
- Listing Fee - fee that we charge from seller on product creation.
- Order Comission - comission that we charge from seller, when someone buys he's product(s)
- Subscription - fee on which we will charge user every period of time (day, week, month, currently it is month)
And in `seller-types.php` config file we have enabled or disabled this three config items. I tried to develop this feature
flexible, so that we will have ability to just change config file and everything will work, but in some places it was
impossible, so if you will change something (except charge amount, you can change it only here, and everything will
work), please, test everything to make sure nothing is broke.
  
THe main seller types logic stored in this service `Webkul\Marketplace\Service\SellerType`. It has three public methods,
for every configuration items. 

###Subscription
Let's first talk about `init` method, which is responsible for subscription initialization
This method is getting called in `Webkul\Marketplace\Http\Controllers\Shop\Account\SellerOnboardingController@storeSeller`
Lets go step by step on this method
1) Fluid Customer registration. For convenience, we are using Fluid Customer Api, it allows us to store all customer
   creds on fluid side, and simply use when we want. At this step we need billing info and token
   [(Fluid Tokenizer)](https://sandbox.fluidpay.com/docs/tokenizer/).
2) Subscription initialization. We check, if current seller plan has subscription enabled, and if yes we call
   `FluidSubscription@createSubscription` method
3) First Listing Fee. if subscription disabled, we check if listing fee enabled, and if yes, we will charge
   user for listing fee amount, to check solvency of seller. Later, on usual listing fee calling, we will check, if
   it is first product creation, and if yes - we won't charge user.
4) If subscription and listing fee is disabled we will do nothing, currently this case is accessible only for
   god type.
   
###Listing Fee
Method `listingFee` is getting called in two places:
1) `Webkul\Marketplace\Repositories\ProductRepository@create`
2) `Webkul\Marketplace\Repositories\ProductRepository@createAssign`
It simply checks if this method enabled, and if yes - makes charge.
   
###Order Commission
Method `orderCommission` is getting called in one place `Webkul\Marketplace\Repositories\OrderRepository@create`, and it
simply calculates the charge amount from order and makes charge request to fluid.
   
