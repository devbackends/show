import {API_ENDPOINTS, FIREARM_FAMILY} from "../constant";
let axios = require('axios').create({
    baseURL: location.protocol + "//" + window.location.hostname + ((location.port != '') ? `:${location.port}` : '')
});

export default {
    state: {
        cart: {},
        customer: {},
        seller: {},
        customerAddresses: [],
        states: {},
        options: {
            ffl: {
                is: false,
                items: [],
            },
            withoutShipping: {
                is: false,
                items: [],
                only: false,
            },
            other: {
                is: false,
                items: [],
            },
            steps: {
                billingAddress: false,
                shipping: false,
                payment: false,
                confirmation: false,
                confirmed: false,
            }
        },
        shipToBillingAddress: false,
    },
    getters: {
        getCart: state => state.cart,
        getCartOptions: state => state.options,
        getStates: state => state.states,
        getCustomer: state => state.customer,
        getSeller: state => state.seller,
        getCustomerAddresses: state => state.customerAddresses,
        getShipToBillingAddress: state => state.shipToBillingAddress,
    },
    mutations: {
        setCart(state, data) {
            state.cart = data;
        },
        setCartOptions(state) {

            // Set items
            let fflItems = [], otherItems = [], withoutShippingItems = [];
            state.cart.items.forEach((item) => {
                if (item.product.attribute_family.code.toLowerCase() === FIREARM_FAMILY) {
                    fflItems.push(item);
                } else if (item.product.type === 'virtual' || item.product.type === 'booking') {
                    withoutShippingItems.push(item);
                } else {
                    otherItems.push(item);
                }
            });
            if (fflItems.length > 0) {
                state.options.ffl.is = true;
                state.options.ffl.items = fflItems;
            }
            if (otherItems.length > 0) {
                state.options.other.is = true;
                state.options.other.items = otherItems;
            }
            if (withoutShippingItems.length > 0) {
                state.options.withoutShipping.is = true;
                state.options.withoutShipping.items = withoutShippingItems;
                if (!state.options.other.is && !state.options.ffl.is) {
                    state.options.withoutShipping.only = true;
                }
            }

            // Set steps
            if (state.cart.billing_address) {
                state.options.steps.billingAddress = true;
            }
            if (state.options.ffl.is && state.options.other.is) {
                state.options.steps.shippingAddress = (state.cart.shipping_address && state.cart.ffl_address);
            } else if (state.options.ffl.is && state.cart.ffl_address) {
                state.options.steps.shippingAddress = true;
            } else if (state.options.other.is && state.cart.shipping_address) {
                state.options.steps.shippingAddress = true;
            }
            if (state.cart.billing_address && state.options.withoutShipping.only) {
                state.options.steps.shippingAddress = false;
                state.options.steps.payment = true;
            }

        },
        setStepToActive(state, step) {
            state.options.steps[step] = true;
        },
        setStepToInactive(state, step) {
            state.options.steps[step] = false;
        },
        setCustomer(state, data) {
            state.customer = data;
        },
        setSeller(state, data) {
            state.seller = data;
        },
        setCustomerAddresses(state, data) {
            state.customerAddresses = data;
        },
        setCustomerEmail(state, data) {
            state.customer.email = data;
        },
        setStates(state, data) {
            state.states = data;
        },
        setShipToBillingAddress(state, data) {
            state.shipToBillingAddress = data;
        },
        setBillingAddress(value){
            this.state.options.steps.billingAddress= true;
        }
    },
    actions: {
        async getCustomerAddresses(context) {
            let res = await axios.get(API_ENDPOINTS.getAddresses).catch(err => {
                console.log(err);
            });
            if (!res) return false;
            context.commit('setCustomerAddresses', res.data.data);
            return res.data.data;
        },
        async getStates(context) {
            let res = await axios.get(API_ENDPOINTS.getStates).catch(err => {
                console.log(err);
            });
            if (!res) return false;
            context.commit('setStates', res.data.data.US);
            return res.data.data.US;
        }
    },
}