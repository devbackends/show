<template>
    <div class="step-content" :class="!isActive ? 'disabled' : ''" id="shipping-section">
        <Accordian :disabled="!isActive" :title="'Shipping information'" :active="isActive">
            <div class="form-header" slot="header">
                <h3 class="display-inbl">Shipping information</h3>
            </div>
            <div :class="'shipping-methods'" slot="body">
                <p class="font-weight-bold">Products sold by {{ $store.getters.getSeller.shop_title }}</p>
                <template v-if="fflItems.length > 0">
                    <p class="mb-4">
                        Certain items in your cart must be delivered to a Federal Firearms License (FFL) holder. Please
                        select an FFL for delivery, or select pickup directly from our store.
                    </p>
                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <h4 class="product-type">Firearms</h4>
                        </div>
                        <shimmer-radiobutton-component v-if="isLoadingFFLRates" shimmer-count="3" class="d-none d-lg-block"></shimmer-radiobutton-component>
                        <shimmer-radiobutton-component v-if="isLoadingFFLRates" shimmer-count="2" class="d-none d-md-block d-lg-none"></shimmer-radiobutton-component>
                        <shimmer-radiobutton-component v-if="isLoadingFFLRates" shimmer-count="1" class="d-block d-md-none"></shimmer-radiobutton-component>
                        <div v-if="error">
                            <p class="text-danger">{{error}}</p>
                        </div>
                        <div v-else>
                            <div class="col-md-12 shipping-methods__options">
                                <div v-if="rates.ffl && rates.ffl.rates" class="shipping-methods__options-seller">
                                    <div class="row">
                                        <div class="col-12 col-sm shipping-methods__products-list" v-html="'<ul><li>' + rates.ffl.products + '</li></ul>'"></div>
                                    </div>
                                    <div class="mt-2">
                                        <div v-for="(items, code) in rates.ffl.rates" :key="'ffl_'+code">
                                            <div class="form-group" v-for="(item, index) in items" :key="index">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" @change="onSelectedRatesChanging"
                                                           type="radio"
                                                           v-model="selectedRates.ffl"
                                                           :id="`ffl_${item.method}`"
                                                           :value="`${item.id}`">
                                                    <label class="custom-control-label" :for="`ffl_${item.method}`">
                                                        <p class="mb-0">{{ item.method_title }}</p>
                                                        <p class="mb-0">${{ Math.round(item.base_price * 100) / 100 }}</p>
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="rates.perProductFfl && Object.keys(rates.perProductFfl).length > 0"
                                     v-for="(rate, productId) in rates.perProductFfl" :key="'perProductFfl'+productId"
                                     class="shipping-methods__options-seller">
                                    <div class="row">
                                        <div class="col-12 col-sm shipping-methods__products-list">
                                            <ul><li>{{rate.product.name}}</li></ul>
                                        </div>
                                    </div>
                                    <div class="mt-2 pb-3">
                                        <div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" @change="onSelectedRatesChanging"
                                                       type="radio"
                                                       v-model="selectedRates.perProductFfl[productId]"
                                                       :id="`${rate.method}`"
                                                       :value="`${rate.id}`">
                                                <label class="custom-control-label" :for="`${rate.method}`">
                                                    <p class="mb-0">{{(rate.base_price > 0) ? '$'+rate.base_price : 'FREE SHIPPING'}}</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 d-flex" v-if="!selectedFflShipping">
                                <div class="mr-auto">
                                    <button
                                        id="select-an-ffl-delivery-button"
                                        data-toggle="modal"
                                        data-target=".bd-example-modal-xl"
                                        class="btn btn-outline-primary"
                                    >Select an FFL for delivery
                                    </button>
                                </div>
                            </div>
                            <div v-if="selectedFflShipping" class="col-sm-12 ">
                                <p class="mb-1">This item will be delivered to:</p>
                                <p class="mb-0">
                                    <strong>{{ selectedFflShipping.company_name }}</strong>
                                </p>
                                <p class="mb-0">{{ selectedFflShipping.street_address }}</p>
                                <p>{{ selectedFflShipping.city }}, {{ selectedFflShipping.stateCode }} {{ selectedFflShipping.zip_code }}</p>
                                <div class="row m-0 ffl-fees-alert">
                                    <div class="col-sm-10 ffl-fees-alert-container">
                                       <div class="row">
                                           <div class="col-1 informative-icon-container">
                                               <span ><i class="fa fa-2x fa-info-square"></i></span>
                                           </div>
                                           <div class="col-10">
                                               <span>Keep in mind that you have to pay fees</span>
                                               <span v-if="typeof selectedFflShipping.hand_gun != 'undefined' && selectedFflShipping.hand_gun" v-text="selectedFflShipping.hand_gun+ '$ for hand gun, '"></span>
                                               <span v-if="typeof selectedFflShipping.long_gun != 'undefined' && selectedFflShipping.long_gun" v-text="selectedFflShipping.long_gun+ '$ for long gun, '"></span>
                                               <span v-if="typeof selectedFflShipping.nics != 'undefined' && selectedFflShipping.nics" v-text="selectedFflShipping.nics+ '$ for nics, '"></span>
                                               <span v-if="typeof selectedFflShipping.other != 'undefined' && selectedFflShipping.other" v-text="selectedFflShipping.other+ '$ for other, '"></span>
                                               <span>when you pick up the product.</span>
                                           </div>
                                       </div>
                                    </div>
                                </div>
                                <button data-toggle="modal" data-target=".bd-example-modal-xl" class="btn btn-outline-dark btn-sm">
                                    Change delivery location
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-if="otherItems.length > 0">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="font-weight-bold product-type">Other products</p>
                        </div>
                        <shimmer-radiobutton-component v-if="isLoadingRates" shimmer-count="3" class="d-none d-lg-block"></shimmer-radiobutton-component>
                        <shimmer-radiobutton-component v-if="isLoadingRates" shimmer-count="2" class="d-none d-md-block d-lg-none"></shimmer-radiobutton-component>
                        <shimmer-radiobutton-component v-if="isLoadingRates" shimmer-count="1" class="d-block d-md-none"></shimmer-radiobutton-component>
                        <div v-if="error" class="w-100">
                          <div class="col-md-12">
                            <p class=" text-danger">{{ error }}</p>
                            <a target="_blank" :href="baseUrl+'/'+$store.getters.getSeller.url">
                              <button class="btn checkout-button btn-outline-gray-dark btn-primary">Contact Seller
                              </button>
                            </a>
                          </div>
                        </div>
                        <div v-else class="w-100">
                            <div class="col-md-12 shipping-methods__options">
                                <div v-if="rates.other && rates.other.rates" class="shipping-methods__options-seller">
                                    <div class="row">
                                        <div class="col-12 col-sm shipping-methods__products-list" v-html="'<ul><li>' + rates.other.products + '</li></ul>'"></div>
                                    </div>
                                    <div class="mt-2">
                                        <div v-for="(items, code) in rates.other.rates" :key="'other_'+code">
                                            <div class="form-group" v-for="(item, index) in items" :key="index">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" @change="onSelectedRatesChanging"s
                                                           type="radio"
                                                           v-model="selectedRates.other"
                                                           :id="`other_${item.method}`"
                                                           :value="`${item.id}`">
                                                    <label class="custom-control-label" :for="`other_${item.method}`">
                                                        <p class="mb-0">{{ item.method_title }}</p>
                                                        <p class="mb-0">${{ Math.round(item.base_price * 100) / 100 }}</p>
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="rates.perProduct && Object.keys(rates.perProduct).length > 0"
                                     v-for="(rate, productId) in rates.perProduct" :key="'perProduct'+productId"
                                     class="shipping-methods__options-seller">
                                    <div class="row">
                                        <div class="col-12 col-sm shipping-methods__products-list">
                                            <ul><li>{{rate.product.name}}</li></ul>
                                        </div>
                                    </div>
                                    <div class="mt-2 pb-3">
                                        <div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" @change="onSelectedRatesChanging"
                                                       type="radio"
                                                       v-model="selectedRates.perProduct[productId]"
                                                       :id="`${rate.method}`"
                                                       :value="`${rate.id}`">
                                                <label class="custom-control-label" :for="`${rate.method}`">
                                                    <p class="mb-0">{{(rate.base_price > 0) ? '$'+rate.base_price : 'FREE SHIPPING'}}</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 d-flex" v-if="!selectedOtherShipping">
                                <div class="mr-auto">
                                    <button
                                        data-toggle="modal"
                                        data-target="#selectAddressPopup"
                                        id="select-an-not-ffl-delivery-button"
                                        class="btn btn-outline-primary shipping-btn"
                                    >Select shipping address
                                    </button>
                                </div>
                            </div>
                            <div v-if="selectedOtherShipping" class="col-sm-12">
                                <p class="mb-1">This item will be delivered to:</p>
                                <p class="mb-0">
                                    <strong>{{ selectedOtherShipping.first_name }}
                                        {{ selectedOtherShipping.last_name }}</strong>
                                </p>
                                <p class="mb-0">{{ selectedOtherShipping.address1[0] }}</p>
                                <p>{{ selectedOtherShipping.city }}, {{ selectedOtherShipping.state }} {{ selectedOtherShipping.postcode }}</p>
                                <button data-toggle="modal" data-target="#selectAddressPopup" class="btn btn-outline-dark btn-sm">
                                    Change shipping address
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <div class="accordian-content--loading" v-show="isLoading">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </Accordian>
        <ffl-popup v-if="$store.getters.getCartOptions.steps.shipping" @done="onSelectedFflShipping"></ffl-popup>
        <address-popup v-if="$store.getters.getCartOptions.steps.shipping" @done="onSelectedOtherShipping"></address-popup>
    </div>
</template>

<script>
import Accordian from "./Accordian";
import {API_ENDPOINTS} from "../../constant";
import FflPopup from "./FflPopup";
import AddressPopup from "./AddressPopup";
import {mapState} from 'vuex';

export default {
    name: "Shipping",
    components: {
        Accordian,
        FflPopup,
        AddressPopup,
    },
    props: {
        isActive: Boolean,
        shipToBillingAddress: [Object, null],
    },
    data: () =>  ({
        fflItems: [],
        otherItems: [],
        selectedFflShipping: null,
        selectedOtherShipping: null,
        rates: {},
        selectedRates: {},
        error: false,
        isLoading: false,
        isLoadingRates: false,
        isLoadingFFLRates: false,
        isRateSelectingDone: false,
    }),
    async mounted() {
        if (this.$store.getters.getCartOptions.withoutShipping.only) return false;

        let cart = this.$store.getters.getCart;
        if (cart.shipping_address && !this.selectedOtherShipping) {
            this.selectedOtherShipping = cart.shipping_address;
        }
        if (cart.ffl_address && !this.selectedFflShipping) {
            let res = await this.$http.get(API_ENDPOINTS.getFflById + "/" + cart.ffl_address.ffl_id).catch((err) => {
                console.log(err);
            });
            const addr = res.data.business_info
            addr.stateCode = this.$store.getters.getStates.filter((s) => s.id === addr.state)[0].code;
            this.selectedFflShipping = addr;
        }
        this.fflItems = this.$store.getters.getCartOptions.ffl.items;
        this.otherItems = this.$store.getters.getCartOptions.other.items;

        // Check if all rates already selected
        let done = true;
        if (this.otherItems.length > 0 && !cart.selected_shipping_rate) done = false;
        if (this.fflItems.length > 0 && !cart.selected_ffl_shipping_rate) done = false;
        this.isRateSelectingDone = done;

        await this.callGetShippingRatesMethod();

        // If all rates already selected - change inputs and emit done
        if (this.isRateSelectingDone) {
            this.$emit('done');
        }

    },
    computed: {
        ...mapState({
            shipAddr: state => state.cart.shipping_address,
        }),
    },
    methods: {
        async onSelectedOtherShipping(addr) {
            this.$store.commit('setStepToInactive', 'payment');
            this.$store.commit('setStepToInactive', 'confirmation');
            this.$store.commit('setStepToInactive', 'confirmed');
            this.rates = {};
            this.selectedRates = {};

            this.selectedOtherShipping = addr;
            //document.body.style.cursor = "wait";
            this.isLoadingRates = true;
            console.log("onSelectedOtherShipping start");
            let res = await this.$http.post(API_ENDPOINTS.postCheckoutShippingAddress, {
                sellerId: this.$store.getters.getCart.seller_id,
                shipping: {
                    ...addr,
                    email: this.$store.getters.getCustomer.email,
                },
            }).catch(err => {
                console.log(err)
            });
            if (res.data.data.error) {
                //document.body.style.cursor = "default";
                this.isLoadingRates = false;
                this.error = res.data.data.message;
            } else {
                let resCart = res.data.data.cart,
                    cart = this.$store.getters.getCart;

                if (cart.billing_address.address1[0] !== resCart.shipping_address.address1[0]
                    || cart.billing_address.city !== resCart.shipping_address.city
                    || cart.billing_address.state !== resCart.shipping_address.state) {
                    this.$store.commit('setShipToBillingAddress', false)
                }

                this.$store.commit('setCart', res.data.data.cart);
                await this.callGetShippingRatesMethod();
                //document.body.style.cursor = "default";
                this.isLoadingRates = false;
            }
        },
        async onSelectedFflShipping(addr) {
            this.$store.commit('setStepToInactive', 'payment');
            this.$store.commit('setStepToInactive', 'confirmation');
            this.$store.commit('setStepToInactive', 'confirmed');
            this.rates = {};
            this.selectedRates = {};

            addr.stateCode = this.$store.getters.getStates.filter((s) => s.id === addr.state)[0].code;
            this.selectedFflShipping = addr;
            //document.body.style.cursor = "wait";
            this.isLoadingFFLRates = true;
            let res = await this.$http.post(API_ENDPOINTS.postCheckoutSaveFflShippingAddress, {
                sellerId: this.$store.getters.getCart.seller_id,
                shipping: {
                    company_name: addr.company_name,
                    first_name: addr.contact_name,
                    last_name: '',
                    ffl_id: addr.ffl_id,
                    address1: [addr.street_address],
                    city: addr.city,
                    phone: addr.phone,
                    postcode: addr.zip_code,
                    country: "US",
                    state: addr.state,
                },
            }).catch(err => {
                console.log(err);
            });

            this.$store.commit('setCart', res.data.data.cart);
            await this.callGetShippingRatesMethod();
            //document.body.style.cursor = "default";
            this.isLoadingFFLRates = false;
        },
        async callGetShippingRatesMethod() {
            let call = true;
            if (this.$store.getters.getCartOptions.other.is && !this.selectedOtherShipping) {
                call = false;
            }
            if (this.$store.getters.getCartOptions.ffl.is && !this.selectedFflShipping) {
                call = false;
            }
            if (call) {
                return await this.getShippingRates();
            }
            return false;
        },
        async getShippingRates() {
            this.isLoadingRates = true;
            this.isLoadingFFLRates = true;
            let res = await this.$http.get(API_ENDPOINTS.getShippingRates
                + '?sellerId='+this.$store.getters.getCart.seller_id
                + '&refresh='+(this.isRateSelectingDone ? '0' : '1')
            ).catch(err => {
                console.log(err);
            });

            this.rates = res.data.data;
            if(this.rates.length==0){
              this.error="Error on shipping Please Contact Seller";
            }
            let cart = this.$store.getters.getCart;
            let keys = Object.keys(this.rates);
            const obj = {}
            for (let key of keys) {
                if (key === 'perProduct' || key === 'perProductFfl') {
                    obj[key] = {}
                    for (let key2 of Object.keys(this.rates[key])) {
                        if (this.isRateSelectingDone) {
                            obj[key][key2] = this.rates[key][key2].id
                        } else  {
                            obj[key][key2] = null;
                        }
                    }
                } else {
                    if (this.isRateSelectingDone) {
                        if (key === 'other') {
                            obj[key] = cart.selected_shipping_rate.id;
                        } else {
                            obj[key] = cart.selected_ffl_shipping_rate.id;
                        }
                    } else {
                        obj[key] = null;
                    }
                }
            }
            this.selectedRates = obj;
            this.isLoadingRates = false;
            this.isLoadingFFLRates = false;
        },
        async onSelectedRatesChanging() {
            // Check if rates have been selected for all types
            for (let key in this.selectedRates) {
                if (key === 'perProduct' || key === 'perProductFfl') {
                    for (let key2 in this.selectedRates[key]) {
                        if (!this.selectedRates[key][key2]) return false;
                    }
                } else {
                    if (!this.selectedRates[key]) delete this.selectedRates[key];
                }
            }
            if(Object.keys(this.selectedRates).length == 0) return false;
            //document.body.style.cursor = 'wait';
            this.isLoading = true;
            let res = await this.$http.post(API_ENDPOINTS.postCheckoutSaveShippingRate, {
                sellerId: this.$store.getters.getCart.seller_id,
                selectedRates: this.selectedRates,
            }).catch(err => {
                console.log(err);
            });
            this.$store.commit('setCart', res.data.data.cart);
            //document.body.style.cursor = 'default';
            this.isLoading = false;
            this.$emit('done');
        },
    },
    watch: {
        shipAddr: function (newValue, oldValue) {
            if (!newValue) return false;
            if (oldValue) {
                if (newValue.address1[0] === oldValue.address1[0] && newValue.city === oldValue.city) return false;
            }
            this.selectedOtherShipping = newValue;
            this.onSelectedOtherShipping(newValue);
        },
    },
};
</script>
