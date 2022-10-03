<country-state></country-state>

@push('scripts')

    <script type="text/x-template" id="country-state-template">
        <div class="form-group">
            <div class="form-group hide" :class="[errors.has('country') ? 'has-error' : '']">
                <label for="country" class="mandatory">
                    {{ __('shop::app.customer.account.address.create.country') }}
                </label>

                <select type="text" v-validate="'required'" class="form-control" id="country" name="country" v-model="country" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.country') }}&quot;">
                    <option value=""></option>
                    @foreach (core()->countries() as $country)
                        <option {{ $country->code === $defaultCountry ? 'selected' : '' }}  value="{{ $country->code }}">{{ $country->name }}</option>
                    @endforeach
                </select>

                <span class="control-error" v-if="errors.has('country')">
                    @{{ errors.first('country') }}
                </span>
            </div>

            <div class="form-group" :class="[errors.has('state') ? 'has-error' : '']">
                <label for="state" class="mandatory">
                    {{ __('shop::app.customer.account.address.create.state') }}
                </label>

                <input
                        id="state"
                        type="text"
                        name="state"
                        v-model="state"
                        class="form-control"
                        v-if="!haveStates()"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.state') }}&quot;" />

                <template v-if="haveStates()">
                    <select
                            id="state"
                            name="state"
                            v-model="state"
                            class="form-control styled-select"
                            v-validate="'required'"
                            data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.state') }}&quot;">

                        <option value="">{{ __('shop::app.customer.account.address.create.select-state') }}</option>

                        <option v-for='(state, index) in countryStates[country]' :value="state.code">
                            @{{ state.default_name }}
                        </option>
                    </select>
                </template>

                <span class="control-error" v-if="errors.has('state')">
                    @{{ errors.first('state') }}
                </span>
            </div>
        </div>
    </script>

    <script>
        Vue.component('country-state', {

            template: '#country-state-template',

            inject: ['$validator'],

            data() {
                return {
                    country: "{{ $countryCode ?? $defaultCountry }}",

                    state: "{{ $stateCode }}",

                    countryStates: @json(core()->groupedStatesByCountries())
            }
            },

            methods: {
                haveStates() {
                    this.country = 'US';
                    if (this.countryStates[this.country] && this.countryStates[this.country].length)
                        return true;

                    return false;
                },
            }
        });
    </script>
@endpush