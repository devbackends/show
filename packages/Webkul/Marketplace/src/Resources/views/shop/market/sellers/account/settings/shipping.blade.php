<div class="row store-settings__payment">

    <div class="col">
        <p class="font-paragraph-big">Shipping Methods</p>
        <div>
                <?php $count = 0;?>
                <div>
                    @foreach (config('carriers') as $carrier)
                        @if (strpos($carrier['code'], 'mp') == false && $carrier['code'] != 'mpmultishipping' && $carrier['code'] != 'tablerate')
                            @if ( core()->getConfigData('sales.carriers.'.$carrier['code'].'.active') == 1 )
                                <?php $count += 1 ?>
                                <div class="form-group booleantest">

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="{{ $carrier['code'] }}" name="shipping_methods[]" @if (in_array($carrier['code'], explode(',', $seller->shipping_methods))) checked @endif value="{{ $carrier['code'] }}">
                                        <label class="custom-control-label" for="{{ $carrier['code'] }}">{{ $carrier['title'] }}</label>
                                    </div>
                                    
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>

                @if ($count == 0)
                    <span style="color:red; padding: 10px; font-size: 18px;">{{ __('mpmultishipping::app.shop.sellers.no-method') }}</span>
                @endif
            </div>
    </div>

    @if(core()->getConfigData('sales.carriers.mpfedex.active') && core()->getConfigData('sales.carriers.mpfedex.allow_seller'))
        @php($credentials = app(\Webkul\MarketplaceFedExShipping\Repositories\FedExRepository::class)->findOneWhere(['marketplace_seller_id' => $seller->id]))
        <div class="wrap list-group-item">
            <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#collapseShipping2" aria-expanded="false" aria-controls="collapseShipping2"><span>{{ __('marketplace_fedex::app.shop.sellers.fedex.manage-configuration') }}</span> <i class="fal fa-angle-right"></i></a>
            <div id="collapseShipping2" class="collapse" data-parent="#marketplace-shipping">
                <div class="inner">
                    <div class="form-group text">
                        <label for="fedex_account_id">
                            {{ __('marketplace_fedex::app.shop.sellers.fedex.account-id') }}
                        </label>
                        <input type="text" id="fedex_account_id" name="fedex[account_id]" value="{{ $credentials->account_id ?? ''}}" data-vv-as="&quot;{{ __('marketplace_fedex::app.shop.sellers.fedex.account-id') }}&quot;" class="form-control">
                    </div>
                    <div class="form-group text">
                        <label for="fedex_password">
                            {{ __('marketplace_fedex::app.shop.sellers.fedex.password') }}
                        </label>
                        <input type="password" id="fedex_password" name="fedex[password]" value="{{ $credentials->password ?? ''}}" data-vv-as="&quot;{{ __('marketplace_fedex::app.shop.sellers.fedex.password') }}&quot;" class="form-control">
                    </div>
                    <div class="form-group text">
                        <label for="fedex_meter_number">
                            {{ __('marketplace_fedex::app.shop.sellers.fedex.meter-number') }}
                        </label>
                        <input type="password" id="fedex_meter_number" name="fedex[meter_number]" value="{{ $credentials->meter_number ?? ''}}" data-vv-as="&quot;{{ __('marketplace_fedex::app.shop.sellers.fedex.meter-number') }}&quot;" class="form-control">
                    </div>
                    <div class="form-group text">
                        <label for="fedex_key">
                            {{ __('marketplace_fedex::app.shop.sellers.fedex.key') }}
                        </label>
                        <input type="password" id="fedex_key" name="fedex[key]" value="{{ $credentials->key ?? ''}}" data-vv-as="&quot;{{ __('marketplace_fedex::app.shop.sellers.fedex.key') }}&quot;" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(core()->getConfigData('sales.carriers.mpups.active') && core()->getConfigData('sales.carriers.mpups.allow_seller'))
        @php($credentials = app(\Webkul\MarketplaceUpsShipping\Repositories\UpsRepository::class)->findOneWhere(['ups_seller_id' => $seller->id]))
        <div class="wrap list-group-item">
            <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#collapseShipping3" aria-expanded="false" aria-controls="collapseShipping3"><span>{{ __('marketplace_ups::app.shop.sellers.ups.manage-configuration') }}</span> <i class="fal fa-angle-right"></i></a>
            <div id="collapseShipping3" class="collapse" data-parent="#marketplace-shipping">
                <div class="inner">
                    <div class="form-group text">
                        <label for="ups_account_id">
                            {{ __('marketplace_ups::app.shop.sellers.ups.account-id') }}
                        </label>
                        <input type="text" id="ups_account_id" name="ups[account_id]" value="{{ $credentials->account_id ?? ''}}" data-vv-as="&quot;{{ __('marketplace_ups::app.shop.sellers.ups.account-id') }}&quot;" class="form-control">
                    </div>
                    <div class="form-group text">
                        <label for="ups_password">
                            {{ __('marketplace_ups::app.shop.sellers.ups.password') }}
                        </label>
                        <input type="password" id="ups_password" name="ups[password]" value="{{ $credentials->account_id ?? ''}}" data-vv-as="&quot;{{ __('marketplace_ups::app.shop.sellers.ups.password') }}&quot;" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(core()->getConfigData('sales.carriers.mpusps.active') && core()->getConfigData('sales.carriers.mpusps.allow_seller'))
        @php($credentials = app(\Webkul\MarketplaceUspsShipping\Repositories\UspsRepository::class)->findOneWhere(['usps_seller_id' => $seller->id]))
        <div class="wrap list-group-item">
            <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#collapseShipping4" aria-expanded="false" aria-controls="collapseShipping4"><span>{{ __('marketplace_usps::app.shop.sellers.usps.manage-configuration') }}</span> <i class="fal fa-angle-right"></i></a>
            <div id="collapseShipping4" class="collapse" data-parent="#marketplace-shipping">
                <div class="inner">
                    <div class="form-group text">
                        <label for="usps_account_id">
                            {{ __('marketplace_usps::app.shop.sellers.usps.account-id') }}
                        </label>
                        <input type="text" id="usps_account_id" name="usps[account_id]" value="{{ $credentials->account_id ?? ''}}" data-vv-as="&quot;{{ __('marketplace_usps::app.shop.sellers.usps.account-id') }}&quot;" class="form-control">
                    </div>
                    <div class="form-group text">
                        <label for="usps_password">
                            {{ __('marketplace_usps::app.shop.sellers.usps.password') }}
                        </label>
                        <input type="password" id="usps_password" name="usps[password]" value="{{ $credentials->account_id ?? ''}}" data-vv-as="&quot;{{ __('marketplace_usps::app.shop.sellers.usps.password') }}&quot;" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="flat-rate-configuration_section" class="wrap list-group-item" style="{{in_array('flatrate', explode(',', $seller->shipping_methods)) ? '' : 'display:none;'}}">
        @php($flatRateData = \Webkul\Marketplace\Models\FlatRateInfo::where(['seller_id' => $seller->id])->first())
        <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#collapseShipping5" aria-expanded="false" aria-controls="collapseShipping5"><span>Flat Rate Configuration</span> <i class="fal fa-angle-right"></i></a>
        <div id="collapseShipping5" class="collapse" data-parent="#marketplace-shipping">
            <div class="inner">
                <div class="form-group select">
                    <label for="flatrate_type">
                        Type
                    </label>
                    <select class="form-control js-example-basic-single" id="flatrate_type" name="flatrate[type]" data-vv-as="&quot;Type&quot;">
                        <option value data-isdefault="true">- select -</option>
                        <option value="per_unit" {{($flatRateData->type ?? '') == 'per_unit' ? 'selected' : ''}}>Per Unit</option>
                        <option value="per_order" {{($flatRateData->type ?? '') == 'per_order' ? 'selected' : ''}}>Per Order</option>
                    </select>
                </div>
                <div class="form-group text">
                    <label for="flatrate_rate">
                        Rate
                    </label>
                    <input type="text" id="flatrate_rate" name="flatrate[rate]" value="{{($flatRateData->rate ?? 0)}}" data-vv-as="&quot;Rate&quot;" class="form-control">
                </div>
                <div>
                    <h4>NOTES:</h4>
                    <p>
                        1. Selecting per unit will result in a multiple of your shipping price. For example if you set the per Unit rate to $10 and a customer purchases 3 items they will be charged $30.<br>
                        2. Flat Rate configurations are global settings and will apply to all of your products.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        $(document).ready(() => {
            const flatRateInput = $('#flatrate')
            flatRateInput.change(function () {
                const flatRateSection = $('#flat-rate-configuration_section');
                if (flatRateInput.is(':checked')) {
                    flatRateSection.css('display', 'block');
                } else {
                    $('#flatrate_rate').val('0');
                    $('#flatrate_type option[data-isdefault="true"]').attr("selected", true).trigger('change');
                    flatRateSection.css('display', 'none');
                }
            })
        })
    </script>
@endpush