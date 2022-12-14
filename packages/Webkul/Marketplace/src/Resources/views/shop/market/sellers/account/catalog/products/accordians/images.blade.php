<div class="wrap list-group-item">
    <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#collapseProductEditImages" aria-expanded="false" aria-controls="collapseProductEditImages"><span>{{ __('admin::app.catalog.products.images') }}</span> <i class="fal fa-angle-right"></i></a>
    <div id="collapseProductEditImages" class="collapse">
        <div class="inner">
            {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.images.controls.before', ['product' => $product]) !!}

            <div class="control-group {!! $errors->has('images.*') ? 'has-error' : '' !!}">
                <label>{{ __('admin::app.catalog.categories.image') }}</label>

                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images" :images='@json($product->images)'></image-wrapper>

                <span class="control-error" v-if="{!! $errors->has('images.*') !!}">
                    @php $count=1 @endphp
                    @foreach ($errors->get('images.*') as $key => $message)
                    @php echo str_replace($key, 'Image'.$count, $message[0]); $count++ @endphp
                    @endforeach
                </span>
            </div>

            <!-- <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images" :images='@json($product->images)'></image-wrapper> -->

            {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.images.controls.after', ['product' => $product]) !!}
        </div>
    </div>
</div>