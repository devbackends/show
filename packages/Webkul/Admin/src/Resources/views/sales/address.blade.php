@if($address)
    <span class="bold capatilize">{{ $address->name }}</span></br>
    @if(!empty($address->address1)) {{ $address->address1 }}</br> @endif
    {{ $address->city }}, {{ $address->state }}, {{ core()->country_name($address->country) }} {{ $address->postcode }}</br>
    {{ __('shop::app.checkout.onepage.contact') }} : {{ $address->phone }}
@endif