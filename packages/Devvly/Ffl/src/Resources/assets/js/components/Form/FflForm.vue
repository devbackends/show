<template>
    <div id="ffl" class="content container-fluid fflonboarding">
        <div class="row ml-3">
            <div class="form-check col-sm-12 mt-3 mb-3">
                <input v-model="fieldsDisabled" class="form-check-input" type="checkbox" value="" id="firearms">
                <label class="form-check-label" for="firearms">
                    I'm not selling firearms
                </label>
            </div>
        </div>
        <hr/>
        <Accordian :class="fieldsDisabled ? 'disabled':''" :disabled="fieldsDisabled"
                   :active="!fieldsDisabled">
            <div slot="header">
                <span class="form-section__title">FFL company</span>
                <!--                <h3 class="form-section__title">What is your FFL Shipping Address?</h3>-->
                <!--                <i data-toggle="tooltip" data-placement="top" title=""-->
                <!--                   class="form-section__icon fa fa-question-circle d-none d-md-inline-block"-->
                <!--                   data-original-title="This is the address people will ship to your FFL"></i>-->
                <!--                <p class="form-section__help-text d-block d-md-none">This is the address people will ship to your-->
                <!--                    FFL</p>-->
            </div>
            <Company :website="company_domain" :ffl-saved="ffl" v-on:changeFfl="onChangeFfl"
                     v-on:changeNextStep="onChangeStepHandler"
                     slot="body"/>
        </Accordian>
        <Accordian :disabled="!steps.includes('address')" :title="'Address'" :active="steps.includes('address')"
                   :class="!steps.includes('address') ? 'disabled':'' ">
            <div slot="header">
                <span class="form-section__title">What is your FFL Shipping Address?</span>
                <i data-toggle="tooltip" data-placement="top" title=""
                   class="form-section__icon fa fa-question-circle d-none d-md-inline-block"
                   data-original-title="This is the address people will ship to your FFL"></i>
                <p class="form-section__help-text d-block d-md-none">This is the address people will ship to your
                    FFL</p>
            </div>
            <Address :ffl-saved="ffl" v-on:changeFfl="onChangeFfl" v-on:changeNextStep="onChangeStepHandler"
                     slot="body"/>
        </Accordian>
        <Accordian :disabled="!steps.includes('contact')" :title="'Contact'" :active="steps.includes('contact')"
                   :class="!steps.includes('contact') ? 'disabled':'' ">
            <div slot="header">
                <span class="form-section__title">What is your contact information used for transfers?</span>
                <i data-toggle="tooltip" data-placement="top" title=""
                   class="form-section__icon fa fa-question-circle d-none d-md-inline-block"
                   data-original-title="This is the email and phone number that people will use to contact for transfers"></i>
                <p class="form-section__help-text d-block d-md-none">This is the email and phone number that people will use
                    to contact for transfers</p>
            </div>
            <Contact :ffl-saved="ffl" v-on:changeFfl="onChangeFfl" v-on:changeNextStep="onChangeStepHandler"
                     slot="body"/>
        </Accordian>
        <Accordian :disabled="!steps.includes('license')" :title="'License'" :active="steps.includes('license')"
                   :class="!steps.includes('license') ? 'disabled':'' ">
            <div slot="header">
                <span class="form-section__title">What is your license number?</span>
            </div>
            <License :ffl-saved="ffl" v-on:changeFfl="onChangeFfl" v-on:changeNextStep="onChangeStepHandler"
                     slot="body"/>
        </Accordian>
        <Accordian :disabled="!steps.includes('transfer_fees')" :title="'Transfer fees'"
                   :active="steps.includes('transfer_fees')"
                   :class="!steps.includes('transfer_fees') ? 'disabled':'' ">
            <div slot="header">
                <span class="form-section__title">What are your transfer fees?</span>
            </div>
            <TransferFees :ffl-saved="ffl" v-on:changeFfl="onChangeFfl" v-on:sendForm="onSendForm" slot="body"/>
        </Accordian>
    </div>
</template>

<script>
    import Accordian
        from "../../../../../../../../Webkul/Velocity/src/Resources/assets/js/components/checkout/Accordian";
    import Company from "./Parts/Company";
    import Address from "./Parts/Address";
    import Contact from "./Parts/Contact";
    import License from "./Parts/License";
    import TransferFees from "./Parts/TransferFees";
    import {API_ENDPOINTS, FFL_STATUS_CONFIG_KEY} from "../../constants";

    export default {
        name: "FflForm",
        components: {
            TransferFees,
            License,
            Contact,
            Address,
            Company,
            Accordian,
        },
        props: {
            api_url: String,
            check_enabled_url: String,
            ffl: Object,
            company_domain: String,
        },
        data() {
            return {
                steps: ['company'],
                http_error: null,
                form: {},
                fieldsDisabled: false,
            };
        },
        methods: {
            onChangeStepHandler: function ({step}) {
                if (!this.steps.includes(step)) {
                    this.steps.push(step);
                }
            },
            onChangeFfl: function (data) {
                this.form = {...this.form, ...data};
            },
            onSendForm: function (data) {
                this.form = {...this.form, ...data};
                this.$http.post(this.api_url, this.form)
                    .then(res => {
                        location.reload();
                    })
                    .catch(err => {
                        console.log(err);
                    })
            }
        },
        computed: {},
        watch: {
            fieldsDisabled: function () {
                this.$http.post(this.check_enabled_url, {
                    status: this.fieldsDisabled
                }).catch(err => {
                    console.log(err);
                    this.fieldsDisabled = !this.fieldsDisabled;
                });
            }
        },
        mounted() {
            this.$http.get(API_ENDPOINTS.coreConfig, {
                params: {
                    '_config': FFL_STATUS_CONFIG_KEY,
                }
            })
                .then(res => {
                    const status = res.data.data.ffl_disabled;
                    if (!status) {
                        this.fieldsDisabled = false;
                    }
                    if (status === "0") {
                        this.fieldsDisabled = false;
                    }
                    if (status === "1") {
                        this.fieldsDisabled = true;
                    }
                })
                .catch(err => {
                    console.log(err);
                })
        }
    }
</script>

<style scoped>

</style>
