<template>
    <div class="product-list recent-products-list">

        <div class="container">
            <shimmer-component v-if="isLoading" shimmer-count="6" class="d-none d-lg-block"></shimmer-component>

            <shimmer-component v-if="isLoading" shimmer-count="2" class="d-none d-sm-block d-lg-none"></shimmer-component>

            <shimmer-component v-if="isLoading" shimmer-count="1" class="d-block d-sm-none"></shimmer-component>

            <div class="row" v-if="title">
                <div class="col product-list__head">
                    <h2 class="h1">{{ title }}</h2>
                </div>
            </div>
            <div v-if="isProductListLoaded" class="row mx-n1">
                <div :class="col"
                     class="px-1 my-1"
                     :key="index"
                     v-for="(product, index) in newProducts.slice(0, 6)">

                    <product-card
                        :list="list"
                        :product="product">

                    </product-card>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['maxProducts', 'title'],
    data: function() {
        return {
            'list': false,
            'isLoading': true,
            'isProductListLoaded': false,
            'newProducts': [],
            'col': '',
        }
    },

    mounted: function() {
        this.adjustCol();
        this.getNewProducts();
    },

    methods: {
        'getNewProducts': function() {
            var count = 6;
            if(this.maxProducts){
                count = this.maxProducts
            }
            this.$http.get(`${this.baseUrl}/category-details?category-slug=new-products&count=${count}`)
                .then(response => {
                    if (response.data.status){
                        $(".recent-products-list").removeClass('hide');
                        this.isProductListLoaded = true;
                        this.newProducts = response.data.products;
                        if(response.data.products.length>0){
                            $(".recent-products-list").removeClass('hide');
                        }else{
                            $(".recent-products-list").addClass('hide');
                        }
                    }else{
                        $(".recent-products-list").addClass('hide');
                    }


                    this.isLoading = false;
                })
                .catch(error => {
                    $(".recent-products-list").addClass('hide');
                    this.isLoading = false;
                    console.log(this.__('error.something_went_wrong'));
                })
        },
        'adjustCol': function (){
            const list = $('.recent-products-list');
            const width = list[0].offsetWidth;
            let col = ''
            if(width <= 300){
                col = "col-lg-12";
            }
            if(width <= 567 || width <= 768){
                col = "col-lg-6";
            }
            if(width > 768){
                col = "col-lg-4";
            }
            if(width > 1200){
                col = "col-lg-2";
            }
            this.col = col;
        }
    }
}
</script>