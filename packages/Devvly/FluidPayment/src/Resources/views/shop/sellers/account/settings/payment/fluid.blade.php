@php($fluidCustomer = app(\Devvly\FluidPayment\Models\FluidCustomer::class)->where(['seller_id' => $seller->id,'type'=>'2acommerce-gateway'])->first())

@if($seller->type === 'plus' || $seller->type === 'god')
    <div class="accordion list-group list-group-flush list-group-accordion d-none payment-method-credentials" id="fluid-payment">
        <div class="wrap list-group-item fluid-details-wrap" >
            <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#collapseFluid" aria-expanded="true" aria-controls="collapseFluid"><span>2A Commerce Gateway Credentials</span> <i class="fal fa-angle-right"></i></a>
            <div id="collapseFluid" class="collapse" data-parent="#fluid-payment">
                <div class="inner">
                    <div class="form-group text">
                        <label for="fluid_public_key">
                            Public key
                        </label>
                        <input type="password" id="fluid_public_key"  name="fluid[public_key]" value="{{ $fluidCustomer->public_key ?? ''}}" data-vv-as="&quot;PublicKey&quot;" class="form-control">
                    </div>
                    <div class="form-group text">
                        <label for="fluid_api_key">
                            Api key
                        </label>
                        <input type="password" id="fluid_api_key" name="fluid[api_key]" value="{{ $fluidCustomer->api_key ?? ''}}" data-vv-as="&quot;ApiKey&quot;" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#fluid').change(function () {
                    if ($(this).is(':checked')) {
                        if(!$('#fluid-payment').hasClass('d-none')){
                            $('#fluid_public_key').attr("required","required");
                            $('#fluid_api_key').attr("required","required");
                        }
                    } else {
                        $('#fluid_public_key').removeAttr("required");
                        $('#fluid_api_key').removeAttr("required");
                        $('#fluid_public_key').val('')
                        $('#fluid_api_key').val('')
                    }
                })
            })
        </script>
    @endpush
@endif

@php($sellerFluidCustomer = app(\Devvly\FluidPayment\Models\FluidCustomer::class)->where(['seller_id' => $seller->id ,'type'=>'seller-gateway'])->first())

@if($seller->type === 'plus' || $seller->type === 'god')
    <div class="accordion list-group list-group-flush list-group-accordion d-none payment-method-credentials" id="seller-fluid-payment">
        <div class="wrap list-group-item seller-fluid-details-wrap" @if(strpos($seller->payment_methods, 'fluid') === false) style="display:none" @endif>
            <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#sellerCollapseFluid" aria-expanded="false" aria-controls="sellerCollapseFluid"><span>Seller Fluid Credentials</span> <i class="fal fa-angle-right"></i></a>
            <div id="sellerCollapseFluid" class="collapse" data-parent="#seller-fluid-payment">
                <div class="inner">
                    <div class="form-group text">
                        <label for="seller_fluid_public_key">
                            Public key
                        </label>
                        <input type="password" id="seller_fluid_public_key"  name="seller-fluid[public_key]"   value="{{ $sellerFluidCustomer->public_key ?? ''}}" data-vv-as="&quot;PublicKey&quot;" class="form-control">
                    </div>
                    <div class="form-group text">
                        <label for="seller_fluid_api_key">
                            Api key
                        </label>
                        <input type="password" id="seller_fluid_api_key" name="seller-fluid[api_key]"   value="{{ $sellerFluidCustomer->api_key ?? ''}}" data-vv-as="&quot;ApiKey&quot;" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#seller-fluid').change(function () {
                    if ($(this).is(':checked')) {
                        $('.seller-fluid-details-wrap').css('display', 'block');
                        $('#seller_fluid_public_key').attr("required","required");
                        $('#seller_fluid_api_key').attr("required","required");

                    } else {
                        $('.seller-fluid-details-wrap').css('display', 'none')
                        $('#seller_fluid_public_key').removeAttr("required");
                        $('#seller_fluid_api_key').removeAttr("required");
                        $('#seller_fluid_public_key').val('')
                        $('#seller_fluid_api_key').val('')
                    }
                })
            });
        </script>
    @endpush
@endif
