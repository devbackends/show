<template>
    <div>
        <div class="row">
            <div class="col-sm-4 mt-3 control-group">
                <label for="phone">Phone</label>
                <input v-model="ffl.phone" data-vv-as=" " v-validate="'required|numeric'" type="tel" name="phone"
                       id="phone"
                       class="form-control"
                       placeholder="Phone">
            </div>
            <div class="col-sm-4 mt-3 control-group">
                <label for="email">Email</label>
                <input v-model="ffl.email" data-vv-as=" " v-validate="'required|email'" type="email" name="email"
                       id="email"
                       class="form-control"
                       placeholder="Email">
            </div>
            <div class="col-sm-4 mt-3 control-group">
                <label for="business-hours">Business hours</label>
                <input v-model="ffl.business_hours" data-vv-as=" " v-validate="'required'" type="text"
                       name="business_hours"
                       id="business-hours"
                       class="form-control"
                       placeholder="Example: 9:30am - 5:30pm Mon-Fri">
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
    export default {
        name: "Contact",
        props: {
            fflSaved: {
                type: Object
            }
        },
        data: function () {
            return {
                ffl: {
                    phone: null,
                    email: null,
                    business_hours: null,
                },
                formIsFilled: false,
            };
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
        methods: {
            onSubmitHandler: async function () {
                const isValid = await this.validate(this.ffl);
                if (isValid) {
                    this.$emit('changeNextStep', {step: "license"});
                    this.$emit('changeFfl', this.ffl);
                }
            },
            validate: async function (data) {
                const fields = Object.keys(data);
                const promises = Promise.all(fields.map(field => this.$validator.validate(field)));
                return (await promises).every(isValid => isValid);
            }
        },
        mounted() {
            if (this.fflSaved) {
                this.ffl = {
                    phone: this.fflSaved.business_info.phone,
                    email: this.fflSaved.business_info.email,
                    business_hours: this.fflSaved.business_info.business_hours,
                };
                this.formIsFilled = true;
                this.$emit('changeNextStep', {step: "license"});
                this.$emit('changeFfl', this.ffl);
            }
        },
    }
</script>

<style scoped>

</style>