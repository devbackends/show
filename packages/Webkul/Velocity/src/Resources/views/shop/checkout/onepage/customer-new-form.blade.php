@php
    $isCustomer = auth()->guard('customer')->check();
@endphp


@if (isset($personal_information) && $personal_information)
    <div :class="`col-lg-6 col-md-12 form-field ${errors.has('address-form.first_name') ? 'has-error' : ''}`">
        <label for="first_name" class="mandatory paragraph regular-font">
            {{ __('shop::app.checkout.onepage.first-name') }}
        </label>
        <input type="text" class="control" id="first_name" name="first_name" v-validate="'required'" v-model="address.first_name" @change="validateForm('address-form')" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;"/>
        <span class="control-error" >@{{ errors.first('address-form.first_name') }}</span>
    </div>

    <div :class="`col-lg-6 col-md-12 form-field ${errors.has('address-form.last_name') ? 'has-error' : ''}`">
        <label for="last_name" class="mandatory paragraph regular-font">
            {{ __('shop::app.checkout.onepage.last-name') }}
        </label>
        <input type="text" class="control" id="last_name" name="last_name" v-validate="'required'" v-model="address.last_name" @change="validateForm('address-form')" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;"/>
        <span class="control-error">@{{ errors.first('address-form.last_name') }}</span>
    </div>

    <div :class="`col-lg-6 col-md-12 form-field ${errors.has('address-form.email') ? 'has-error' : ''}`">
        <label for="email" class="mandatory paragraph regular-font">
            {{ __('shop::app.checkout.onepage.email') }}
        </label>
        <input type="text" class="control" id="email" name="email" v-validate="'required'" v-model="address.email" @change="validateForm('address-form')" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;" />
        <span class="control-error" >@{{ errors.first('address-form.email') }}</span>
    </div>

    <div :class="`col-lg-6 col-md-12 form-field ${errors.has('address-form.phone') ? 'has-error' : ''}`">
        <label for="phone" class="mandatory paragraph regular-font">
            Phone number
        </label>
        <input type="text" class="control" id="phone" name="phone" v-validate="'required'" v-model="address.phone" @change="validateForm('address-form')" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;"/>
        <span class="control-error">@{{ errors.first('address-form.phone') }}</span>
    </div>

@endif


    @if (isset($shipping) && $shipping)




        <div :class="`col-lg-12 col-md-12 form-field ${errors.has('address-form.shipping[address1][]') ? 'has-error' : ''}`" style="margin-bottom: 0;">
            <label for="shipping_address_0" class="mandatory  paragraph regular-font">
                {{ __('shop::app.checkout.onepage.address') }}
            </label>

            <input
                type="text"
                class="control"
                v-validate="'required'"
                id="shipping_address_0"
                name="shipping[address1][]"
                v-model="address.shipping.address1[0]"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.shipping[address1][]')">
                @{{ errors.first('address-form.shipping[address1][]') }}
            </span>
        </div>

        @if (
            core()->getConfigData('customer.settings.address.street_lines')
            && core()->getConfigData('customer.settings.address.street_lines') > 1
        )
            @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                <div class="col-12 form-field" style="margin-top: 10px; margin-bottom: 0">
                    <input
                        type="text"
                        class="control"
                        id="shipping_address_{{ $i }}"
                        name="shipping[address1][{{ $i }}]"
                        @change="validateForm('address-form')"
                        v-model="address.shipping.address1[{{$i}}]" />
                </div>
            @endfor
        @endif

        <div :class="`col-lg-4 col-md-12 form-field ${errors.has('address-form.shipping[city]') ? 'has-error' : ''}`">
            <label for="shipping[city]" class="mandatory  paragraph regular-font">
                {{ __('shop::app.checkout.onepage.city') }}
            </label>

            <input
                type="text"
                class="control"
                id="shipping[city]"
                name="shipping[city]"
                v-validate="'required'"
                v-model="address.shipping.city"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.shipping[city]')">
                @{{ errors.first('address-form.shipping[city]') }}
            </span>
        </div>

        <div :class="`col-12 form-field hide ${errors.has('address-form.shipping[country]') ? 'has-error' : ''}`">
            <label for="shipping[country]" class="mandatory  paragraph regular-font">
                {{ __('shop::app.checkout.onepage.country') }}
            </label>

            <select
                type="text"
                id="shipping[country]"
                v-validate="'required'"
                name="shipping[country]"
                class="control styled-select"
                v-model="address.shipping.country"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.country') }}&quot;">

                <option value=""></option>

                @foreach (core()->countries() as $country)
                    <option  value="{{ $country->code }}">{{ $country->name }}</option>
                @endforeach
            </select>

            <span class="control-error" v-if="errors.has('address-form.shipping[country]')">
                @{{ errors.first('address-form.shipping[country]') }}
            </span>
        </div>


        <div :class="`col-lg-4 col-md-12 form-field ${errors.has('address-form.shipping[state]') ? 'has-error' : ''}`">
            <label for="shipping[state]" class="mandatory  paragraph regular-font">
                {{ __('shop::app.checkout.onepage.state') }}
            </label>

            <input
                type="text"
                class="control"
                id="shipping[state]"
                name="shipping[state]"
                v-validate="'required'"
                v-if="!haveStates('shipping')"
                v-model="address.shipping.state"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;" />

            <select
                id="shipping[state]"
                name="shipping[state]"
                v-validate="'required'"
                class="control styled-select"
                v-if="haveStates('shipping')"
                v-model="address.shipping.state"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;">

                <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>

                <option v-for='(state, index) in countryStates[address.shipping.country]' :value="state.code">
                    @{{ state.default_name }}
                </option>
            </select>

            <span class="control-error" v-if="errors.has('address-form.shipping[state]')">
                @{{ errors.first('address-form.shipping[state]') }}
            </span>
        </div>

        <div :class="`col-lg-4 col-md-12 form-field ${errors.has('address-form.shipping[postcode]') ? 'has-error' : ''}`">
            <label for="shipping[postcode]" class="mandatory  paragraph regular-font">
                {{ __('shop::app.checkout.onepage.postcode') }}
            </label>

            <input
                type="text"
                class="control"
                id="shipping[postcode]"
                v-validate="'required'"
                name="shipping[postcode]"
                v-model="address.shipping.postcode"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.shipping[postcode]')">
                @{{ errors.first('address-form.shipping[postcode]') }}
            </span>
        </div>



        @auth('customer')
            <div class="mb10 col-md-12">
                <span class="checkbox fs16 display-inbl no-margin custom-check-box">
                    <input
                        class="ml0"
                        type="checkbox"
                        id="shipping[save_as_address]"
                        name="shipping[save_as_address]"
                        @change="validateForm('address-form')"
                        v-model="address.shipping.save_as_address"/>
                        <label for="custom-checkbox-view" class="custom-checkbox-view margin-r-10"></label>
                    <span class="paragraph regular-font">
                        {{ __('shop::app.checkout.onepage.save_as_address') }}
                    </span>
                </span>
            </div>
        @endauth

    @elseif (isset($billing) && $billing)


        {{--  for customer login checkout   --}}
        @if (! $isCustomer)
            @include('shop::checkout.onepage.customer-checkout')
        @endif


        <div :class="`col-lg-12 padding-tb-30`" >
            <span class="paragraph bold ">Billing address</span>
        </div>



        <div :class="`col-lg-12 col-md-12 form-field ${errors.has('address-form.billing[address1][]') ? 'has-error' : ''}`" style="margin-bottom: 0;">
            <label for="billing_address_0" class="mandatory  paragraph regular-font">
                {{ __('shop::app.checkout.onepage.address') }}
            </label>

            <input
                type="text"
                class="control"
                v-validate="'required'"
                id="billing_address_0"
                name="billing[address1][]"
                v-model="address.billing.address1[0]"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.billing[address1][]')">
                @{{ errors.first('address-form.billing[address1][]') }}
            </span>
        </div>

        @if (
            core()->getConfigData('customer.settings.address.street_lines')
            && core()->getConfigData('customer.settings.address.street_lines') > 1
        )
            @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                <div class="col-12 form-field" style="margin-top: 10px; margin-bottom: 0">
                        <input
                            type="text"
                            class="control"
                            id="billing_address_{{ $i }}"
                            name="billing[address1][{{ $i }}]"
                            v-model="address.billing.address1[{{$i}}]" />
                </div>
            @endfor
        @endif

        <div :class="`col-lg-4 col-md-12 form-field ${errors.has('address-form.billing[city]') ? 'has-error' : ''}`">
            <label for="billing[city]" class="mandatory  paragraph regular-font">
                {{ __('shop::app.checkout.onepage.city') }}
            </label>

            <input
                type="text"
                class="control"
                id="billing[city]"
                name="billing[city]"
                v-validate="'required'"
                v-model="address.billing.city"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.billing[city]')">
                @{{ errors.first('address-form.billing[city]') }}
            </span>
        </div>

        <div :class="`col-12 form-field hide ${errors.has('address-form.billing[country]') ? 'has-error' : ''}`">
            <label for="billing[country]" class="mandatory  paragraph regular-font">
                {{ __('shop::app.checkout.onepage.country') }}
            </label>

            <select
                type="text"
                id="billing[country]"
                name="billing[country]"
                v-validate="'required'"
                class="control styled-select"
                v-model="address.billing.country"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.country') }}&quot;">

                <option value=""></option>

                @foreach (core()->countries() as $country)
                    <option   value="{{ $country->code }}">{{ $country->name }}</option>
                @endforeach
            </select>

            <span class="control-error" v-if="errors.has('address-form.billing[country]')">
                @{{ errors.first('address-form.billing[country]') }}
            </span>
        </div>

        <div :class="`col-lg-4 col-md-12 form-field ${errors.has('address-form.billing[state]') ? 'has-error' : ''}`">
            <label for="billing[state]" class="mandatory  paragraph regular-font">
                {{ __('shop::app.checkout.onepage.state') }}
            </label>

            <input
                type="text"
                class="control"
                id="billing[state]"
                name="billing[state]"
                v-validate="'required'"
                v-if="!haveStates('billing')"
                v-model="address.billing.state"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;" />

            <select
                id="billing[state]"
                name="billing[state]"
                v-validate="'required'"
                v-if="haveStates('billing')"
                class="control styled-select"
                v-model="address.billing.state"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;">

                <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>

                <option v-for='(state, index) in countryStates[address.billing.country]' :value="state.code">
                    @{{ state.default_name }}
                </option>
            </select>

            <span class="control-error" v-if="errors.has('address-form.billing[state]')">
                @{{ errors.first('address-form.billing[state]') }}
            </span>
        </div>

        <div :class="`col-lg-4 col-md-12 form-field ${errors.has('address-form.billing[postcode]') ? 'has-error' : ''}`">
            <label for="billing[postcode]" class="mandatory  paragraph regular-font">
                {{ __('shop::app.checkout.onepage.postcode') }}
            </label>

            <input
                type="text"
                class="control"
                id="billing[postcode]"
                v-validate="'required'"
                name="billing[postcode]"
                v-model="address.billing.postcode"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.billing[postcode]')">
                @{{ errors.first('address-form.billing[postcode]') }}
            </span>
        </div>


        @if ($cart->haveStockableItems())
            <div class="mb10 col-md-12 ">
                <span class="checkbox fs16 display-inbl no-margin custom-check-box">
                    <input
                        class="ml0"
                        type="checkbox"
                        id="billing[use_for_shipping]"
                        name="billing[use_for_shipping]"
                        v-model="address.billing.use_for_shipping"
                        @change="setTimeout(() => validateForm('address-form'), 0)" />
                    <label for="custom-checkbox-view" class="custom-checkbox-view margin-r-10"></label>

                </span>
                <span class="paragraph regular-font">
                        {{ __('shop::app.checkout.onepage.use_for_shipping') }} (not available for firearms)
                </span>
            </div>
        @endif

        @auth('customer')
            <div class="mb10 col-md-12">
                <span class="checkbox fs16 display-inbl no-margin custom-check-box">
                    <input
                        class="ml0"
                        type="checkbox"
                        id="billing[save_as_address]"
                        name="billing[save_as_address]"
                        @change="validateForm('address-form')"
                        v-model="address.billing.save_as_address"/>
                    <label for="custom-checkbox-view" class="custom-checkbox-view margin-r-10"></label>
                    <span class="paragraph regular-font">
                        {{ __('shop::app.checkout.onepage.save_as_address') }}
                    </span>
                </span>
            </div>
            @php
            @endphp
        @endauth
    @endif


@if (isset($payment_information) && $payment_information)

    <div :class="`col-lg-12 padding-tb-30`" >
        <span class="paragraph bold ">Payment information</span>
    </div>

    <div :class="`col-lg-12 col-md-12 form-field`">
        <label for="credit_card_number" class="mandatory paragraph regular-font">
            Credit card number
        </label>
        <input type="text" class="control" id="credit_card_number" name="credit_card_number" placeholder="Enter your credit card number"/>
        <span class="control-error">
        </span>
    </div>

    <div :class="`col-lg-4 col-md-12 form-field`">
        <label for="exp_date_month" class="mandatory paragraph regular-font">
            Exp. date month
        </label>
        <input type="text" class="control" id="exp_date_month" name="exp_date_month" placeholder="Exp. date month"/>
        <span class="control-error">
        </span>
    </div>

    <div :class="`col-lg-4 col-md-12 form-field`">
        <label for="exp_date_year" class="mandatory paragraph regular-font">
            Exp. date year
        </label>
        <input type="text" class="control" id="exp_date_year" name="exp_date_year" placeholder="Exp. date year"/>
        <span class="control-error">
        </span>
    </div>

    <div :class="`col-lg-4 col-md-12 form-field `">
        <label for="ccv" class="mandatory paragraph regular-font">
            CCV
        </label>
        <input type="text" class="control" id="ccv" name="ccv" placeholder="CCV"/>
        <span class="control-error">
        </span>
    </div>
    <div class="col-md-12 right"><button type="button" class="theme-btn fs16 fw6">Next</button></div>
@endif
