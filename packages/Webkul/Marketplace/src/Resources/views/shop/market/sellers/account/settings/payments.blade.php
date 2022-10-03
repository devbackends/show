
@php  array_push($paymentMethods,["method" => "bluedog","method_title" => "BlueDog","description" => "BlueDog Payments","sort" => "1"]); @endphp
<store-settings></store-settings>
@push('scripts')
    <script type="text/x-template" id="store-settings-template">
        <div>
            <div class="row store-settings__payment">
                <div class="col" v-if="paymentMethods.length > 0">
                    <p class="font-paragraph-big">Payment Methods</p>
                    <div v-for="(payment,index) in paymentMethods" :key="index" class="form-group booleantest">
                        <div class="custom-control custom-switch d-inline-block" v-if="payment.method!='authorize' && payment.method!='stripe' && payment.method!='bluedog'">
                            <div v-if="payment.method=='fluid'">
<!--                                <a v-if="seller.type=='basic'" role="button" class="store-settings__popover-link"
                                   data-html="true" data-toggle="popover"
                                   data-trigger="focus" tabindex="0" data-placement="top"
                                   data-content="<div class='store-settings__popover'><p>If you want to use this payment method, </p><a class='btn btn-primary' id='upgrade-plan'>Upgrade Your Seller Plan</a></div>"></a>-->
<!--                                <a v-if="fluidCustomer && (seller.type=='plus' || seller.type=='god') && !fluidCustomer.is_approved "
                                   role="button" class="store-settings__popover-link" data-html="true"
                                   data-toggle="popover"
                                   data-trigger="focus" tabindex="0" data-placement="top"
                                   data-content="<div class='store-settings__popover'><p>Your card processing application is in review. Once your account is approved, you can enable this option.</p></div>"></a>-->
                            </div>
                            <input type="checkbox" class="custom-control-input payment-methods" :id="payment.method"
                                   name="payment_methods[]"
                                   :checked="payment.method!='fluid' ? seller.payment_methods.split(',').includes(payment.method) :  seller.payment_methods.split(',').includes('fluid')|| seller.payment_methods.split(',').includes('seller-fluid') || seller.payment_methods.split(',').includes('bluedog') || seller.payment_methods.split(',').includes('stripe') || seller.payment_methods.split(',').includes('authorize') "
                                   :value="payment.method">
                            <label class="custom-control-label" v-if="payment.method=='fluid'"
                                   :for="payment.method">Credit Card</label>
                            <label class="custom-control-label" v-else
                                   :for="payment.method" v-text="payment.method_title"></label>
                        </div>
                    </div>
                </div>
                <span v-if="paymentMethods.length == 0" style="color:red; padding: 10px; font-size: 18px;">There is no payment methods availiable for you to configure</span>
            </div>

            <div v-if="paymentMethods.length > 0" class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label class="label-control bold" style="font-weight: 700;">Gateway Credentials</label>
                        <select class="form-control" style="max-width: 200px;" v-model="selectedPaymentMethod">
                            <option v-for="(payment,index) in creditCardsMethods" :key="index"  :value="payment.method"
                                    v-text="payment.method_title"></option>
                            <option v-if="fluidExists()" value="seller-fluid" v-text="`Fluidpay`"></option>
                        </select>
                    </div>
                </div>
            </div>
            {{--start fluid--}}
            <div class="inner" v-visible="selectedPaymentMethod=='fluid'">
                <div class="form-group text">
                    <label for="fluid_public_key">
                        Public key
                    </label>
                    <input type="password" id="fluid_public_key" name="fluid[public_key]" v-model="fluid_public_key"
                           data-vv-as="&quot;PublicKey&quot;" class="form-control">
                </div>
                <div class="form-group text">
                    <label for="fluid_api_key">
                        Api key
                    </label>
                    <input type="password" id="fluid_api_key" name="fluid[api_key]" v-model="fluid_api_key"
                           data-vv-as="&quot;ApiKey&quot;" class="form-control">
                </div>
            </div>
            {{--end fluid--}}


            {{--start seller-fluid--}}
            <div class="inner" v-visible="selectedPaymentMethod=='seller-fluid'">
                <div class="form-group text">
                    <label for="seller_fluid_public_key">
                        Public key
                    </label>
                    <input type="password" id="seller_fluid_public_key" name="seller-fluid[public_key]"
                           v-model="seller_fluid_public_key" data-vv-as="&quot;PublicKey&quot;" class="form-control">
                </div>
                <div class="form-group text">
                    <label for="seller_fluid_api_key">
                        Api key
                    </label>
                    <input type="password" id="seller_fluid_api_key" name="seller-fluid[api_key]"
                           v-model="seller_fluid_api_key" data-vv-as="&quot;ApiKey&quot;" class="form-control">
                </div>
            </div>
            {{--end seller-fluid--}}

            {{--start blueDog --}}
            <div class="inner" v-visible="selectedPaymentMethod=='bluedog'">
                <div class="form-group text">
                    <label for="bluedog_public_key">
                        Public key
                    </label>
                    <input type="password" id="bluedog_public_key" name="bluedog[public_key]"
                           v-model="bluedog_public_key" data-vv-as="&quot;PublicKey&quot;" class="form-control">
                </div>
                <div class="form-group text">
                    <label for="bluedog_api_key">
                        Api key
                    </label>
                    <input type="password" id="bluedog_api_key" name="bluedog[api_key]" v-model="bluedog_api_key"
                           data-vv-as="&quot;ApiKey&quot;" class="form-control">
                </div>
            </div>
            {{--end blueDog--}}



            {{--start authorize--}}
            <div class="inner" v-visible="selectedPaymentMethod=='authorize'">
                <div class="form-group text">
                    <label for="public_key">
                        Transaction Key
                    </label>
                    <input type="password" id="authorize_public_key" name="authorize[public_key]"
                           v-model="authorize_public_key" data-vv-as="&quot;PublicKey&quot;" class="form-control">
                </div>
                <div class="form-group text">
                    <label for="api_key">
                        Api Login Id
                    </label>
                    <input type="password" id="authorize_api_key" name="authorize[api_key]" v-model="authorize_api_key"
                           data-vv-as="&quot;ApiKey&quot;" class="form-control">
                </div>
            </div>
            {{--end authorize--}}

            {{--start stripe--}}
            <div class="inner" v-visible="selectedPaymentMethod=='stripe'">
                <div class="form-group text">
                    <label for="public_key">
                        Public key
                    </label>
                    <input type="password" id="stripe_public_key" name="stripe[public_key]" v-model="stripe_public_key"
                           data-vv-as="&quot;PublicKey&quot;" class="form-control">
                </div>
                <div class="form-group text">
                    <label for="api_key">
                        Api key
                    </label>
                    <input type="password" id="stripe_api_key" name="stripe[api_key]" v-model="stripe_api_key"
                           data-vv-as="&quot;ApiKey&quot;" class="form-control">
                </div>
            </div>
            {{--end stripe--}}


        </div>
    </script>


    <script>

        Vue.component('store-settings', {
            template: '#store-settings-template',
            inject: ['$validator'],
            data: function () {
                return {
                    creditCardsMethods: [],
                    paymentMethods: @json($paymentMethods),
                    selectedPaymentMethod: '',
                    seller: @json($seller),
                    fluidCustomer: @json($fluidCustomer),
                    sellerFluidCustomer: @json($sellerFluidCustomer),
                    bluedogCustomer: @json($bluedogCustomer),
                    authorizeCustomer: @json($authorizeCustomer),
                    stripeCustomer: @json($stripeCustomer),
                    fluid_public_key: null,
                    fluid_api_key: null,
                    seller_fluid_public_key: null,
                    seller_fluid_api_key: null,
                    bluedog_public_key: null,
                    bluedog_api_key: null,
                    authorize_public_key: null,
                    authorize_api_key: null,
                    stripe_public_key: null,
                    stripe_api_key: null

                }

            },

            created: function () {

            },
            mounted: function () {

                for (let i=0 ;i < this.paymentMethods.length; i++){
                    if(['authorize','stripe','bluedog','fluidpay','fluid'].includes(this.paymentMethods[i].method)){
                        this.creditCardsMethods.push(this.paymentMethods[i]);
                    }
                }

                if (this.fluidCustomer) {
                    this.fluid_public_key = this.fluidCustomer.public_key;
                    this.fluid_api_key = this.fluidCustomer.api_key;
                }
                if (this.sellerFluidCustomer) {
                    this.seller_fluid_public_key = this.sellerFluidCustomer.public_key;
                    this.seller_fluid_api_key = this.sellerFluidCustomer.api_key;
                }
                if (this.bluedogCustomer) {
                    this.bluedog_public_key = this.bluedogCustomer.public_key;
                    this.bluedog_api_key = this.bluedogCustomer.api_key;
                }
                if (this.authorizeCustomer) {
                    this.authorize_public_key = this.authorizeCustomer.public_key;
                    this.authorize_api_key = this.authorizeCustomer.api_key;
                }
                if (this.stripeCustomer) {
                    this.stripe_public_key = this.stripeCustomer.public_key;
                    this.stripe_api_key = this.stripeCustomer.api_key;
                }
                this.checkSelectedMethod();
            },
            methods: {
                fluidExists(){
                    let x=false;
                    for (let i=0 ;i < this.paymentMethods.length;i++){
                        if(this.paymentMethods[i].method=='fluid'){
                            x=true;
                        }
                    }
                    return x;
                },
                checkSelectedMethod() {
                         if(this.fluid_api_key && this.fluid_public_key){
                             this.selectedPaymentMethod='fluid';
                         }
                        if(this.seller_fluid_api_key && this.seller_fluid_public_key){
                            this.selectedPaymentMethod='seller-fluid';
                        }
                        if(this.stripe_api_key && this.stripe_public_key){
                            this.selectedPaymentMethod='stripe';
                        }
                        if(this.authorize_api_key && this.authorize_public_key){
                            this.selectedPaymentMethod='authorize';
                        }
                        if(this.bluedog_api_key && this.bluedog_public_key){
                            this.selectedPaymentMethod='bluedog';
                        }
                }
            }
        });
        Vue.directive('visible', function (el, binding) {
            el.style.visibility = !!binding.value ? 'visible' : 'hidden';
            el.style.height = !!binding.value ? 'auto' : '0px';
        });
    </script>




@endpush