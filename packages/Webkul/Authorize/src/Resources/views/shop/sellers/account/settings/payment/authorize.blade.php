@php($authorizeCustomer = app(\Webkul\Authorize\Models\AuthorizeCustomer::class)->where(['seller_id' => $seller->id])->first())

@if($seller->type === 'plus' || $seller->type === 'god')
    <div class="accordion list-group list-group-flush list-group-accordion d-none payment-method-credentials" id="authorize-payment">
        <div class="wrap list-group-item authorize-details-wrap" >
            <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#collapseAuthorize" aria-expanded="true" aria-controls="collapseAuthorize"><span>Authorize Gateway Credentials</span> <i class="fal fa-angle-right"></i></a>
            <div id="collapseAuthorize" class="collapse" data-parent="#authorize-payment">
                <div class="inner">
                    <div class="form-group text">
                        <label for="public_key">
                            Transaction Key
                        </label>
                        <input type="password" id="authorize_public_key"  name="authorize[public_key]"  value="{{ $authorizeCustomer->public_key ?? ''}}" data-vv-as="&quot;PublicKey&quot;" class="form-control">
                    </div>
                    <div class="form-group text">
                        <label for="api_key">
                            Api Login Id
                        </label>
                        <input type="password" id="authorize_api_key" name="authorize[api_key]"  value="{{ $authorizeCustomer->api_key ?? ''}}" data-vv-as="&quot;ApiKey&quot;" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            $(document).ready(function () {

                $('#authorize').change(function () {
                    if ($(this).is(':checked')) {
                        if(!$('#authorize-payment').hasClass('d-none')){
                            $('#authorize_public_key').attr("required","required");
                            $('#authorize_api_key').attr("required","required");
                        }
                    } else {
                        $('#authorize_public_key').removeAttr("required");
                        $('#authorize_api_key').removeAttr("required");
                        $('#authorize_public_key').val('')
                        $('#authorize_api_key').val('')
                    }
                })
            })
        </script>
    @endpush
@endif
