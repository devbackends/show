<template>
    <a :class="`btn btn-link product-item__overlay-action ${addClass}`" @click="add">
        <i class="far fa-compress-alt"></i>
    </a>
</template>

<script>
    export default {
        props: ['customer', 'productId', 'marketplaceSellerId', 'addClass', 'token'],

        methods: {
            add() {
                if (this.customer) {
                    this.addAuthCase();
                } else {
                    this.addGuestCase();
                }
            },

            addAuthCase() {
                this.$http.post(
                    `${this.$root.baseUrl}/marketplace/compare/add`, {
                        productId: this.productId,
                        marketplaceSellerId: this.marketplaceSellerId,
                        _token: this.token,
                    }
                ).then(response => {
                    window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                    this.$root.headerCompareCount++;
                }).catch(error => {
                    window.showAlert(
                        'alert-danger',
                        'Error',
                        'Something went wrong'
                    );
                });
            },

            addGuestCase() {
                let compareItems = this.getStorageValue('compared_product');

                if (!compareItems) {
                    compareItems = {};
                }
                    if (typeof compareItems !== 'object') {
                        compareItems = {}
                    }
                    
                    if (compareItems[this.marketplaceSellerId]) {
                        if (!compareItems[this.marketplaceSellerId][this.productId]) {
                            compareItems[this.marketplaceSellerId][this.productId] = true;
                        } else {
                            window.showAlert(
                                `alert-success`,
                                'Success',
                                'Item already added to compare list'
                            );
                            return true;
                        }
                    } else {
                        compareItems[this.marketplaceSellerId] = {}
                        compareItems[this.marketplaceSellerId][this.productId] = true;
                    }



                this.setStorageValue('compared_product', compareItems);
                window.showAlert(
                    `alert-success`,
                    'Success',
                    'Item successfully added to campare list'
                );
                this.$root.headerCompareCount++;
            },
        }
    }
</script>