@php
    $cards = collect();
    if(auth()->guard('customer')->check()) {
        $customer_id = auth()->guard('customer')->user()->id;
        $cards = app('Webkul\StripeConnect\Repositories\StripeRepository')->findWhere(['marketplace_seller_id' => $customer_id]);
    }
@endphp

@if(auth()->guard('customer')->check())
    <div class="stripe-cards-block" id="saved-cards">
        <div class="control-info mt-10 mb-10">
            @foreach($cards as $card)
                @if(!$card->need_new_token)
                    <div class="stripe-card-info" id="{{ $card->id }}">
                        <label class="radio-container">
                            <input type="radio" name="saved-card" class="saved-card-list" id="{{ $card->id }}" value="{{ $card->id }}">
                            <span class="checkmark"></span>
                        </label>
                        <span class="icon currency-card-icon"></span>
                        <span class="card-last-four">*** *** {{ $card->last_four }}</span>
                        <a id="delete-card" style="cursor: pointer;" data-id="{{ $card->id }}">{{ __('stripe::app.delete') }}</a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif