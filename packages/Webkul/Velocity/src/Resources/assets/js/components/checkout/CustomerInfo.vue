<template>
    <div class="step-content information" id="address-section">
        <Accordian :active="true" :title="'Billing address'">
            <div class="form-header" slot="header">
                <h3 class="display-inbl">Billing address</h3>
            </div>

            <div slot="body">
                <div class="row d-flex align-items-stretch">
                    <div
                        :key="index"
                        class="col-lg-6 holder_item checkout__form-customer-item"
                        v-for="(addresses, index) in this.addresses"
                    >
                        <div
                            class="card checkout__form-customer-card"
                            v-bind:class="selectedAddress.id === addresses.id ? ' active' : ' no'"
                        >
                            <!--                             v-bind:class="activeAddress === index ? ' active' : ' no'">-->
                            <div class="card-body">
                                <div class="checkout__form-customer-card-radio">
                                    <div class="form-group">
                                        <div class="radio-button">
                                            <div class="radio-button__input">
                                                <input
                                                    v-bind:checked="selectedAddress.id === addresses.id"
                                                    type="radio"
                                                    name="billing[address_id]"
                                                    :value="addresses.id"
                                                    v-on:change="onAddressUpdate(index)"
                                                />
                                                <label for="billing[use_for_shipping]"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout__form-customer-card-info">
                                    <p
                                        class="card-title checkout__form-customer-card-name"
                                    >{{ addresses.first_name }} {{ addresses.last_name }},</p>
                                    <ul type="none" class="checkout__form-customer-card-address">
                                        <li>{{ addresses.address1[0] }}</li>
                                        <li>{{ addresses.city }}, {{ addresses.state }} {{ addresses.postcode }}</li>
                                        <li>{{ addresses.country }}</li>
                                        <li>Contact : {{ addresses.phone }}</li>
                                    </ul>
                                </div>
                                <div>
                                    <span class="control-error"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 checkout__form-customer-item">
                        <div
                            class="card h-100 cursor-pointer"
                            data-toggle="modal"
                            data-target="#newAddressPopup"
                        >
                            <div
                                class="card-body add-address-button text-center d-flex align-items-center justify-content-center"
                            >
                                <!--                                <div @click="newBillingAddress" class="cursor-pointer" data-toggle="modal"-->
                                <div class>
                                    <i class="material-icons">add_circle_outline</i>
                                    <p>Add a new address</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="control-error"></span>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <div class="radio-button checkbox-button">
                                <div class="radio-button__input">
                                    <input
                                        class
                                        type="checkbox"
                                        id="billing[use_for_shipping]"
                                        name="billing[use_for_shipping]"
                                        v-model="shipToBillingAddress"
                                    />
                                    <label for="billing[use_for_shipping]"></label>
                                </div>
                                <div class="radio-button__label">Ship to this address (not available for firearms)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Accordian>
        <address-popup v-on:change="addNewAddress"></address-popup>
    </div>
</template>

<script>
    import Accordian from "./Accordian";
    import {API_ENDPOINTS} from "../../constant";
    import AddressPopup from "./AddressPopup";

    export default {
        name: "CustomerInfo",
        inject: {$validator: "$validator"},
        components: {
            Accordian,
            AddressPopup,
        },
        props: {
            cart: {
                type: Object,
            },
        },
        data: function () {
            return {
                addresses: [],
                selectedAddress: {id: null},
                shipToBillingAddress: false,
            };
        },
        methods: {
            onAddressUpdate: function (index) {
                this.selectedAddress = this.addresses[index];
                this.$emit("change", this.addresses[index]);
            },
            addNewAddress: function (address) {
                this.addresses.push(address);
            },
        },
        async mounted() {
            const addresses = await this.$http
                .get(API_ENDPOINTS.getAddresses)
                .catch((err) => {
                    console.log(err);
                });
            if (addresses) {
                addresses.data.data.map((el) => {
                    this.addresses.push(el);
                });
                if (this.cart.billing_address) {
                    this.addresses.forEach((el, key) => {
                        if (
                            el.city === this.cart.billing_address.city &&
                            el.address1[0] === this.cart.billing_address.address1[0]
                        ) {
                            this.onAddressUpdate(key);
                        }
                    });
                }
            }
        },
        watch: {
            cart: {
                handler() {
                    if (!this.cart.billing_address) return;
                    if (this.selectedAddress.id) return;
                    this.addresses.forEach((el, key) => {
                        if (
                            el.city === this.cart.billing_address.city &&
                            el.address1[0] === this.cart.billing_address.address1[0]
                        ) {
                            // this.onAddressUpdate(key);
                        }
                    });
                },
                deep: true,
            },
            shipToBillingAddress: function () {
                eventBus.$emit("shipToBillingAddress", this.shipToBillingAddress);
            },
        },
        computed: {},
    };
</script>

<style scoped>
</style>
