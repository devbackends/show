@if($payment['method'] == "authorize")
    @include('authorize::components.add-card')
    @include('authorize::components.saved-cards')
@endif