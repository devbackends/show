<template>
    <div :class="`${additionalClasses.div}`">
        <form data-type="submit" method="POST" @submit.prevent="addToCart">
            <!-- <button
                type="submit"
                :class="`btn ${additionalClasses.btn}`"
                :disabled="isDisabled">

                <i data-type="submit" class="far fa-cart-plus"></i>
                <span data-type="submit" v-text="btnText"></span>
            </button> -->

            <submit-button :disable-reason="disableReason" :cssClass="`${additionalClasses.btn}`" color=" " faIconLeft="cart-plus" :disabled="isDisabled" :text="btnText" :loading="isLoading">
            </submit-button>
        </form>
    </div>
</template>

<script>

import {API_ENDPOINTS} from "../constant";

export default {
    props: [
        'product',
        'btnText',
        'isDisabled',
        'disableReason',
        'csrfToken',
        'productId',
        'reloadPage',
        'moveToCart',
        'productFlatId',
        'sellerInfo',
        'ui',
        'selectedTicket',
        'selectedSlot',
        'selectedDate',
        'bookingProductType',
        'bookingProductId',
        'defaultSlots'

    ],

    data: () => ({
        additionalClasses: {
            div: '',
            btn: '',
        },
        additionalOptions: {},
        changedProductId: this.productId,
        isLoading: false
    }),

    mounted() {

        switch (this.ui) {
            case 'primary':
                this.additionalClasses.div = '';
                this.additionalClasses.btn = 'btn-primary';
                break;
            default:
                this.additionalClasses.div = 'product-item__action';
                this.additionalClasses.btn = 'btn-link small-padding';
        }
        this.$root.$on('updateProductId', (text) => {
            this.changedProductId= text;
            this.isButtonEnable = true;
        });
        this.$root.$on('disableAddtoCart', (text) => {
            this.isButtonEnable = false;
        });
        eventBus.$on('addToCartAdditionalOptions', (options) => {
            this.additionalOptions = options;
        });
        if (this.product && this.product.type==='configurable') {
            this.isButtonEnable = false;
        }
    },

    methods: {
        addToCart() {
            this.isButtonEnable = false;
            if (!this.changedProductId) {
                this.changedProductId = this.productId;
            }

            const options = this.getOptions();
            const res = this.validateOptions(options);
            if (!res.status) {
                window.showAlert(`alert-danger`, 'Error', res.message);
                this.isButtonEnable = true;
                return false;
            }
            this.isLoading=true;

            this.$http.post(API_ENDPOINTS.cart.add, options).then(response => {
                this.isButtonEnable = true;
                this.isLoading=false;
                if (response.data.status == 'success') {
                    this.$root.miniCartKey++;

                    if (this.moveToCart == "true") {
                        let wishlistItems = this.getStorageValue('wishlist_product');

                        let sellerId = this.seller ? this.seller.id : 0;
                        if (wishlistItems[sellerId] && wishlistItems[sellerId][this.changedProductId]) {
                            delete wishlistItems[sellerId][this.changedProductId];
                            if (Object.keys(wishlistItems[sellerId]).length === 0) delete wishlistItems[sellerId];
                        }

                        this.$root.headerWishlistCount++;
                        this.setStorageValue('wishlist_product', wishlistItems);
                    }

                    window.showAlert(`alert-success`, 'Success', response.data.message);

                    if (this.reloadPage == "1") {
                        window.location.reload();
                    }
                } else {
                    window.showAlert(`alert-${response.data.status}`, response.data.label ? response.data.label : 'Error', response.data.message);

                    if (response.data.redirectionRoute) {
                        window.location.href = response.data.redirectionRoute;
                    }
                }
            }).catch(error => {
                console.log('Error');
            })
        },
        getOptions() {
            let options = {
                '_token': this.csrfToken,
                'product_id': this.changedProductId,
            }

            options = {...this.additionalOptions, ...options}

            if (this.sellerInfo) {
                options['seller_info'] = {
                    'product_id': this.sellerInfo.productId,
                    'seller_id': this.sellerInfo.sellerId,
                    'is_owner': this.sellerInfo.isOwner,
                }
            }

            if (this.product && this.product.type !== 'booking') {
                options['quantity'] = (this.$root.productCartQuantity) ? this.$root.productCartQuantity : 1;
            }
            return options;
        },
        validateOptions(options) {
            let status = true;
            let message = null;
            if (this.product && this.product.type === 'booking') {

                if (!this.bookingProductType) {
                    status = false;
                }else{
                    options.type=this.bookingProductType;
                }

                if (!this.bookingProductId){
                    status = false;
                }else{
                    options.booking_product_id=this.bookingProductId;
                }

                if (this.bookingProductType &&
                    (this.bookingProductType === 'default_event'
                        || this.bookingProductType === 'training')
                ) {

                    if(! this.selectedSlot) status = false;
                    if(status){
                        this.selectedSlot.qty=1;
                        if(this.selectedDate)    options.selectedDate= this.selectedDate
                        options.defaultSlots=this.defaultSlots;
                        options.selectedSlot=this.selectedSlot;
                        options.selectedTickets= [this.selectedSlot];
                    }
                } else if (options.type && options.type === 'event') {
                    if(this.defaultSlots.type_of_event=='repeating' && ! this.selectedSlot) status = false;
                    if(status){
                        if(typeof this.selectedTicket.qty=='undefined') {
                            this.selectedTicket.qty = 1;
                        }
                        if(this.defaultSlots)    options.defaultSlots= this.defaultSlots
                        if(this.selectedSlot)   options.selectedSlot= this.selectedSlot;
                        options.selectedTickets= [this.selectedTicket];
                    }

                } else if (options.type && options.type === 'rental') {


                    if (!options.rentalType) {

                        status = false;
                    } else {

                        if(! this.selectedSlot) status = false;
                            if (options.rentalType === 'daily') {
                                this.selectedSlot.qty=1;
                                options.selectedSlot= this.selectedSlot;
                            } else if (options.rentalType === 'hourly') {

                                if (!this.selectedSlot) status = false;
                                if (!this.selectedDate) status = false;
                                this.selectedSlot.qty=1;
                                options.selectedSlot= this.selectedSlot;
                            }else if (options.rentalType === 'both') {
                                if (!this.selectedSlot) status = false;
                                if (!this.selectedDate) status = false;
                                this.selectedSlot.qty=1;
                                options.selectedSlot= this.selectedSlot;
                            }

                    }

                } else {
                    status = false;
                }
                if (!status && !message) {
                    message = 'Please select what you want to book';
                }
            }
            return {
                status: status,
                message: message,
            }
        },

    }
}
</script>