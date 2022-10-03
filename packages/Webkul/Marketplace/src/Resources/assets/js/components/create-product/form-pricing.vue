<template>
    <div class="create-product__box">
        <p class="create-product__box-title">Pricing</p>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="productPrice" class="form-label-required">Price<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="Decide the amount that will be displayed to your customer."></i></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="productPrice">$</span>
                        </div>
                        <input type="text" class="form-control" placeholder="0.00" aria-label="Price" aria-describedby="productPrice">
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="productCost">Cost<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="How much you paid for this product. This is for internal use so you can determine your margins."></i></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="productCost">$</span>
                        </div>
                        <input type="text" class="form-control" placeholder="0.00" aria-label="Cost" aria-describedby="productCost">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="productPromotionalPrice">Promotional Price<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="Run a special, promotional price on a product to entice customers to buy."></i></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="productPromotionalPrice">$</span>
                        </div>
                        <input type="text" class="form-control" placeholder="0.00" aria-label="Promotional Price" aria-describedby="productPromotionalPrice">
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <i class="far fa-chevron-right create-product__pricing-promo-arrow"></i>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="productPromotionalPriceFrom">From<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="Select start date for your promotional price."></i></label>
                    <input type="text" class="form-control" placeholder="From" aria-label="From" aria-describedby="productPromotionalPriceFrom">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="productPromotionalPriceTo">To<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="Select end date for your promotional price for this item."></i></label>
                    <input type="text" class="form-control" placeholder="To" aria-label="To" aria-describedby="productPromotionalPriceTo">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {},

        data: function () {
            return {}
        },

        computed: {},
    }
</script>
