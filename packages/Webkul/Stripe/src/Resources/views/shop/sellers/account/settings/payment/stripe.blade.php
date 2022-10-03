@php($stripeCustomer = app(\Webkul\Stripe\Models\StripeCustomer::class)->where(['seller_id' => $seller->id])->first())

@if($seller->type === 'plus' || $seller->type === 'god')
    <div class="accordion list-group list-group-flush list-group-accordion d-none payment-method-credentials" id="stripe-payment">
        <div class="wrap list-group-item stripe-details-wrap" >
            <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#collapseStripe" aria-expanded="true" aria-controls="collapseStripe"><span>Stripe Gateway Credentials</span> <i class="fal fa-angle-right"></i></a>
            <div id="collapseStripe" class="collapse" data-parent="#stripe-payment">
                <div class="inner">
                    <div class="form-group text">
                        <label for="public_key">
                            Public key
                        </label>
                        <input type="password" id="stripe_public_key"  name="stripe[public_key]" value="{{ $stripeCustomer->public_key ?? ''}}" data-vv-as="&quot;PublicKey&quot;" class="form-control">
                    </div>
                    <div class="form-group text">
                        <label for="api_key">
                            Api key
                        </label>
                        <input type="password" id="stripe_api_key" name="stripe[api_key]"  value="{{ $stripeCustomer->api_key ?? ''}}" data-vv-as="&quot;ApiKey&quot;" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            $(document).ready(function () {

                $('#stripe').change(function () {
                    if ($(this).is(':checked')) {
                        if(!$('#stripe-payment').hasClass('d-none') ){
                            $('#stripe_public_key').attr("required","required");
                            $('#stripe_api_key').attr("required","required");

                        }
                    } else {
                        $('#stripe_public_key').removeAttr("required");
                        $('#stripe_api_key').removeAttr("required");
                        $('#stripe_public_key').val('');
                        $('#stripe_api_key').val('');
                    }
                })
            })
        </script>
    @endpush
@endif
