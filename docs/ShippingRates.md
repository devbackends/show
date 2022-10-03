# Shipping Rates

This document describes how Shipping Rates detection for Checkout works. It is third step on checkout sequence after 
Billing Address and Shipping Address selection.

It all starts from call to this endpoint `/api/checkout/shipping-rates`, and this route is reletad to this controller 
action `\Webkul\PWA\Http\Controllers\Shop\CartController@getShippingRates`. This action executes `collectRates` method 
of `Webkul\Shipping\Shipping` service.

There are four groups of rates exist:
1) Per Product Rates - this group is for not `firearm` products, that have `shipping_price` attribute set
2) Per Product Rates FFL - this group is for `firearm` products, that have `shipping_price` attribute set
2) FFL rates - this group is for `firearm` products, that don't have `shipping_price` attribute value
3) Other rates - this group is for not `firearm` products, that don't have `shipping_price` attribute value

From now, I will describe what `collectRates` method doing step by step
1) Get specific seller Cart by `sellerId` parameter and then, get Seller by id
2) Group all CartItems by described above four groups. Group rules:
    - If `free_shipping` or `shipping_price` CartItem Product attributes have value - it is 1 or 2 group. If Product 
      AttributeFamily is `firearm` then it is second group, if not - first. Also, here we calculate the shipping rate 
      price for item, also depending on `shipping_price_additional` Product Attribute
    - If it is not PerProduct Item - detect 3 or 4 group relating on AttributeFamily of the product.
3) At this step, we are checking the `$refresh`*1 parameter, and if it is true - break method execution and **return** 
   grouped rates
4) If `$refresh` is false - remove all shipping rates related to this Cart (if exist)
5) Create instance of `CartShippingRate` for every rate from PerProduct Groups (1, 2), dependign on group, we will set
   the `ffl` property of `CartShippingRate`. Store all created rates in `rates` property of service
6) Go throw all available `carries` and generate rates for 3 and 4 groups
    - For seller with id 0 (10 vet products or admin products) we use ONLY *TableRate* Shipping Method, so we skip all 
      other carriers
    - For all other sellers we check `shipping_methods` property of Seller and skip disabled carries. Then we skip 
      all carries except those, whose code start on `mp`, it is *FEDEX*, *UPS* and *USPS*
7) Execute carrier service adn store generated rates in `rates` property of service
8) **Return** all generated grouped rates

Remarks
*1 - `collectRates` method has two work flows, which depend on `$refresh` parameter. If `$refresh` is true (it is default
parameter value) we will generate new rates, but if it is false we will get already existing ones, group them and return
to user.