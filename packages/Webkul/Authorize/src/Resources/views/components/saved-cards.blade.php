@php
    $cards = collect();
    if(auth()->guard('customer')->check()) {
        $customer_id = auth()->guard('customer')->user()->id;
        $cards = app('Webkul\Authorize\Repositories\AuthorizeRepository')->findWhere(['customers_id' => $customer_id]);
    }
@endphp

@if(auth()->guard('customer')->check())
    <div class="authorize-cards-block" id="saved-cards" style="display:none;">
        <div class="control-info mt-10 mb-10">
            @foreach($cards as $card)
                    <div class="authroizenet-card-info" id="{{ $card->id }}">
                        <label class="radio-container">
                            <input type="radio" name="saved-card" class="saved-card-list" id="{{ $card->id }}" value="{{ $card->id }}" @if($card->is_default == '1') checked="checked" @endif>
                            <span class="checkmark"></span>
                        </label>
                        <span class="icon currency-card-icon"></span>
                        <span class="card-last-four" style="margin-left:16px;"> {{ $card->last_four }}</span>
                        <a id="delete-card" style="cursor: pointer;" data-id="{{ $card->id }}">{{ __('authorize::app.delete') }}</a>
                    </div>
            @endforeach
        </div>
    </div>
@endif