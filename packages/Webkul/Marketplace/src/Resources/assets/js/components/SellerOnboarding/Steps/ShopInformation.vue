<template>
    <div id="shop-information">
        <div class="row">
            <div class="col">
                <div class="form-section">
                    <h3 class="form-section__title">About Shop</h3>
                    <div class="errors" v-if="stepErrors.length > 0">
                        <p class="text-danger" v-for="error in stepErrors">{{error}}</p>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="sellerShopTitle" class="form-label-required">Shop Title</label>
                                <input type="text" class="form-control" id="sellerShopTitle" aria-describedby="shopTitle"
                                       v-model="shopInfo.title" placeholder="Shop Title" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group has-validation">
                                <label for="sellerShopUrl" class="form-label-required w-100">Shop Url</label>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">www.2agunshow.com/</span>
                                </div>
                                <input type="text" class="form-control" id="sellerShopUrl" aria-describedby="shopUrl"
                                       v-model="shopInfo.url" placeholder="your-shop-url" required>
                                <div class="invalid-feedback">
                                    Please provide the Url for your 2AGunShow marketplace store
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <h3 class="form-section__title">Contact Information</h3>
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group form__just-text">
                                <label>Name</label>
                                <p>{{ $store.getters.getCustomer.first_name }} {{ $store.getters.getCustomer.last_name }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group form__just-text">
                                <label>Email</label>
                                <p>{{ $store.getters.getCustomer.email }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="sellerShopPhoneNumber" class="form-label-required">Contact Phone Number</label>
                                <input type="text" class="form-control" id="sellerShopPhoneNumber" aria-describedby="shopPhoneNumber"
                                       v-model="shopInfo.phone" placeholder="Contact Phone Number" required>
                                <div class="invalid-feedback">Please provide a valid phone number.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="sellerAddressOne" class="form-label-required">Address 1</label>
                                <input type="text" class="form-control" id="sellerAddressOne" aria-describedby="addressOne"
                                       v-model="shopInfo.address1" placeholder="Address 1" required>
                                <div class="invalid-feedback">Please provide a valid address.</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="sellerAddressTwo">Address 2</label>
                                <input type="text" class="form-control" id="sellerAddressTwo" aria-describedby="AddressTwo"
                                       v-model="shopInfo.address2" placeholder="Address 2">
                                <div class="invalid-feedback">Please provide a valid address.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="sellerAddressCity" class="form-label-required">City</label>
                                <input type="text" class="form-control" id="sellerAddressCity" aria-describedby="addressCity"
                                       v-model="shopInfo.city" placeholder="City" required>
                                <div class="invalid-feedback">Please provide a valid city.</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="sellerAddressState">State</label>
                                <select id="sellerAddressState" class="form-control" required v-model="shopInfo.state">
                                    <option selected>Select State</option>
                                    <option v-for="(state, index) in states" :value="state.code" :key="index">
                                        {{ state.default_name }}
                                    </option>
                                </select>
                                <div class="invalid-feedback">Please select a state.</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="sellerAddressZipCode" class="form-label-required">Zip Code</label>
                                <input type="text" class="form-control" id="sellerAddressZipCode" aria-describedby="addressZipCode"
                                       v-model="shopInfo.zipcode" placeholder="Zip Code" required>
                                <div class="invalid-feedback">Please provide a valid zip code.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col">
                            <a href="/" class="btn btn-outline-gray"><i class="far fa-times mr-2"></i>Cancel</a>
                        </div>
                        <div class="col text-right">
                            <submit-button text="Next" faIconRight="chevron-right" @clickEvent="submit" :loading="isLoading"></submit-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {API_ENDPOINTS} from "../../../constant";

export default {
    name: "ShopInformation",
    data: () => ({
        states: [],
        shopInfo: {
            title: '',
            url: '',
            phone: '',
            address1: '',
            address2: '',
            city: '',
            state: '',
            zipcode: '',
        },
        stepErrors: [],
        requiredFields: ['title', 'url', 'phone', 'address1', 'city', 'zipcode'],
        isLoading: false
    }),
    async mounted() {
        this.states = await this.$store.dispatch('getStates');
    },
    methods: {
        async submit() {
            // Validate required fields
            for (let key of this.requiredFields) {
                if (!this.shopInfo[key] || this.shopInfo[key] === '') {
                    this.stepErrors.push(`${key.charAt(0).toUpperCase() + key.slice(1)} is required`);
                    setTimeout(() => {
                        this.stepErrors = [];
                    }, 3000)
                    return false;
                }
            }

            // Save to backend
            this.$store.commit('setLoading', true);
            this.isLoading = true;
            const response = await this.$http.post(API_ENDPOINTS.sellerOnboarding.storeShopInfo, this.shopInfo);
            this.$store.commit('setLoading', false);
            this.isLoading = false;

            // Enable next step
            if (response && response.data.status === 'success') {
                this.$store.commit('finishStep', 'shopInformation')
            }
        },
    },
}
</script>

<style scoped>

</style>