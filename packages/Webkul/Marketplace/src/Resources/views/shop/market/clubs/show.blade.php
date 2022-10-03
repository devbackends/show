@extends('marketplace::shop.layouts.master')

@section('page_title')
    Club
@stop

@section('seo')
    <meta name="description" content="Club"/>
    <meta name="keywords" content="Club"/>
@stop

@section('content-wrapper')
    <!-- HERO SECTION -->
    <div class="hero-section" style="background-image: url(/themes/market/assets/images/hero_show.jpg);">
        <div class="container">
            <a href="{{route('marketplace.clubs.index')}}" class="hero-section__link"><i class="fal fa-angle-left"></i>Back to all Clubs</a>
            <h1 class="h1 hero-section__title">{{$clubData['club_name']}}</h1>
        </div>
    </div>
    <!-- END HERO SECTION -->
    <div class="shows-detail">
        <div class="container">
            <div class="modal fade" id="contactSeller" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                            <div class="modal-head">
                                <i class="fal fa-comment-alt-lines"></i>
                                <h3>Contact </h3>
                            </div>
                            <contact-seller-form></contact-seller-form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label>Description</label>
                        <p class="font-paragraph-big-bold">{{$clubData['org_desc']}}</p>
                    </div>
                </div>
                <div class="col-12 col-md-3">

                    <div class="form-group">
                        <label>Phone</label>
                        <p>{{$clubData['phone']}}</p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label>Address</label>
                        <p>{{$clubData['location']}}</p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        @if($clubData['email'])
                            <a href="#" class="action btn btn-outline-primary" data-toggle="modal" data-target="#contactSeller">
                                <i class="far fa-comment-alt-lines mr-2"></i>Contact                            </a>
                        @endif


                    </div>

                    <div>
                        @if($clubData['web'])
                            <a href="{{$clubData['web']}}" target="_blank" class="btn btn-outline-primary">Visit website</a>
                        @else

                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-9">
                    <iframe width="100%" height="100%" style="min-height: 360px;" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo $clubData['map_location']; ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
                <div class="col-12 col-md-3 mt-3 mt-md-0">
                    <a href="/marketplace/start-selling">
                        <img src="/themes/market/assets/images/2a_2020_nov_promo_6.jpg" alt="" class="w-100">
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('shop::home.recent-products')
    @include('shop::home.uscca-link')
@endsection

@push('scripts')
    <script type="text/x-template" id="contact-form-template">
        <form method="POST" data-vv-scope="contact-form"
              @submit.prevent="contactSeller('contact-form')">
            <div class="form-group" :class="[errors.has('contact-form.name') ? 'has-error' : '']">
                <input type="text" class="form-control" placeholder="{{ __('marketplace::app.shop.sellers.profile.name') }}" v-model="contact.name"
                       name="name" v-validate="'required'"
                       data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.name') }}&quot;">
                <span class="control-error" v-if="errors.has('contact-form.name')">@{{ errors.first('contact-form.name') }}</span>
            </div>
            <div class="form-group" :class="[errors.has('contact-form.email') ? 'has-error' : '']">
                <!-- <label for="email" class="required mandatory">{{ __('marketplace::app.shop.sellers.profile.email') }}</label> -->
                <input type="text" v-model="contact.email" class="form-control" name="email" v-validate="'required|email'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.email') }}&quot;" placeholder="{{ __('marketplace::app.shop.sellers.profile.email') }}">
                <span class="control-error" v-if="errors.has('contact-form.email')">@{{ errors.first('contact-form.email') }}</span>
            </div>
            <div class="form-group"
                 :class="[errors.has('contact-form.subject') ? 'has-error' : '']">
                <input type="text" class="form-control" placeholder="{{ __('marketplace::app.shop.sellers.profile.subject') }}"
                       v-model="contact.subject" name="subject" v-validate="'required'"
                       data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.subject') }}&quot;">
                <span class="control-error" v-if="errors.has('contact-form.subject')">@{{ errors.first('contact-form.subject') }}</span>
            </div>
            <div class="form-group" :class="[errors.has('contact-form.query') ? 'has-error' : '']">
                                    <textarea class="form-control" placeholder="{{ __('marketplace::app.shop.sellers.profile.query') }}" v-model="contact.query"
                                              name="query" v-validate="'required'"
                                              data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.query') }}&quot;"></textarea>
                <span class="control-error" v-if="errors.has('contact-form.query')">@{{ errors.first('contact-form.query') }}</span>
            </div>
            <div class="text-right">
                <input type="submit" class="btn btn-primary" value="Send message" :disabled="disable_button">
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

        created() {



        },

        methods: {
            contactSeller(formScope) {
                var this_this = this;

                this_this.disable_button = true;

                this.$validator.validateAll(formScope).then((result) => {
                    if (result) {

                        this.$http.post("{{ route('marketplace.club.contact', $clubData['id']) }}", this.contact)
                            .then(function (response) {
                                this_this.disable_button = false;

                                $('#contactSeller').modal('hide');

                                window.flashMessages = [{
                                    'type': 'alert-success',
                                    'message': response.data.message
                                }];

                                this_this.$root.addFlashMessages()
                            })

                            .catch(function (error) {
                                this_this.disable_button = false;

                                this_this.handleErrorResponse(error.response, 'contact-form')
                            })
                    } else {
                        this_this.disable_button = false;
                    }
                });
            },

            handleErrorResponse(response, scope) {
                if (response.status == 422) {
                    serverErrors = response.data.errors;
                    this.$root.addServerErrors(scope)
                }
            }
        }
    });

</script>
@endpush