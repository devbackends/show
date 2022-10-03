<template>
    <div>
        <div class="row">
            <div class="col-sm-6 form-group control-group"
                 v-bind:class="[errors.has('company_name') ? 'has-error' : '']">
                <label for="company-name">FFL Company name</label>
                <input v-validate="'required'"
                       v-model="ffl.company_name"
                       data-vv-as=" "
                       type="text" name="company_name" id="company-name"
                       class="form-control"
                       placeholder="Company name">
            </div>
            <div class="col-sm-6  form-group control-group"
                 v-bind:class="[errors.has('contact_name') ? 'has-error' : '']">
                <label for="contact-name">FFL Contact name</label>
                <input v-validate="'required'"
                       v-model="ffl.contact_name"
                       data-vv-as=" " type="text" name="contact_name" id="contact-name" class="form-control"
                       placeholder="Contact name">
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-sm-4 mt-3">
                <div class="control-group onboarding-field radio-container"
                     v-bind:class="[errors.has('retail_store') ? 'has-error' : '']">
                    <label
                        class="col-sm-12 legend">Do you have a retail Store Front?</label>
                    <span class="onboadrding-radio-box">
                            <input
                                v-model="ffl.retail_store"
                                data-vv-as=" "
                                v-validate="'required'" type="radio" v-bind:value="true"
                                name="retail_store"
                                class="form-control mailing_address_same">
                            <label class="onboadrding-radio-view"></label>
                        </span>
                    <span class="radio-title text-uppercase">Yes</span>
                    <span class="onboadrding-radio-box">
                                <input data-vv-as=" " v-validate="'required'" type="radio" v-bind:value="false"
                                       v-model="ffl.retail_store"
                                       name="retail_store"
                                       class="form-control mailing_address_same">
                                <label class="onboadrding-radio-view"></label>
                            </span>
                    <span class="radio-title text-uppercase">No</span>
                </div>
                <label class="onboarding-helping-text d-none"></label>
            </div>
            <div class="col-sm-4 mt-3">
                <div class="control-group onboarding-field radio-container"
                     v-bind:class="[errors.has('importer_exporter') ? 'has-error' : '']">
                    <label
                        class="col-sm-12 legend">Are you an importer / exporter?</label>
                    <span class="onboadrding-radio-box">
                            <input
                                v-model="ffl.importer_exporter"
                                data-vv-as=" "
                                v-validate="'required'" type="radio" v-bind:value="true"
                                name="importer_exporter"
                                class="form-control"
                            >
                            <label class="onboadrding-radio-view"></label>
                        </span>
                    <span class="radio-title text-uppercase">Yes</span>
                    <span class="onboadrding-radio-box">
                            <input data-vv-as=" " v-validate="'required'" type="radio"
                                   v-bind:value="false"
                                   v-model="ffl.importer_exporter"
                                   name="importer_exporter"
                                   class="form-control"
                            >
                            <label class="onboadrding-radio-view"></label>
                        </span>
                    <span class="radio-title text-uppercase">No</span>
                </div>
                <label class="onboarding-helping-text d-none"></label>
            </div>
        </div>
        <div class="row d-flex flex-row justify-content-end">
            <div class="col-sm-3">
                <button :disabled="!formIsFilled" v-on:click="onSubmitHandler" class="btn btn-primary ml-auto">Save and
                    continue
                </button>
            </div>
        </div>
    </div>
</template>

<script>export default {
    name: "Company",
    props: {
        fflSaved: {
            type: Object
        },
        website: String,
    },
    data: function () {
        return {
            ffl: {
                company_name: null,
                contact_name: null,
                retail_store: null,
                importer_exporter: null,
                website: null,
                website_link: null,
            },
            formIsFilled: false,
        };
    },
    watch: {
        ffl: {
            handler() {
                let allFieldsSet = true;
                for (let field in this.ffl) {
                    if (!this.ffl[field] && this.ffl[field] !== false) allFieldsSet = false;
                }
                this.formIsFilled = allFieldsSet;
            },
            deep: true
        },
    },
    mounted() {
        this.ffl.website = true;
        this.ffl.website_link = this.website;
        if (this.fflSaved) {
            this.ffl = {
                company_name: this.fflSaved.business_info.company_name,
                contact_name: this.fflSaved.business_info.contact_name,
                retail_store: this.fflSaved.business_info.retail_store !== 0,
                importer_exporter: this.fflSaved.business_info.importer_exporter !== 0,
                website: true,
                website_link: this.fflSaved.business_info.website,
            };
            this.formIsFilled = true;
            this.$emit('changeNextStep', {step: "address"});
            this.$emit('changeFfl', this.ffl);
        }
    },
    methods: {
        onSubmitHandler: async function () {
            const isValid = await this.validate(this.ffl);
            if (isValid) {
                this.$emit('changeNextStep', {step: "address"});
                this.$emit('changeFfl', this.ffl);
            }
        },
        validate: async function (data) {
            const fields = Object.keys(data).filter(field => !['website', 'website_link'].includes(field));
            const promises = Promise.all(fields.map(field => this.$validator.validate(field)));
            return (await promises).every(isValid => isValid);
        }
    }
}
</script>

<style scoped>

</style>