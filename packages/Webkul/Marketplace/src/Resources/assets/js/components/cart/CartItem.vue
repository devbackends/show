<template>
    <div class="shopping-cart__product">
        <div class="row">
            <div class="col-auto">
                <img :src="item.images.small_image_url" alt="" class="shopping-cart__product-image">
            </div>
            <div class="col">
                <div class="shopping-cart__product-description">
                    <a :href="`/product/${item.url_key}`" class="shopping-cart__product-title">{{item.name}}</a>
                    <p v-if="typeof item.note!='undefined'" style="color:red" v-text="item.note"></p>
                    <p v-if="item.type === 'booking'" v-html="getBookingProductItemHtml(item)"></p>
                    <p>${{parseFloat(item.price).toFixed(2)}}</p>
                    <!--<p class="text-muted">This item will ship to a local FFL Dealer for pickup.</p>-->
                    <div class="shopping-cart__product-actions">
                        <!--<a href="#"><i class="far fa-heart mr-2"></i>Move to wishlist</a>-->
                        <a @click="$emit('remove', item.id)"><i class="far fa-trash-alt mr-2"></i>Remove</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-auto d-flex justify-content-end">
                <div class="quantity-selector">
                    <button @click="quantity++" type="button" class="btn btn-outline-gray quantity-selector__btn quantity-selector__remove">+</button>
                    <input v-model="quantity" type="text" class="form-control" aria-describedby="quantity" readonly>
                    <button @click="quantity--" type="button" class="btn btn-outline-gray quantity-selector__btn quantity-selector__add">-</button>
                </div>
            </div>
            <div class="col-12 col-md-auto">
                <p class="shopping-cart__product-price">${{ parseFloat(item.base_total).toFixed(2) }}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CartItem',
    props: ['item'],
    data() {
        return {
            quantity: this.item.quantity,
        };
    },
    methods: {
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
        quantity() {
            if (this.quantity===0) {
                if (confirm('Are you sure you want to delete this item?')) {
                    this.$emit('remove', this.item.id);
                } else {
                    this.quantity = 1;
                }
            } else {
                this.$emit('updateQuantity', {
                    id: this.item.id,
                    quantity: this.quantity
                });
            }
        }
    },
}
</script>

<style scoped>

</style>