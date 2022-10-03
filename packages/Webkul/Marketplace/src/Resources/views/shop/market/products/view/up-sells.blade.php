@if ($sellerProducts->count())
    <div class="list-group list-group-flush product-thumb__list">
        @foreach ($sellerProducts as $index => $sellerProduct)
            <div class="list-group-item product-thumb__list-item">
                <div class="row">
                    <div class="col-md-6 product-thumb">
                        <div class="product-thumb__image" style="max-width: 20%">
                            <img src="{{$image['medium_image_url']}}" alt="Product image">
                            <span class="preferred"></span>
                        </div>
                        <div class="product-thumb__info">
                            <a class="name">{{$sellerProduct->seller->shop_title}}</a>
                            <div class="rate">
                                <span class="stars">
                                  @for($i = 0; $i < $product->rate['avgStarRating']; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @for($i = 0; $i < (5 - $product->rate['avgStarRating']); $i++)
                                        <i class="fal fa-star"></i>
                                    @endfor
                                </span>
                                <span>{{$product->rate['avgRatings']}} ({{$product->rate['total']}} ratings)</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 labels">
                        @if($sellerProduct->condition == 'new')
                            <small class="text-success">NEW</small>
                        @endif
                        <span class="h3">{{core()->currency($sellerProduct->price)}}</span>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <add-to-cart
                            csrf-token='{{ csrf_token() }}'
                            product-flat-id="{{ $sellerProduct->product_id }}"
                            product-id="{{ $sellerProduct->product_id }}"
                            reload-page="{{false}}"
                            move-to-cart="{{false}}"
                            is-enable="{{ ! $product->isSaleable() ? 'false' : 'true' }}"
                            btn-text="{{ (!$product->isSaleable()) ? __('shop::app.products.out-of-stock') : $btnText ?? __('shop::app.products.add-to-cart') }}"
                            ui="primary"
                            :seller-info="{
                                productId: '{{$sellerProduct->id}}',
                                sellerId: '{{$sellerProduct->marketplace_seller_id}}',
                                isOwner: '{{$sellerProduct->is_owner}}',
                            }">
                        </add-to-cart>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif