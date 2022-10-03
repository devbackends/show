
<select v-validate="'{{$validations}}'" class="form-control js-example-basic-single" id="{{ $attribute->code }}" name="{{ $attribute->code }}" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;">

    <?php $selectedOption = old($attribute->code) ?: $product[$attribute->code] ?>

    @if ($attribute->code != 'tax_category_id')
            <option value data-isdefault="true">- select -</option>
        @foreach ($attribute->options as $option)
            <option value="{{ $option->id }}" {{ $option->id == $selectedOption ? 'selected' : ''}} {{($attribute->code == 'condition' && strtolower($option->admin_name) == 'used') ? 'data-used="true"' : ''}}>
                {{ $option->admin_name }}
            </option>
        @endforeach

    @else

        <option value=""></option>

        @foreach (app('Webkul\Tax\Repositories\TaxCategoryRepository')->all() as $taxCategory)
            <option value="{{ $taxCategory->id }}" {{ $taxCategory->id == $selectedOption ? 'selected' : ''}}>
                {{ $taxCategory->name }}
            </option>
        @endforeach

    @endif
</select>

    @if(Route::currentRouteName()=='admin.catalog.products.edit')
        @if($attribute->code=='manufacturer_firearm')
            <small class="form-text text-muted">Not seeing what you are looking for? <a id="manufacturerFirearmAdd" type="button" onclick="showAttributeOption()" class="text-info">Add one</a></small>

            <div id="manufacturerFirearmAddField" class="mt-3 d-none">
                <add-attribute-form></add-attribute-form>
            </div>
            <script>
                function showAttributeOption() {
                    $('#manufacturerFirearmAddField').toggleClass("d-block");
                }
            </script>
            @push('scripts')

                @parent
                <script type="text/x-template" id="add-attribute-form-template">
                    <div>
                        <form  method="POST" action="{{ route('admin.catalog.attributes.option.store') }}" data-vv-scope="add-attribute-form" @submit.prevent="addAttributeOption('add-attribute-form')" class="">
                            @csrf
                            <div  class="form-group" :class="[errors.has('variant_option') ? 'has-error' : '']">
                                <label for="variant_option">Add Attribute Option</label>
                                <div class="row">
                                    <div class="col col-md-4 pr-0"><input  type="text" v-model="variant_option" id="variant_option"  class="form-control" name="variant_option"  v-validate="'required'" data-vv-as="'&quot; Attribute Option &quot;'" ></div>
                                    <div class="col-auto"><button type="submit" class="btn btn-outline-primary">Save</button></div>
                                </div>
                                <span class="control-error" v-if="errors.has('variant_option')">@{{ errors.first('variant_option') }}</span>
                            </div>
                            
                        </form>
                    </div>

                </script>
                <script>
                    var attribute_id = @json($attribute->id);
                    Vue.component('add-attribute-form', {
                        data: function(){
                            return {
                                attribute_id:attribute_id,
                                variant_option: null,

                            }
                        },

                        template: '#add-attribute-form-template',


                        methods: {
                            addAttributeOption: function (formScope,e) {
                                this.$validator.validateAll(formScope).then((result) => {
                                    if (result) {
                                        var this_this = this;
                                        this_this.$http.post("{{ route('admin.catalog.attributes.option.store') }}", {'variant_option':this_this.variant_option,'attribute_id':this_this.attribute_id})
                                            .then(function(response) {
                                                this_this.variant_option=null;
                                                //this_this.$parent.closeModal();
                                                location.reload();
                                            })
                                            .catch(function (error) {

                                            })
                                    }
                                });
                            }
                        }
                    });
                </script>
            @endpush
        @endif

    @endif

