<select  v-validate="'{{$validations}}'" class="form-control js-example-basic-multiple" id="{{ $attribute->code }}" name="{{ $attribute->code }}[]" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" multiple>

    <?php $selected = $product[$attribute->code] ?>

    @foreach ($attribute->options as $option)
        <option value="{{ $option->id }}" {{ in_array($option->id, explode(',', $selected)) ? 'selected' : ''}}>
            {{ $option->admin_name }}
        </option>
    @endforeach

</select>

@if(Route::currentRouteName()=='marketplace.account.products.edit')
    @if(in_array($attribute->code,['manufacturer_firearm','material','manufacturer_ammunition','color']))

        <div class="col-12">
            <small class="form-text text-muted">Not seeing what you are looking for? <a id="manufacturerFirearmAdd" type="button" onclick="showAttributeOption({{$attribute->id}})" class="text-info">Add one</a></small>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addAttributeModal{{$attribute->id}}" tabindex="-1" aria-labelledby="addAttributeModal{{$attribute->id}}Label" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <add-attribute-form-{{$attribute->id}}></add-attribute-form-{{$attribute->id}}>
            </div>
        </div>


        @push('scripts')

            @parent

            <script>
                function showAttributeOption(attribute_id) {
                    /* $('#attribute_container_'+attribute_id).toggleClass("d-block"); */
                    $('#addAttributeModal'+attribute_id).modal('show');
                }
            </script>

            <script type="text/x-template" id="add-attribute-{{$attribute->id}}-form-template">
                <div>
                    <div class="modal-content">
                        <div class="modal-header pb-0">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form  method="POST" action="{{ route('admin.catalog.attributes.option.store') }}" data-vv-scope="add-attribute-form" @submit.prevent="addAttributeOption('add-attribute-form')" class="">
                                @csrf
                                <div  class="form-group mb-0" :class="[errors.has('variant_option') ? 'has-error' : '']">
                                    <label for="variant_option">Add {{ $attribute->name }} Option</label>
                                    <input  type="text" v-model="variant_option" id="variant_option"  class="form-control" name="variant_option" required >
                                    <span class="control-error" v-if="errors.has('variant_option')">@{{ errors.first('variant_option') }}</span>
                                </div>
                                <div class="mt-3">
                                    <submit-button text="Save" color="primary" :loading="isSaveLoading"></submit-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </script>

            <script>
                var attribute_id = @json($attribute->id);
                Vue.component('add-attribute-form-{{$attribute->id}}', {
                    data: function(){
                        return {
                            attribute_id:attribute_id,
                            variant_option: null,
                            isSaveLoading: false
                        }
                    },

                    template: '#add-attribute-'+attribute_id+'-form-template',


                    methods: {
                        addAttributeOption: function (formScope,e) {
                            this.isSaveLoading = true;
                            this.$validator.validateAll(formScope).then((result) => {
                                if (result) {
                                    var this_this = this;
                                    this_this.$http.post("{{ route('admin.catalog.attributes.option.store') }}", {'variant_option':this_this.variant_option,'attribute_id':this_this.attribute_id})
                                        .then(function(response) {
                                            if(response.status==200) {

                                                this_this.variant_option = null;
                                                if (response.data.status == 'success') {
                                                    $('#{{ $attribute->code }}').append(`<option value="${response.data.id}">${response.data.option}</option>`);
                                                    const values = $('#{{ $attribute->code }}').val();
                                                    values.push(response.data.id);
                                                    $('#{{ $attribute->code }}').val(values);
                                                }
                                                if (response.data.status == 'found') {
                                                    this.$toast.error('Option Already Found', {
                                                        position: 'top-right',
                                                        duration: 5000,
                                                    });
                                                }


                                            }

                                            $('#addAttributeModal'+{{ $attribute->id }}).modal('hide');
                                            this_this.isSaveLoading = false;
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

