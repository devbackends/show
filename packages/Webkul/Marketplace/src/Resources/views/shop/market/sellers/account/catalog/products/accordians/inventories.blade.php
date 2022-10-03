@php array_push($GLOBALS['groups'],['id'=>'inventory_box','title'=>'Inventories']); @endphp
        <div class="create-product__box" id="inventory_box">
            <span id="inventories_box" class="create-product__box-spy-anchor"></span>
            <p class="create-product__box-title">Inventory</p>
            @if ($product->type != 'configurable' && $product->type != 'booking')
            {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.inventories.controls.before', ['product' => $product]) !!}

            <?php $productFlat = app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['product_id'=>$product->id])->first();  ?>

            <input type="hidden" name="vendor_id" value="{{ $productFlat->marketplace_seller_id }}">

            <?php $qty = old('quantity') ?: $productFlat->quantity; ?>

            <div class="form-group" :class="[errors.has('quantity') ? 'has-error' : '']">
                <label>Inventory</label>

                <input type="text" v-validate="'numeric|min:0'" name="quantity" class="form-control" value="{{ $qty }}" data-vv-as="&quot;Inventory&quot;" />

                <span class="control-error" v-if="errors.has('quantity')">@{{ errors.first('quantity') }}</span>
            </div>


            @endif
            @foreach ($product->attribute_family->attribute_groups as $attributeGroup)
                @php $customAttributes = $product->getEditableAttributes($attributeGroup); @endphp
                @if (count($attributeGroup->custom_attributes))
                    @if (strtolower($attributeGroup->name) == "inventory")

                        @foreach ($attributeGroup->custom_attributes as $attribute)

                            @if (! $product->super_attributes->contains($attribute))
                                <?php
                                if ($attribute->code == 'guest_checkout' || $attribute->code == 'tax_category_id') {
                                    continue;
                                }
                                $validations = [];
                                $disabled = false;
                                if ($product->type == 'configurable' && in_array($attribute->code, ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'width', 'height', 'depth', 'weight'])) {
                                    if (!$attribute->is_required)
                                        continue;

                                    $disabled = true;
                                } else {
                                    if ($attribute->is_required) {
                                        array_push($validations, 'required');
                                    }

                                    if ($attribute->type == 'price') {
                                        array_push($validations, 'decimal');
                                    }

                                    array_push($validations, $attribute->validation);
                                }

                                $validations = implode('|', array_filter($validations));

                                ?>

                                @if (view()->exists($typeView = 'shop::sellers.account.catalog.products.field-types.' . $attribute->type))
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group {{ $attribute->type }}"
                                                 :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                                                @if($attribute->type=='text')
                                                    <label for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=form-label-required' : '' }}>{{ $attribute->admin_name }}</label>
                                                    @if($attribute->code=='sku')
                                                        <i data-toggle="tooltip" data-placement="top" title="" class="fas fa-question-circle form-tooltip-icon px-2" data-original-title="A stock-keeping unit (SKU) is a unique code, most often seen printed on product labels is a retail store. The unique code allows vendors to automatically track the movement of inventory."></i>
                                                    @endif
                                                    <div class="input-group">
                                                        @include ($typeView)
                                                    </div>
                                                    @if($attribute->code=='sku')
                                                        <input type="hidden" id="auto_generated_sku" value="{{$product[$attribute->code]}}" />
                                                        <div class="custom-control custom-switch pt-3">
                                                            <input type="checkbox" id="auto_generate_sku"  name="auto_generate_sku" v-on:change="validateAutoGenerateSku"  @if($seller->auto_generate_sku) checked @endif
                                                                   class="custom-control-input boolean-input">
                                                            <label for="auto_generate_sku" class="custom-control-label">Auto Generate SKU</label>
                                                        </div>
                                                    @endif
                                                @elseif($attribute->type=='boolean')
                                                    @include ($typeView)
                                                @else
                                                    <label
                                                        for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=form-label-required' : '' }}>{{ $attribute->admin_name }}</label>
                                                    <div class="input-group">
                                                        @include ($typeView)
                                                    </div>

                                                @endif
                                                <span class="control-error"
                                                      v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
                                            </div>

                                        </div>
                                    </div>

                                @endif
                            @endif
                        @endforeach

                    @endif
                @endif
            @endforeach
            {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.inventories.controls.after', ['product' => $product]) !!}
        </div>

