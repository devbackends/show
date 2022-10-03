<template>
    <div class="modal fade bd-example-modal-sm" :id="elId" tabindex="-1" role="dialog" aria-labelledby="select-ffl" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" ref="closeModal" class="close shipping-modal" data-dismiss="modal" aria-label="Close">
                        <i class="far fa-times" aria-hidden="true"></i>
                    </button>

                    <form action="" method="POST" data-vv-scope="address-form" @submit.prevent="saveAddress">
                        <h3 class="modal-title mb-3">Add new address</h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div :class="`form-group form-field ${errors.has('address-form.first_name') ? 'has-error' : ''}`">
                                    <label class="mandatory" for="first_name">First name</label>
                                    <input
                                        placeholder="First name"
                                        class="form-control"
                                        type="text"
                                        id="first_name"
                                        name="first_name"
                                        v-validate="'required'"
                                        v-model="addressForm.first_name"
                                        data-vv-as="&quot;First name&quot;"
                                    />
                                    <span class="control-error">{{ errors.first('address-form.first_name') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="`form-group form-field ${errors.has('address-form.last_name') ? 'has-error' : ''}`">
                                    <label for="last_name" class="mandatory">Last name</label>
                                    <input
                                        placeholder="Last name"
                                        class="form-control"
                                        type="text"
                                        id="last_name"
                                        name="last_name"
                                        v-validate="'required'"
                                        v-model="addressForm.last_name"
                                        data-vv-as="&quot;Last name&quot;"
                                    />
                                    <span class="control-error">{{ errors.first('address-form.last_name') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="`form-field ${errors.has('address-form.email') ? 'has-error' : ''}`">
                                    <label for="email" class="mandatory">Email</label>
                                    <input
                                        placeholder="Email"
                                        class="form-control"
                                        type="text"
                                        id="email"
                                        name="email"
                                        v-validate="'required'"
                                        v-model="addressForm.email"
                                        data-vv-as="&quot;Email&quot;"
                                    />
                                    <span class="control-error">{{ errors.first('address-form.email') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="`form-field ${errors.has('address-form.phone') ? 'has-error' : ''}`">
                                    <label for="phone" class="mandatory">Phone number</label>
                                    <input
                                        placeholder="Phone"
                                        class="form-control"
                                        type="text"
                                        id="phone"
                                        name="phone"
                                        v-validate="'required'"
                                        v-model="addressForm.phone"
                                        data-vv-as="&quot;Phone number&quot;"
                                    />
                                    <span class="control-error">{{ errors.first('address-form.phone') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h3 class="modal-section-title">Address</h3>
                            </div>
                            <div class="col-12">
                                <div :class="`form-field form-group ${errors.has('address-form.address1') ? 'has-error' : ''}`">
                                    <label for="shipping_address_0" class="mandatory">Street</label>
                                    <input
                                        type="text"
                                        placeholder="Street"
                                        class="form-control"
                                        v-validate="'required'"
                                        id="shipping_address_0"
                                        name="address1"
                                        v-model="addressForm.address1[0]"
                                        data-vv-as="&quot;Street&quot;"
                                    />
                                    <span class="control-error" v-if="errors.has('address-form.address1')">
                                    {{ errors.first('addressForm.address1') }}
                                </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div :class="`form-field form-group ${errors.has('address-form.city') ? 'has-error' : ''}`">
                                    <label for="shipping[city]" class="mandatory">City</label>
                                    <input
                                        type="text"
                                        placeholder="City"
                                        class="form-control"
                                        id="shipping[city]"
                                        name="city"
                                        v-validate="'required'"
                                        v-model="addressForm.city"
                                        data-vv-as="&quot;City&quot;"
                                    />
                                    <span class="control-error" v-if="errors.has('address-form.city')">
                                    {{ errors.first('address-form.city') }}
                                </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div :class="`form-field form-group ${errors.has('address-form.state') ? 'has-error' : ''}`">
                                    <label for="shipping[state]" class="mandatory">State</label>
                                    <select
                                        id="shipping[state]"
                                        name="state"
                                        v-validate="'required'"
                                        class="form-control"
                                        v-model="addressForm.state"
                                        data-vv-as="&quot;State&quot;"
                                    >
                                        <option value>State</option>
                                        <option v-for="(state, index) in states" :value="state.code" :key="index">
                                            {{ state.default_name }}
                                        </option>
                                    </select>
                                    <span class="control-error" v-if="errors.has('address-form.state')">
                                    {{ errors.first('address-form.state') }}
                                </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div :class="`form-field form-group ${errors.has('address-form.postcode') ? 'has-error' : ''}`">
                                    <label for="shipping[postcode]" class="mandatory">Zip/Postcode</label>
                                    <input
                                        type="text"
                                        placeholder="Zip/Postcode"
                                        class="form-control"
                                        id="shipping[postcode]"
                                        v-validate="'required'"
                                        name="postcode"
                                        v-model="addressForm.postcode"
                                        data-vv-as="&quot;Zip/Postcode&quot;"
                                    />
                                    <span class="control-error" v-if="errors.has('address-form.postcode')">
                                    {{ errors.first('address-form.shipping[postcode]') }}
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-actions d-flex flex-row-reverse align-center">
                            <div class="col-sm-2 text-right p-0 mt-1">
                                <submit-button text="Add new address" cssClass="ml-auto" :loading="isLoading"></submit-button>
                            </div>
                            <div :class="!addressAlreadyTaken ? 'hide' : ''" class="text-right col-sm-10">
                                <p class="red mb-0">You have entered an identical address to one already on your
                                    profile, please select the existing address or enter a new one. If you are shipping to
                                    the same address as your billing select "Ship to this address" above</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {API_ENDPOINTS} from "../../constant";
import submitButton from '../submit-button.vue';

    export default {
  components: { submitButton },
        name: "AddressFormPopup",
        props: {
            elId: {
                type: String,
                default: "newAddressFormPopup",
            },
        },
        inject: ["$validator"],
        data: function () {
            return {
                states: [],
                addressForm: {
                    first_name: null,
                    last_name: null,
                    email: null,
                    phone: null,
                    address1: [null],
                    city: null,
                    state: null,
                    country: "US",
                    postcode: null,
                    country_name: "United States",
                },
                addressAlreadyTaken: null,
                isLoading: false
            };
        },
        mounted() {
            this.states = this.$store.getters.getStates;

            let customer = this.$store.getters.getCustomer;
            this.addressForm.first_name = customer.first_name;
            this.addressForm.last_name = customer.last_name;
            this.addressForm.email = customer.email;
            this.addressForm.phone = customer.phone;
        },
        methods: {
            async saveAddress(e) {
                e.preventDefault();
                this.isLoading = true;
                let result = await this.$validator.validateAll('address-form');

                if (result) {
                    let res = await this.$http.post(API_ENDPOINTS.postAddress, {
                        address1: [this.addressForm.address1[0]],
                        city: this.addressForm.city,
                        country: this.addressForm.country,
                        country_name: this.addressForm.country_name,
                        phone: this.addressForm.phone,
                        postcode: this.addressForm.postcode,
                        state: this.addressForm.state,
                        first_name: this.addressForm.first_name,
                        last_name: this.addressForm.last_name,
                    }).catch(err => {
                        if (err.response.status === 422) {
                            serverErrors = err.response.data.errors;
                            this.$root.addServerErrors('address-form')
                        }

                        if (err.response.status === 409) {
                            this.addressAlreadyTaken = true;
                        }

                        if (err.response.status === 401) {
                            this.$store.commit('setCustomerEmail', this.addressForm.email);
                            this.$emit("change", {
                                address1: [this.addressForm.address1[0]],
                                city: this.addressForm.city,
                                country: this.addressForm.country,
                                country_name: this.addressForm.country_name,
                                phone: this.addressForm.phone,
                                postcode: this.addressForm.postcode,
                                state: this.addressForm.state,
                                first_name: this.addressForm.first_name,
                                last_name: this.addressForm.last_name,
                                email: this.addressForm.email,
                            });
                            this.addressAlreadyTaken = null;
                            this.$refs.closeModal.click();
                        }
                    });
                    if (res) {
                        this.$emit("change", {
                            ...res.data.data,
                            first_name: this.addressForm.first_name,
                            last_name: this.addressForm.last_name,
                        });
                        this.addressAlreadyTaken = null;
                        this.$refs.closeModal.click();
                    }
                }
                this.isLoading = false;
            },
        },
    };
</script>