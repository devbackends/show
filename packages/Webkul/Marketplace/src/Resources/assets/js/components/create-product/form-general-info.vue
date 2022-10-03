<template>
    <div class="create-product__box">
        <p class="create-product__box-title">General Information</p>
        <p class="create-product__box-subtitle">Main Details</p>
        <!-- General Info -->
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="productName" class="form-label-required">Name</label>
                    <input type="text" class="form-control" id="productName" placeholder="Give it a short, descriptive name" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="productDescription">Description</label>
                    <textarea rows="3" type="text" class="form-control" id="productDescription" placeholder="Describe your event, so bookers know what to expect"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="productInstructions">Attendee Instructions</label>
                    <textarea rows="3" type="text" class="form-control" id="productInstructions" placeholder="How to attend, what to bring, etc"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg">
                <div class="form-group">
                    <label for="productManufacturer">Manufacturer</label>
                    <select class="custom-select">
                        <option selected>Select manufacturer</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-lg">
                <div class="form-group">
                    <label for="productTaxCategory">Tax category</label>
                    <input type="text" class="form-control" id="productTaxCategory" placeholder="Enter tax category" required>
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

        computed: {
            customFunction () {}
        },
    }
</script>
