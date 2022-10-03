
<template>
    <div class="coupon-container my-3">
        <div class="discount-control">
            <form class="coupon-form" method="post" data-vv-scope="coupon-form" @submit.prevent="applyCoupon('coupon-form')" >
                <div class="row">
                    <div class="col pr-0">
                        <div class="control-group" :class="[error_message || errors.has('code')  ? 'has-error' : '']" >
                            <input type="text" class="form-control" v-model="coupon_code" v-validate="'required'" name="code" placeholder="Enter Coupon Code">
                            <div class="control-error" v-text="error_message"></div>
                            <span class="control-error" v-if="errors.has('code')" v-text="errors.first('code')"></span>
                        </div>
                    </div>

                    <div class="col-auto pl-0">
                        <button class="btn btn-outline-dark" :disabled="disable_button">Apply</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="applied-coupon-details" v-if="appliedCoupon">
            <label>Applied Coupon</label>

            <label class="right" style="display: inline-flex; align-items: center;">
                <b v-text="appliedCoupon"></b>
                <span class="icon cross-icon" title="Remove Coupon" v-on:click="removeCoupon"></span>
            </label>
        </div>
    </div>
</template>

<script>
import CartItem from "../cart/CartItem";

export default {
    name: 'coupon',
    inject: ['$validator'],
    props: ['cart'],
    data: () => ({
            coupon_code: '',

            error_message: '',

            disable_button: false
        })
    ,
    computed: {
        appliedCoupon(){
            return this.cart.coupon_code;
        }
    },
    methods: {
        applyCoupon: function(formScope) {
            this.$validator.validateAll(formScope).then((result) => {
                var self = this;
                self.error_message = null;
                self.disable_button = true;
                axios.post('/checkout/cart/coupon', {code: self.coupon_code,seller_id:self.cart.seller_id})
                    .then(function(response) {
                        if (response.data.success) {
                            self.$emit('onApplyCoupon');

                            self.coupon_code = '';
                            window.flashMessages = [{'type': 'alert-success', 'message': response.data.message}];
                            self.$root.addFlashMessages();
                        } else {
                            self.error_message = response.data.message;
                        }
                        self.disable_button = false;
                    })
                    .catch(function(error) {
                        self.error_message = error.response.data.message;
                        self.disable_button = false;
                    });
            });

        },

        removeCoupon: function () {
            var self = this;

            axios.delete('/checkout/cart/coupon')
        .then(function(response) {
                self.$emit('onRemoveCoupon')

                self.applied_coupon = '';

                window.flashMessages = [{'type': 'alert-success', 'message': response.data.message}];

                self.$root.addFlashMessages();

            })
                .catch(function(error) {
                    window.flashMessages = [{'type': 'alert-error', 'message': error.response.data.message}];

                    self.$root.addFlashMessages();
                });
        }
    }
}
</script>
