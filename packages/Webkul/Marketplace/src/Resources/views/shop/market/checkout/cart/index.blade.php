@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.cart.title') }}
@stop

@section('content-wrapper')
@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

<carts-wrapper></carts-wrapper>

@endsection
