export const FIREARM_FAMILY = 'firearm'
export const GOOGLE_MAPS_API_KEY = "AIzaSyDnUKjS6BAjGhL8XL8SacAYmDQMpWEchXs"
export const FFL_PREFERRED_SOURCE = "on_boarding_form"
export const GOOGLE_GEOCODE_API_URL = "https://maps.googleapis.com/maps/api/geocode/json";
export const API_ENDPOINTS = {
    getShippingRates: 'api/checkout/shipping-rates',
    getCart: 'api/checkout/cart',
    getAddresses: "api/addresses",
    getClosestFfls: "api/ffl/find/closest",
    getFflByZip: "api/ffl/find",
    getStates: "api/country-states",
    getCustomerInfo: "api/customer/get",
    postAddress: "api/addresses/create",
    getPaymentMethods: "api/checkout/payment",
    postCheckoutSaveBillingAddress: "api/checkout/save-billing-address",
    postCheckoutShippingAddress: "api/checkout/save-shipping-address",
    postCheckoutSaveShippingRate: "api/checkout/save-shipping",
    postCheckoutSavePaymentMethod: "api/checkout/save-payment",
    postCheckoutSaveFflShippingAddress: "api/checkout/save-ffl-address",
    postCheckoutSaveFflShippingRate: "api/checkout/save-ffl-shipping",
    postCheckoutSaveOrder: "api/checkout/save-order",
    getFflById: "api/ffl/find",
    getConfig: "api/config",
    getCompany: "api/company",
    getCompanyAddress: "api/company-addresses"
};
