<template>
    <div class="create-product__box">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="productPublished">
                <label class="custom-control-label" for="productPublished">Published?</label>
            </div>
        </div>
        <div class="form-group">
            <label for="productUrl">Publication url<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="Create a unique url for your product page."></i></label>
            <input type="text" class="form-control" id="productUrl" placeholder="product-name-url" required>
            <small id="passwordHelpBlock" class="form-text text-muted">
                Lowercase separated with dash.
            </small>
        </div>
        <div class="form-group">
            <label for="productCondition">Condition<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="Let your customers know the condition of your product, e.g. New, Used, Poor, Refurbished, etc."></i></label>
            <select class="custom-select">
                <option selected>Open this select menu</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="form-group mb-0">
            <div class="custom-control custom-switch mb-2">
                <input type="checkbox" class="custom-control-input" id="productFeatured">
                <label class="custom-control-label" for="productFeatured">Featured?<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="This product will display prominently as one of the first products on your store page."></i></label>
            </div>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="productPreorder">
                <label class="custom-control-label" for="productPreorder">Allow preorder?<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="Allow customers to pre-order products featured in your store but not yet in stock."></i></label>
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

        computed: {
            customFunction () {}
        },
    }
</script>
