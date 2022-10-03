@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.catalog.products.search-title') }}
@endsection

@section('content')

    <div class="account-layout">

        <div class="account-head mb-10">
            <span class="account-heading">
                {{ __('marketplace::app.shop.sellers.account.catalog.products.search-title') }}
            </span>

            <div class="account-action">
            </div>

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('marketplace.sellers.account.catalog.products.search.before') !!}

        <div class="account-items-list">

            <div class="info">
                {!!
                    __('marketplace::app.shop.sellers.account.catalog.products.assign-info', [
                            'create_link' => '<a href="' . route('marketplace.account.products.create') . '">' . __('marketplace::app.shop.sellers.account.catalog.products.create-new') . '</a>'
                        ])
                !!}
            </div>

            <div class="form-container" style="margin-top: 40px">

                <product-search></product-search>

            </div>

        </div>

        {!! view_render_event('marketplace.sellers.account.catalog.products.search.after') !!}

    </div>

@endsection

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
                                            <a :href="['{{ route('marketplace.account.products.assign') }}/' + product.id ]" class="btn btn-primary btn-sm">
                                                Sell yours
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

                        this.$http.get ("{{ route('marketplace.account.products.search') }}", {params: {query: this.term}})
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