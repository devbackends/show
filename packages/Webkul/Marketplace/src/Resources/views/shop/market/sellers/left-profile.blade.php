<div class="seller-section mb-5">
    @if($banner_url = $seller->banner_url)
        @php $banner = $banner_url; @endphp
    @else
        @php $banner = bagisto_asset('images/seller-default-banner.jpg') @endphp
    @endif
        <div class="seller-section__promo" style="background-image: url('{{$banner}}')"></div>
    <div class="container">
        <div class="seller-section__info">
            <div class="logo">
                @if ($logo = $seller->logo_url)
                    <img src="{{ $logo }}" />
                @else
                    <img src="{{ bagisto_asset('images/seller-default-logo.png') }}" />
                @endif
            </div>
            <div class="seller-section__info-inner">
                <div class="seller-section__head">
                    <h2 class="h3 mb-0">{{ $seller->shop_title }}</h2>
                </div>
                <div class="rate">
                    <?php $reviewRepository = app('Webkul\Marketplace\Repositories\ReviewRepository') ?>

                    <?php $productFlatRepository = app('Webkul\Product\Repositories\ProductFlatRepository') ?>
                    {{ $reviewRepository->getAverageRating($seller) }}
                    <star-rating
                                ratings="{{ ceil($reviewRepository->getAverageRating($seller)) }}"
                                push-class="mx-2"
                                ></star-rating>

                <a class="pt-1 btn btn-link btn-sm border-left" href="{{ route('marketplace.reviews.index', $seller->url) }}">
                    {{
                        __('marketplace::app.shop.sellers.profile.total-rating', [
                                'total_rating' => $reviewRepository->getTotalRating($seller),
                                'total_reviews' => $reviewRepository->getTotalReviews($seller),
                            ])
                    }}
                </a>

                </div>
                @if ($seller->country)
                <div class="location box-location">
                    <i class="far fa-map-marker-alt"></i>
                    <div class="box-location__inner">
                        <p class="font-weight-bold">{{ $seller->city . ', '. $seller->state . ' (' . core()->country_name($seller->country) . ')' }}</p>
                    </div>
                </div>
                @endif

                <div class="social-links">


                    @if ($seller->facebook)
                        <a href="{{ $seller->facebook }}" target="_blank">
                            <i class="icon social-icon mp-facebook-icon"></i>
                        </a>
                    @endif

                    @if ($seller->twitter)
                        <a href="https://twitter.com/{{ $seller->twitter }}" target="_blank">
                            <i class="icon social-icon mp-twitter-icon"></i>
                        </a>
                    @endif

                    @if ($seller->instagram)
                        <a href="https://www.instagram.com/{{$seller->instagram}}" target="_blank"><i class="icon social-icon mp-instagram-icon"></i></a>
                    @endif

                    @if ($seller->linked_in)
                        <a href="{{ $seller->linked_in }}" target="_blank">
                            <i class="icon social-icon mp-linked-in-icon"></i>
                        </a>
                    @endif

                    @if ($seller->youtube)
                        <a href="{{ $seller->youtube }}" target="_blank">
                            <i class="icon social-icon mp-youtube-icon"></i>
                        </a>
                    @endif

                    @if ($seller->parler)
                        <a href="{{ $seller->parler }}" target="_blank">
                            <i class="icon social-icon mp-parler-icon"></i>
                        </a>
                    @endif

                    @if ($seller->gab)
                        <a href="https://gab.com/{{ $seller->gab }}" target="_blank">
                            <i class="icon social-icon mp-gab-icon"></i>
                        </a>
                    @endif
                </div>

            </div>
            <div class="action">
<!--                <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="btn btn-outline-primary">
                    <i class="fal fa-tags"></i>
                    <span>See {{ __('marketplace::app.shop.sellers.profile.count-products', [
                                                'count' => $productFlatRepository->getTotalProducts($seller)
                                            ])
                                        }} from this seller</span>
                </a>-->

                @if($seller->description)

<button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalAboutThisShop">
    About this shop
</button>

<!-- Modal -->
<div class="modal fade" id="modalAboutThisShop" tabindex="-1" aria-labelledby="modalAboutThisShopLabel" aria-hidden="true">
<div class="modal-dialog modal-xl">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalAboutThisShopLabel">About {{ $seller->shop_title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        {!! $seller->description!!}
    </div>
    </div>
</div>
</div>
<!-- End Modal -->

@endif


                @if (auth()->guard('customer')->user())
                <a href="#" class="btn btn-outline-primary ml-0 ml-sm-3 mt-3 mt-sm-0" data-toggle="modal" data-target="#contactSeller">
                    <i class="fal fa-comment-alt-lines"></i>
                    <span>Contact @if($seller->nra_certified || $seller->uscca_certified || $seller->general_events_certified) instructor @else seller @endif </span>
                </a>
                @else
                <a href="#" class="btn btn-outline-primary ml-0 ml-sm-3 mt-3 mt-sm-0" data-toggle="tooltip" data-placement="top" title="Please log in or register to contact this seller"><i class="fal fa-comment-alt-lines"></i><span>Contact seller</span></a>
                @endif

            </div>
        </div>
    </div>
</div>




      <!-- MODAL CONTACT SELLER -->
      <div class="modal fade" id="contactSeller" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="fal fa-times"></i>
              </button>
              <div class="modal-head">
                <i class="fal fa-comment-alt-lines"></i>
                <h3>{{ __('marketplace::app.shop.sellers.profile.contact-seller') }}</h3>
              </div>
              <contact-seller-form></contact-seller-form>
            </div>
          </div>
        </div>
      </div>
      <!-- MODAL CONTACT SELLER -->


@push('scripts')

    <script type="text/x-template" id="contact-form-template">

        <form action="" class="account-table-content" method="POST" data-vv-scope="contact-form" @submit.prevent="contactSeller('contact-form')">

            @csrf

            <div>

                <div class="form-group" :class="[errors.has('contact-form.name') ? 'has-error' : '']">
                    <input type="text" v-model="contact.name" class="form-control" name="name" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.name') }}&quot;" placeholder="{{ __('marketplace::app.shop.sellers.profile.name') }}">
                    <span class="control-error" v-if="errors.has('contact-form.name')">@{{ errors.first('contact-form.name') }}</span>
                </div>

                <div class="form-group" :class="[errors.has('contact-form.email') ? 'has-error' : '']">
                    <input type="text" v-model="contact.email" class="form-control" name="email" v-validate="'required|email'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.email') }}&quot;" placeholder="{{ __('marketplace::app.shop.sellers.profile.email') }}">
                    <span class="control-error" v-if="errors.has('contact-form.email')">@{{ errors.first('contact-form.email') }}</span>
                </div>

                <div class="form-group" :class="[errors.has('contact-form.subject') ? 'has-error' : '']">
                    <input type="text" v-model="contact.subject" class="form-control" name="subject" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.subject') }}&quot;" placeholder="{{ __('marketplace::app.shop.sellers.profile.subject') }}">
                    <span class="control-error" v-if="errors.has('contact-form.subject')">@{{ errors.first('contact-form.subject') }}</span>
                </div>

                <div class="form-group" :class="[errors.has('contact-form.query') ? 'has-error' : '']">
                    <textarea class="form-control" v-model="contact.query" name="query" v-validate="'required'"  data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.query') }}&quot;" placeholder="{{ __('marketplace::app.shop.sellers.profile.query') }}">
                    </textarea>
                    <span class="control-error" v-if="errors.has('contact-form.query')">@{{ errors.first('contact-form.query') }}</span>
                </div>

                <button type="submit" class="btn btn-primary" :disabled="disable_button">
                    {{ __('marketplace::app.shop.sellers.profile.submit') }}
                </button>

            </div>

        </form>

    </script>

    <script>
        Vue.component('contact-seller-form', {

            data: () => ({
                contact: {
                    'name': '',
                    'email': '',
                    'subject': '',
                    'query': ''
                },

                disable_button: false,
            }),

            template: '#contact-form-template',

            created () {

                @auth('customer')
                    @if(auth('customer')->user())
                        this.contact.email = "{{ auth('customer')->user()->email }}";
                        this.contact.name = "{{ auth('customer')->user()->first_name }} {{ auth('customer')->user()->last_name }}";
                    @endif
                @endauth

            },

            methods: {
                contactSeller (formScope) {
                    var this_this = this;

                    this_this.disable_button = true;

                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {

                            this.$http.post ("{{ route('marketplace.seller.contact', $seller->url) }}", this.contact)
                                .then (function(response) {
                                    this_this.disable_button = false;

                                    $(".modal").modal('hide');


                                    window.flashMessages = [{
                                        'type': 'alert-success',
                                        'message': response.data.message
                                    }];

                                    this_this.$root.addFlashMessages()
                                })

                                .catch (function (error) {
                                    this_this.disable_button = false;

                                    this_this.handleErrorResponse(error.response, 'contact-form')
                                })
                        } else {
                            this_this.disable_button = false;
                        }
                    });
                },

                handleErrorResponse (response, scope) {
                    if (response.status == 422) {
                        serverErrors = response.data.errors;
                        this.$root.addServerErrors(scope)
                    }
                }
            }
        });

    </script>
@endpush