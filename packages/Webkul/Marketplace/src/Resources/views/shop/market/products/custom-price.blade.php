{!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}
{!! $product->getTypeInstance()->getCustomPrice() !!}
{!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}