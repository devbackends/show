<template>
    <div>
        <div class="row mt-4">
            <div class="col-sm-12">
                <label for="long-gun">Long Gun</label>
            </div>
            <div class="col-sm-3 control-group" v-bind:class="[errors.has('long_gun') ? 'has-error' : '']">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span id="basic-addon" class="input-group-text bg-white border-right-0">$</span>
                    </div>
                    <input v-model="ffl.long_gun" data-vv-as=" " v-validate="'required|decimal'" type="text"
                           name="long_gun" id="long-gun"
                           class="form-control border-left-0"
                           placeholder="0.00" aria-describedby="basic-addon">
                </div>
            </div>
            <div class="col-sm-9 control-group" v-bind:class="[errors.has('long_gun_description') ? 'has-error' : '']">
                <input v-model="ffl.long_gun_description" data-vv-as=" " type="text"
                       name="long_gun_description" class="form-control"
                       placeholder="Add a description">
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-12">
                <label for="hand-gun">Hand Gun</label>
            </div>
            <div class="col-sm-3 control-group" v-bind:class="[errors.has('hand_gun') ? 'has-error' : '']">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span id="basic-addon2" class="input-group-text bg-white border-right-0">$</span>
                    </div>
                    <input v-model="ffl.hand_gun" data-vv-as=" " v-validate="'required|decimal'" type="text"
                           name="hand_gun" id="hand-gun"
                           class="form-control border-left-0"
                           placeholder="0.00" aria-describedby="basic-addon2">
                </div>
            </div>
            <div class="col-sm-9 control-group" v-bind:class="[errors.has('hand_gun_description') ? 'has-error' : '']">
                <input v-model="ffl.hand_gun_description" data-vv-as=" " type="text"
                       name="hand_gun_description" class="form-control"
                       placeholder="Add a description">
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-12">
                <label for="nics">NICS</label>
            </div>
            <div class="col-sm-3 control-group" v-bind:class="[errors.has('nics') ? 'has-error' : '']">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span id="basic-addon3" class="input-group-text bg-white border-right-0">$</span>
                    </div>
                    <input v-model="ffl.nics" data-vv-as=" " v-validate="'required|decimal'" type="text" name="nics"
                           id="nics"
                           class="form-control border-left-0"
                           placeholder="0.00" aria-describedby="basic-addon3">
                </div>
            </div>
            <div class="col-sm-9 control-group" v-bind:class="[errors.has('nics_description') ? 'has-error' : '']">
                <input v-model="ffl.nics_description" data-vv-as=" " type="text"
                       name="nics_description" class="form-control"
                       placeholder="Add a description">
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-12">
                <label for="other">Other</label>
            </div>
            <div class="col-sm-3 control-group" v-bind:class="[errors.has('other') ? 'has-error' : '']">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span id="basic-addon4" class="input-group-text bg-white border-right-0">$</span>
                    </div>
                    <input v-model="ffl.other" data-vv-as=" " type="text" name="other"
                           id="other"
                           v-validate="'required|decimal'"
                           class="form-control border-left-0"
                           placeholder="0.00" aria-describedby="basic-addon4">
                </div>
            </div>
            <div class="col-sm-9 control-group" v-bind:class="[errors.has('other_description') ? 'has-error' : '']">
                <input v-model="ffl.other_description" data-vv-as=" " type="text"
                       name="other_description" class="form-control"
                       placeholder="Add a description">
            </div>
        </div>
        <div class="col-sm-12 mb-3 mt-5 pl-0">
            <span class="form-part-heading">What are your accepted payment methods?</span>
        </div>
        <div class="row">
            <div class="col-sm-12" id="mailing_address_same_container">
                <div class="control-group onboarding-field radio-container"
                     v-bind:class="[errors.has('payment') ? 'has-error' : '']">
                    <span class="onboadrding-radio-box">
                            <input v-model="ffl.payment" data-vv-as=" " v-validate="'required'" type="radio"
                                   value="cc_cash" name="payment"
                                   class="form-control mailing_address_same">
                            <label class="onboadrding-radio-view"></label>
                        </span>
                    <span class="radio-title">Both credit card and cash</span>
                    <span class="onboadrding-radio-box">
                            <input v-model="ffl.payment" data-vv-as=" " v-validate="'required'" type="radio" value="cc"
                                   name="payment"
                                   class="form-control mailing_address_same">
                            <label class="onboadrding-radio-view"></label>
                        </span>
                    <span class="radio-title">Credit card</span>
                    <span class="onboadrding-radio-box">
                            <input v-model="ffl.payment" data-vv-as=" " v-validate="'required'" type="radio"
                                   value="cash" name="payment"
                                   class="form-control mailing_address_same">
                            <label class="onboadrding-radio-view"></label>
                        </span>
                    <span class="radio-title">Cash</span>
                </div>
            </div>
        </div>
        <div class="row d-flex flex-row justify-content-end">
            <div class="col-sm-3">
                <button :disabled="!formIsFilled" v-on:click="onSubmitHandler" class="btn btn-primary ml-auto">Finish
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TransferFees",
        props: {
            fflSaved: {
                type: Object
            }
        },
        data: function () {
            return {
                ffl: {
                    long_gun: null,
                    long_gun_description: null,
                    hand_gun: null,
                    hand_gun_description: null,
                    nics: null,
                    nics_description: null,
                    other: null,
                    other_description: null,
                    payment: null,
                },
                formIsFilled: false,
            };
        },
        watch: {
            ffl: {
                handler() {
                    let allFieldsSet = true;
                    //Skip not required fields
                    const skip = ['long_gun_description', 'hand_gun_description', 'nics_description', 'other_description'];
                    for (let field in this.ffl) {
                        if (skip.includes(field)) continue;
                        if (!this.ffl[field]) allFieldsSet = false;
                    }
                    this.formIsFilled = allFieldsSet;
                },
                deep: true
            },
        },
        methods: {
            onSubmitHandler: async function () {
                const isValid = await this.validate(this.ffl);
                if (isValid) {
                    this.$emit('sendForm', this.ffl);
                }
            },
            validate: async function (data) {
                const skip = ['long_gun_description', 'hand_gun_description', 'nics_description', 'other_description'];
                const fields = Object.keys(data).filter(field => !skip.includes(field));
                const promises = Promise.all(fields.map(field => this.$validator.validate(field)));
                return (await promises).every(isValid => isValid);
            }
        },
        mounted() {
            if (this.fflSaved) {
                this.ffl = {
                    long_gun: this.fflSaved.transfer_fees.long_gun,
                    long_gun_description: this.fflSaved.transfer_fees.long_gun_description,
                    hand_gun: this.fflSaved.transfer_fees.hand_gun,
                    hand_gun_description: this.fflSaved.transfer_fees.hand_gun_description,
                    nics: this.fflSaved.transfer_fees.long_gun,
                    nics_description: this.fflSaved.transfer_fees.nics,
                    other: this.fflSaved.transfer_fees.other,
                    other_description: this.fflSaved.transfer_fees.other_description,
                    payment: this.fflSaved.transfer_fees.payment,
                };
                this.formIsFilled = true;
            }
        },
    }
</script>

<style scoped>

</style>