@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.review.index.page-title') }}
@endsection

@section('page-detail-wrapper')

    <div class="settings-page">
        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('shop::app.customer.account.review.index.title') }}</p>
            </div>
            <div class="settings-page__header-actions">
            @if (count($reviews) > 1)
            <a href="{{ route('customer.review.deleteall') }}" class="btn btn-outline-gray"><i
                            class="far fa-trash-alt"></i><span>Delete all</span></a>
                            @endif
            </div>
        </div>
        {!! view_render_event('bagisto.shop.customers.account.reviews.list.before', ['reviews' => $reviews]) !!}
        <div class="settings-page__body">
            @if (! $reviews->isEmpty())
                @foreach ($reviews as $review)
                    <div class="row">
                        <div class="col-12 col-xl-9">
                            <div class="card card--review">
                                @php
                                    $image = $productImageHelper->getProductBaseImage($review->product);
                                @endphp
                                <a href="{{ url()->to('/').'/'.$review->product->url_key }}"
                                   title="{{ $review->product->name }}" class="card-img">
                                    <img src="{{ $image['small_image_url'] }}">
                                </a>
                                <div class="card-body">
                                    <div class="card-action" id="modal-container">
                                        <a href="#" data-review="{{$review}}" data-toggle="modal"
                                           data-target="#editReview" class="card-link review-modal">
                                            <i class="far fa-pencil"></i>
                                            <span>Edit</span>
                                        </a>
                                        <a href="{{ route('customer.review.delete', $review->id) }}" class="card-link">
                                            <i class="far fa-trash-alt"></i>
                                            <span>{{ __('shop::app.checkout.cart.remove') }}</span>
                                        </a>
                                    </div>
                                    <div class="card-title">
                                        <a href="{{ url()->to('/').'/'.$review->product->url_key }}"
                                           title="{{ $review->product->name }}">{{$review->product->name}}</a>
                                    </div>
                                    <div class="rate">
                                    <!-- <star-ratings class="black" ratings="{{ $review->rating }}"></star-ratings> -->

                                        <span class="stars">
                                @for($i=1; $i <= 5 ;$i++)
                                                @if($i <= $review->rating )
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                            </span>

                                    </div>
                                    <div class="card-text">
                                        <h5>{{ $review->title }}</h5>
                                        <p>{{ $review->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="bottom-toolbar">
                    {{ $reviews->links()  }}
                </div>
                {{-- <load-more-btn></load-more-btn> --}}
            @else
                <div class="fs16">
                    {{ __('customer::app.reviews.empty') }}
                </div>
            @endif

        </div>
        {!! view_render_event('bagisto.shop.customers.account.reviews.list.after', ['reviews' => $reviews]) !!}
    </div>

    <!-- MODAL EDIT REVIEW -->
    <div class="modal modal--review fade" id="editReview" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                    <div class="modal-head">
                        <i class="far fa-star"></i>
                        <small>Edit review for</small>
                        <h3 id="review-product-name"></h3>{{--here set seller shop title--}}
                        <p>Product bought: <b></b></p> {{--here set product description--}}
                    </div>
                    <form method="POST" action="{{route('shop.reviews.update-review')}}">
                        @csrf
                        <input type="hidden" id="id" name="id"/>
                        <input type="hidden" id="product_id" name="product_id"/>
                        <div class="form-group text-center">
                            <div class="rate">
                            <span class="stars" id="review-modal-rating">
                                <i id="review-rate-1" data-rate="1" class="far fa-star review-rate"></i>
                                <i id="review-rate-2" data-rate="2" class="far fa-star review-rate"></i>
                                <i id="review-rate-3" data-rate="3" class="far fa-star review-rate"></i>
                                <i id="review-rate-4" data-rate="4" class="far fa-star review-rate"></i>
                                <i id="review-rate-5" data-rate="5" class="far fa-star review-rate"></i>
                                <input type="hidden" id="rating" name="rating"/>
                            </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" required name="title" id="title">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" required name="comment" id="comment"></textarea>
                        </div>
                        <div class="text-right">
                            <input type="submit" class="btn btn-primary" value="Save review">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- END MODAL EDIT REVIEW -->

@endsection

@push('scripts')
    <script type="text/x-template" id="load-more-template">
        <div class="col-12 row justify-content-center">
            <button type="button" class="btn btn-outline-primary" @click="loadNextPage">Load More</button>
        </div>
    </script>

    <script type="text/javascript">


        (() => {
            Vue.component('load-more-btn', {
                template: '#load-more-template',

                methods: {
                    'loadNextPage': function () {
                        let splitedParamsObject = {};

                        let searchedString = window.location.search;
                        searchedString = searchedString.replace('?', '');

                        let splitedParams = searchedString.split('&');

                        splitedParams.forEach(value => {
                            let splitedValue = value.split('=');
                            splitedParamsObject[splitedValue[0]] = splitedValue[1];
                        });

                        splitedParamsObject[page]
                    },

                }
            })

        })()


    </script>
@endpush
@push('scripts')
    <script>
        $(function () {
            $(document).on('click', '.review-modal', function () {
                var review = $(this).data('review');
                $('#id').val(review.id);
                $('#title').val(review.title);
                $('#comment').val(review.comment);
                $('#review-product-name').html(review.product.name);
                $('#rating').val(review.rating);
                $('#product_id').val(review.product_id);
                for (i = 1; i < review.rating + 1; i++) {
                    $('#review-rate-' + i).removeClass('far');
                    $('#review-rate-' + i).addClass('fas');
                }
            });
            $(document).on('click', '.review-rate', function () {
                var rate = $(this).data('rate');
                $('#rating').val(rate);
                $('.review-rate').removeClass('fas');
                $('.review-rate').addClass('far');
                for (i = 1; i <= rate; i++) {
                    $('#review-rate-' + i).removeClass('far');
                    $('#review-rate-' + i).addClass('fas');
                }

            });
        });

    </script>
@endpush