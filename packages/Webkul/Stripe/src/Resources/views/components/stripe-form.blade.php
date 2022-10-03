<div class="card justify-content-center stripe-card-shadow">
    <div class="card-body">
        <button type="button" class="close payment-cancel" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <img class="stripe-logo mx-auto d-block" src="{{ asset('vendor/webkul/stripe/assets/images/icons/stripe-logo.png') }}" style="height: 70px;"/>
        <div class="stripe-form-content">
            <form  id="stripe-payment-form">
                <div class="stripe-fields new-card" style="display: flex; flex-direction: column; justify-content: center; align-items: center; font-family: 'Montserrat', sans-serif; padding-left: 30px; padding-right: 30px;">
                    
                    <div id="card-number" class="control-group form-control card-number-field"></div>
                    <!-- Used to display card number date error -->
                    <div class="stripe-errors" id="card-number-error" role="alert"></div>
        
                    <div class="stripe-field-combinator" style="display: flex; width: 100%; flex-direction: row; justify-content: space-between; align-items: center;">
                        <div id="card-expiry" class="control-group" style="background: #FFFFFF; border: 1px solid #979797; border-radius: 5px; width: 65%; height: 43px; border-radius: 3px; font-size: 16px; padding-left: 15px; padding-top: 12px;"></div>
        
                        <div id="card-cvc" class="control-group" style="background: #FFFFFF; border: 1px solid #979797; border-radius: 5px; width: 30%; height: 43px; border-radius: 3px; font-size: 16px; padding-left: 15px; padding-top: 12px;"></div>
                    </div>

        
                    <!-- Used to display card expiry date error -->
                    <div class="stripe-errors" id="card-expiration-error" role="alert"></div>
                    <button class="btn btn-primary btn-lg" id="stripe-pay-button" style="border-radius: 3px !important;">
                        {{ __('stripe::app.pay-now') }} ( {{ core()->currency(\Cart::getCart()->base_grand_total) }} )
                    </button>
        
                </div>
            </form>
        
            
            @php
                $cards = collect();
                if(auth()->guard('customer')->check()) {
                    $customer_id = auth()->guard('customer')->user()->id;
                    $cards = app('Webkul\Stripe\Repositories\StripeRepository')->findWhere(['customer_id' => $customer_id]);
                }
            @endphp

            @if ($cards->count() > 0)
                <p class="text-center new-card">OR</p>
                <a href="#"><p class="text-center saved-card-payment new-card" style="cursor:pointer;">{{ __('stripe::app.pay-with-saved-card') }}</p></a>
            @endif

            @if(auth()->guard('customer')->check())
                <div id="saved-cards" class="saved-old-card">
                    <div class="control-info mt-10 mb-10">
                        @foreach($cards as $card)
                                <div class="stripe-card-info mb-2" id="{{ $card->id }}">
                                    <label class="radio-container">
                                        <input type="radio" name="saved-card" class="saved-card-list" id="{{ $card->id }}" value="{{ $card->id }}">
                                        <span class="checkmark"></span>
                                    </label>
                                    <table>
                                        <tr>
                                            <td width="130px">
                                                <span class="card-last-four">*** *** {{ $card->last_four }}</span>
                                            </td>
                                            <td>
                                                <a id="delete-card" class="btn btn-danger" style="cursor: pointer;margin-left:10px;color:white;" data-id="{{ $card->id }}">{{ __('stripe::app.delete') }}</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                        @endforeach
                    </div>
                      <div class="col text-center">
                            <button class="btn btn-primary btn-lg btn-center old-stripe-button"  style="border-radius: 3px !important;">
                                {{ __('stripe::app.pay-now') }} ( {{ core()->currency(\Cart::getCart()->base_grand_total) }} )
                            </button>
                      </div>
                      <p class="text-center old-card" style="margin-top:33px;">OR</p>
                      <p class="text-center saved-card-payment old-card" style="cursor:pointer;"><a href="#">{{ __('stripe::app.pay-with-new-card') }}</a></p>
                </div>
            @endif
        </div>
    </div>
  </div>

