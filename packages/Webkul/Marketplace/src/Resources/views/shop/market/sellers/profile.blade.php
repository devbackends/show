@extends('marketplace::shop.layouts.master')

@section('page_title')
    {{ $seller->shop_title }}
@stop

@section('seo')
    <meta name="description"
          content="{{ trim($seller->meta_description) != "" ? $seller->meta_description : str_limit(strip_tags($seller->description), 120, '') }}"/>
    <meta name="keywords" content="{{ $seller->meta_keywords }}"/>
@stop
@php $isDisplayMode=true; @endphp
@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@section('content-wrapper')

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
                        <h3>Contact {{$seller->shop_title}}</h3>
                    </div>
                    <contact-seller-form></contact-seller-form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CONTACT SELLER -->
    <?php $reviews = app('Webkul\Marketplace\Repositories\ReviewRepository')->getRecentReviews($seller->id);

    ?>

    <!-- SELLER STORE SECTION -->
    @include('marketplace::shop.sellers.left-profile')
    <!-- END SELLER STORE SECTION -->

    @include('marketplace::shop.instructors.profile')

    

    <seller-advanced-search-component></seller-advanced-search-component>


    <div class="container border-top pt-4 pb-5">
        <div class="row mx-n3 justify-content-center">
            <div class="col-auto px-2">
                <button type="button" class="btn btn-outline-black" data-toggle="modal" data-target="#modalReturnPolicy">
                    Return Policy
                </button>
            </div>
            <div class="col-auto px-2">
                <button type="button" class="btn btn-outline-black" data-toggle="modal" data-target="#modalShippingPolicy">
                    Shipping policy
                </button>
            </div>
            <div class="col-auto px-2">
                <button type="button" class="btn btn-outline-black" data-toggle="modal" data-target="#modalPrivacyPolicy">
                    Privacy policy
                </button>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="modalReturnPolicy" tabindex="-1" aria-labelledby="modalReturnPolicyLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReturnPolicyLabel">Return Policy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($seller->return_policy)
                    {!! $seller->return_policy !!}
                @else
                    No return policy set up
                @endif
                
            </div>
            </div>
        </div>
        </div>
        <!-- End Modal -->

        <!-- Modal -->
        <div class="modal fade" id="modalShippingPolicy" tabindex="-1" aria-labelledby="modalShippingPolicyLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalShippingPolicyLabel">Shipping policy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($seller->shipping_policy)
                    {!! $seller->shipping_policy !!}
                @else
                    No shipping policy set up
                @endif
                
            </div>
            </div>
        </div>
        </div>
        <!-- End Modal -->


        <!-- Modal -->
        <div class="modal fade" id="modalPrivacyPolicy" tabindex="-1" aria-labelledby="modalPrivacyPolicyLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPrivacyPolicyLabel">Privacy Policy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($seller->privacy_policy)
                    {!! $seller->privacy_policy !!}
                @else
                    No privacy policy set up
                @endif
                
            </div>
            </div>
        </div>
        </div>
        <!-- End Modal -->


    </div>



    @push('scripts')

        <script type="text/x-template" id="seller-advanced-search-template">
            <div class="">
                <div class="container my-0 my-sm-5">
                    <div class="row">
                        <div class="col-sm-3 d-none d-sm-block">
                            @if ($isDisplayMode)
                                @include('shop::products.list.layered-navigation')
                            @endif
                        </div>

                        <div class="col-sm-9 my-4 my-sm-0">
                            <div class="product-list__head mb-4">
                                <div class="row mb-3">
                                    <div class="col">
                                        <h2 class="mb-0"></h2>
                                    </div>
                                    <div class="col-auto pl-0 d-sm-none">
                                        <button class="btn btn-link text-decoration-none p-0 m-0" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                                            <i class="far fa-filter mr-2"></i>Filter
                                        </button>
                                    </div>
                                </div>

                                <div class="w-100 collapse d-sm-none" id="collapseFilter">
                                    @if ($isDisplayMode)
                                        @include('shop::products.list.layered-navigation')
                                    @endif
                                </div>

                                @include ('shop::products.list.toolbar')
                            </div>
                            <div class="">
                                @if ($isDisplayMode)

                                    @if ($toolbarHelper->getCurrentMode() == 'grid')
                                        <shimmer-component v-if="isLoading" shimmer-count="4" class="d-none d-lg-block"></shimmer-component>

                                        <shimmer-component v-if="isLoading" shimmer-count="2" class="d-none d-sm-block d-lg-none"></shimmer-component>

                                        <shimmer-component v-if="isLoading" shimmer-count="1" class="d-block d-sm-none"></shimmer-component>
                                    @else
                                        <shimmer-component v-if="isLoading" list=true shimmer-count="3" class="d-none d-lg-block"></shimmer-component>
                                    @endif

                                    <template v-else-if="products.length > 0 && !isLoading">
                                        @if ($toolbarHelper->getCurrentMode() == 'grid')
                                            <div class="row mx-n1">
                                                <div class="product-item__card col-12 col-sm-6 col-lg-3 px-1"
                                                     :key="index"
                                                     v-for="(product, index) in products">
                                                    <product-card
                                                            :product="product">
                                                    </product-card>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row mx-n1">
                                                <div class="col-12 px-1" v-for="(product, index) in products">
                                                    <product-card
                                                            list=true
                                                            :key="index"
                                                            :product="product">
                                                    </product-card>
                                                </div>
                                            </div>
                                        @endif


                                        {!! view_render_event('marketplace.shop.sellers.products.index.pagination.before') !!}
                                        <div class="bottom-toolbar" v-html="paginationHTML"></div>
                                        {!! view_render_event('marketplace.shop.sellers.products.index.pagination.after') !!}


                                    </template>

                                    <div class="empty" v-else-if="products.length == 0 && !isLoading">
                                        <p class="product-list__items-emtpy-title">{{ __('shop::app.products.whoops') }}</p>
                                        <p>{{ __('shop::app.products.empty') }}</p>
                                    </div>
                                    <!-- <a href="/marketplace-start-selling" class="d-block d-sm-none mt-4">
                                        <img src="/themes/market/assets/images/2a_2020_nov_promo_5.jpg" alt="" class="w-100">
                                    </a> -->
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script>
            Vue.component('seller-advanced-search-component', {
                template: '#seller-advanced-search-template',

                data: () => ({
                    products: [],
                    isLoading: true,
                    paginationHTML: '',
                }),

                mounted() {

                    const pageFilterData=localStorage.getItem('pageFilterData');
                    let x='';
                    if(pageFilterData){
                        const data=JSON.parse(pageFilterData);
                        if(typeof data.seller !='undefined'){
                            if(data.seller=={{$seller->id}}){
                                const currentDate=new Date();
                                const lastFilterDate=new Date(data.time);
                                var Difference_In_Time = currentDate- lastFilterDate;
                                var diffMins = Math.round(((Difference_In_Time % 86400000) % 3600000) / 60000); // minutes
                                if(diffMins < 4){
                                    delete data.time;
                                    delete data.seller;
                                    const qs = Object.keys(data)
                                        .map(key => `${key}=${data[key]}`)
                                        .join('&');
                                    x='?'+qs;
                                }
                            }
                        }
                    }

                    this.getSellerProducts(x);
                    eventBus.$on('reload-products', search => {

                        let s2 = search;
                        if (search[1] == '?') {
                            s2 = search.substring(1);
                        }
                        const x = s2.split("&");
                        for (let i = 0; i < x.length; i++) {
                            const s = x[i].split('=');
                            if (s[0] == 'page') {
                                const pageFilterData = localStorage.getItem('pageFilterData');
                                if (pageFilterData) {
                                    const data = JSON.parse(pageFilterData);
                                    data.page = [parseInt(s[1])];
                                    data.time=new Date();
                                    localStorage.setItem('pageFilterData', JSON.stringify(data));
                                }else{
                                    const data={};
                                    data.page=[parseInt(s[1])];
                                    data.time=new Date();
                                    data.seller={{$seller->id}};
                                    localStorage.setItem('pageFilterData', JSON.stringify(data));
                                }
                            }
                        }

                        this.isLoading = true;
                        this.getSellerProducts(search);
                    });
                },

                methods: {
                    getSellerProducts(search = false) {
                        this.products=[];
                        this.$http.get(`${this.$root.baseUrl}/products/sellerProductAdvancedSearch/{{ $seller->id }}${search ? search : window.location.search}`).then(response => {
                            this.isLoading = false;
                            if(typeof response.data.products.data != 'undefined'){
                                this.products = response.data.products.data;
                                this.paginationHTML = response.data.paginationHTML;
                            }
                        }).catch(error => {
                            this.isLoading = false;
                            console.log(this.__('error.something_went_wrong'));
                        })
                    },
                }
            })
        </script>


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
                    <input type="email" class="form-control" placeholder="{{ __('marketplace::app.shop.sellers.profile.email') }}" v-model="contact.email"
                           name="email" v-validate="'required|email'"
                           data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.email') }}&quot;">
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
                <input type="text" style="display: none" v-model="captcha">
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
                    captcha: '',

                    disable_button: false,
                }),

                template: '#contact-form-template',

                created() {

                    @auth('customer')
                        @if(auth('customer')->user())
                        this.contact.email = "{{ auth('customer')->user()->email }}";
                    this.contact.name = "{{ auth('customer')->user()->first_name }} {{ auth('customer')->user()->last_name }}";
                    @endif
                    @endauth

                },

                methods: {
                    contactSeller(formScope) {
                        var this_this = this;

                        this_this.disable_button = true;

                        if (this.captcha === '') {
                            this.$validator.validateAll(formScope).then((result) => {
                                if (result) {

                                    this.$http.post("{{ route('marketplace.seller.contact', $seller->url) }}", this.contact)
                                        .then(function (response) {
                                            this_this.contact.query='';
                                            this_this.contact.subject='';
                                            this_this.disable_button = false;
                                            this_this.$toast.success('Your message has been sent successfully.', {
                                                position: 'top-right',
                                                duration: 5000,
                                            });

                                            $('#contactSeller').modal('hide');

                                        })

                                        .catch(function (error) {
                                            this_this.disable_button = false;
                                           console.log(error);
                                         //   this_this.handleErrorResponse(error.response, 'contact-form')
                                        })
                                } else {
                                    this_this.disable_button = false;
                                }
                            });
                        } else {
                            $('#contactSeller').modal('hide');
                        }
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

        <script>
            $(document).ready(() => {
                $(document).on('click', '.pagination .page-item', e => {
                    e.preventDefault();
                    var href = e.target.href;
                    if(!href){
                        href = $(e.target).parent()[0].href;
                    }
                    eventBus.$emit('reload-products', '?' + href.split('?')[1]);
                })
            })
        </script>

    @endpush

@endsection