@if ($product->type != 'configurable')
<accordian :title="'{{ __('admin::app.catalog.products.inventories') }}'" :active="true">
    <div slot="body">

        {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.inventories.controls.before', ['product' => $product]) !!}

        <?php $product =app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['product_id'=>$product->id])->first();  ?>
        
        <input type="hidden" name="vendor_id" value="{{ $product->marketplace_seller_id }}">


            <?php
                $qty = 0;


                $qty = old('quantity') ?: $qty;

            ?>

            <div class="control-group" :class="[errors.has('quantity') ? 'has-error' : '']">
                <label>Inventory</label>

                <input type="text" v-validate="'numeric|min:0'" name="quantity" class="control" value="{{ $qty }}" data-vv-as="&quot;quantity&quot;"/>
                
                <span class="control-error" v-if="errors.has('quantity')">@{{ errors.first('quantity') }}</span>
            </div>
        


        {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.inventories.controls.after', ['product' => $product]) !!}

    </div>
</accordian>
@endif