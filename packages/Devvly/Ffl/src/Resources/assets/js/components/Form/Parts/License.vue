<template>
    <div>
        <div class="row mt-5">
            <div class="col-sm-12 form-group d-flex flex-wrap">
                <label class="w-100">What is your license number?</label>
                <input v-model="ffl.license_number" type="hidden">
                <input name="license_number_first"
                       v-bind:class="[errors.has('license_number_first') ? 'has-error' : '']"
                       v-model="ffl.license_number_parts.first" v-validate="'required|min:1|max:1|licenseRegion'"
                       maxlength="1" placeholder="X" type="text" class="form-control col-sm-1 dash-after text-center"/>
                <span class="input-delimiter"> - </span>
                <input name="license_number_second"
                       v-bind:class="[errors.has('license_number_second') ? 'has-error' : '']"
                       v-model="ffl.license_number_parts.second" v-validate="'required|min:2|max:2'" maxlength="2"
                       placeholder="XX" type="text" class="form-control col-sm-1 dash-after text-center"/>
                <span class="input-delimiter"> - </span>
                <input name="license_number_third"
                       v-bind:class="[errors.has('license_number_third') ? 'has-error' : '']"
                       v-model="ffl.license_number_parts.third" v-validate="'required|min:3|max:3'" maxlength="3"
                       placeholder="XXX" type="text" class="form-control col-sm-1 dash-after text-center"/>
                <span class="input-delimiter"> - </span>
                <input name="license_number_fourth"
                       v-bind:class="[errors.has('license_number_fourth') ? 'has-error' : '']"
                       v-model="ffl.license_number_parts.fourth" v-validate="'required|min:2|max:2|licenseType'"
                       maxlength="2" placeholder="XX" type="text" class="form-control col-sm-1 dash-after text-center"/>
                <span class="input-delimiter"> - </span>
                <input name="license_number_fifth"
                       v-bind:class="[errors.has('license_number_fifth') ? 'has-error' : '']"
                       v-model="ffl.license_number_parts.fifth" v-validate="'required|min:2|max:2|licenseExpire'"
                       maxlength="2" placeholder="XX" type="text" class="form-control col-sm-1 dash-after text-center"/>
                <span class="input-delimiter"> - </span>
                <input name="license_number_sixth"
                       v-bind:class="[errors.has('license_number_sixth') ? 'has-error' : '']"
                       v-model="ffl.license_number_parts.sixth" v-validate="'required|min:3|max:6'" maxlength="6"
                       placeholder="XXX" type="text" class="form-control col-sm-2 dash-after text-center"/>
            </div>
        </div>
        <div class="col-sm-12 mb-1 mt-3 pl-0">
            <span class="pl-0 col-sm-12 after pr-0">Upload a copy of your FFL license</span>
            <i data-toggle="tooltip" data-placement="top" title=""
               class="form-section__icon fa fa-question-circle d-none d-md-inline-block"
               data-original-title="Upload a copy of your FFL license"></i>
            <p class="form-section__help-text d-block d-md-none">Upload a copy of your FFL license</p>
        </div>
        <div class="col-sm-8 upload-container control-group"
             v-bind:class="[errors.has('license_image ') ? 'has-error' : '']">
            <input data-vv-as=" "
                   v-on:change="onFileChange"
                   v-validate="'required|ext:png,jpg,gif,pdf'" class="custom_upload mt-5"
                   id="license_image" name="license_image"
                   type="file"/>
            <div><span class="icon license-icon mt-4"></span></div>
            <div>
                <template v-if="ffl.license_image.name">
                    <span class="upload-text">{{ffl.license_image.name}}</span>
                </template>
                <template v-else>
                    <span class="upload-text">Upload a copy of your FFL license</span>
                </template>
            </div>
            <div class="onboard-upload-button-container p-3"><span class="onboarding-upload-button"><span
                class="onboarding-upload-button-text">Upload a copy of your FFL license</span></span>
            </div>
            <span>Or drag it here</span>
            <div class="control-error" v-if="errors.has('license_image')">{{ errors.first('license_image')}}</div>
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

<script>
    export default {
        name: "License",
        props: {
            fflSaved: {
                type: Object
            }
        },
        data() {
            return {
                ffl: {
                    license_number_parts: {
                        first: null,
                        second: null,
                        third: null,
                        fourth: null,
                        fifth: null,
                        sixth: null
                    },
                    license_image: {
                        file: null,
                        name: null,
                    },
                },
                license_number: null,
                formIsFilled: false,
            }
        },
        watch: {
            ffl: {
                handler() {
                    let allFieldsSet = true;
                    for (let field in this.ffl.license_number_parts) {
                        if (!this.ffl.license_number_parts[field]) allFieldsSet = false;
                    }
                    if (!this.ffl.license_image.name && !this.ffl.license_image.file) allFieldsSet = false;
                    this.formIsFilled = allFieldsSet;
                },
                deep: true
            },
        },
        methods: {
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
                this.ffl.license_image.file = (await toBase64(file)).replace(/.*base64,/, "");
                this.ffl.license_image.name = file.name;
            },
            onSubmitHandler: async function () {
                const isValid = await this.validate(this.ffl.license_number_parts);
                if (isValid) {
                    this.license_number = Object.values(this.ffl.license_number_parts).join('-');
                    this.$emit('changeNextStep', {step: "transfer_fees"});
                    this.$emit('changeFfl', {...this.ffl, license_number: this.license_number});
                }
            },
            validate: async function (data) {
                const fields = Object.keys(data).map(el => "license_number_" + el);
                const promises = Promise.all(fields.map(field => this.$validator.validate(field)));
                return (await promises).every(isValid => isValid);
            },
        },
        mounted() {
            if (this.fflSaved) {
                this.ffl = {
                    license_number_parts: {
                        first: this.fflSaved.license.license_region,
                        second: this.fflSaved.license.license_district,
                        third: this.fflSaved.license.license_fips,
                        fourth: this.fflSaved.license.license_type,
                        fifth: this.fflSaved.license.license_expire_date,
                        sixth: this.fflSaved.license.license_sequence
                    },
                    license_image: {
                        file: null,
                        name: this.fflSaved.license.license_file
                    }

                };
                this.formIsFilled = true;
                this.license_number = Object.values(this.ffl.license_number_parts).join('-');
                this.$emit('changeNextStep', {step: "transfer_fees"});
                this.$emit('changeFfl', {...this.ffl, license_number: this.license_number});
            }
        },
    }
</script>

<style scoped>

</style>