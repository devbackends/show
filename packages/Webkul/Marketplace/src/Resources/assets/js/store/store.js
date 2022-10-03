import Vue from 'vue'
import Vuex from 'vuex'
import checkout from "./checkout";
import sellerOnboarding from './seller-onboarding'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        ...checkout.state,
        ...sellerOnboarding.state,
    },
    getters: {
        ...checkout.getters,
        ...sellerOnboarding.getters,
    },
    mutations: {
        ...checkout.mutations,
        ...sellerOnboarding.mutations,
    },
    actions: {
        ...checkout.actions,
        ...sellerOnboarding.actions,
    },
})

export default store;