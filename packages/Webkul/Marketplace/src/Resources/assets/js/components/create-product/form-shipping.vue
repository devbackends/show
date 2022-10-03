<template>
    <div class="create-product__box">
        <p class="create-product__box-title">Shipping</p>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="autocalculated" value="autocalculated" v-model="shippingType" class="custom-control-input">
                        <label class="custom-control-label" for="autocalculated">Auto calculated<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="Get auto-calculated shipping rates based on weight and size from your preferred carriers."></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="flat" value="flat" v-model="shippingType" class="custom-control-input">
                        <label class="custom-control-label" for="flat">Flat rate<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="Set a fixed amount for shipping."></i></label>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="shippingType == 'autocalculated'">
            <p class="create-product__box-subtitle">Package Weight</p>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="productPromotionalPrice">Lbs.</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="0">
                            <div class="input-group-append">
                                <span class="input-group-text">In.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="productPromotionalPrice">Oz.</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="0">
                            <div class="input-group-append">
                                <span class="input-group-text">Oz.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="create-product__box-subtitle">Package Dimensions</p>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="productPromotionalPrice">Depth</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="0">
                            <div class="input-group-append">
                                <span class="input-group-text">In.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="productPromotionalPrice">Width</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="0">
                            <div class="input-group-append">
                                <span class="input-group-text">In.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="productPromotionalPrice">Height</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="0">
                            <div class="input-group-append">
                                <span class="input-group-text">In.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="productPromotionalPrice">Shipping size</label>
                        <select class="custom-select">
                            <option selected>Select size</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="shippingType == 'flat'">
            <div class="col">
                <div class="form-group">
                    <label for="productShippingFlatPrice">Shipping Price</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="productShippingFlatPrice">$</span>
                        </div>
                        <input type="text" class="form-control" placeholder="0.00">
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="productShippingFlatPriceAdditionalItem">Price per additional item</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="productShippingFlatPriceAdditionalItem">$</span>
                        </div>
                        <input type="text" class="form-control" placeholder="0.00">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <p class="create-product__box-subtitle">Restrictions</p>
        <div class="row">
            
        </div>
    </div>
</template>

<script>
    export default {
        props: {},

        data: function () {
            return {
                shippingType: "autocalculated"
            }
        },

        computed: {
            customFunction () {}
        },
    }
</script>
