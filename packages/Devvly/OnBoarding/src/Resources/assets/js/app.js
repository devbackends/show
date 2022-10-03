
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap.js');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('main-component', require('./components/MainComponent.vue'));
Vue.component('business-information', require('./components/BusinessInformation.vue'));
Vue.component('business-contact', require('./components/BusinessContact.vue'));
Vue.component('merchant-profile', require('./components/MerchantProfile'));
Vue.component('banking', require('./components/Banking'));
Vue.component('pricing', require('./components/Pricing'));
