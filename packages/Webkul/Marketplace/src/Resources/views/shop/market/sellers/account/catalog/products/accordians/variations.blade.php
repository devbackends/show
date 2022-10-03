@if ($product->type == 'configurable')
    @php array_push($GLOBALS['groups'],['id'=>'variations_box','title'=>'Variations']); @endphp
    <!-- Form Variations -->
    <div class="create-product__box" id="variations_box">
        <p class="create-product__box-title">Variations</p>
        {{--{!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.variations.controls.before', ['product' => $product]) !!}--}}
        <variations></variations>
        {{--{!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.variations.controls.after', ['product' => $product]) !!}--}}
        <!-- END Variations -->
    </div>
    <!-- END Form Variations -->


<?php $productFlat =$product->productFlat; ?>

@push('scripts')

    <script type="text/x-template" id="variations-template">
        <div class="">
            <div class="row" v-if="super_attributes.length > 0">
                <div class="col">
                    <p class="create-product__box-subtitle">Attributes<i
                            class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip"
                            data-placement="top"
                            title="Options for different physical characteristics of your product such as color and size."></i>
                    </p>
                    <input type="hidden" name="formatted_variants" v-model="JSON.stringify(variants)">
                </div>
            </div>
            <!-- Attributes -->
            <input type="hidden" name="super_attributes" :value="JSON.stringify(super_attributes)"/>
            <div class="create-product__attributes mb-4">
                <form id="add-super-attributes-form"  method="POST" data-vv-scope="add-super-attributes-form"  @submit.prevent="addSuperAttributes('add-super-attributes-form')">
                    <div class="row mb-3" v-for="(attrobj,index) in super_attributes" :key="index">
                        <div class="col-12 col-lg form-group"  :class="[errors.has('add-super-attributes-form.attribute_name_' + index) ? 'has-error' : '']">
                            <Select2
                                v-if="!superAttributesSaved"
                                :options="configurableAttributes"
                                :select="super_attributes[index]"
                                @change="changeAttributeEvent($event,index)"
                                @select="selectAttributeEvent($event,index)"></Select2>
                            <select v-if="superAttributesSaved"  class="custom-select variants_attribute_select" disabled>
                                     <option v-text="super_attributes[index].admin_name" ></option>
                            </select>
                            <span class="control-error" v-if="errors.has('add-super-attributes-form.attribute_name_' + index)">@{{ errors.first('add-super-attributes-form.attribute_name_' + index) }}</span>
                        </div>
                        <div :id="`parent_${index}`" class="col-12 col-lg form-group" :class="[errors.has('add-super-attributes-form.attribute_option_' + index) ? 'has-error' : '']">

                            <Select2MultipleControl v-model="super_attributes[index].selected_attribute_options"  :ref="`attribute_options_${index}`" :options="super_attributes[index].options"  @change="changeOptionEvent($event,index)" @select="selectOptionEvent($event)" ></Select2MultipleControl>

                            <span class="control-error" v-if="errors.has('add-super-attributes-form.attribute_option_' + index)">@{{ errors.first('add-super-attributes-form.attribute_option_' + index) }}</span>
                        </div>
                        <div class="col-auto">
                            <a v-on:click="removeAttribute(index)" v-show="!superAttributesSaved && super_attributes.length > 0" href="javascript:;" class="create-product__delete-icon-button"><i class="far fa-trash-alt"></i></a>
                        </div>
                    </div>
                    <div class="row" style="justify-content: space-between;">
                            <a v-if="!superAttributesSaved" v-on:click="addNewAttribute()" href="javascript:;" class="create-product__add-button float-left"><i
                                    class="far fa-plus"></i>Add attribute</a>

                        <submit-button v-show="!superAttributesSaved && super_attributes.length > 0" cssClass="float-right" :loading="loadingVariant"  text="Save & Generate Attributes"></submit-button>
                    </div>
                </form>
            </div>
            <!-- END Attributes -->
            <hr>
            <input type="hidden" id="editable-variant-index" />
            <div v-if="showEditVariantForm">
                <!-- start edit Variations -->
                <p class="create-product__box-subtitle">Variation<i
                        class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top"
                        title="Product variations occur when an item that has different attributes, such as color and size, is grouped together with its variants on a single product page."></i>
                </p>

                <div class="create-product__inner-box">
                    <form id="edit-variant-form"  method="POST" data-vv-scope="edit-variant-form"  @submit.prevent="editVariantForm('edit-variant-form')">
                        <div class="row" >

                            <div class="col">
                                <p class="create-product__inner-box-title">
                                    <span v-for="(attribute,index) in super_attributes" :key="index" v-text="index==super_attributes.length -1 ? attribute.admin_name : attribute.admin_name+' / ' "></span>
                                </p>
                            </div>
                            <div class="col-auto">
                                <a v-on:click="showEditVariantForm=false" href="javascript:;" class="create-product__inner-box-close"><i class="far fa-times"></i></a>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col" :class="[errors.has('edit-variant-form.product-variation-price') ? 'has-error' : '']">
                                <label for="productVariationPrice">Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="productVariationPriceCurrency">$</span>
                                    </div>
                                    <input type="text" v-validate="'required'" v-model="editableVariant.price" id="product-variation-price" name="product-variation-price" class="form-control"
                                           placeholder="Price" aria-label="Price"
                                           aria-describedby="productVariationPriceCurrency">
                                </div>
                                <span class="control-error" v-if="errors.has('edit-variant-form.product-variation-price')">@{{ errors.first('edit-variant-form.product-variation-price') }}</span>
                            </div>

                            <div class="col"  :class="[errors.has('edit-variant-form.product-variation-available') ? 'has-error' : '']">
                                <label for="product-variation-available">Available</label>
                                <div class="input-group">
                                    <input type="text" id="product-variation-available" name="product-variation-available" class="form-control" v-model="editableVariant.quantity"  v-validate="'required'"
                                           placeholder="Available" aria-label="Available"
                                           aria-describedby="productVariationAvailableUnits">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="productVariationAvailableUnits">Units</span>
                                    </div>
                                </div>
                                <span class="control-error" v-if="errors.has('edit-variant-form.product-variation-available')">@{{ errors.first('edit-variant-form.product-variation-available') }}</span>
                            </div>
                            <div class="col" :class="[errors.has('edit-variant-form.product-variation-name') ? 'has-error' : '']">
                                <label for="productVariationPrice">Name</label>
                                <div class="input-group " >

                                    <input type="text"  id="product-variation-name"  class="form-control" name="product-variation-name"  v-model="editableVariant.name" v-validate="'required'"
                                           placeholder="Name" aria-label="Name"
                                           data-vv-as="&quot;Name&quot;">
                                </div>
                                <span class="control-error" v-if="errors.has('edit-variant-form.product-variation-name')">@{{ errors.first('edit-variant-form.product-variation-name') }}</span>
                            </div>

                            <div class="col" :class="[errors.has('edit-variant-form.product-variation-sku') ? 'has-error' : '']">
                                <label for="product-variation-sku">SKU</label>
                                <input type="text" v-model="editableVariant.sku" id="product-variation-sku" name="product-variation-sku" class="form-control" placeholder="SKU" v-validate="'required'"
                                       aria-label="SKU">
                                <span class="control-error" v-if="errors.has('edit-variant-form.product-variation-sku')">@{{ errors.first('edit-variant-form.product-variation-sku') }}</span>
                            </div>
                        </div>
                        <div v-if="typeof variants[editableVariantIndex].id !='undefined'" class="row">
                            <div class="col mb-3">
                                <div class="control-group">
                                    <image-wrapper :sku="variants[editableVariantIndex].sku"  :items='variants[editableVariantIndex].images'></image-wrapper>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary">
                                    Save variation
                                </button>
                            </div>
                            <div class="col text-right">
                                <a v-on:click="removeEditableVariant()"  href="javascript:;" class="btn btn-link create-product__inner-box-delete">Delete</a>
                            </div>
                        </div>
                    </form>
                </div>
                {{--end edit variations--}}
            </div>

            <div class="table-responsive" >
                <table class="table table-sm create-product__variations-table my-3" v-if="variants.length > 0">
                    <thead>
                    <tr>
                        <th scope="col">Variation</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Name</th>
                        <th scope="col">SKU</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr  v-for='(variant, index) in variants'  :key="index">
                        <input type="hidden"  value="{{ $productFlat->marketplace_seller_id }}"/>
                        <th scope="row" v-text="getVariantOptions(variant)">Red / Medium / Plastic</th>
                        <td v-text="`$`+ parseFloat(variant.price).toFixed(2)"></td>
                        <td v-text="variant.quantity"></td>
                        <td v-text="variant.name"></td>
                        <td v-text="variant.sku"></td>
                        <td class="create-product__variations-table-actions">
                            <a v-on:click="editVariant(index,variant)" href="javascript:;" class="table-icon-button table-icon-button--edit"><i
                                    class="far fa-edit"></i></a>
                            <a v-on:click="removeVariant(variant)" href="javascript:;" class="table-icon-button table-icon-button--delete"><i
                                    class="far fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <p>
                    <a v-show="!showAddVariantForm && variants.length < noOfAvailableVariants && superAttributesSaved"
                       v-on:click="showAddVariantForm=true" href="javascript:;" class="create-product__add-button mt-3"><i
                            class="far fa-plus"></i>Add variant</a>
                </p>

            </div>

            <div v-if="showAddVariantForm">
                <!--start add Variations -->
                <p class="create-product__box-subtitle">Variation<i
                        class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top"
                        title="Product variations occur when an item that has different attributes, such as color and size, is grouped together with its variants on a single product page."></i>
                </p>
                <div class="create-product__inner-box">
                    <form id="submit-new-variant-form"  method="POST" data-vv-scope="add-variant-form"  @submit.prevent="submitNewVariant('add-variant-form')">
                        @csrf()
                        <div class="row">
                            <div class="col">
                                <p class="create-product__inner-box-title">
                                    <span v-for="(attribute,index) in super_attributes" :key="index" v-text="index==super_attributes.length -1 ? attribute.admin_name : attribute.admin_name+' / ' "></span>
                                </p>
                            </div>
                            <div class="col-auto">
                                <a  v-on:click="showAddVariantForm=false" href="javascript:;" class="create-product__inner-box-close"><i class="far fa-times"></i></a>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col" v-for="(attribute,index) in super_attributes" :key="index" :class="[errors.has('add-variant-form.' + attribute.code) ? 'has-error' : '']">
                                <select class="custom-select" v-validate="'required'" v-model="variant[attribute.code]" :id="attribute.code" :name="attribute.code" :data-vv-as="'&quot;' + attribute.admin_name + '&quot;'" >
                                    <option :selected="true" v-text="'Select '+attribute.admin_name" value="" ></option>
                                    <option v-for="(option,i) in super_attributes[index].options" :key="i" v-if="super_attributes[index].selected_attribute_options.includes(super_attributes[index].options[i].id.toString())" :value="super_attributes[index].options[i].id"  v-text="option.admin_name"></option>
                                </select>
                                <span class="control-error" v-if="errors.has('add-variant-form.' + attribute.code)">@{{ errors.first('add-variant-form.' + attribute.code) }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col" :class="[errors.has('add-variant-form.new-variant-price') ? 'has-error' : '']">
                                <label for="productVariationPrice">Price</label>
                                <div class="input-group " >
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="productVariationPriceCurrency">$</span>
                                    </div>
                                    <input type="text"  id="new-variant-price" name="new-variant-price" class="form-control" v-validate="'required'"
                                           placeholder="Price" aria-label="Price"
                                           data-vv-as="&quot;Price&quot;"
                                           aria-describedby="productVariationPriceCurrency">
                                </div>
                                <span class="control-error" v-if="errors.has('add-variant-form.new-variant-price')">@{{ errors.first('add-variant-form.new-variant-price') }}</span>
                            </div>

                            <div class="col"  :class="[errors.has('add-variant-form.new-variant-quantity') ? 'has-error' : '']">
                                <label for="productVariationAvailable">Available</label>
                                <div class="input-group" >
                                    <input type="text" id="new-variant-quantity" name="new-variant-quantity" class="form-control" v-validate="'required'"
                                           placeholder="Available" aria-label="Available"
                                           data-vv-as="&quot;Quantity&quot;"
                                           aria-describedby="productVariationAvailableUnits">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="productVariationAvailableUnits">Units</span>
                                    </div>
                                </div>
                                <span class="control-error" v-if="errors.has('add-variant-form.new-variant-quantity')">@{{ errors.first('add-variant-form.new-variant-quantity') }}</span>
                            </div>
                            <div class="col" :class="[errors.has('add-variant-form.new-variant-name') ? 'has-error' : '']">
                                <label for="productVariationPrice">Name</label>
                                <div class="input-group" >

                                    <input type="text"  id="new-variant-name" name="new-variant-name" class="form-control" v-validate="'required'"
                                           placeholder="Name" aria-label="Name"
                                           data-vv-as="&quot;Name&quot;">
                                </div>
                                <span class="control-error" v-if="errors.has('add-variant-form.new-variant-name')">@{{ errors.first('add-variant-form.new-variant-name') }}</span>
                            </div>
                            <div class="col"   :class="[errors.has('add-variant-form.new-variant-sku') ? 'has-error' : '']">
                                <label for="productVariationSKU">SKU</label>
                                <div class="input-group" >
                                    <input type="text" id="new-variant-sku" name="new-variant-sku" class="form-control" placeholder="SKU" v-validate="'required'"
                                           data-vv-as="&quot;Sku&quot;"
                                           aria-label="SKU">
                                </div>
                                <span class="control-error" v-if="errors.has('add-variant-form.new-variant-sku')">@{{ errors.first('add-variant-form.new-variant-sku') }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary">
                                    Save variation
                                </button>
                            </div>
                            <div class="col text-right">
                                <a v-on:click="showAddVariantForm=false;" href="javascript:;" class="btn btn-link create-product__inner-box-delete">Delete</a>
                            </div>
                        </div>
                    </form>
                </div>
                {{--end add variation--}}
            </div>
        </div>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        const fetchedSuperAttributes = @json(app('\Webkul\Product\Repositories\ProductRepository')->getSuperAttributes($product));
        const configurableAttributes=@json($configurableAttributes);

        Vue.component('variations', {
            data: () => ({
                configurableAttributes:configurableAttributes,
                variants:[],
                variant: {},
                super_attributes:fetchedSuperAttributes,
                editableVariant:{
                    price:'',
                    name:'',
                    sku:'',
                    quantity:0
                    /*,weight:0*/
                },
                showAddVariantForm:false,
                showEditVariantForm:false,
                autoGenerateVariation:true,
                inventories: {},
                totalQty: 0,
                loadingVariant:false,
                editableVariantIndex: null,
                superAttributesSaved:  fetchedSuperAttributes.length > 0 ? true : false
            }),
            watch: {

            },
            template: '#variations-template',
            created () {
                this.resetModel();

                var this_this = this;
 /*               for(let i=0;i<this_this.variants.length;i++){
                    this.inventorySources.forEach(function(inventorySource) {
                        this_this.variants[i].inventories[inventorySource.id] = this_this.sourceInventoryQty(inventorySource.id,this_this.variants[i])
                        this_this.variants[i].inventories[inventorySource.id]['inventory_source_id']=inventorySource.id;
                        this_this.variants[i].totalQty += parseInt(this_this.inventories[inventorySource.id]);
                    })

                }*/
                this.checkConfigurableAttributes()

                for (let i = 0; i < this.super_attributes.length; i++) {
                    this.super_attributes[i].text = this.super_attributes[i].admin_name;
                    for (let j = 0; j < this.super_attributes[i].options.length; j++) {
                        this.super_attributes[i].options[j].text = this.super_attributes[i].options[j].admin_name;
                    }
                }
            },
            async mounted() {
                await this.fetchVariants();

                this.$root.$on('changeVariantImages', ({files, variantindex}) => {
                    this.variants[variantindex].to_upload_images=[];
                    var form = $('#productCreationForm')[0]; // You need to use standard javascript object here
                    ([...files]).forEach((f,x) => {
                        this.variants[variantindex].to_upload_images.push(f);
                    });
                    console.log(this.variants[variantindex].to_upload_images);
                });


                this.$root.$on('reloadVariableImages', ({sku, images}) => {
                    for (let i=0;i<this.variants.length;i++){
                        if(this.variants[i].sku==sku){
                            this.variants[i].images=images;
                            console.log('reload Variable Images')
                            console.log(images);
                        }
                    }
                });
                this.checkConfigurableAttributes();
            },
            computed: {
                   noOfAvailableVariants(){

                       const options=[];
                       let p= 1;
                       for(let i=0;i < this.super_attributes.length;i++){
                           if(typeof this.super_attributes[i].selected_attribute_options != 'undefined') {
                               if (this.super_attributes[i].selected_attribute_options.length > 0) {
                                   options.push(this.super_attributes[i].selected_attribute_options.length);
                               }
                           }
                       }

                       for (let i = 0; i < options.length; i += 1)
                       {
                           p *= options[i];
                       }
                       if(options.length > 0){
                           return p
                       }
                       return 0;
                   }
            },
            methods: {

                addNewAttribute: function(){
                    for(let i=0;i < this.configurableAttributes.length;i++){
                        let found=false;
                        for(let j=0;j < this.super_attributes.length;j++){
                            if(this.super_attributes[j]['id']==this.configurableAttributes[i]['id']){
                               found=true;
                            }
                        }
                        if(!found){
                            this.super_attributes.push(this.configurableAttributes[i]);
                            var index=this.super_attributes.length -1;
                            break;
                        }
                    }
                },
                 async addSuperAttributes(formScope){
                    this.loadingVariant=true;
                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {
                            var this_this = this;

                            this.$http.post("{{ route('marketplace.account.products.add-super-attributes',$product->id) }}",{'super_attributes':this_this.super_attributes,'auto_generate_variation':this_this.autoGenerateVariation})
                                .then(async response => {
                                    if(response.status==200){
                                        this.superAttributesSaved=true;
                                       await this_this.fetchVariants();

                                    }
                                })
                                .catch(function (error) {

                                })
                        }else{

                        }
                    });
                },
                 async fetchVariants(){
                    var this_this = this;
                    this.$http.get ("{{ route('marketplace.account.products.get-variants',$product->id) }}")
                        .then (async response => {
                            if(response.status==200){
                                this_this.variants=response.data;
                        }
                        })
                        .catch (function (error) {

                        })
                },
                removeVariant: function(variant) {
                    let index = this.variants.indexOf(variant);
                    this.variants.splice(index, 1)
                },
                removeEditableVariant: function(){
                    let index=this.editableVariantIndex;
                    this.variants.splice(index, 1);
                    this.showEditVariantForm=false;
                },
                submitNewVariant: function(formScope){
                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {
                            var this_this = this;

                            var filteredVariants = this_this.variants.filter(function(variant) {
                                var matchCount = 0;
                                for (var key in this_this.variant) {
                                    if (variant[key] == this_this.variant[key]) {
                                        matchCount++;
                                    }
                                }
                                return matchCount == Object.keys(this_this.variant).length;
                            });
                            if (filteredVariants.length) {
                                window.showAlert(`alert-danger`, 'Error', "{{ __('admin::app.catalog.products.variant-already-exist-message') }}");
                            } else {
                                this_this.variants.push(Object.assign({
                                    sku: $('#new-variant-sku').val(),
                                    name: $('#new-variant-name').val(),
                                    price: $('#new-variant-price').val(),
                                    quantity:$('#new-variant-quantity').val(),
                                    /*weight: $('#new-variant-weight').val(),*/
                                    status: 1
                                }, this.variant));
                                this.showAddVariantForm=false;

                            }
                        }
                    });
                }
                ,
                editVariantForm: function(formScope){
                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {
                            this.variants[this.editableVariantIndex].price=this.editableVariant.price;
                            this.variants[this.editableVariantIndex].name=this.editableVariant.name;
                            this.variants[this.editableVariantIndex].sku=this.editableVariant.sku;
                            this.variants[this.editableVariantIndex].quantity=this.editableVariant.quantity;

                            this.resetEditForm();
                            this.showEditVariantForm=false;
                        }
                    });
                },
                editVariant: function(index,variant){
                    this.setDynamicPropertiesForEditObject();
                    this.showEditVariantForm=true;

                    this.editableVariantIndex=index;
                    for (var property in this.editableVariant) {
                        if (this.editableVariant.hasOwnProperty(property)) {
                            if(property=='price'){
                                this.editableVariant[property]=parseFloat(variant.price).toFixed(2);
                            }else{
                                this.editableVariant[property]=variant[property];
                            }

                        }
                    }

                },
                setDynamicPropertiesForEditObject: function(){
                    for(let i=0;i< this.super_attributes.length;i++){

                        this.editableVariant[this.super_attributes[i].code]=0;
                    }
                },
                removeAttribute: function(index){
                    this.super_attributes.splice(index, 1);

                },
                addVariant (formScope) {
                },
                resetModel () {
                },
                getCol: function(matrix, col){
                   var column = [];
                   for(var i=0; i<matrix.length; i++){
                    column.push(matrix[i][col]);
                     }
                         return column;
                },
                getVariantOptions: function(variant){
                      const options=[];
                      const values=[];
                      let values_string='';
                      for (let i=0;i < this.super_attributes.length;i++){

                          options.push({'attribute_id':this.super_attributes[i].id,'attribute_option_id':variant[this.super_attributes[i].code]})
                      }
                    for (let i=0;i < options.length;i++){
                        for (let j=0;j < this.configurableAttributes.length;j++){
                            if(this.configurableAttributes[j].id==options[i].attribute_id){
                                for (let k=0;k < this.configurableAttributes[j].options.length;k++){
                                    if(this.configurableAttributes[j].options[k].id==options[i].attribute_option_id){
                                        values.push(this.configurableAttributes[j].options[k].admin_name);
                                    }
                                }
                            }
                        }
                    }

                    for (let i=0;i < values.length;i++){
                        if(i==values.length -1){
                            values_string +=values[i];
                        }else{
                            values_string +=values[i]+' / ';
                        }

                    }
                  return values_string;
                }/*,
                sourceInventoryQty (inventorySourceId,variant) {
                    var inventories = variant.inventories.filter(function(inventory) {
                        return inventorySourceId === inventory.inventory_source_id && inventory.vendor_id == "{{ $productFlat->marketplace_seller_id }}";
                    })

                    if (inventories.length)
                        return inventories[0]['qty'];

                    return 0;
                },

                updateTotalQty () {
                    this.totalQty = 0;
                    for (var key in this.inventories) {
                        this.totalQty += parseInt(this.inventories[key]);
                    }
                },
                checkInventory(index){
                   if(typeof this.variants[index].inventories[0] != 'undefined'){
                      if(this.variants[index].inventories[0].hasOwnProperty("qty") > -1){
                          return this.variants[index].inventories[0]['qty'];
                      }else{
                          this.variants[index].inventories[0]={'qty':0};
                          return 0;

                      }
                   }else{
                       this.variants[index].inventories[0]={'qty':0};
                       return 0;
                   }
                }*/,
                resetEditForm: function(){
                    this.editableVariant={
                        price:'',
                            name:'',
                            sku:'',
                            quantity:0
                            /*,weight:0*/
                    };
                },
                changeAttributeEvent(val,index){
                    for(let i=0;i< this.configurableAttributes.length; i++){
                        if(this.configurableAttributes[i].id==parseInt(val)){
                            this.$set(this.super_attributes, index, this.configurableAttributes[i]);
                        }
                    }
                },
                selectAttributeEvent({id, text},index){
                    for(let i=0;i< this.configurableAttributes.length; i++){
                        if(this.configurableAttributes[i].id==parseInt(id)){
                            this.$set(this.super_attributes, index, this.configurableAttributes[i]);

                        }
                    }
                },
                changeOptionEvent(val,index){

                    this.super_attributes[index].selected_attribute_options=val;

                },
                selectOptionEvent({id, text}){
                    console.log('');
                },
                 getCol(matrix, col) {
                     var column = [];
                     for (var i = 0; i < matrix.length; i++) {

                     }
                     return column;
                 },
                checkConfigurableAttributes(){
                    // make configurable attributes compatible with select vue by adding text field
                    if(typeof this.configurableAttributes !='undefined'){
                        for (let i = 0; i < this.configurableAttributes.length; i++) {
                            this.configurableAttributes[i].text = this.configurableAttributes[i].admin_name;
                            if(typeof this.configurableAttributes[i].options !='undefined'){
                                for (let j = 0; j < this.configurableAttributes[i].options.length; j++) {
                                    this.configurableAttributes[i].options[j].text = this.configurableAttributes[i].options[j].admin_name;
                                }
                            }
                        }
                    }
                }
        }
        });

    </script>
@endpush
@endif