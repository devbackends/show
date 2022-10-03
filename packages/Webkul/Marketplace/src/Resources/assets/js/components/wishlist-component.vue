<template>
    <a
        :title="linkTitle"
        @click="toggle"
        :class="`btn btn-link product-item__overlay-action ${addClassToLink ? addClassToLink : ''}`">
        <i :class="`${isActive ? 'fas' : 'far'} fa-heart`"></i>
    </a>
</template>

<script type="text/javascript">
    export default {
        props: [
            'active',
            'addClassToLink',
            'addUrl',
            'removeUrl',
            'linkTitle',
            'productId',
            'sellerId',
            'isCustomer',
        ],

        data: () => ({
            isActive: false,
        }),

        mounted() {
            this.isActive = this.active;
            this.isActive = this.isWishlisted();
        },

        methods: {
            toggle(e) {
                e.preventDefault();
                if (this.isCustomer) {
                    this.toggleAuth();
                } else {
                    this.toggleGuest();
                }
            },

            async toggleAuth() {
                let url = this.isActive ? this.removeUrl : this.addUrl;

                let res = await this.$http.post(url, {
                    productId: this.productId,
                    marketplaceSellerId: this.sellerId,
                })

                window.showAlert(`alert-${res.data.status}`, res.data.label, res.data.message);
                this.$root.headerWishlistCount++;
                this.isActive = !this.isActive
            },

            toggleGuest() {
                let wishlistItems = this.getStorageValue('wishlist_product');
               if(wishlistItems){
                   if (typeof wishlistItems !== 'object') {
                       wishlistItems = {}
                       if (this.isActive) return false;
                   }

                   if (wishlistItems[this.sellerId]) {
                       if (wishlistItems[this.sellerId][this.productId]) {
                           if (this.isActive) {
                               delete wishlistItems[this.sellerId][this.productId];
                               if (Object.keys(wishlistItems[this.sellerId]).length === 0) delete wishlistItems[this.sellerId]
                               this.isActive = false;
                               window.showAlert('alert-success', 'Success', 'Item successfully removed from wishlist');
                           } else {
                               this.isActive = true;
                               window.showAlert('alert-success', 'Success', 'Item already added to wishlist');
                           }
                       } else {
                           wishlistItems[this.sellerId][this.productId] = true;
                           this.isActive = true;
                           window.showAlert('alert-success', 'Success', 'Item successfully added to wishlist');
                       }
                   } else {
                       wishlistItems[this.sellerId] = {}
                       wishlistItems[this.sellerId][this.productId] = true;
                       this.isActive = true;
                       window.showAlert('alert-success', 'Success', 'Item successfully added to wishlist');
                   }
               } else {
                   wishlistItems=[];
                   wishlistItems[this.sellerId] = {};
                   wishlistItems[this.sellerId][this.productId] = true;
                   this.isActive = true;
                   window.showAlert('alert-success', 'Success', 'Item successfully added to wishlist');
               }

                this.setStorageValue('wishlist_product', wishlistItems);
                this.$root.headerWishlistCount++;
            },

            isWishlisted() {
                if (this.isCustomer) return this.active;
                let wishlistItems = this.getStorageValue('wishlist_product');
                if(wishlistItems){
                    return (typeof wishlistItems === 'object' && wishlistItems[this.sellerId] && wishlistItems[this.sellerId][this.productId]);
                }
                return false;
            }
        }
    }
</script>