<template>
    <div v-if="attribute.code!='guest_checkout' && attribute.code!='tax_category_id'">


        <!--text-->
        <div v-if="attribute.type=='text'">
            <label v-text="attribute.admin_name"></label>
            <input type="text" v-model="product[attribute.code]" :placeholder="attribute.description"
                   class="form-control" :id="attribute.code" :name="attribute.code"/>
        </div>


        <!--textarea-->
        <div v-if="attribute.type=='textarea'">
            <label v-text="attribute.admin_name"></label>
            <textarea v-model="product[attribute.code]" class="form-control" :id="attribute.code"
                      :name="attribute.code"></textarea>
        </div>


        <!--select-->
        <div v-if="attribute.type=='select'">
            <label v-text="attribute.admin_name"></label>
            <Select2 :id="attribute.code" :name="attribute.code" v-model="product[attribute.code]" :options="attribute.options" ></Select2>

        </div>


        <!--multiselect-->
        <div v-if="attribute.type=='multiselect'">
            <label v-text="attribute.admin_name"></label>
            <input type="hidden" :name="attribute.code" v-model="product[attribute.code]">
            <Select2MultipleControl  :id="attribute.code"  v-model="product[attribute.code]" :options="attribute.options" @change="changeDynamicMultiselectEvent($event)" ></Select2MultipleControl>

        </div>

        <div
            v-if="['select','multiselect'].includes(attribute.type) && ['manufacturer_firearm','material','manufacturer_ammunition','color'].includes(attribute.code) ">
            <div class="col-12 mt-1">
                <small class="form-text text-muted">Not seeing what you are looking for? <a type="button"
                                                                                            v-on:click="showAttributeOption(attribute.id)"
                                                                                            class="text-info">Add
                    one</a></small>
            </div>
            <div :id="`attribute_container_${attribute.id}`" class="col-12 mt-1 d-none">
                <form method="POST" action="" data-vv-scope="add-attribute-form"
                      @submit.prevent="addAttributeOption('add-attribute-form')" class="">
                    <div class="form-group" :class="[errors.has('variant_option') ? 'has-error' : '']">
                        <label for="variant_option">Add Attribute Option</label>
                        <div class="row">
                            <div class="col col-md-4 pr-0">
                                <input type="text" v-model="variant_option" id="variant_option" class="form-control"
                                       name="variant_option" v-validate="'required'"
                                       data-vv-as="'&quot; Attribute Option &quot;'">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-outline-primary">Save</button>
                            </div>
                        </div>
                        <span class="control-error"
                              v-if="errors.has('variant_option')">@{{ errors.first('variant_option') }}</span>
                    </div>

                </form>
            </div>
        </div>


        <!--checkbox-->
        <div v-if="attribute.type=='checkbox'">
            <label v-text="attribute.admin_name"></label>
            <div class="control-group">
        <span v-for="(option,key) in attribute.options" :key="key" class="checkbox" style="margin-top: 5px;">
            <input type="checkbox" class="checkbox" :name="`${attribute.code}[]`" :value="option.id"
                   v-model="product[attribute.code]"/>
            <label class="checkbox-view"></label>
            @{{ option.admin_name }}
        </span>
            </div>
        </div>


        <!--boolean-->
        <div v-if="attribute.type=='boolean'">
            <label v-text="attribute.admin_name"></label>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input boolean-input" v-model="product[attribute.code]"
                       :name="attribute.code" :id="attribute.code">
                <label class="custom-control-label" :for="attribute.code" v-text="`${attribute.admin_name}`"></label>
            </div>

        </div>


        <!--price-->
        <div v-if="attribute.type=='price'">
            <label v-text="attribute.admin_name"></label>
            <input v-model="product[attribute.code]" type="text" class="form-control"
                   :id="attribute.code" :name="attribute.code"/>
        </div>

    </div>
</template>
<script>


export default {
    props: {
        attribute: Object,
        product: Object
    },

    data: () => ({
        variant_option: null
    }),
    created() {
        if(this.attribute.type == 'multiselect'){
            if(this.product[this.attribute.code]){
                this.product[this.attribute.code]=this.product[this.attribute.code].split(',');
            }else{
                this.product[this.attribute.code]=[];
            }

        }
    }
    ,
    mounted() {
        if (this.attribute.type == 'select' || this.attribute.type == 'multiselect' || this.attribute.type == 'checkbox') {
         //   this.getAttributeOpions(this.attribute.id);
        }

    },
    computed: {
        multiselectModel() {
            if (this.product[this.attribute.code]) {
                return this.product[this.attribute.code].split(',');
            }
            return [];

        }
    },
    methods: {
        async getAttributeOpions(attribute_id) {

            var this_this = this;
            await this.$http.get(`/marketplace/account/catalog/products/get-attribute-options/${attribute_id}`)
                .then(async function (response) {
                    if (response.status == 200) {
                        this_this.attribute.options = response.data;
                           for (let i=0;i<this_this.attribute.options.length;i++){
                               this_this.attribute.options[i].text=this_this.attribute.options[i].admin_name;
                           }

                    }
                })
                .catch(function (error) {

                })
        },
        showAttributeOption: function (attribute_id) {
            $('#attribute_container_' + attribute_id).toggleClass("d-block");
        },
        async addAttributeOption(formScope, e) {
            this.$validator.validateAll(formScope).then((result) => {
                if (result) {
                    var this_this = this;
                    this_this.$http.post("/attributes/options/create", {
                        'variant_option': this_this.variant_option,
                        'attribute_id': this_this.attribute.id
                    })
                        .then(async function (response) {

                            if (response.status == 200) {

                                this_this.variant_option = null;

                                const getAttributes = await this_this.getAttributeOpions(this_this.attribute.id);

                                if (this_this.attribute.type == 'select') {
                                    if (response.data.status == 'success') {
                                        $('#' + this_this.attribute.code).val(response.data.id);
                                        $('#' + this_this.attribute.code).trigger('change');
                                    }

                                    if (response.data.status == 'found') {
                                        this.$toast.error('Option Already Found', {
                                            position: 'top-right',
                                            duration: 5000,
                                        });
                                    }
                                }
                                if (this_this.attribute.type == 'multiselect') {
                                    if (response.data.status == 'success') {
                                        const values = $('#' + this_this.attribute.code).val();
                                        values.push(response.data.id);
                                        $('#' + this_this.attribute.code).val(values);
                                        $('#' + this_this.attribute.code).trigger('change');
                                    }
                                    if (response.data.status == 'found') {
                                        this.$toast.error('Option Already Found', {
                                            position: 'top-right',
                                            duration: 5000,
                                        });
                                    }
                                }
                                $('#attribute_container_' + this_this.attribute.id).toggleClass("d-block");
                            }
                        })
                        .catch(function (error) {

                        })
                }
            });
        },
        changeDynamicMultiselectEvent: function(val){
            this.product[this.attribute.code]=[];
            for (let j=0;j<this.attribute.options.length;j++){
                if (val.includes(this.attribute.options[j].id.toString())) {
                    this.product[this.attribute.code].push(this.attribute.options[j].id);
                }
            }
        }
    }
};
</script>