<div class="cart-details__summary">
    <p class="cart-details__summary-title">{{ __('shop::app.checkout.total.order-summary') }}</p>

    <div class="item-detail row">
        <label class="col">
            {{ intval($cart->items_qty) }}
            {{ __('shop::app.checkout.total.sub-total') }}
            {{ __('shop::app.checkout.total.price') }}
        </label>
        <label class="col-auto">{{ core()->currency($cart->base_sub_total) }}</label>
    </div>

    @if ($cart->selected_shipping_rate)
        <div class="item-detail row">
            <label class="col">{{ __('shop::app.checkout.total.delivery-charges') }}</label>
            <label class="col-auto">{{ core()->currency($cart->selected_shipping_rate->base_price) }}</label>
        </div>
    @endif

    @if ($cart->base_tax_total)
        @foreach (Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($cart, true) as $taxRate => $baseTaxAmount )
        <div class="item-detail row">
            <label class="col" id="taxrate-{{ core()->taxRateAsIdentifier($taxRate) }}">{{ __('shop::app.checkout.total.tax') }} {{ $taxRate }} %</label>
            <label class="col-auto" id="basetaxamount-{{ core()->taxRateAsIdentifier($taxRate) }}">{{ core()->currency($baseTaxAmount) }}</label>
        </div>
        @endforeach
    @endif

    <div class="item-detail row" id="discount-detail" @if ($cart->base_discount_amount && $cart->base_discount_amount > 0) style="display: block;" @else style="display: none;" @endif>
        <label class="col">
            {{ __('shop::app.checkout.total.disc-amount') }}
        </label>
        <label class="col-auto">
            -{{ core()->currency($cart->base_discount_amount) }}
        </label>
    </div>


    <div class="cart-details__summary-total row" id="grand-total-detail">
        <label class="col">{{ __('shop::app.checkout.total.grand-total') }}</label>
        <label class="col-auto" id="grand-total-amount-detail">
            {{ core()->currency($cart->base_grand_total) }}
        </label>
    </div>
</div>