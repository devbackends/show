@inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')
@inject ('attributeRepository', 'Webkul\Attribute\Repositories\AttributeRepository')
@inject ('productFlatRepository', 'Webkul\Product\Repositories\ProductFlatRepository')

<?php
$filterAttributes = [];
if(!isset($showSearch)){
    $showSearch=true;
}
if (isset($category)) {
    $products = $productRepository->getAll($category->id);
    $filterAttributes=$category->filterableAttributesWithOptionsAndProductsAmount($category->id);
    $attributes=$filterAttributes['attributes'];
    $categories=$filterAttributes['categories'];
    $sellers=$filterAttributes['sellers'];

}elseif(isset($_GET['type']) && isset($_GET['search'])){
    $attributes=[];
    $categories=[];
    $sellers=[];
    if($_GET['type']!='product'){
        $showSearch=false;
    }else{
        $filterAttributes=$productFlatRepository->getFilterableAttributesWithOptionsAndProductsAmountForSearchPage($_GET['type'],$_GET['search']);
        $attributes=$filterAttributes['attributes'];
    }
}else{
    if (!count($filterAttributes) > 0) {
        $sellerId = 0;
        if (isset($seller) && $seller) {
            $sellerId = $seller->id;
        }
        $filterAttributes = $attributeRepository->getFilterAttributesWithOptionsAndProductsAmount($sellerId);
        $attributes=$filterAttributes['attributes'];
    }
}



foreach ($filterAttributes as $attribute) {
    //check if it is an attribute or category
    if(isset($attribute->code)){
        //it is an attribute
        if ($attribute->code <> 'price') {
            if (!$attribute->options->isEmpty()) {
                $attributes[] = $attribute;
            }
        } else {
            $attributes[] = $attribute;
        }
    }else{
        //it is a category
        $subcategories[]=$attribute;
    }

}




?>

{!! view_render_event('bagisto.shop.products.list.layered-nagigation.before') !!}

<layered-navigation></layered-navigation>

{!! view_render_event('bagisto.shop.products.list.layered-nagigation.after') !!}

@push('scripts')
    <script type="text/x-template" id="layered-navigation-template">
        <div class="product-list__filter mb-3" >
            <h3 class="product-list__filter-head">{{ __('shop::app.products.layered-nav-title') }}</h3>
            <product-search :latestSearch="typeof appliedFilters['search'] !='undefined' ? appliedFilters['search'][0] : ''" :latestCompatibleWith="typeof appliedFilters['compatibleWith'] !='undefined' ? appliedFilters['compatibleWith'][0] : ''" v-if="showSearch"></product-search>
            <div class="accordion list-group list-group-flush list-group-accordion" id="filter-list">

                <filter-attribute-item
                    attribute="instock"
                    type="instock"
                    :expandFilter="expandFilter"
                    @onFilterAdded="addFilters('isInStockIndex', $event)"
                    :appliedFilterValues="appliedFilters['instock']">
                </filter-attribute-item>

                <filter-attribute-item v-if="categories.length > 0"
                                       v-for='attribute in categories'
                                       :attribute="attribute"
                                       :expandFilter="expandFilter"
                                       type="categories"
                                       @onFilterAdded="addFilters('cat', $event)"
                                       :appliedFilterValues="appliedFilters['cat']">
                </filter-attribute-item>

                <filter-attribute-item v-if="sellers.length > 0"
                                       v-for='attribute in sellers'
                                       :attribute="attribute"
                                       :expandFilter="expandFilter"
                                       type="sellers"
                                       @onFilterAdded="addFilters('marketplace_seller_id', $event)"
                                       :appliedFilterValues="appliedFilters['marketplace_seller_id']">
                </filter-attribute-item>

                <filter-attribute-item v-if="attributes.length > 0"
                    v-for='attribute in attributes'
                    :attribute="attribute"
                    :expandFilter="expandFilter"
                    type="attributes"
                    @onFilterAdded="addFilters(attribute.code, $event)"
                    :appliedFilterValues="appliedFilters[attribute.code]">
                </filter-attribute-item>
                <div  v-if="attributes.length > 0" >
                        <div class="layered-navigation-expand-filter" >
                            <span  v-show="!expandFilter" v-on:click="expandFilter=true">More filters</span>
                            <span  v-show="expandFilter"  v-on:click="expandFilter=false">Less filters</span>
                        </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="filter-attribute-item-template">
        <div class="wrap list-group-item">

            <div v-if="type=='attributes' && attribute.options.length > 0" v-show="expandFilter || attribute.primary_filter">
                <div  class="list-group-accordion-btn" data-toggle="collapse" :data-target="'#id' + attribute.id" aria-expanded="false" :aria-controls="'id' + attribute.id">
                    <span>@{{ attribute.name ? attribute.name : attribute.admin_name }}</span>
                    <small v-if="appliedFilters.length" @click.stop="clearFilters()">{{ __('shop::app.products.remove-filter-link-title') }}</small>
                    <i class="fas fa-angle-right"></i>
                </div>
                <div class="filter-attributes-content collapse" :id="'id' + attribute.id">
                    <div class="search" v-if="isSearchNeeded && attribute.type != 'price'">
                        <input type="text" v-model="search" class="form-control mb-3" :placeholder="'Search ' + attribute.admin_name + '...'">
                    </div>
                    <ol class="inner list-unstyled" v-if="attribute.type != 'price'">
                        <li class="form-group form-check" v-for='(option, index) in filteredOptions'>
                            <div @click="optionClicked(option.id, $event)">
                                <input type="checkbox" class="form-check-input" :id="option.id" :value="option.id" v-model="appliedFilters">
                                <label class="form-check-label d-inline-block"  :for="option.id">@{{ option.label ? option.label : option.admin_name }}&nbsp;&nbsp;<span v-if="option.products_amount" class="form-check-label__amount">(@{{ option.products_amount }})</span></label>
                            </div>
                        </li>
                    </ol>

                    <div class="price-range-wrapper" v-else>

                        <div class="mb-3">
                            <vue-slider
                                ref="slider"
                                v-model="sliderConfig.value"
                                :process-style="sliderConfig.processStyle"
                                :tooltip-style="sliderConfig.tooltipStyle"
                                :rail-style="sliderConfig.railStyle"
                                :dotStyle="sliderConfig.dotStyle"
                                :max="sliderConfig.max"
                                :lazy="true"
                                @change="priceRangeUpdated($event)"
                            ></vue-slider>
                        </div>

                        <div class="filter-input row mb-3">
                            <div class="col pr-0">
                                <input
                                    type="text"
                                    class="form-control"
                                    disabled
                                    name="price_from"
                                    :value="sliderConfig.priceFrom"
                                    id="price_from" />
                            </div>
                            <div class="col-2 px-0 d-flex align-items-center">
                                <label class="text-center w-100" for="to">to</label>
                            </div>
                            <div class="col pl-0">
                                <input
                                    type="text"
                                    class="form-control"
                                    disabled
                                    name="price_to"
                                    :value="sliderConfig.priceTo"
                                    id="price_to">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div v-if="type=='categories'">
                <div  class="list-group-accordion-btn" data-toggle="collapse" :data-target="'#id-subcategories'" aria-expanded="false" :aria-controls="'id-subcategories'">
                    <span>Subcategory</span>
                    <small v-if="appliedFilters.length" @click.stop="clearFilters()">{{ __('shop::app.products.remove-filter-link-title') }}</small>
                    <i class="fas fa-angle-right"></i>
                </div>
                <div class="filter-attributes-content collapse" :id="'id-subcategories'">
                    <div class="search" v-if="isSearchNeeded">
                        <input type="text" v-model="search" class="form-control mb-3" :placeholder="'Search Categories ...'">
                    </div>
                    <ol class="inner list-unstyled">
                        <li class="form-group form-check" v-for='(category, index) in filteredOptions'>
                            <div @click="optionClicked(category.id, $event)">
                                <input type="checkbox" class="form-check-input" :id="category.id" :value="category.id" v-model="appliedFilters">
                                <label class="form-check-label d-inline-block" :for="category.id">@{{ category.name }}&nbsp;&nbsp;<span v-if="category.products_amount" class="form-check-label__amount">(@{{ category.products_amount }})</span></label>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>

            <div v-if="type=='sellers'">
                <div  class="list-group-accordion-btn" data-toggle="collapse" :data-target="'#id-sellers'" aria-expanded="false" :aria-controls="'id-sellers'">
                    <span>Sellers</span>
                    <small v-if="appliedFilters.length" @click.stop="clearFilters()">{{ __('shop::app.products.remove-filter-link-title') }}</small>
                    <i class="fas fa-angle-right"></i>
                </div>
                <div class="filter-attributes-content collapse" :id="'id-sellers'">
                    <div class="search" v-if="isSearchNeeded">
                        <input type="text" v-model="search" class="form-control mb-3" :placeholder="'Search Sellers ...'">
                    </div>
                    <ol class="inner list-unstyled">
                        <li class="form-group form-check" v-for='(seller, index) in filteredOptions'>
                            <div @click="optionClicked(seller.id, $event)">
                                <input type="checkbox" class="form-check-input" name="sellersGroup" :id="seller.id" :value="seller.id" v-model="appliedFilters">
                                <label class="form-check-label d-inline-block" :for="seller.id">@{{ seller.shop_title }}&nbsp;&nbsp;<span v-if="seller.products_amount" class="form-check-label__amount">(@{{ seller.products_amount }})</span></label>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="product-search-template">
        <div class="my-3">
            <form>
                <!-- Search and Compatible with fields -->
                <div class="p-3 bg-gray-lighter rounded">
                    <input v-model="search" type="text" class="form-control rounded-left mb-2" id="product_search" placeholder="Search">
                    <input v-model="compatibleWith" type="text" class="form-control rounded-left mb-2" id="product_search" placeholder="Compatible With">
                    <button class="btn btn-outline-gray-darker btn-block" @click="runSearch"><span class="w-100 text-center">Search</span></button>
                </div>
                <!-- END Search and Compatible with fields -->
            </form>
        </div>
    </script>

    <script>
        Vue.component('layered-navigation', {
            template: '#layered-navigation-template',
            data: function() {
                return {
                    expandFilter: false,
                    showSearch:@json($showSearch),
                    appliedFilters: {},
                    attributes: @json($attributes),
                    categories: @if(isset($categories)) @json($categories) @else @json(null) @endif ,
                    sellers:   @if(isset($sellers)) @json($sellers) @else @json(null) @endif,
                    category: @if(isset($category)) {{$category->id}} @else @json(null) @endif,
                    seller:  @if(isset($seller) && $seller) {{$seller->id}} @else @json(null) @endif
                }
            },

            created() {
                const pageFilterData=localStorage.getItem('pageFilterData');
                if(pageFilterData){
                    const data=JSON.parse(pageFilterData);
                    if(typeof data.category !='undefined'){
                            if(data.category==this.category){
                                const currentDate=new Date();
                                const lastFilterDate=new Date(data.time);
                                var Difference_In_Time = currentDate- lastFilterDate;
                                var diffMins = Math.round(((Difference_In_Time % 86400000) % 3600000) / 60000); // minutes
                                if(diffMins < 4){
                                    delete data.time;
                                    delete data.category;
                                    this.appliedFilters=JSON.parse(pageFilterData);
                                    let params=[];
                                    for (let key in this.appliedFilters) {
                                        if (key != 'time' && key!='category' && key!='seller') {
                                            params.push(key + '=' + this.appliedFilters[key].join(','))
                                        }
                                    }
                                    setTimeout(function() {
                                        eventBus.$emit('reload-products', '?' + params.join('&'));
                                    }, 1000);
                                }
                            }
                    }
                    if(typeof data.seller !='undefined'){
                        if(data.seller==this.seller){
                            const currentDate=new Date();
                            const lastFilterDate=new Date(data.time);
                            var Difference_In_Time = currentDate- lastFilterDate;
                            var diffMins = Math.round(((Difference_In_Time % 86400000) % 3600000) / 60000); // minutes
                            if(diffMins < 4){
                                delete data.time;
                                delete data.seller;
                                this.appliedFilters=JSON.parse(pageFilterData);
                                let params=[];
                                for (let key in this.appliedFilters) {
                                    if (key != 'time' && key!='category' && key!='seller') {
                                        params.push(key + '=' + this.appliedFilters[key].join(','))
                                    }
                                }
                                setTimeout(function() {
                                    eventBus.$emit('reload-products', '?' + params.join('&'));
                                }, 1000);
                            }
                        }
                    }
                }
                let urlParams = new URLSearchParams(window.location.search);
                urlParams.forEach((value, index) => {
                    this.appliedFilters[index] = value.split(',');
                });

            },
            mounted(){

                this.$root.$on('productSearch', (text) => {
                    const x = text.split("&");
                    const pageFilterData = localStorage.getItem('pageFilterData');
                    for (let i = 0; i < x.length; i++) {
                        const s = x[i].split('=');
                        if (s[0] == 'search' || s[0] == 'compatibleWith') {
                            if (pageFilterData) {
                                const data = JSON.parse(pageFilterData);
                                data[s[0]] = [s[1]];
                                if (typeof data['page'] != 'undefined'){
                                    delete data.page;
                                }
                                localStorage.setItem('pageFilterData', JSON.stringify(data));
                            }else{
                                const data={};
                                data[s[0]] = [s[1]];
                                data['time']=new Date();

                                <?php
                                    if (isset($category)) { ?>
                                    data['category']={{$category->id}};
                                <?php    } ?>
                                localStorage.setItem('pageFilterData', JSON.stringify(data));
                            }
                        }
                    }
                    const filterLocalStorage = localStorage.getItem('pageFilterData');
                    if (filterLocalStorage) {
                        this.appliedFilters=JSON.parse(filterLocalStorage);
                        let params=[];
                        for (let key in this.appliedFilters) {
                            if (key != 'time' && key!='category' && key!='seller') {
                                params.push(key + '=' + this.appliedFilters[key].join(','))
                            }
                        }
                        eventBus.$emit('reload-products', '?' + params.join('&'));
                    }

                });
            },
            methods: {
                addFilters(attributeCode, filters) {
                    if (filters.length) {
                        this.appliedFilters[attributeCode] = filters;
                    } else {
                        delete this.appliedFilters[attributeCode];
                    }

                    this.applyFilter();
                },

                applyFilter() {
                    let params = [];
                    if(this.category){
                        delete this.appliedFilters['page'];
                        this.setFilterData(this.appliedFilters,'category');
                    }
                    if(this.seller || this.seller==0){
                        delete this.appliedFilters['page'];
                        this.setFilterData(this.appliedFilters,'seller');
                    }
                    for (let key in this.appliedFilters) {
                        if (key != 'page' && key != 'time' && key!='category' && key!='seller') {
                            params.push(key + '=' + this.appliedFilters[key].join(','))
                        }
                    }
                    eventBus.$emit('reload-products', '?' + params.join('&'));
                },
                setFilterData(search,pageType){
                    search['time']=new Date();
                    if (typeof search['page'] != 'undefined'){
                        delete search.page;
                    }
                    if(pageType=='category'){
                            search['category']=this.category;
                    }
                    if(pageType=='seller'){
                            search['seller']=this.seller;
                    }
                    localStorage.setItem('pageFilterData', JSON.stringify(search));
                }

            }
        });

        Vue.component('filter-attribute-item', {
            template: '#filter-attribute-item-template',
            props: [
                'index',
                'attribute',
                'appliedFilterValues',
                'type',
                'expandFilter'
            ],

            data: function() {
                let maxPrice=0;
                <?php
                    if (isset($category)) { ?>
                          maxPrice = '{{ core()->convertPrice($productFlatRepository->getCategoryProductMaximumPrice($category)) }}';
                <?php    } ?>

                maxPrice = maxPrice ? ((parseInt(maxPrice) !== 0 || maxPrice) ? parseInt(maxPrice) : 500) : 500;

                return {
                    active: false,
                    appliedFilters: [],
                    sliderConfig: {
                        max: maxPrice,
                        value: [0, 0],
                        processStyle: {
                            backgroundColor: "#FFD913"
                        },
                        tooltipStyle: {
                            color: "black",
                            backgroundColor: "#FFD913",
                            padding: "5px"
                        },
                        labelStyle: {
                            backgroundColor: "black"
                        },
                        railStyle: {
                            backgroundColor: "black"
                        },
                        dotStyle: {
                            backgroundColor: "green"
                        },
                        stepStyle: {
                            backgroundColor: "green",
                            color: "green"
                        },
                        priceTo: 0,
                        priceFrom: 0,
                    },
                    isSearchNeeded: false,
                    search: '',
                    filteredOptions: [],
                }
            },

            mounted() {
                if (!this.index) {
                    this.active = false;
                }
                //check if this is an attribute else category
               if(this.type =='attributes'){
                   if (this.appliedFilterValues && this.appliedFilterValues.length) {
                       this.appliedFilters = this.appliedFilterValues;
                       if (this.attribute.type == 'price') {
                           this.sliderConfig.value = this.appliedFilterValues;
                           this.sliderConfig.priceFrom = this.appliedFilterValues[0];
                           this.sliderConfig.priceTo = this.appliedFilterValues[1];
                       }
                       this.active = true;
                   }
                   if (this.attribute.options.length > 25) {
                       this.isSearchNeeded = true;
                   }
                   this.filteredOptions = [...this.attribute.options];
               }else{
                   if (this.appliedFilterValues && this.appliedFilterValues.length) {
                       this.appliedFilters = this.appliedFilterValues;
                   }
                   this.isSearchNeeded = true;
                   this.filteredOptions = [...this.attribute];
               }

            },

            methods: {
                optionClicked(id, event) {
                    event.preventDefault();
                    let checkbox = $(`#${id}`);
                    if (checkbox && checkbox.length > 0 && event.target.type != "checkbox") {
                        checkbox = checkbox[0];
                        checkbox.checked = !checkbox.checked;

                        if (checkbox.checked) {
                            this.appliedFilters.push(id);
                        } else {
                            let idPosition = this.appliedFilters.indexOf(id);
                            if (idPosition == -1)
                                idPosition = this.appliedFilters.indexOf(id.toString());

                            this.appliedFilters.splice(idPosition, 1);
                        }

                        this.$emit('onFilterAdded', this.appliedFilters)
                    }
                },

                clearFilters() {
                    if (this.attribute.type == 'price') {
                        this.sliderConfig.value = [0, 0];
                    }

                    this.appliedFilters = [];

                    this.$emit('onFilterAdded', this.appliedFilters)
                },

                priceRangeUpdated(value) {
                    this.appliedFilters = value;
                    this.$emit('onFilterAdded', this.appliedFilters)
                },
            },

            watch: {
                search() {
                    let options = [];
                    if(this.type=="attributes"){
                        for (let option of this.attribute.options) {
                            if (option.admin_name.toLowerCase().search(this.search.toLowerCase()) !== -1) {
                                options.push(option);
                            }
                        }
                    }
                    if(this.type=="categories"){
                        for (let option of this.attribute) {
                            if (option.name.toLowerCase().search(this.search.toLowerCase()) !== -1) {
                                options.push(option);
                            }
                        }
                    }
                    if(this.type=="sellers"){
                        for (let option of this.attribute) {
                            if (option.shop_title.toLowerCase().search(this.search.toLowerCase()) !== -1) {
                                options.push(option);
                            }
                        }
                    }
                    this.filteredOptions = options;
                },
            },
        });

        Vue.component('product-search', {
            template: '#product-search-template',
            props: ['latestSearch','latestCompatibleWith'],
            data: () => ({
                url: null,
                search:'',
                compatibleWith:''
            }),
            mounted() {
                this.search=this.latestSearch;
                this.compatibleWith=this.latestCompatibleWith;
                this.url = new URLSearchParams(window.location.search);
                if (this.url.get('search')) {
                    this.search = this.url.get('search');
                }
                if (this.url.get('compatibleWith')) {
                    this.compatibleWith = this.url.get('compatibleWith');
                }
            },
            methods: {
                runSearch(e) {
                    e.preventDefault();
                   if (this.search === '') {
                        this.url.delete('search');
                    } else {
                        this.url.set('search', this.search);
                    }

                    if (this.compatibleWith === '') {
                        this.url.delete('compatibleWith');
                    } else {
                        this.url.set('compatibleWith', this.compatibleWith);
                    }


                    /*       window.location.href = location.origin + location.pathname + '?' + this.url.toString();*/
                    this.$root.$emit('productSearch',this.url.toString());
                },
            },
        });
    </script>
@endpush