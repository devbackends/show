import Vue from 'vue';
import VueCarousel from 'vue-carousel';
import 'vue-toast-notification/dist/index.css';
import VueToast from 'vue-toast-notification';
import accounting from 'accounting';
import messagesAr from 'vee-validate/dist/locale/ar';
import store from './store/store';
import Select2MultipleControl from 'v-select2-multiple-component';
import Select2 from 'v-select2-component';
// Main JS (in UMD format)
import VueTimepicker from 'vue2-timepicker'
// CSS
import 'vue2-timepicker/dist/VueTimepicker.css'
// Image Zoom
import 'vue-inner-image-zoom/lib/vue-inner-image-zoom.css';
import InnerImageZoom from 'vue-inner-image-zoom';

import Header from "./components/Datagrid/Header/Header";
// Datagrid
Vue.component('DatagridHeader', Header);


window.axios = require("axios");
window.VeeValidate = require("vee-validate");
window.jQuery = window.$ = require("jquery");
window.eventBus = new Vue();

Vue.use(VueToast);
Vue.use(VeeValidate);
Vue.use(VueCarousel);
Vue.prototype.$http = axios;

Vue.directive("debounce", require("./directives/debounce"));

Vue.filter('currency', function (value, argument) {
    return accounting.formatMoney(value, argument);
})

Vue.use(VeeValidate, {
    dictionary: {
        ar: { messages: messagesAr }
    }
});

window.Vue = Vue;
window.Carousel = VueCarousel;

Vue.config.devtools = true;

Vue.component("vue-slider", require("vue-slider-component"));
Vue.component('mini-cart-btn', require('./components/mini-cart-btn'));
Vue.component('loading-spinner', require('./components/loading-spinner'));
Vue.component('mini-cart', require('./components/mini-cart'));
Vue.component('mobile-mini-cart', require('./components/mobile-mini-cart'));
Vue.component('product-card', require('./components/product-card'));
Vue.component('course-card', require('./components/course-card'));
Vue.component('category-card', require('./components/category-card'));
Vue.component('wishlist-component', require('./components/wishlist-component'));
Vue.component('compare-component', require('./components/product-compare'));
Vue.component('add-to-cart', require('./components/add-to-cart'));
Vue.component('carousel-component', require('./components/carousel'));
Vue.component('star-rating', require('./components/star-rating'));
Vue.component('sidebar-component', require('./components/sidebar'));
Vue.component('categories-menu-component', require('./components/categories-menu'));
Vue.component('magnify-image', require('./components/image-magnifier'));
Vue.component('shimmer-component', require('./components/shimmer-component'));
Vue.component('submit-button', require('./components/submit-button'));
Vue.component('shimmer-teaser-component', require('./components/shimmer-teaser-component'));
Vue.component('shimmer-radiobutton-component', require('./components/shimmer-radiobutton-component'));
Vue.component('product-quick-view', require('./UI/components/product-quick-view'));
Vue.component('product-quick-view-btn', require('./UI/components/product-quick-view-btn'));
Vue.component('image-wrapper', require('./components/image/image-wrapper'));
Vue.component('image-item', require('./components/image/image-item'));
Vue.component('image-uploader', require('./components/image/image-uploader'));
Vue.component('image-container', require('./components/image/image-container'));
Vue.component('show-wrapper-index', require('./components/show/show-wrapper-index'));
Vue.component('show-wrapper', require('./components/show/show-wrapper'));
Vue.component('instructor-wrapper', require('./components/instructors/instructor-wrapper'));
Vue.component('club-wrapper', require('./components/clubs/club-wrapper'));
Vue.component('club-item', require('./components/clubs/club-item'));
Vue.component('ffl-wrapper', require('./components/ffls/ffl-wrapper'));
Vue.component('ffl-item', require('./components/ffls/ffl-item'));
Vue.component('instructor-item', require('./components/instructors/instructor-item'));
Vue.component('gun-ranges-wrapper', require('./components/gun-ranges/gun-ranges-wrapper'));
Vue.component('gun-range-item', require('./components/gun-ranges/gun-range-item'));
Vue.component('show-item', require('./components/show/show-item'));
Vue.component('tree-view', require('./components/tree-view/tree-view'));
Vue.component('tree-item', require('./components/tree-view/tree-item'));
Vue.component('tree-checkbox', require('./components/tree-view/tree-checkbox'));
Vue.component('tree-radio', require('./components/tree-view/tree-radio'));
Vue.component('BuyAll', require('./components/buyall/BuyAll'));
Vue.component('buyallnavcategories', require('./components/buyall/BuyAllNavCategories'));
Vue.component('SellerOnboarding', require('./components/SellerOnboarding/SellerOnboarding'));
Vue.component('CartsWrapper', require('./components/cart/CartsWrapper'));
Vue.component('Coupon', require('./components/coupon/coupon'));
Vue.component('seller-products', require('./components/seller-products'));
Vue.component('product-gallery', require('./components/product-gallery'));
Vue.component('contact-form', require('./components/contact-form'));
Vue.component('site-search', require('./components/site-search'));
Vue.component('accordian', require('./components/accordian'));

Vue.directive('slugify', require('./directives/slugify'))
Vue.directive('slugify-target', require('./directives/slugify-target'))

Vue.component('date', require('./components/date'))
Vue.component('datetime', require('./components/datetime'))
Vue.component('timepicker', require('./components/timepicker'))
Vue.component('time-component', require('./components/time'))
Vue.component('featured-products-t', require('./components/featured-products-t'))
Vue.component('new-products-t', require('./components/new-products-t'))

Vue.component('product-steps-menu', require('./components/create-product/steps-menu'));
Vue.component('product-info-wrapper', require('./components/create-product/product-info-wrapper'));
Vue.component('product-form-general-info', require('./components/create-product/form-general-info'));
Vue.component('product-form-side', require('./components/create-product/form-side'));
Vue.component('product-form-media', require('./components/create-product/form-media'));
Vue.component('product-form-pricing', require('./components/create-product/form-pricing'));
Vue.component('product-form-shipping', require('./components/create-product/form-shipping'));
Vue.component('dynamic-input', require('./components/dynamic-input'));
Vue.component('Select2MultipleControl', Select2MultipleControl);
Vue.component('Select2', Select2);
Vue.component('vue-timepicker', VueTimepicker);
Vue.component('inner-image-zoom', InnerImageZoom);
Vue.component('count-down', require('./components/count-down'));

// for compilation of html coming from server
Vue.component('vnode-injector', {
    functional: true,
    props: ['nodes'],
    render(h, {props}) {
        return props.nodes;
    }
});
require('flatpickr/dist/flatpickr.css');
$(document).ready(function () {

    Vue.mixin({

        data: function () {
            return {
                'baseUrl': document.querySelector("script[src$='marketplace.js']").getAttribute('baseUrl'),
                'navContainer': false,
                'headerItemsCount': 0,
                'headerWishlistCount': 0,
                'headerCompareCount': 0,
                'responsiveSidebarTemplate': '',
                'responsiveSidebarKey': Math.random(),
                'sharedRootCategories': [],
                'productCartQuantity': 0,
                'imageObserver': null,
            }
        },
        mounted(){

        },
        methods: {
            redirect: function (route) {
                route ? window.location.href = route : '';
            },

            debounceToggleSidebar: function (id, {target}, type) {
                // setTimeout(() => {
                this.toggleSidebar(id, target, type);
                // }, 500);
            },


            toggleSidebar: function (id, {target}, type) {
                if (
                    Array.from(target.classList)[0] == "main-category"
                    || Array.from(target.parentElement.classList)[0] == "main-category"
                ) {
                    let sidebar = $(`#sidebar-level-${id}`);
                    if (sidebar && sidebar.length > 0) {
                        if (type == "mouseover") {
                            this.show(sidebar);
                        } else if (type == "mouseout") {
                            this.hide(sidebar);
                        }
                    }
                } else if (
                    Array.from(target.classList)[0]     == "category"
                    || Array.from(target.classList)[0]  == "category-icon"
                    || Array.from(target.classList)[0]  == "category-title"
                    || Array.from(target.classList)[0]  == "category-content"
                    || Array.from(target.classList)[0]  == "rango-arrow-right"
                ) {
                    let parentItem = target.closest('li');

                    if (target.id || parentItem.id.match('category-')) {
                        let subCategories = $(`#${target.id ? target.id : parentItem.id} .sub-categories`);

                        if (subCategories && subCategories.length > 0) {
                            let subCategories1 = Array.from(subCategories)[0];
                            subCategories1 = $(subCategories1);

                            if (type == "mouseover") {
                                this.show(subCategories1);

                                let sidebarChild = subCategories1.find('.sidebar');
                                this.show(sidebarChild);
                            } else if (type == "mouseout") {
                                this.hide(subCategories1);
                            }
                        } else {
                            if (type == "mouseout") {
                                let sidebar = $(`#${id}`);
                                sidebar.hide();
                            }
                        }
                    }
                }
            },

            show: function (element) {
                element.show();
                document.querySelector('#shop-by-category-button').classList.add('sidebar__open-button');
                element.mouseleave(({target}) => {
                    $(target.closest('.sidebar')).hide();
                });
            },

            hide: function (element) {
                element.hide();
                document.querySelector('#shop-by-category-button').classList.remove('sidebar__open-button');
            },

            toggleButtonDisability ({event, actionType}) {
                let button = event.target.querySelector('button[type=submit]');

                button ? button.disabled = actionType : '';
            },

            onSubmit: function (event) {

                event.target.preventDefault;
                this.toggleButtonDisability({event, actionType: true});
                if(typeof this.product!= 'undefined'){
                    if(!$('#formated_categories').val()){
                        window.showAlert('alert-danger', 'Error', 'At least one category is required');
                        $('#categories_box').css('border-color','#FB7C88')
                        eventBus.$emit('onFormError');
                        return
                    }else{
                        $('#categories_box').css('border-color','#DDD');
                    }
                }

                if(!this.errors.has('url_key')){
                    this.$validator.validateAll().then(result => {
                        if (result) {
                            if(this.shipping_type =="auto_calculated" && this.product.type != 'booking'){
                                let error=false;
                                if(document.getElementById('width').value < 2){
                                    this.errors.add({
                                        field: 'width',
                                        msg: 'Width should be greater than 1'
                                    });
                                    error=true;
                                }else{
                                    this.errors.remove('width');
                                }
                                if(document.getElementById('height').value  < 2){
                                    this.errors.add({
                                        field: 'height',
                                        msg: 'Height should be greater than 1'
                                    });
                                    error=true;
                                }else{
                                    this.errors.remove('height');
                                }
                                if(document.getElementById('depth').value  < 2){
                                    this.errors.add({
                                        field: 'depth',
                                        msg: 'Length should be greater than 1'
                                    });
                                    error=true;
                                }else{
                                    this.errors.remove('depth');
                                }
                                if(error){
                                    eventBus.$emit('onFormError');
                                    return
                                }
                            }
                            
                            eventBus.$emit('onEditProductFormSubmit',true);
                            event.target.submit();
                        } else {
                            this.toggleButtonDisability({event, actionType: false});
                            eventBus.$emit('onFormError')
                            const el = document.querySelector(".has-error:first-of-type");
                            if(el){
                                el.scrollIntoView();
                                return;
                            }
                        }
                    });
                }

            },

            isMobile: function () {
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true
                } else {
                    return false
                }
            },

            getDynamicHTML: function (input) {
                var _staticRenderFns;
                const { render, staticRenderFns } = Vue.compile(input);

                if (this.$options.staticRenderFns.length > 0) {
                    _staticRenderFns = this.$options.staticRenderFns;
                } else {
                    _staticRenderFns = this.$options.staticRenderFns = staticRenderFns;
                }

                try {
                    var output = render.call(this, this.$createElement);
                } catch (exception) {
                    console.log(this.__('error.something_went_wrong'));
                }

                this.$options.staticRenderFns = _staticRenderFns;

                return output;
            },

            getStorageValue: function (key) {
                let value = window.localStorage.getItem(key);

                if (value) {
                    value = JSON.parse(value);
                }

                return value;
            },

            setStorageValue: function (key, value) {
                window.localStorage.setItem(key, JSON.stringify(value));

                return true;
            },
        }
    });

    new Vue({
        el: "#app",
        store: store,

        data: function () {
            return {
                modalIds: {},
                miniCartKey: 0,
                quickView: false,
                productDetails: [],
            }
        },

        created: function () {
            window.addEventListener('click', () => {
                let modals = document.getElementsByClassName('sensitive-modal');

                Array.from(modals).forEach(modal => {
                    modal.classList.add('hide');
                });
            });
        },

        mounted: function () {
            setTimeout(() => {
                this.addServerErrors();
            }, 0);

            document.body.style.display = "block";
            this.$validator.localize(document.documentElement.lang);

            if (location.pathname !== '/checkout/onepage') {
                this.loadCategories();
            }
            this.addIntersectionObserver();
        },

        methods: {
            onSubmit: function (event) {
                console.log('submitting 1');
                if (location.pathname === '/customer/register') { // captcha
                    if ($('#honeypot').val() !== '') {
                        return false;
                    }
                }
                this.toggleButtonDisability({event, actionType: true});

          /*      if(typeof tinyMCE !== 'undefined')
                    tinyMCE.triggerSave();*/

                this.$validator.validateAll().then(result => {
                    if (result) {
                        event.target.submit();
                    } else {
                        this.toggleButtonDisability({event, actionType: false});

                        eventBus.$emit('onFormError')
                    }
                });
            },

            toggleButtonDisable (value) {
                var buttons = document.getElementsByTagName("button");

                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = value;
                }
            },

            addServerErrors: function (scope = null) {
                for (var key in serverErrors) {
                    var inputNames = [];
                    key.split('.').forEach(function(chunk, index) {
                        if(index) {
                            inputNames.push('[' + chunk + ']')
                        } else {
                            inputNames.push(chunk)
                        }
                    })

                    var inputName = inputNames.join('');

                    const field = this.$validator.fields.find({
                        name: inputName,
                        scope: scope
                    });

                    if (field) {
                        this.$validator.errors.add({
                            id: field.id,
                            field: inputName,
                            msg: serverErrors[key][0],
                            scope: scope
                        });
                    }
                }
            },

            addFlashMessages: function () {
                // const flashes = this.$refs.flashes;

                // flashMessages.forEach(function (flash) {
                //     flashes.addFlash(flash);
                // }, this);

                if (window.flashMessages.alertMessage)
                    window.alert(window.flashMessages.alertMessage);
            },

            showModal: function (id) {
                this.$set(this.modalIds, id, true);
            },

            loadCategories: function () {
                this.$http.get(`${this.baseUrl}/categories`)
                    .then(response => {
                        this.sharedRootCategories = response.data.categories;
                        $(`<style type='text/css'> .sub-categories{ min-height:${response.data.categories.length * 30}px;} </style>`).appendTo("head");
                    })
                    .catch(error => {
                        console.log('failed to load categories');
                    })
            },

            addIntersectionObserver: function () {
                this.imageObserver = new IntersectionObserver((entries, imgObserver) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            const lazyImage = entry.target
                            lazyImage.src = lazyImage.dataset.src
                        }
                    })
                });
            },
        }
    });

});

