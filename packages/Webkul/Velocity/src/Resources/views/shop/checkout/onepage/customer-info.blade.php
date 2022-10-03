<form data-vv-scope="address-form" class="custom-form dev-form">
    <div class="form-container" v-if="!this.new_billing_address">
        <accordian
            :active="true"
            :title="'{{ __('shop::app.checkout.onepage.billing-address') }}'">

            <div class="form-header mb-30 padding-sides-15" slot="header">
                <h3 class="fw6 display-inbl">
                    Contact information
                </h3>
                <i class="rango-arrow"></i>
            </div>

            <div slot="body">
                <div class="address-container row full-width no-margin padding-sides-15">
                    <div
                        :key="index"
                        class="col-lg-6 col-md-12 address-holder pl0"
                        v-for='(addresses, index) in this.allAddress'>
                        <div class="card">
                            <div class="card-body row">

                                <div class="col-1">

                                    <input
                                        type="radio"
                                        v-validate="'required'"
                                        name="billing[address_id]"
                                        :value="addresses.id"
                                        v-model="address.billing.address_id"
                                        @change="validateForm('address-form')"
                                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.billing-address') }}&quot;" />

                                    <span class="checkmark"></span>
                                </div>

                                <div class="col-10">
                                    <h5 class="card-title fw6">
                                        @{{ allAddress.first_name }} @{{ allAddress.last_name }},
                                    </h5>

                                    <ul type="none">
                                        <li>@{{ addresses.address1 }},</li>
                                        <li>@{{ addresses.city }},</li>
                                        <li>@{{ addresses.state }},</li>
                                        <li>@{{ addresses.country }} @{{ addresses.postcode }}</li>
                                        <li>
                                            {{ __('shop::app.customer.account.address.index.contact') }} : @{{ addresses.phone }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 address-holder pl0">
                        <div class="card">
                            <div class="card-body add-address-button">
                                <div class="cursor-pointer" @click="newBillingAddress()">
                                    <i class="material-icons">
                                        add_circle_outline
                                    </i>
                                    <span>{{ __('shop::app.checkout.onepage.new-address') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="`col-12 mt15 has-error ${errors.has('address-form.billing[address_id]') ? 'has-error' : ''}`">
                    <span
                        class="control-error"
                        v-if="errors.has('address-form.billing[address_id]')">
                        @{{ errors.first('address-form.billing[address_id]') }}
                    </span>
                </div>

                @if ($cart->haveStockableItems())
                    <div class="mt10 mb10" v-if="address.billing.address_id">
                        <span class="checkbox fs16 display-inbl">
                            <input
                                type="checkbox"
                                class="ml0"
                                id="billing[use_for_shipping]"
                                name="billing[use_for_shipping]"
                                @change="setTimeout(() => validateForm('address-form'), 0)"
                                v-model="address.billing.use_for_shipping" />

                            <span class="ml-5">
                                {{ __('shop::app.checkout.onepage.use_for_shipping') }}
                            </span>
                        </span>
                    </div>
                @endif
            </div>
        </accordian>
    </div>

    <div class="form-container" v-else>
        <accordian :title="'{{ __('shop::app.checkout.onepage.billing-address') }}'" :active="true">
            <div class="form-header padding-sides-15" slot="header">
                <h3 class="fw6 display-inbl">
                    Contact information
                </h3>

                <i class="rango-arrow"></i>
            </div>

            @auth('customer')

            <div class="col-12 no-padding" slot="body">
                @if(count(auth('customer')->user()->addresses))
                    <div class="col-md-12 no-padding" >
                        <a
                                class="theme-btn light pull-right text-up-14"
                                @click="backToSavedBillingAddress()">

                            {{ __('shop::app.checkout.onepage.back') }}
                        </a>
                    </div>
                @endif
                @endauth

                @include('shop::checkout.onepage.customer-new-form', [
                    'personal_information' => true
                ])
                @include('shop::checkout.onepage.customer-new-form', [
                    'billing' => true
                ])

                    @if ($cart->haveStockableItems())

                        <div class="col-12 no-padding" v-if="!address.billing.use_for_shipping && !this.new_shipping_address">

                            <div :class="`col-lg-12 padding-tb-30`" >
                                <span class="paragraph bold ">Shipping address</span>
                            </div>

                            <div class="address-container row mb30 remove-padding-margin padding-sides-15">
                                <div
                                        class="col-lg-6 address-holder pl0"
                                        v-for='(addresses, index) in this.allAddress'>

                                    <div class="card">
                                        <div class="card-body row">
                                            <div class="col-1">
                                                <input
                                                        type="radio"
                                                        v-validate="'required'"
                                                        :value="addresses.id"
                                                        name="shipping[address_id]"
                                                        v-model="address.shipping.address_id"
                                                        @change="validateForm('address-form')"
                                                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.shipping-address') }}&quot;" />

                                                <span class="checkmark"></span>
                                            </div>

                                            <div class="col-10">
                                                <h5 class="card-title fw6">
                                                    @{{ allAddress.first_name }} @{{ allAddress.last_name }},
                                                </h5>

                                                <ul type="none">
                                                    <li>@{{ addresses.address1 }},</li>
                                                    <li>@{{ addresses.city }},</li>
                                                    <li>@{{ addresses.state }},</li>
                                                    <li>@{{ addresses.country }} @{{ addresses.postcode }}</li>
                                                    <li>
                                                        {{ __('shop::app.customer.account.address.index.contact') }} : @{{ addresses.phone }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 address-holder pl0">
                                    <div class="card">
                                        <div class="card-body add-address-button">
                                            <div class="cursor-pointer" @click="newShippingAddress()">
                                                <i class="material-icons">
                                                    add_circle_outline
                                                </i>
                                                <span>{{ __('shop::app.checkout.onepage.new-address') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div :class="`col-12 mt15 has-error pl0 ${errors.has('address-form.shipping[address_id]') ? 'has-error' : ''}`">
                        <span class="control-error" v-if="errors.has('address-form.shipping[address_id]')">
                            @{{ errors.first('address-form.shipping[address_id]') }}
                        </span>
                                </div>
                            </div>

                        </div>



                        <div class="col-12 no-padding" v-if="!address.billing.use_for_shipping && this.new_shipping_address">

                            <div :class="`col-lg-12 padding-tb-30`" >
                                <span class="paragraph bold ">Shipping address</span>
                            </div>

                            @auth('customer')
                                @if(count(auth('customer')->user()->addresses))
                                    <div class="col-md-12">
                                        <a
                                                class="theme-btn light pull-right text-up-14"
                                                @click="backToSavedShippingAddress()">

                                            {{ __('shop::app.checkout.onepage.back') }}
                                        </a>
                                    </div>

                                @endif
                            @endauth

                            @include('shop::checkout.onepage.customer-new-form', [
                                'shipping' => true
                            ])
                        </div>

                    @endif

                @include('shop::checkout.onepage.customer-new-form', [
                    'payment_information' => true
                ])
            </div>
        </accordian>
    </div>
</form>

    <div class="form-container">
        <accordian :title="'FFL'" :active="false">
            <div class="form-header padding-sides-15" slot="header">
                <h3 class="fw6 display-inbl">
                    Select an FFL for delivery
                </h3>

                <i class="rango-arrow"></i>
            </div>


            <div class="col-12 no-padding" slot="body">
                @auth('customer')
                    @if(count(auth('customer')->user()->addresses))
                        <a
                                class="theme-btn light pull-right text-up-14"
                                @click="backToSavedBillingAddress()">

                            {{ __('shop::app.checkout.onepage.back') }}
                        </a>
                    @endif
                @endauth

                @include('shop::checkout.onepage.ffl-delivery-form', [
                    'ffl_delivery' => true
                ])

            </div>
        </accordian>
    </div>

    <div class="form-container">
        <accordian :title="'shipping-confirmation'" :active="false">
            <div class="form-header padding-sides-15" slot="header">
                <h3 class="fw6 display-inbl">
                    Shipping Confirmation
                </h3>

                <i class="rango-arrow"></i>
            </div>


            <div class="col-12 no-padding" slot="body">
                @auth('customer')
                    @if(count(auth('customer')->user()->addresses))
                        <a
                                class="theme-btn light pull-right text-up-14"
                                @click="backToSavedBillingAddress()">

                            {{ __('shop::app.checkout.onepage.back') }}
                        </a>
                    @endif
                @endauth

                @include('shop::checkout.onepage.shipping-confirmation-form', [
                    'shipping_confirmation' => true
                ])

            </div>
        </accordian>
    </div>



