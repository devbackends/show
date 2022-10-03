@extends('marketplace::admin.layouts.content')

@section('page_title')
    {{ __('marketplace::app.admin.sellers.search-product') }}
@stop

@section('content-wrapper')
    <div class="content" style="margin-left: 20px; margin-right: 20px;">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                    {{ __('marketplace::app.admin.sellers.search-product') }}
                </h1>
            </div>
        </div>

        <div class="page-content">
            <div class="form-container">
                <accordian :title="'{{ __('marketplace::app.admin.sellers.search') }}'" :active="true">
                    <div slot="body">

                        <product-search></product-search>

                    </div>
                </accordian>
            </div>
        </div>

    </div>
@stop

@push('scripts')

    <script type="text/x-template" id="product-search-template">

        <div class="control-group">
            <label for="search">{{ __('marketplace::app.shop.sellers.account.catalog.products.search') }}</label>
            <input type="text" class="control dropdown-toggle" name="search" placeholder="{{ __('marketplace::app.shop.sellers.account.catalog.products.search-term') }}" autocomplete="off" v-model.lazy="term" v-debounce="500"/>

            <div class="dropdown-list bottom-left product-search-list" style="top: 68px; width: 70%;">
                <div class="dropdown-container">
                    <ul>
                        <li v-if="products.length" class="table">
                            <table>
                                <tbody>
                                    <tr v-for='(product, index) in products'>
                                        <td>
                                            <img v-if="!product.base_image" src="{{ bagisto_asset('images/Default-Product-Image.png') }}" />
                                            <img v-if="product.base_image" :src="product.base_image" style="
                                            height: 40px;
                                            width: 40px;
                                        "/>
                                        </td>
                                        <td>
                                            @{{ product.name }}
                                        </td>
                                        <td>
                                            @{{ product.formated_price }}
                                        </td>
                                        <td class="last">
                                            <a :href="['{{ route('admin.marketplace.seller.product.create', $id) }}/' + product.id ]" class="btn btn-primary btn-sm" style="color: white; margin-top: -10px;">
                                                Assign Product
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>

                        <li v-if="!products.length && term.length > 2 && !is_searching">
                            {{ __('marketplace::app.shop.sellers.account.catalog.products.no-result-found') }}
                        </li>

                        <li v-if="term.length < 3 && !is_searching">
                            {{ __('marketplace::app.shop.sellers.account.catalog.products.enter-search-term') }}
                        </li>

                        <li v-if="is_searching">
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

                        this.$http.get ("{{ route('admin.marketplace.seller.product.search', $id) }}", {params: {query: this.term}})
                            .then (function(response) {
                                this_this.products = response.data;

                                this_this.is_searching = false;
                            })

                            .catch (function (error) {
                                this_this.is_searching = false;
                            })
                    }
                },
            }
        });


    </script>

@endpush