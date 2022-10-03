<template>
    <div class="row">
        <div class="steps">
            <div
                :class="`step-item ${getStepClass($store.getters.getSellerOnboardingSteps.shopInformation)}`">
                <a href="#" class="btn btn-link step-item__link"><i class="far fa-store mr-2"></i>Shop Information</a>
            </div>
            <div
                :class="`step-item ${getStepClass($store.getters.getSellerOnboardingSteps.shippingServices)}`">
                <a href="#" class="btn btn-link step-item__link"><i class="far fa-shipping-fast mr-2"></i>Shipping Services</a>
            </div>
            <div
                :class="`step-item ${getStepClass($store.getters.getSellerOnboardingSteps.plan)}`">
                <a href="#" class="btn btn-link step-item__link"><i class="far fa-ballot-check mr-2"></i>Select Your Plan</a>
            </div>
            <div
                :class="`step-item ${getStepClass($store.getters.getSellerOnboardingSteps.payments)}`">
                <a href="#" class="btn btn-link step-item__link"><i class="far fa-credit-card mr-2"></i>Payments</a>
            </div>
            <div
                v-if="$store.getters.getSellerPlan === 'plus'"
                :class="`step-item ${getStepClass($store.getters.getSellerOnboardingSteps.processing)}`">
                <a href="#" class="btn btn-link step-item__link"><i class="far fa-clipboard-list-check mr-2"></i>Processing</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Steps",
    methods: {
        getStepClass(step) {
            if (step === 'finished') {
                return 'step-item--success'
            } else if (step === 'on') {
                return 'step-item--active'
            }
            return ''
        }
    },
}
</script>

<style scoped>

</style>