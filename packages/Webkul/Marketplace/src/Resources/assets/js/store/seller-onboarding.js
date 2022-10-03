export default {
    state: {
        sellerOnboarding: {
            steps: {
                shopInformation: 'on',
                shippingServices: 'intact',
                plan: 'intact',
                payments: 'intact',
                processing: 'intact',
            },
            selectedPlan: '',
            redirectUrl: '',
            loading: false,
        },
    },
    getters: {
        getSellerOnboardingSteps: state => state.sellerOnboarding.steps,
        getSellerPlan: state => state.sellerOnboarding.selectedPlan,
        getRedirectUrl: state => state.sellerOnboarding.redirectUrl,
        getLoadingStatus: state => state.sellerOnboarding.loading,
    },
    mutations: {
        finishStep(state, finishedStep) {
            for (let step in state.sellerOnboarding.steps) {
                if (step === finishedStep) {
                    state.sellerOnboarding.steps[step] = 'finished';
                } else if (state.sellerOnboarding.steps[step] === 'intact') {
                    state.sellerOnboarding.steps[step] = 'on';
                    break;
                }
            }
        },
        revertStep(state, revertedStep) {
            let keys = Object.keys(state.sellerOnboarding.steps).reverse();
            for (let step of keys) {
                if (step === revertedStep) {
                    state.sellerOnboarding.steps[step] = 'intact';
                } else if (state.sellerOnboarding.steps[step] === 'finished') {
                    state.sellerOnboarding.steps[step] = 'on';
                    break;
                }
            }
        },
        setPlan(state, plan) {
            state.sellerOnboarding.selectedPlan = plan;
        },
        setRedirectUrl(state, url) {
            state.sellerOnboarding.redirectUrl = url;
        },
        setLoading(state, status) {
            document.body.style.cursor = status ? 'wait' : 'default';
            state.sellerOnboarding.loading = status;
        },
    },
    actions: {},
}