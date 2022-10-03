<template>
    <div id="payments">
        <div class="row">
            <div class="col">
                <div class="form-section">
                    <h3 class="form-section__title">Payment information</h3>
                    <p>Commission payments will be billed to your credit card on file.</p>
                    <p class="form-notice bg-light">
                        <span class="notice-icon"><i class="far fa-info-circle"></i></span>
                        Your card will be charged $0.99 to validate your seller account. The $0.99 will go towards your first listing fee for your first product.
                    </p>
                    <div class="errors" v-if="stepErrors.length > 0">
                        <p class="text-danger" v-for="error in stepErrors">{{error}}</p>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="fluid-form" class="mb-3"></div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col">
                            <a class="btn btn-outline-gray"
                               @click="$store.commit('revertStep', 'payments')">
                                <i class="far fa-chevron-left mr-2"></i>Back
                            </a>
                        </div>
                        <div class="col text-right">
                            <submit-button v-if="$store.getters.getSellerPlan === 'basic'" text="Finish" faIconRight="check" @clickEvent="tokenizer.submit()" :loading="isLoading"></submit-button>
                            <submit-button v-else text="Next" faIconRight="chevron-right" @clickEvent="tokenizer.submit()" :loading="isLoading"></submit-button>
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
    name: "Payments",
    data: () => ({
        tokenizer: null,
        stepErrors: [],
        isLoading: false,
    }),
    async mounted() {
        const response = await this.$http.get(API_ENDPOINTS.sellerOnboarding.getTokenizerInfo);
        if (response.data.status) {
            this.tokenizer = new Tokenizer({
                url: response.data.data.url,
                apikey: response.data.data.public_key,
                container: document.querySelector('#fluid-form'),
                settings: {
                    payment: {
                        showTitle: true,
                        placeholderCreditCard: '0000 0000 0000 0000',
                        showExpDate: true,
                        showCVV: true
                    },
                    user: {
                        showInline: true,
                        showName: true,
                        showEmail: true,
                        showTitle: true
                    },
                    billing: {
                        show: true,
                        showTitle: true
                    }
                },
                submission: (res) => {
                    if (res.status === 'success') {
                        this.submit({
                            token: res.token,
                            billingInfo: {
                                ...res.user,
                                ...res.billing,
                            }
                        })
                    } else {
                        this.stepErrors.push(res.message)
                        setTimeout(() => {
                            this.stepErrors = [];
                        }, 3000)
                    }
                },
            })
        }
    },
    methods: {
        async submit(data) {
            this.$store.commit('setLoading', true);
            this.isLoading = true;
            let response = await this.$http.post(API_ENDPOINTS.sellerOnboarding.storePayments, {
                paymentInfo: {
                    token: data.token,
                },
                billingInfo: data.billingInfo,
            })
            if (response.data.status !== 'success') {
                this.$store.commit('setLoading', false);
                this.isLoading = false;
                return false;
            }

            response = await this.$http.get(API_ENDPOINTS.sellerOnboarding.storeSeller);
            this.$store.commit('setLoading', false);
            this.isLoading = false;
            if (response.data.status === 'success') {
                if (this.$store.getters.getSellerPlan === 'basic') {
                    location.href = response.data.data.redirectUrl;
                } else {
                    this.$store.commit('setRedirectUrl', response.data.data.redirectUrl)
                    this.$store.commit('finishStep', 'payments')
                }
            } else {
                window.showAlert('alert-danger', 'Error', 'Something is wrong with your payment info, please retype it');
            }
        },
    },
}
</script>

<style scoped>

</style>
