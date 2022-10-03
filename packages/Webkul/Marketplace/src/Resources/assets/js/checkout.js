import Checkout from './components/checkout/Checkout'
import * as VueGoogleMaps from 'vue2-google-maps';
import {GOOGLE_MAPS_API_KEY} from "./constant";

window.axios = require('axios').create({
    baseURL: location.protocol + "//" + window.location.hostname + ((location.port != '') ? `:${location.port}` : '')
});
Vue.config.devtools = true;
Vue.prototype.$http = axios;
Vue.use(VueGoogleMaps, {
    load: {
        key: GOOGLE_MAPS_API_KEY,
        libraries: ''
    }
});
Vue.component('Checkout', Checkout);