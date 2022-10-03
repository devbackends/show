@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.reviews.add-review-page-title') }} - {{ $product->name }}
@endsection

@section('content-wrapper')

    <div class="container review-page-container py-5">
        <section class="row mb-5">
            <div class="col-12 col-md-4">
                @include ('shop::products.view.small-view', ['product' => $product])
            </div>
            <div class="col-12 col-md-8">
                <a href="{{ route('shop.product.index', $product->url_key) }}" class="d-block mb-4">
                    <h2>{{ $product->name }}</h2>
                </a>
                <div>
                    <h3 class="mb-4">
                        {{ __('shop::app.reviews.write-review') }}
                    </h3>

                    <form
                        method="POST"
                        class="review-form"
                        @submit.prevent="onSubmit"
                        action="{{ route('shop.reviews.store', $product->product_id ) }}">

                        @csrf

                        <div :class="`${errors.has('rating') ? 'has-error' : ''}`" class="form-group">
                            <label for="title" class="required">
                                {{ __('admin::app.customers.reviews.rating') }}
                            </label>
                            <star-rating ratings="5" size="24" editable="true"></star-rating>
                            <span :class="`control-error ${errors.has('rating') ? '' : 'hide'}`" v-if="errors.has('rating')">
                                @{{ errors.first('rating') }}
                            </span>
                        </div>

                        <div :class="`${errors.has('title') ? 'has-error' : ''}`" class="form-group">
                            <label for="title" class="required">
                                {{ __('shop::app.reviews.title') }}
                            </label>
                            <input
                                type="text"
                                name="title"
                                class="form-control"
                                v-validate="'required'"
                                value="{{ old('title') }}" />

                            <span :class="`control-error ${errors.has('title') ? '' : 'hide'}`">
                                @{{ errors.first('title') }}
                            </span>
                        </div>

                        @if (core()->getConfigData('catalog.products.review.guest_review') && ! auth()->guard('customer')->user())
                            <div :class="`${errors.has('name') ? 'has-error' : ''}`" class="form-group">
                                <label for="title" class="required">
                                    {{ __('shop::app.reviews.name') }}
                                </label>
                                <input  type="text" class="form-control" name="name" v-validate="'required'" value="{{ old('name') }}">
                                <span :class="`control-error ${errors.has('name') ? '' : 'hide'}`">
                                    @{{ errors.first('name') }}
                                </span>
                            </div>
                        @endif

                        <div :class="`${errors.has('comment') ? 'has-error' : ''}`" class="form-group">
                            <label for="comment" class="required">
                                {{ __('admin::app.customers.reviews.comment') }}
                            </label>
                            <textarea
                                type="text"
                                class="form-control"
                                name="comment"
                                v-validate="'required'"
                                value="{{ old('comment') }}">
                            </textarea>
                            <span :class="`control-error ${errors.has('comment') ? '' : 'hide'}`">
                                @{{ errors.first('comment') }}
                            </span>
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="btn btn-primary">
                                {{ __('velocity::app.products.submit-review') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <div class="row">
            @if ($showRecentlyViewed)
                @include ('shop::products.list.recently-viewed', [
                                                'quantity'          => 4,'addClass' => 'col-12'
                ])
            @endif
        </div>
    </div>

@endsection
