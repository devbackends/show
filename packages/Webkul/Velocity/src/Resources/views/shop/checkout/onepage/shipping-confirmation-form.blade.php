@php
    $isCustomer = auth()->guard('customer')->check();
@endphp

@if (isset($shipping_confirmation) && $shipping_confirmation)
    <div class="col-md-12">
        <h4 class="heading">Firearms</h4>
    </div>
    <div class="col-md-6 products-container">
        <div class="top-products-border"></div>
        <div class="bottom-products-border"></div>
        <div class="product-container">
            <div class="product-image-container">
                <img src="{{asset('images/glock-Image.png')}}">
            </div>
            <div class="product-description-container padding-sides-10 ">
                <div class="quantity-container">
                    <span>1 x</span>
                </div>
                <div class="inner-description-container">
                    <span class="paragraph regular-font">22 LR 10+1 5 BARREL 2.2 LBS</span>
                    <p class="no-margin paragraph bold">$4567.00</p>
                </div>
            </div>
        </div>
        <div class="product-container">
            <div class="product-image-container">
                <img src="{{asset('images/glock-Image.png')}}">
            </div>
            <div class="product-description-container padding-sides-10 ">
                <div class="quantity-container">
                    <span>1 x</span>
                </div>
                <div class="inner-description-container">
                    <span class="paragraph regular-font">22 LR 10+1 5 BARREL 2.2 LBS</span>
                    <p class="no-margin paragraph bold">$4567.00</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <p class="paragraph no-margin padding-tb-5">Your firearm will be delivered to the following FFL:</p>
            <p class="paragraph bold no-margin padding-tb-5">Gander Outdoors</p>
            <p class="paragraph no-margin padding-tb-5">6802 118th ave, Kenosha, WI 53142</p>
            <p class="paragraph no-margin padding-tb-5">( 12 mi. )</p>
            <p class="paragraph no-margin padding-tb-5">Ground delivery (Free)</p>
        </div>
        <div class="delivery-location-container padding-tb-5">
            <a href="http://paravision.localhost/devvly/2agun/public/customer/register" class="bordered-button black-bordered-button left"><span>Change delivery location</span></a>
        </div>
    </div>

    <div class="col-md-12">
        <h4 class="heading">OTHER PRODUCTS</h4>
    </div>

    <div class="col-md-6 products-container">
        <div class="top-products-border"></div>
        <div class="bottom-products-border"></div>
        <div class="product-container">
            <div class="product-image-container">
                <img src="{{asset('images/futaba.png')}}">
            </div>
            <div class="product-description-container padding-sides-10 ">
                <div class="quantity-container">
                    <span>1 x</span>
                </div>
                <div class="inner-description-container">
                    <span class="paragraph regular-font">22 LR 10+1 5 BARREL 2.2 LBS</span>
                    <p class="no-margin paragraph bold">$4567.00</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <p class="paragraph no-margin padding-tb-5">These items will be delivered to:</p>
            <p class="paragraph bold no-margin padding-tb-5">Gregory Tanacea</p>
            <p class="paragraph no-margin padding-tb-5">6500 400th ave, Burlington, WI 53105, 563-580-4493</p>
            <p class="paragraph no-margin padding-tb-5">( 12 mi. )</p>
            <p class="paragraph no-margin padding-tb-5">Ground delivery (Free)</p>
        </div>
        <div>
            <span class="checkbox fs16 display-inbl no-margin custom-check-box">
                <input type="checkbox" id="" name="" value="62" class="">
                <label for="custom-checkbox-view" class="custom-checkbox-view"></label>
            </span>
            <span class="paragraph regular-font">Pick up in our store</span>
        </div>
        <div class="delivery-location-container padding-tb-5">
            <a href="http://paravision.localhost/devvly/2agun/public/customer/register" class="bordered-button black-bordered-button left"><span>Change delivery location</span></a>
        </div>
    </div>
    <div class="col-md-12">
        <div class="accept-shipping-confirmation-container padding-tb-30">
            <span class="checkbox fs16 display-inbl no-margin custom-check-box">
                <input type="checkbox" id="" name="" value="62" class="">
                <label for="custom-checkbox-view" class="custom-checkbox-view"></label>
            </span>
            <span class="paragraph regular-font">Accept terms and conditions before placing the order</span>
        </div>
    </div>
@endif
