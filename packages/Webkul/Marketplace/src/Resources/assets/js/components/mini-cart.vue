<template>
    <div class="mini-cart-container pull-right" :style="`${(itemsCount > 0) ? '' : 'pointer-events: none'}`">
        <div class="dropdown disable-active">

            <mini-cart-btn :text="cartButtonText" :item-count="itemsCount"></mini-cart-btn>

            <div class="dropdown-menu dropdown-menu--cart dropdown-menu-right" aria-labelledby="mini-cart">
                <div class="cart-dropdown__content">
                    <div class="cart-dropdown__list" :key="cartIndex" v-for="(cart, cartIndex) in carts">
                      <div v-if="cart.items.length > 0">
                        <div class="cart-dropdown__content-head">Products from {{ cart.seller.shop_title }}</div>
                        <div class="cart-dropdown__list-item" :key="index" v-for="(item, index) in cart.items">
                          <div class="image">
                            <a :href="`${$root.baseUrl}/product/${item.url_key}`">
                              <img :src="`${item.images.medium_image_url}`"
                                   alt="Product image">
                            </a>
                          </div>
                          <div>{{ item.quantity }} x</div>
                          <div>
                            <p>{{ item.name }}</p>
                            <p v-if="item.type === 'booking'" v-html="getBookingProductItemHtml(item)"></p>
                            <p><b>{{ Number(item.base_total).toFixed(2) }}</b></p>
                          </div>
                          <div class="ml-auto mr-3 cart-dropdown__remove-btn">
                            <a @click="removeProduct(cart.seller_id, item.id)">
                              <i class="far fa-trash-alt"></i>
                            </a>
                          </div>
                        </div>
                        <div class="cart-dropdown__content-bottom">
                          <small>Subtotal from this seller</small>
                          <span>${{Number(cart.base_sub_total).toFixed(2)}}</span>
                          <a :href="checkoutUrl(cart.seller_id)" class="btn btn-primary ml-auto">{{ checkoutText }}</a>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="modal-footer__actions cart-dropdown__footer d-flex align-items-center">
                    <a :href="viewCart" class="btn btn-link mr-auto">{{ cartText }}</a>
                    <p class="ml-auto mb-0">{{ subtotalText }}: {{ cartsDetails.cartsTotal }}</p>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import {API_ENDPOINTS} from "../constant";

export default {
    props: [
        'cartText',
        'cartButtonText',
        'viewCart',
        'checkoutText',
        'subtotalText',
    ],

    data: function () {
        return {
            carts: [],
            cartsDetails: [],
            itemsCount: 0,
        };
    },

    mounted: function () {
        this.getCartDetails();
    },

    watch: {
        '$root.miniCartKey': function () {
            this.getCartDetails();
        },
    },

    methods: {
        checkoutUrl(sellerId) {
            return API_ENDPOINTS.checkout.replace('{sellerId}', sellerId)
        },

        getCartDetails() {
            this.$http.get(API_ENDPOINTS.cart.get)
                .then((response) => {
                    if (response.data.status) {
                        this.carts = response.data.data.carts;
                        this.cartsDetails = response.data.data.cartsDetails;
                        this.setCartItemsCount();
                    }
                }).catch((e) => {
                    console.log('Error');
                });
        },

        removeProduct(sellerId, itemId) {
            this.$http
                .delete(API_ENDPOINTS.cart.remove
                    .replace('{sellerId}', sellerId)
                    .replace('{itemId}', itemId))
                .then((response) => {
                    window.showAlert(
                        `alert-${response.data.status}`,
                        response.data.label,
                        response.data.message
                    );
                    if (response.data.status) {
                        this.getCartDetails();
                    }
                })
                .catch((exception) => {
                    console.log('Error');
                });
        },

        setCartItemsCount() {
            let count = 0;
            for (let key in this.carts) {
                for (let item of this.carts[key].items) {
                    count += item.quantity;
                }
            }
            this.itemsCount = count;
        },

        getBookingProductItemHtml(item) {
            const add = item.additional;
            let date='';
            let slot='';
            let found=0;
            let htmlData='';
            if(add.type=='event'){
                if(typeof add.attributes[0].option_label != 'undefined') htmlData+='Ticket Type : '+add.attributes[0].option_label+'<br>'
                if(add.defaultSlots.type_of_event=='single-day'){
                    for(let i=0;i<add.defaultSlots.slots.length;i++){
                        for(let j=0;j<add.defaultSlots.slots[i].durations.length;j++){
                            htmlData+=this.getFormattedDate(add.defaultSlots.slots[i].day)+' '+add.defaultSlots.slots[i].durations[j].from+' - '+add.defaultSlots.slots[i].durations[j].to+'<br>'
                        }
                    }
                }else if(add.defaultSlots.type_of_event=='multi-day'){
                    for(let i=0;i<add.defaultSlots.slots.length;i++){
                        for(let j=0;j<add.defaultSlots.slots[i].durations.length;j++){
                            htmlData+=this.getFormattedDate(add.defaultSlots.slots[i].day)+' '+add.defaultSlots.slots[i].durations[j].from+' - '+add.defaultSlots.slots[i].durations[j].to+'<br>'
                        }
                    }
                }else{
                    htmlData+=add.selectedSlot;
                }
            }


            //Basic event
            if(add.type=='default_event'){
                if(add.defaultSlots.type_of_event=='single-day'){
                    for(let i=0;i<add.defaultSlots.slots.length;i++){
                        for(let j=0;j<add.defaultSlots.slots[i].durations.length;j++){
                            htmlData+=this.getFormattedDate(add.defaultSlots.slots[i].day)+' '+add.defaultSlots.slots[i].durations[j].from+' - '+add.defaultSlots.slots[i].durations[j].to+'<br>'
                        }
                    }
                }else if(add.defaultSlots.type_of_event=='multi-day'){
                    for(let i=0;i<add.defaultSlots.slots.length;i++){
                        for(let j=0;j<add.defaultSlots.slots[i].durations.length;j++){
                            htmlData+=this.getFormattedDate(add.defaultSlots.slots[i].day)+' '+add.defaultSlots.slots[i].durations[j].from+' - '+add.defaultSlots.slots[i].durations[j].to+'<br>'
                        }
                    }
                }else{
                    date= add.selectedSlot.day
                    slot=  add.selectedSlot.durations[0].from +' to '+ add.selectedSlot.durations[0].to;
                    htmlData += this.getFormattedDate(date) + ' ' + slot;
                    }
            }

            //Training
            if(add.type=='training'){
                if(add.defaultSlots.type_of_event=='single-day' || add.defaultSlots.type_of_event=='multi-day'){
                    for (let i=0;i< add.defaultSlots.slots.length;i++){
                        date=add.defaultSlots.slots[i].day;
                        for (let j=0;j < add.defaultSlots.slots[i].durations.length;j++){
                            if(add.defaultSlots.slots[i].durations[j].slotId==add.selectedSlot.slotId){
                                found=1;
                                slot=  add.defaultSlots.slots[i].durations[j].from +' to '+ add.defaultSlots.slots[i].durations[j].to;
                                break;
                            }
                        }
                        if(found==1){
                            break;
                        }
                    }
                    htmlData+= this.getFormattedDate(date) +' '+slot;
                }else{
                    date=add.selectedDate;
                    for (let i=0;i< add.defaultSlots.slots.length;i++){
                        for (let j=0;j < add.defaultSlots.slots[i].durations.length;j++){
                            if(add.defaultSlots.slots[i].durations[j].slotId==add.selectedSlot.slotId){
                                found=1;
                                slot=  add.defaultSlots.slots[i].durations[j].from +' to '+ add.defaultSlots.slots[i].durations[j].to;
                                break;
                            }
                        }
                        if(found==1){
                            break;
                        }
                    }
                    htmlData+= this.getFormattedDate(date) +' '+slot;
                }
            }

            return htmlData;
        },
        getFormattedDate(date) {
            var myDate = new Date(date);
            var day = myDate.getDate();
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var dayName = days[myDate.getDay()];
            var month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"][myDate.getMonth()];
            return dayName+', ' +month + '. ' + day + ', ' + myDate.getFullYear();
        }
    },
};
</script>
