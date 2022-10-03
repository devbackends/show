import './bootstrap';
import FflForm from "./components/Form/FflForm";

Vue.config.devtools = true;

window.axios = require('axios').create({
    baseURL: location.protocol + "//" + window.location.hostname + ((location.port != '') ? `:${location.port}` : '')
});
Vue.prototype.$http = axios;

Vue.component('FflForm', FflForm);
