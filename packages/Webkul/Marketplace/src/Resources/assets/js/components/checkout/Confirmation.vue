<template xmlns="http://www.w3.org/1999/html">
    <div class="step-content" :class="!isActive ? 'disabled' : ''" id="confirmation-section">
        <div class="form-container">
            <Accordian :disabled="!isActive" :title="'Confirmation'" :active="isActive">
                <div class="form-header" slot="header">
                    <h3 class="display-inbl">Confirmation</h3>
                </div>
                <div :class="'confirmation'" slot="body">
                    <template v-if="fflItems.length > 0">
                        <h4 class="heading">Firearms</h4>
                        <div class="row mb-3">
                            <div class="col-md-7 pr-0">
                                <div class="products-container">
                                    <div class="top-products-border"></div>
                                    <div class="bottom-products-border"></div>
                                    <template v-for="item in fflItems">
                                        <div class="row product-container">
                                            <div class="col-auto pr-0">
                                                <img
                                                    :src="item.product.base_image.small_image_url ?
                                                        item.product.base_image.small_image_url :
                                                        `/vendor/webkul/ui/assets/images/product/small-product-placeholder.png`"
                                                    class="checkout__form-confirmation-content-image"
                                                />
                                            </div>
                                            <div class="col">
                                                <div class="row checkout__form-confirmation-content-products">
                                                    <div class="col-auto pr-0">
                                                        <p>{{ item.quantity }} x</p>
                                                    </div>
                                                    <div class="col">
                                                        <p>{{ item.name }}</p>
                                                        <p>${{ parseFloat(item.total).toFixed(2) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div v-if="shipping.selectedFflShipping">
                                    <p>
                                        Your firearm will be delivered to the
                                        following FFL:
                                    </p>
                                    <p class="checkout__form-confirmation-shipping-name">
                                        {{ fflShippingAddress.first_name }}</p>
                                    <p class="mb-0">
                                        {{ fflShippingAddress.address1[0] }},
                                        {{ fflShippingAddress.city }},
                                        {{ fflShippingAddress.state }},
                                        {{ fflShippingAddress.postcode }}
                                    </p>
                                </div>
                                <div class="delivery-location-container">
                                    <button
                                        data-toggle="modal"
                                        data-target=".bd-example-modal-xl"
                                        class="btn btn-outline-dark btn-sm"
                                    >
                                        Change
                                        delivery
                                        location
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-if="otherItems.length > 0">
                        <h4 class="heading">Other products</h4>
                        <div class="row mb-3">
                            <div class="col-md-7 pr-0">
                                <div class="products-container">
                                    <div class="top-products-border"></div>
                                    <div class="bottom-products-border"></div>
                                    <template v-for="item in otherItems">
                                        <div class="row product-container">
                                            <div class="product-image-container col-auto pr-0">
                                                <img
                                                    :src="item.product.base_image.small_image_url ?
                                                        item.product.base_image.small_image_url :
                                                        `/vendor/webkul/ui/assets/images/product/small-product-placeholder.png`"
                                                    class="checkout__form-confirmation-content-image"
                                                />
                                            </div>
                                            <div class="col">
                                                <div class="row checkout__form-confirmation-content-products">
                                                    <div class="col-auto pr-0">
                                                        <p>{{ item.quantity }} x</p>
                                                    </div>
                                                    <div class="col">
                                                        <p>{{ item.name }}</p>
                                                        <p>${{ parseFloat(item.total).toFixed(2) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div v-if="shipping.selectedOtherShipping">
                                    <p>These items will be delivered to:</p>
                                    <p class="checkout__form-confirmation-shipping-name">
                                        {{ shipping.selectedOtherShipping.first_name }}
                                        {{ shipping.selectedOtherShipping.last_name }}
                                    </p>
                                    <p>
                                        {{ shipping.selectedOtherShipping.address1[0] }},
                                        {{ shipping.selectedOtherShipping.city }},
                                        {{ shipping.selectedOtherShipping.state }},
                                        {{ shipping.selectedOtherShipping.postcode }}
                                    </p>
                                </div>
                                <div class="delivery-location-container">
                                    <button
                                        data-toggle="modal"
                                        data-target="#newsShippingPopup"
                                        class="btn btn-outline-dark btn-sm"
                                    >
                                        Change
                                        shipping address
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-if="withoutShippingItems.length > 0">
                        <h4 class="heading">Products without shipping</h4>
                        <div class="row mb-3">
                            <div class="col-md-12 pr-0">
                                <div class="products-container">
                                    <div class="top-products-border"></div>
                                    <div class="bottom-products-border"></div>
                                    <template v-for="item in withoutShippingItems">
                                        <div class="row product-container">
                                            <div class="product-image-container col-auto pr-0">
                                                <img
                                                    :src="item.product.base_image.small_image_url ?
                                                        item.product.base_image.small_image_url :
                                                        `/vendor/webkul/ui/assets/images/product/small-product-placeholder.png`"
                                                    class="checkout__form-confirmation-content-image"
                                                />
                                            </div>
                                            <div class="col">
                                                <div class="row checkout__form-confirmation-content-products">
                                                    <div class="col-auto pr-0">
                                                        <p>{{ item.quantity }} x</p>
                                                    </div>
                                                    <div class="col">
                                                        <p>{{ item.name }}<span v-if="item.product.type === 'booking' && (!item.additional.type || item.additional.type === 'rental')"> ({{item.additional.attributes[0].option_label}})</span></p>
                                                        <p v-if="item.type === 'booking'" v-html="getBookingProductItemHtml(item)"></p>
                                                        <p>${{ parseFloat(item.total).toFixed(2) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input ref="confirmationRadio" v-model="confirmed"
                                       type="checkbox" id name="confirmed" value class="custom-control-input">
                                <label class="custom-control-label" @click="onConfirmationClick">Accept terms and conditions before placing the order</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <ul class="checkout__form-confirmation-conditions">
                                <li>Are at least 21 years of age</li>
                                <li>Have no felony convictions</li>
                                <li>Are in compliance with applicable state and federal regulations</li>
                                <li>
                                    Are not prohibited from legally purchasing or possessing firearms and/or other
                                    products in
                                    your cart according to local, state, and federal laws
                                </li>
                                <li>Agree to 2AGunShow's <a target="_blank" href="/page/privacy-policy">Privacy policy</a> and <a target="_blank" href="/page/terms-of-service">Terms and Conditions</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </Accordian>
        </div>
    </div>
</template>

<script>
import Accordian from "./Accordian";
import {FIREARM_FAMILY} from "../../constant";

export default {
    name: "Confirmation",
    components: {
        Accordian,
    },
    props: {
        isActive: Boolean,
    },
    data: function () {
        return {
            fflItems: [],
            otherItems: [],
            withoutShippingItems: [],
            shipping: {},
            confirmed: false,
        };
    },
    mounted() {
        this.fflItems = this.$store.getters.getCartOptions.ffl.items;
        this.otherItems = this.$store.getters.getCartOptions.other.items;
        this.withoutShippingItems = this.$store.getters.getCartOptions.withoutShipping.items;
        this.shipping = {
            selectedFflShipping: this.$store.getters.getCart.ffl_address,
            selectedOtherShipping: this.$store.getters.getCart.shipping_address,

        };
    },
    computed: {
        fflShippingAddress() {
            return this.$store.getters.getCart.ffl_address
        },
        otherShippingAddress() {
            return this.$store.getters.getCart.shipping_address
        }
    },
    methods: {
        onConfirmationClick: function () {
            this.$refs.confirmationRadio.click();
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
    watch: {
        confirmed() {
            if (this.$refs.confirmationRadio.checked) {
                this.$store.commit('setStepToActive', 'confirmed')
            } else {
                this.$store.commit('setStepToInactive', 'confirmed')
            }
        },
        isActive() {
            if (this.isActive && this.confirmed) {
                this.$store.commit('setStepToActive', 'confirmed')
            }
        },
    }
};
</script>

<style scoped>
.product-image-container img {
    max-width: 75%;
}

.heading {
    text-transform: uppercase;
}

.quantity-container {
    max-width: initial;
    float: left;
    line-height: 70px;
    padding-right: 10px;
}

.accept-shipping-confirmation-container {
    text-align: left;
    padding-bottom: 0;
}

span.after:before {
    display: block;
    overflow: hidden;
    margin-left: 2rem;
    content: ">";
    float: left;
    margin-right: 1rem;
}

span.after {
    font-style: normal;
    font-weight: normal;
    font-size: 16px;
    line-height: 23px;
}
</style>
