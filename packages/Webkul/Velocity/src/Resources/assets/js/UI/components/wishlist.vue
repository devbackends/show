<template>
    <a v-if="isCustomer == 'true'" :class="`unset wishlist-icon ${addClass ? addClass : ''} text-right`">
        <i :class="`far fa-2x fa-heart ${addClass ? addClass : ''}`"
            @mouseover="isActive ? isActive = !isActive : ''"
            @mouseout="active !== '' && !isActive ? isActive = !isActive : ''">


        </i>
    </a>
    <a
        v-else

        @click="toggleProductWishlist(productId)"
        :class="`unset wishlist-icon ${addClass ? addClass : ''} text-right`">

        <i
            @mouseout="! isStateChanged ? isActive = !isActive : isStateChanged = false"
            @mouseover="! isStateChanged ? isActive = !isActive : isStateChanged = false"
            :class="`far fa-heart ${addClass ? addClass : ''}`">


        </i>

        <span style="vertical-align: super;" v-html="text"></span>
    </a>
</template>

<script type="text/javascript">
    export default {
        props: [
            'text',
            'active',
            'addClass',
            'addedText',
            'productId',
            'removeText',
            'isCustomer',
            'productSlug',
            'moveToWishlist',
        ],

        data: function () {
            return {
                isStateChanged: false,
                isActive: this.active,
            }
        },

        created: function () {
            if (this.isCustomer == 'false') {
                this.isActive = this.isWishlisted(this.productId);
            }
        },

        methods: {
            toggleProductWishlist: function (productId) {
                var updatedValue = [productId];
                let existingValue = this.getStorageValue('wishlist_product');

                if (existingValue) {
                    var valueIndex = existingValue.indexOf(productId);

                    if (valueIndex == -1) {
                        this.isActive = true;
                        existingValue.push(productId);
                    } else {
                        this.isActive = false;
                        existingValue.splice(valueIndex, 1);
                    }

                    updatedValue = existingValue;
                }

                this.$root.headerItemsCount++;
                this.isStateChanged = true;

                this.setStorageValue('wishlist_product', updatedValue);

                window.showAlert(
                    'alert-success',
                    this.__('shop.general.alert.success'),
                    this.isActive ? this.addedText : this.removeText
                );

                if (this.moveToWishlist && valueIndex == -1) {
                    window.location.href = this.moveToWishlist;
                }

                return true;
            },

            isWishlisted: function (productId) {
                let existingValue = this.getStorageValue('wishlist_product');

                if (existingValue) {
                    return ! (existingValue.indexOf(productId) == -1);
                } else {
                    return false;
                }
            }
        }
    }
</script>