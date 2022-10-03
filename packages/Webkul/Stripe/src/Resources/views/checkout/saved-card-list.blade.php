@if($payment['method'] == "stripe")
    @include('stripe::components.saved-cards')
@endif
