@push('css')
    <style>
        .text-success {
            color: #4CAF50 !important;
        }

        .text-danger {
            color: #FC6868 !important;
        }
    </style>
@endpush

<seller-controls></seller-controls>

@push('scripts')
    <script type="text/x-template" id="seller-controls-template">

        <div class="seller-form-controls">
            <div class="control-group">
                <label>{{ __('marketplace::app.shop.sellers.account.signup.want-to-be-seller') }}</label>

                <span class="radio">
                    <input type="radio" id="yes" name="want_to_be_seller" value="1" v-model="want_to_be_seller">
                    <label class="radio-view" for="yes"></label>
                    {{ __('marketplace::app.shop.sellers.account.signup.yes') }}
                </span>

                <span class="radio">
                    <input type="radio" id="no" name="want_to_be_seller" value="0" v-model="want_to_be_seller" :checked="!want_to_be_seller">
                    <label class="radio-view" for="no"></label>
                    {{ __('marketplace::app.shop.sellers.account.signup.no') }}
                </span>
            </div>

            <div class="control-group" :class="[errors.has('url') ? 'has-error' : '']" v-if="want_to_be_seller == 1">
                <label for="url" class="required">{{ __('marketplace::app.shop.sellers.account.signup.shop_url') }}</label>

                <input type="text" class="control" name="url" v-validate="'required'" value="{{ old('url') }}" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.signup.shop_url') }}&quot;" v-on:keyup="checkShopUrl($event.target.value)">

                <span class="control-info text-success" v-if="isShopUrlAvailable != null && isShopUrlAvailable">{{ __('marketplace::app.shop.sellers.account.signup.shop_url_available') }}</span>

                <span class="control-info text-danger" v-if="isShopUrlAvailable != null && !isShopUrlAvailable">{{ __('marketplace::app.shop.sellers.account.signup.shop_url_not_available') }}</span>

                <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
            </div>
        </div>

    </script>

    <script>

        Vue.component('seller-controls', {
            template: "#seller-controls-template",

            data: () => ({
                want_to_be_seller: 0,

                isShopUrlAvailable: null
            }),

            watch: {
                'want_to_be_seller': function(newVal, oldVal) {
                    this.toggleButtonDisable(newVal)
                }
            },

            methods: {
                checkShopUrl (shopUrl) {
                    this_this = this;

                    this.$http.post("{{ route('marketplace.seller.url') }}", {'url': shopUrl})
                        .then(function(response) {
                            if (response.data.available) {
                                this_this.isShopUrlAvailable = true;

                                document.querySelectorAll("form button.btn")[0].disabled = false;
                            } else {
                                this_this.isShopUrlAvailable = false;
                                document.querySelectorAll("form button.btn")[0].disabled = true;
                            }
                        })
                        .catch(function (error) {
                            document.querySelectorAll("form button.btn")[0].disabled = true;
                        })
                },

                toggleButtonDisable (value) {
                    var buttons = document.querySelectorAll("form button.btn");

                    if (value == 1) {
                        buttons[0].disabled = true;
                    } else {
                        buttons[0].disabled = false;
                    }
                },
            }
        });


    </script>

@endpush