import Vue from 'vue'
import VeeValidate from 'vee-validate';
import {Validator} from 'vee-validate';

import './bootstrap';
import * as rule from './rules/license'
import geocoding from "../../../../../../Webkul/Velocity/src/Resources/assets/js/utility/geocoding";

Validator.extend('licenseRegion', rule.licenseRegion);
Validator.extend('licenseType', rule.licenseType);
Validator.extend('licenseExpire', rule.licenseExpire);

window.Vue = Vue;
window.VeeValidate = VeeValidate;

Vue.use(VeeValidate, {
    events: 'blur',
});
Vue.prototype.$http = axios

window.eventBus = new Vue();

$(document).ready(function () {
    new Vue({
        el: "#ffl-root",
        data() {
            return {
                addressFields: {
                    city: null,
                    street: null,
                    zipCode: null,
                    country: "US",
                },
                api_url: null,
                currentStep: 1,
                http_error: null,
                license_image: null,
                form: {
                    //First screen
                    company_name: null,
                    contact_name: null,
                    retail_store: null,
                    importer_exporter: null,
                    website: null,
                    website_link: null,
                    social: null,
                    social_link: null,
                    social_link1: null,
                    social_link2: null,
                    social_link3: null,
                    street_address: null,
                    city: null,
                    mailing_state: null,
                    zip_code: null,
                    phone: null,
                    email: null,
                    business_hours: null,
                    //Second screen
                    license_number_parts: {
                        first: null,
                        second: null,
                        third: null,
                        fourth: null,
                        fifth: null,
                        sixth: null
                    },
                    license_name: null,
                    license_number: null,
                    license_image: {
                        file: null,
                        name: null,
                    },
                    //Third screen
                    long_gun: null,
                    long_gun_description: null,
                    hand_gun: null,
                    hand_gun_description: null,
                    nics: null,
                    nics_description: null,
                    other: null,
                    other_description: null,
                    payment: null,
                    comments: null,
                    position: {
                        lng: null,
                        lat: null,
                    }
                },
                mapStepToFields: [
                    {
                        step: 1, fields: [
                            "company_name",
                            "contact_name",
                            "retail_store",
                            "importer_exporter",
                            "website",
                            "street_address",
                            "city",
                            "mailing_state",
                            "zip_code",
                            "phone",
                            "email",
                            "business_hours",
                            'website_link',
                            'social_link',
                        ]
                    },
                    {
                        step: 2, fields: [
                            'license_image',
                        ], custom: [
                            'license_number_first',
                            'license_number_second',
                            'license_number_third',
                            'license_number_fourth',
                            'license_number_fifth',
                            'license_number_sixth',
                        ]
                    },
                    {
                        step: 3, fields: [
                            'long_gun',
                            'hand_gun',
                            'nics',
                            'retail_store',
                            'payment',
                        ]
                    }

                ],
            };
        },
        methods: {
            onNextStep: async function (event, isCustom = false) {
                event.preventDefault();
                let isValid;
                if (isCustom) {
                    isValid = await this.validateScreenWithCustoms();
                } else {
                    isValid = await this.validateScreenInputs();
                }
                if (this.currentStep < 3 && isValid) {
                    this.currentStep += 1
                }
            },
            onPrevStep: function (event) {
                event.preventDefault();
                if (this.currentStep > 1) {
                    this.currentStep -= 1
                }
            },
            validateScreenWithCustoms: async function () {
                const [stepToFields] = this.mapStepToFields.filter(stepToFields => this.currentStep === stepToFields.step)
                const promisesCustom = Promise.all(stepToFields.custom.map(field => this.$validator.validate(field)));
                const promisesDefault = Promise.all(stepToFields.fields.map(field => {
                    if(field === 'license_image' && this.form.license_image.file){
                        return true;
                    }
                    return this.$validator.validate(field);
                }));
                return (await promisesDefault).every(isValid => isValid) && (await promisesCustom).every(isValid => isValid);
            },
            validateScreenInputs: async function () {
                const [stepToFields] = this.mapStepToFields.filter(stepToFields => this.currentStep === stepToFields.step)
                const promises = Promise.all(stepToFields.fields.map(field => this.$validator.validate(field)));
                return (await promises).every(isValid => isValid);
            },
            onFileChange: async function (event) {
                const input = event.target.files || event.dataTransfer.files;
                if (!input.length) {
                    return;
                }
                const file = input[0];
                const toBase64 = file => new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = error => reject(error);
                });
                const valid = await this.$validator.validate('license_image');
                if (valid) {
                    this.form.license_image.file = (await toBase64(file)).replace(/.*base64,/, "");
                    this.form.license_image.name = file.name;
                }
                else {
                    this.form.license_image.file = null;
                    this.form.license_image.name = null;
                }
            },
            onSubmit: async function (event) {
                event.preventDefault();
                const isValid = await this.validateScreenInputs();
                if (!isValid) {
                    return;
                }
                const data = this.castStringToBool();
                this.$http.post(this.api_url, data).then(() => {
                    localStorage.clear();
                    window.location.href = this.$refs.submit.getAttribute('data-url');
                }).catch(err => {
                    this.http_error = err.response;
                })
            },
            composeLicenseNumber: function () {
                this.form.license_number = Object.values(this.form.license_number_parts).join('-');
            },
            onCancel: function (event) {
                event.preventDefault();
                window.location.href = this.$refs.cancel.getAttribute('data-url');
            },
            castStringToBool: function () {
                const data = {};
                for (let field in this.form) {
                    if (this.form[field] === 'true') {
                        data[field] = true;
                    } else if (this.form[field] === 'false') {
                        data[field] = false;
                    } else {
                        data[field] = this.form[field];
                    }
                }
                return data;
            },
        },
        mounted() {
            this.api_url = this.$refs.form.getAttribute('data-url');
            if (localStorage.getItem('form')) {
                this.form = JSON.parse(localStorage.getItem('form'));
            }
        },
        watch: {
            form: {
                handler() {
                    localStorage.setItem('form', JSON.stringify(this.form));
                    if (this.form.street_address) {
                        this.addressFields.street = this.form.street_address;
                    }
                    if (this.form.city) {
                        this.addressFields.city = this.form.city;
                    }
                    if (this.form.zip_code) {
                        this.addressFields.zipCode = this.form.zip_code;
                    }
                },
                deep: true
            },
            addressFields: {
                handler(addressFields) {
                    for (let field in addressFields) {
                        if (!addressFields[field] || addressFields[field] === '') return
                    }
                    let addr = addressFields.street + ", " + addressFields.city + ", " + addressFields.zipCode + ", " + addressFields.country;
                    geocoding(addr)
                        .then(res => {
                            this.form.position = {
                                lat: res.data.results[0].geometry.location.lat,
                                lng: res.data.results[0].geometry.location.lng,
                            };
                        })
                        .catch(err => {
                            this.form.position = {
                                lat: 0,
                                lng: 0,
                            };
                        })
                },
                deep: true,
            }
        }
    });
    //Tooltip init
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
});
