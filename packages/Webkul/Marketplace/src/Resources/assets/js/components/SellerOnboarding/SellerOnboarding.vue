<template>
    <div :class="`container py-5 seller-signup ${$store.getters.getLoadingStatus ? '' : ''}`">
        <!-- Steps -->
        <Steps/>
        <!-- END Steps -->

        <!-- Shop Information -->
        <ShopInformation
            v-show="$store.getters.getSellerOnboardingSteps.shopInformation === 'on'"/>
        <!-- END Shop Information -->

        <!-- Shipping Services -->
        <ShippingServices
            v-show="$store.getters.getSellerOnboardingSteps.shippingServices === 'on'"/>
        <!-- END Shipping Services -->

        <!-- Select your plan -->
        <Plan
            v-show="$store.getters.getSellerOnboardingSteps.plan === 'on'"/>
        <!-- END Select your plan -->

        <!-- Payments -->
        <Payments
            v-show="$store.getters.getSellerOnboardingSteps.payments === 'on'"/>
        <!-- Payments END -->

        <!-- Success message -->
        <Processing
            v-show="$store.getters.getSellerOnboardingSteps.processing === 'on'"/>
        <!-- END Success message -->
    </div>
</template>

<script>
import Steps from "./Steps";
import ShopInformation from "./Steps/ShopInformation";
import ShippingServices from "./Steps/ShippingServices";
import Plan from "./Steps/Plan";
import Payments from "./Steps/Payments";
import Processing from "./Steps/Processing";

export default {
    name: "SellerOnboarding",
    props: ['customer', 'submitUrl'],
    components: {Processing, Payments, Plan, ShippingServices, ShopInformation, Steps},
    mounted() {
        this.$store.commit('setCustomer', this.customer);
        this.$store.commit('setSubmitUrl', this.submitUrl);
    }
}
</script>

<style scoped>
    .loading {
        pointer-events: none;
        opacity: 0.5;
    }
</style>