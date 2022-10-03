<template>
    <div class="step-content information" id="address-section"  :class="!isActive ? 'disabled' : ''">
        <Accordian  title="Billing address" :disabled="!isActive" :title="'Shipping information'" :active="isActive">
            <div class="form-header" slot="header">
                <h3 class="display-inbl">Billing address</h3>
            </div>
            <div slot="body">
                <div  class="row d-flex align-items-stretch">
                    <div
                        :key="index"
                        class="col-lg-6 holder_item checkout__form-customer-item"
                        v-for="(address, index) in this.addresses"
                    >
                        <div class="card checkout__form-customer-card"
                            :class="selectedAddress.id === address.id ? ' active' : ' no'">
                            <div class="card-body">
                                <div class="checkout__form-customer-card-radio">
                                    <div class="custom-control custom-radio">
                                        <input :checked="selectedAddress.id === address.id"
                                                    type="radio"
                                                    name="billing[address_id]"
                                                    :value="address.id"
                                                    @change="onAddressInputUpdate(index)"
                                                    :id="`billing-${address.id}`" class="custom-control-input">
                                        <label class="custom-control-label" :for="`billing-${address.id}`">
                                            <p class="card-title checkout__form-customer-card-name">{{ address.first_name }} {{ address.last_name }},</p>
                                            <ul type="none" class="checkout__form-customer-card-address">
                                                <li>{{ address.address1[0] }}</li>
                                                <li>{{ address.city }}, {{ address.state }} {{ address.postcode }}</li>
                                                <li>{{ address.country }}</li>
                                                <li>Contact : {{ address.phone }}</li>
                                            </ul>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 checkout__form-customer-item">
                        <div class="card h-100 cursor-pointer" data-toggle="modal" data-target="#newAddressFormPopup">
                            <div class="card-body add-address-button text-center d-flex align-items-center justify-content-center">
                                <div class>
                                    <i class="far fa-plus"></i>
                                    <p>Add a new address</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group mb-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" id="billing[use_for_shipping]" v-model="shipToBillingAddress" name="billing[use_for_shipping]" class="custom-control-input">
                                <label class="custom-control-label" for="billing[use_for_shipping]">Ship to this address (not available for firearms)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordian-content--loading" v-show="isLoading">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </Accordian>
        <address-form-popup v-if="$store.getters.getCartOptions.steps.billingAddress"  @change="addNewAddress"></address-form-popup>
    </div>
</template>

<script>
    import {API_ENDPOINTS} from "../../constant";
    import Accordian from "./Accordian";
    import AddressFormPopup from "./AddressFormPopup";
    import {mapState} from "vuex";

    export default {
        name: "BillingAddress",
        inject: {$validator: "$validator"},
        components: {
            Accordian,
            AddressFormPopup,
        },
        data: function () {
            return {
                addresses: [],
                selectedAddress: {id: null},
                shipToBillingAddress: false,
                isLoading: false
            };
        },
        props: {
            isActive: Boolean
        },
        mounted() {
            this.addresses = this.$store.getters.getCustomerAddresses;
            let cartBillingAddress = this.$store.getters.getCart.billing_address;
            if (cartBillingAddress) {
                this.addresses.forEach(el => {
                    if (el.city === cartBillingAddress.city
                        && el.address1[0] === cartBillingAddress.address1[0]
                    ) {
                        this.selectedAddress = el;
                        this.emitResult();
                    }
                });
            }
        },
        computed: {
            ...mapState({
                stateShipToBillingAddress: state => state.shipToBillingAddress,
            }),
        },
        methods: {
            async updateAddress() {
                //document.body.style.cursor = "wait";
                this.isLoading = true;
                let res = await this.$http.post(API_ENDPOINTS.postCheckoutSaveBillingAddress, {
                    sellerId: this.$store.getters.getCart.seller_id,
                    billing: {
                        ...this.selectedAddress,
                        email: this.$store.getters.getCustomer.email,
                    },
                }).catch(err => {
                    console.log(err);
                });
                if(typeof res.data.data.cart != 'undefined'){
                    this.$store.commit('setCart', res.data.data.cart);
                }
                //document.body.style.cursor = "default";
                this.isLoading = false;
                return true;
            },
            async onAddressInputUpdate(index) {
                this.selectedAddress = this.addresses[index];
                await this.updateAddress();
                this.emitResult();
            },
            addNewAddress(address) {
                this.addresses = [
                    ...this.addresses,
                    address
                ]; //
                this.$store.commit('setCustomerAddresses', [...this.addresses]);
                this.onAddressInputUpdate(this.addresses.length - 1 );
            },
            emitResult() {
                this.$emit('done');
            },
            setAddresses(addresses) {
                this.addresses = addresses;
            }
        },
        watch: {

            shipToBillingAddress() {
                this.$store.commit('setShipToBillingAddress', this.shipToBillingAddress);
                if (!this.$store.getters.getCartOptions.other.is) return false;
                let cart = this.$store.getters.getCart;
                if (this.shipToBillingAddress) {
                    cart.shipping_address = {
                        ...this.selectedAddress,
                        name: this.selectedAddress.first_name + ' ' + this.selectedAddress.last_name,
                        email: this.$store.getters.getCustomer.email,
                    }
                } else {
                    cart.shipping_address = null;
                }
                this.$store.commit('setCart', cart);
            },
            stateShipToBillingAddress: function () {
                this.shipToBillingAddress = this.stateShipToBillingAddress;
            }
        },
    };
</script>

<style scoped>
</style>
