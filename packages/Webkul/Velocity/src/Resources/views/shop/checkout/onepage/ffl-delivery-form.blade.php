@php
    $isCustomer = auth()->guard('customer')->check();
@endphp

@if (isset($ffl_delivery) && $ffl_delivery)
    <div class="col-lg-12">
        <span class="paragraph regular-font">
            Certain items in your cart must be delivered to a Federal Firearms License (FFL) holder. Please select an FFL for delivery, or select pickup directly from our store.
        </span>
    </div>
    <div class="col-md-8 padding-tb-15">
        <div style="height: 50px;">
            <button id="select-an-ffl-delivery-button" class="btn btn-outline-primary">Select an FFL for delivery</button>
        </div>

        <div style="height: 50px;">
            <div class="radio-input-container" >
                <div class="" style="float: left">
                                    <span class="custom-radio-box">
                    <input type="radio" name="ffl-delivery"   value="62">
                    <label  for="custom-radio-view" class="custom-radio-view"></label>
                </span>
                </div>
                <div class="radio-text-container">
                    <span class="paragraph">Ground</span>
                    <p class="s-paragraph no-margin">Free</p>
                </div>
            </div>
            <div class="radio-input-container" >
                <div class="" style="float: left">
                                    <span class="custom-radio-box">
                    <input type="radio" name="ffl-delivery"   value="62">
                    <label  for="custom-radio-view" class="custom-radio-view"></label>
                </span>
                </div>
                <div class="radio-text-container">
                    <span class="paragraph">Second day</span>
                    <p class="s-paragraph no-margin">$20.00</p>
                </div>
            </div>
            <div class="radio-input-container" >
                <div class="" style="float: left">
                                    <span class="custom-radio-box">
                    <input type="radio" name="ffl-delivery"   value="62">
                    <label  for="custom-radio-view" class="custom-radio-view"></label>
                </span>
                </div>
                <div class="radio-text-container">
                    <span class="paragraph">Over night</span>
                    <p class="s-paragraph no-margin">$20.00</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 padding-tb-15">
        <div><button id="pick-up-from-our-store-button" class="btn btn-outline-primary">Pick up from our store</button></div>
        <div><span class="paragraph">
                We are 24.3 miles from your preferred shipping address
            </span>
        </div>
    </div>

    <div class="col-md-12 right">
        <button type="button" class="theme-btn fs16 fw6">Next</button>
    </div>

@endif
