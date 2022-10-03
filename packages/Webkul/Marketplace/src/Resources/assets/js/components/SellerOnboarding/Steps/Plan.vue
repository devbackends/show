<template>
    <div id="select-your-plan">

        <div class="row">
            <div class="col">
                <div class="form-section">
                    <div class="errors" v-if="stepErrors.length > 0">
                        <p class="text-danger" v-for="error in stepErrors">{{error}}</p>
                    </div>
                    <div class="row" v-if="Object.keys(plans).length > 0">
                        <div class="col-12 col-md">
                            <div id="basic-seller-plan" :class="`seller-plan-card ${selectedPlan === 'basic' ? 'seller-plan-card--selected' : ''}`">
                                <h2 class="seller-plan-card__title">Basic Seller</h2>
                                <div class="seller-plan-card__link">
                                    <button type="button" @click="selectPlan('basic')" class="btn btn-primary btn-block">Select this plan</button>
                                </div>
                                <ul class="seller-plan-card__features">
                                    <li>Accept Cash Payments</li>

                                    <li v-if="plans.basic.listingFee.enabled">${{ plans.basic.listingFee.amount }} listing fee</li>
                                    <li v-else>No listing fee</li>

                                    <li v-if="plans.basic.orderComission.enabled">{{ plans.basic.orderComission.percentage }}% commission fee</li>
                                    <li v-else>No commission fee</li>
                                </ul>
                                <div class="seller-plan-card__price seller-plan-card__price--no-fee">
                                    <div v-if="plans.basic.subscription.enabled">
                                        <p class="seller-plan-card__price-number">${{ plans.basic.subscription.amount }}</p>
                                        <p class="seller-plan-card__price-description">Monthly subscription</p>
                                    </div>
                                    <p v-else class="seller-plan-card__price-no-fee">No monthly fee</p>
                                </div>
                                <p>Recommended for casual sellers, gun show vendors, collectors, or any seller listing fewer than 10 products per month.</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-5">
                            <div id="plus-seller-plan" :class="`seller-plan-card seller-plan-card--recommended ${selectedPlan === 'plus' ? 'seller-plan-card--selected' : ''}`">
                                <img src="/themes/market/assets/images/seller-plan-recommended.svg" alt="" class="seller-plan-card--recommended-badge">
                                <h2 class="seller-plan-card__title"><span>Plus</span> Seller</h2>
                                <div class="seller-plan-card__link">
                                    <button type="button" @click="selectPlan('plus')" class="btn btn-primary btn-lg btn-block">Select this plan</button>
                                </div>
                                <ul class="seller-plan-card__features">
                                    <li>
                                        <p>Accept Cash and <strong>Credit Card</strong> Payments</p>
                                       <div>
                                            <p class="mb-0 font-weight-bold">Powered by:</p>
                                        </div>
                                        <div class="mb-3">
                                            <img src="https://www.2acommerce.com/themes/commerce/public/images/site-logo.svg" alt="" width="150px">
                                        </div>
                                        <div class="mb-2">
                                            <button type="button" class="btn btn-outline-info-dark" data-toggle="modal" data-target="#plusSellerModal">
                                            Learn More
                                            </button>
                                        </div>
                                    </li>
                                    <li v-if="plans.plus.listingFee.enabled">${{ plans.plus.listingFee.amount }} listing fee</li>
                                    <li v-else>No listing fee</li>

                                    <li v-if="plans.plus.orderComission.enabled">{{ plans.plus.orderComission.percentage }}% commission fee</li>
                                    <li v-else>No commission fee</li>
                                </ul>
                                <p>Recommended for sellers who plan to list more than 10 items per month or who would like to provide the option of paying with a credit card.</p>
                            </div>
                        </div>
                        <div class="col-12 col-md">
                            <div id="pro-seller-plan" class="seller-plan-card">
                                <h2 class="seller-plan-card__title">Pro Seller</h2>
                                <div class="seller-plan-card__link">
                                    <button type="button" class="btn btn-primary btn-block" disabled>Coming soon!</button>
                                </div>
                                <ul class="seller-plan-card__features">
                                    <li>Merchant sellers with their own custom ecommerce webstore</li>
                                    <li>Use your own merchant services account or register to use ours at 2A Commerce</li>
                                </ul>
                                <div class="seller-plan-card__price">
                                    <p class="seller-plan-card__price-number">${{ plans.pro.subscription.amount }}</p>
                                    <p class="seller-plan-card__price-description">Monthly subscription</p>
                                </div>
                                <p>Recommended for custom brands, gun stores, etc. who are operating their own ecommerce website but want to sell into 2A Gun Show and harness the power of our marketing channels.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col">
                            <a class="btn btn-outline-gray"
                               @click="$store.commit('revertStep', 'plan')">
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

        <!-- Plus Seller Learn More Modal -->
        <div class="modal fade" id="plusSellerModal" tabindex="-1" aria-labelledby="plusSellerModalLabel" aria-hidden="true" v-if="Object.keys(plans).length && plans.plus">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content seller-plus-modal">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="far fa-times"></i>
                    </button>
                    <div class="modal-body">
                            <div class="row mx-0 align-items-md-stretch">
                                <div class="col-12 col-md-auto px-0">
                                    <div class="seller-plus-modal__side-info">
                                        <div class="seller-plus-modal__side-info-logo">
                                            <img src="https://www.2acommerce.com/themes/commerce/public/images/site-logo.svg" alt="" width="150px">
                                        </div>
                                        <div class="seller-plan-card__price">
                                            <div>
                                                <p class="seller-plan-card__price-number">${{ plans.plus.subscription.amount }}</p>
                                                <p class="seller-plan-card__price-description">Monthly subscription</p>
                                            </div>
                                        </div>
                                        <div class="seller-plan-card__price">
                                            <div>
                                                <p class="seller-plan-card__price-number">2.8%</p>
                                                <p class="seller-plan-card__price-description">Credit card processing</p>
                                            </div>
                                        </div>
                                        <div class="seller-plan-card__price mb-0">
                                            <div>
                                                <p class="seller-plan-card__price-number">$0.30</p>
                                                <p class="seller-plan-card__price-description">Transaction fee</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md px-0">
                                    <div class="h-100 seller-plus-modal__content">
                                        <div>
                                            <p class="mb-0"><span class="font-weight-bold">2A Commerce</span> is veteran-owned, second amendment-friendly payment processing built for the firearms industry that gives you the freedom to conduct your business without fear of being shut down.</p>
                                            <div class="my-5">
                                                <a href="https://www.2acommerce.com/" target="_blank" class="btn btn-gray-darker mx-2">Learn more, visit 2acommerce.com</a>
                                            </div>
                                            <div>
                                                <ul class="text-left mb-0">
                                                    <li>Free card reader for in-person transactions</li>
                                                    <li>Multiple POS integration options</li>
                                                    <li>Accept credit card payment on 2agunshow.com and reduce your commission fees</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    name: "Plan",
    data: () => ({
        plans: {},
        selectedPlan: '',
        stepErrors: [],
        isLoading: false
    }),
    mounted() {
        this.getPlans();
    },
    methods: {
        selectPlan(planCode) {
            if (this.plans[planCode]) {
                this.selectedPlan = planCode;
            }
        },
        async getPlans() {
            const response = await this.$http.get(API_ENDPOINTS.sellerOnboarding.getPlans);
            this.plans = (response.data.status === 'success') ? response.data.data.plans : [];
        },
        async submit() {
            if (this.plans[this.selectedPlan]) {
                this.$store.commit('setLoading', true);
                this.isLoading = true;
                const response = await this.$http.post(API_ENDPOINTS.sellerOnboarding.storePlan, {
                    plan: this.selectedPlan,
                });
                this.$store.commit('setLoading', false);
                this.isLoading = false;
                if (response.data.status === 'success') {
                    this.$store.commit('setPlan', this.selectedPlan)
                    this.$store.commit('finishStep', 'plan')
                }
            } else {
                this.stepErrors.push(`Please, select your plan!`);
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