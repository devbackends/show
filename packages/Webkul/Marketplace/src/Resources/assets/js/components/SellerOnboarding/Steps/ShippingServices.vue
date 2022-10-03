<template>
    <div id="shipping-services">
        <div class="row">
            <div class="col">
                <div class="form-section">
                    <h3 class="form-section__title">Select Shipping Provider</h3>
                    <p>Please select one or more options from the shipping providers below. The selected shipping providers will dynamically generate shipping costs for your customers at checkout. You can always edit your shipping provider settings in your admin panel.</p>
                    <div class="errors" v-if="stepErrors.length > 0">
                        <p class="text-danger" v-for="error in stepErrors">{{error}}</p>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-switch" v-for="(method, index) in shippingMethods" :key="index">
                                    <input type="checkbox" class="custom-control-input" name="shippingMethods[]" :id="method.code" :value="method.code">
                                    <label class="custom-control-label" :for="method.code">{{ method.title }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col">
                            <a @click="$store.commit('revertStep', 'shippingServices')"
                               class="btn btn-outline-gray">
                                <i class="far fa-chevron-left mr-2"></i>Back
                            </a>
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
    name: "ShippingServices",
    data: () => ({
        shippingMethods: [],
        selectedShippingMethods: [],
        stepErrors: [],
        isLoading: false,
    }),
    mounted() {
        this.getShippingMethods();
    },
    methods: {
        async getShippingMethods() {
            const res = await this.$http.get(API_ENDPOINTS.sellerOnboarding.getShippingMethods);
            this.shippingMethods = (res.data.status === 'success') ? res.data.data.shippingMethods : [];
        },
        async submit() {
            const selectedShippingMethods = [];
            for (let checkbox of $('input[name="shippingMethods[]"]:checked')) {
                selectedShippingMethods.push($(checkbox).val())
            }

            if (selectedShippingMethods.length > 0) {
                this.$store.commit('setLoading', true);
                this.isLoading = true;
                const response = await this.$http.post(API_ENDPOINTS.sellerOnboarding.storeShippingInfo, {
                    shippingMethods: selectedShippingMethods
                });
                this.$store.commit('setLoading', false);
                this.isLoading = false;
                if (response.data.status === 'success') {
                    this.$store.commit('finishStep', 'shippingServices')
                } else {
                    this.stepErrors.merge(res.data.data.errors);
                    setTimeout(() => {
                        this.stepErrors = [];
                    }, 3000)
                }
            } else {
                this.stepErrors.push(`Please select at least one provider to continue.`);
                setTimeout(() => {
                    this.stepErrors = [];
                }, 3000)
                return false;
            }
        },
    },
}
</script>

<style scoped>

</style>