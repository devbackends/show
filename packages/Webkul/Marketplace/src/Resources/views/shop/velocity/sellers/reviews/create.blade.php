@extends('marketplace::shop.layouts.master')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.reviews.create-title', ['shop_title' => $seller->shop_title]) }} - {{ $seller->name }}
@endsection

@section('content-wrapper')
    <section class="profile-container review">
        @include('marketplace::shop.velocity.sellers.left-profile')

        <div class="profile-right-block review-layouter">

            {!! view_render_event('marketplace.shop.sellers.reviews.create.before') !!}

            <div class="review-form">

                <form class="form-group" method="POST" action="{{ route('marketplace.reviews.store', $seller->url) }}" @submit.prevent="onSubmit">

                    @csrf

                    <div class="heading mt-10 mb-30">
                        <span>{{ __('marketplace::app.shop.sellers.reviews.write-review') }}</span>
                    </div>

                    <div class="form-container">

                        <div class="rating control-group form-group" :class="[errors.has('rating') ? 'has-error' : '']">
                            <label class="required">{{ __('marketplace::app.shop.sellers.reviews.rating') }}</label>

                            <div class="stars">
                                <span class="star star-5" for="star-5" onclick="calculateRating(id)" id="1"></span>
                                <span class="star star-4" for="star-4" onclick="calculateRating(id)" id="2"></span>
                                <span class="star star-3" for="star-3" onclick="calculateRating(id)" id="3"></span>
                                <span class="star star-2" for="star-2" onclick="calculateRating(id)" id="4"></span>
                                <span class="star star-1" for="star-1" onclick="calculateRating(id)" id="5"></span>
                            </div>

                            <input type="hidden" id="rating" name="rating" v-validate="'required'">

                            <div class="control-error" v-if="errors.has('rating')">@{{ errors.first('rating') }}</div>
                        </div>

                        <div class="control-group form-group" :class="[errors.has('comment') ? 'has-error' : '']">
                            <label for="comment" class="required">{{ __('marketplace::app.shop.sellers.reviews.comment') }}</label>
                            <textarea type="text" class="control" name="comment" v-validate="'required'" value="{{ old('comment') }}"  data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.reviews.comment') }}&quot;">
                            </textarea>
                            <span class="control-error" v-if="errors.has('comment')">@{{ errors.first('comment') }}</span>
                        </div>

                        <button type="submit" class="btn btn-lg theme-btn">
                            {{ __('shop::app.reviews.submit') }}
                        </button>

                    </div>

                </form>

            </div>

            {!! view_render_event('marketplace.shop.sellers.reviews.create.after') !!}

        </div>

    </section>

@endsection


@push('scripts')

    <script>

        function calculateRating(id) {
            var a = document.getElementById(id);
            document.getElementById("rating").value = id;

            for (let i=1 ; i <= 5 ; i++) {
                document.getElementById(i).style.color = id >= i ? "#242424" : "#d4d4d4";
            }
        }

    </script>

@endpush