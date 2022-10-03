{!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

{!! $product->getTypeInstance()->getPriceHtmlWithCurrency() !!}

{!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}