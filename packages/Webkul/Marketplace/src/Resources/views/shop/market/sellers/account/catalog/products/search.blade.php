@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.catalog.products.list-new-product-title') }}
@endsection

@section('content')

    <div class="account-layout">

        <div class="account-head">
        <h1 class="h3">{{ __('marketplace::app.shop.sellers.account.catalog.products.list-new-product-title') }}</h1>

            <div class="account-action">
            </div>
        </div>

        {!! view_render_event('marketplace.sellers.account.catalog.products.search.before') !!}

        <div class="account-items-list">
            <div class="row">
                <div class="col-md-8 mb-4 mb-md-0">
                    <div class="pr-md-5 create-product__search-form">
                        <p><strong>{{ __('marketplace::app.shop.sellers.account.catalog.products.assign-search-title') }}</strong></p>
                        <p>{{ __('marketplace::app.shop.sellers.account.catalog.products.assign-search-product') }}</p>
                        <product-search></product-search>
                    </div>
                </div>
                <div class="col-md-4">
                    <p><strong>{{ __('marketplace::app.shop.sellers.account.catalog.products.create-new-title') }}</strong></p>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#whatAreYouSelling">{{ __('marketplace::app.shop.sellers.account.catalog.products.create-new-button') }}</button>
                </div>
            </div>
        </div>

        {!! view_render_event('marketplace.sellers.account.catalog.products.search.after') !!}

    </div>

    <!-- MODAL What are you going to sell  -->
    <div class="modal normal fade" id="whatAreYouSelling" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-head">
                        <i class="far fa-exclamation-triangle"></i>
                        <h3 class="mb-3">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-selling-firearms-title') }}</h3>
                        <p>{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-selling-firearms-text') }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-primary" style="min-width: 90px; justify-content: center;" data-toggle="modal" data-target="#createNow" data-dismiss="modal">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-selling-firearms-no') }}</button>
                        <button type="button" class="btn btn-primary" style="min-width: 90px; justify-content: center;" data-toggle="modal" data-target="#comingSoon" data-dismiss="modal">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-selling-firearms-yes') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODAL What are you going to sell -->

    <!-- MODAL Got it  -->
    <div class="modal normal fade" id="comingSoon" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-head">
                        <i class="far fa-clock"></i>
                        <h3 class="mb-3">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-got-it-title') }}</h3>
                        <p>{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-got-it-text') }}</p>
                        <p class="font-weight-bold">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-got-it-promise') }}</p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-got-it-ok') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODAL Got it -->

    <!-- MODAL Create Product NOW  -->
    <div class="modal normal fade" id="createNow" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-head">
                        <i class="far fa-tag"></i>
                        <p>{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-create-now-title') }}</p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <a href="{{route('marketplace.account.products.create')}}" class="btn btn-primary btn-lg">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-create-now-button') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODAL Create Product NOW -->

@endsection

@push('scripts')

    <script type="text/x-template" id="product-search-template">

        <div class="form-group">
            <label for="search"><strong>{{ __('marketplace::app.shop.sellers.account.catalog.products.search') }}</strong></label>
            <input type="text" class="form-control" name="search" placeholder="{{ __('marketplace::app.shop.sellers.account.catalog.products.search-term') }}" autocomplete="off" v-model.lazy="term" v-debounce="500"/>

            <div class="dropdown-list bottom-left product-search-list">
                <div class="dropdown-container">
                    <ul type="none">
                        <li v-if="products.length" class="table">
                            <table>
                                <tbody>
                                    <tr v-for='(product, index) in products'>
                                        <td>
                                            <img v-if="!product.base_image" src="{{ bagisto_asset('images/Default-Product-Image.png') }}"/>
                                            <img v-if="product.base_image" :src="product.base_image"/>
                                        </td>
                                        <td>
                                            @{{ product.name }}
                                        </td>
                                        <td>
                                            @{{ product.formated_price }}
                                        </td>
                                        <td class="last">
                                            <a :href="['{{ route('marketplace.account.products.assign') }}/' + product.id ]" class="btn btn-primary">
                                                Sell yours
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>

                        <li v-if="!products.length && term.length > 2 && !is_searching" class="mt-3">
                            {{ __('marketplace::app.shop.sellers.account.catalog.products.no-result-found') }}
                        </li>

                        <li v-if="term.length < 3 && !is_searching" class="mt-3">
                            {{ __('marketplace::app.shop.sellers.account.catalog.products.enter-search-term') }}
                        </li>

                        <li v-if="is_searching" class="mt-3">
                            {{ __('marketplace::app.shop.sellers.account.catalog.products.searching') }}
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </script>

    <script>

        Vue.component('product-search', {

            template: '#product-search-template',

            data: () => ({
                products: [],

                term: "",

                is_searching: false
            }),

            watch: {
                'term': function(newVal, oldVal) {
                    this.search()
                }
            },

            methods: {
                search () {
                    if (this.term.length > 2) {
                        this_this = this;

                        this.is_searching = true;

                        this.$http.get ("{{ route('marketplace.account.products.search') }}", {params: {query: this.term}})
                            .then (function(response) {
                                console.log("d", response);

                                this_this.products = response.data;

                                this_this.is_searching = false;
                            })

                            .catch (function (error) {
                                console.log("sd", error);
                                this_this.is_searching = false;
                            })
                    }
                },
            }
        });
    </script>

@endpush