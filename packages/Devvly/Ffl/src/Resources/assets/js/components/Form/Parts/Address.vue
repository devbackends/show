<template>
    <div>
        <div class="row">
            <div class="col-sm-12 form-group control-group"
                 v-bind:class="[errors.has('street_address') ? 'has-error' : '']">
                <label for="street-address">Street address</label>
                <input v-model="ffl.street_address" data-vv-as=" " v-validate="'required'" type="text"
                       name="street_address"
                       id="street-address"
                       class="form-control"
                       placeholder="Street address">
            </div>
            <div class="col-sm-4 mt-3 control-group" v-bind:class="[errors.has('city') ? 'has-error' : '']">
                <label for="city">City</label>
                <input v-model="ffl.city" data-vv-as=" " v-validate="'required'" type="text" name="city" id="city"
                       class="form-control"
                       placeholder="City">
            </div>

            <div class="col-sm-4 mt-3 control-group" v-bind:class="[errors.has('mailing_state') ? 'has-error' : '']">
                <label for="mailing-state">State</label>
                <label class="select_label">
                    <select v-model="ffl.mailing_state" v-validate="'required'" data-vv-as=" " id="mailing-state"
                            name="mailing_state"
                            class="form-control customSelect">
                        <option v-for="state in states" :key="state.id" v-bind:value="state.id">{{state.default_name}}
                        </option>
                    </select>
                </label>
            </div>
            <div class="col-sm-4 mt-3 control-group" v-bind:class="[errors.has('zip_code') ? 'has-error' : '']">
                <label for="zip-code">Postal / Zip code</label>
                <input v-model="ffl.zip_code" data-vv-as=" " v-validate="'required|numeric'" type="text" name="zip_code"
                       id="zip-code"
                       class="form-control"
                       placeholder="Postal / Zip code">
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

<script>
    import {API_ENDPOINTS} from "../../../constants";
    import geocoding from "../../../../../../../../../Webkul/Velocity/src/Resources/assets/js/utility/geocoding";

    export default {
        name: "Address",
        props: {
            fflSaved: {
                type: Object
            }
        },
        data() {
            return {
                ffl: {
                    street_address: null,
                    city: null,
                    mailing_state: null,
                    zip_code: null,

                },
                states: [],
                formIsFilled: false,
                position: {
                    lng: null,
                    lat: null,
                }
            }
        },
        watch: {
            ffl: {
                handler() {
                    let allFieldsSet = true;
                    for (let field in this.ffl) {
                        if (!this.ffl[field]) allFieldsSet = false;
                    }
                    this.formIsFilled = allFieldsSet;
                },
                deep: true
            },
        },
        mounted() {
            this.$http.get(API_ENDPOINTS.getStates)
                .then(res => {
                    this.states = res.data.data.US;
                }).catch(err => {
                console.log(err);
            });
            if (this.fflSaved) {
                this.ffl = {
                    street_address: this.fflSaved.business_info.street_address,
                    city: this.fflSaved.business_info.city,
                    mailing_state: this.fflSaved.business_info.state,
                    zip_code: this.fflSaved.business_info.zip_code,
                };
                this.position = {
                    lat: parseFloat(this.fflSaved.business_info.latitude),
                    lgn: parseFloat(this.fflSaved.business_info.longitude)
                };
                this.formIsFilled = true;
                this.$emit('changeNextStep', {step: "contact"});
                this.$emit('changeFfl', this.ffl);
            }
        },
        methods: {
            onSubmitHandler: async function () {
                const isValid = await this.validate(this.ffl);
                let addr = this.ffl.street + ", " + this.ffl.city + ", " + this.ffl.zipCode + ", " + "US";
                const response = await geocoding(addr).catch(err => {
                    console.log(err);
                    this.position = {
                        lat: 0,
                        lng: 0,
                    };
                });
                this.position = {
                    lat: response.data.results[0].geometry.location.lat,
                    lng: response.data.results[0].geometry.location.lng,
                };
                if (isValid) {
                    this.$emit('changeNextStep', {step: "contact"});
                    this.$emit('changeFfl', {...this.ffl, position: this.position});
                }
            },
            validate: async function (data) {
                const fields = Object.keys(data);
                const promises = Promise.all(fields.map(field => this.$validator.validate(field)));
                return (await promises).every(isValid => isValid);
            }
        }
    }
</script>

<style scoped>

</style>