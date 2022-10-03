<template>
    <div>
        <div class="cards">
                <div class="custom-control custom-radio" :key="index" v-for="(card, index) in cards">
                    <input type="radio" :id="`card-bluedog-`+index" name="card-bluedog" :value="index" class="custom-control-input" v-model="selectedCard">
                    <label class="custom-control-label" :for="`card-bluedog-`+index">{{getCardFullName(card)}}<a v-on:click="removeCard(index)" href="javascript:;"><i class="far fa-times ml-2"></i></a></label>
                </div>
        </div>
        <button data-toggle="modal"
                data-target="#newBlueDogCardPopup" id="add-bluedog-credit-card"
                class="btn btn-outline-dark btn-sm mt-1">
            Add a new card
        </button>
        <NewCard @submit="submit"/>
    </div>
</template>

<script>
import {API_ENDPOINTS} from "../../../constant";
import NewCard from "./NewCard";

export default {
    name: 'BlueDogPayment',
    components: {NewCard},
    data: () => ({
        cards: [],
        selectedCard: null,
    }),
    props: ['selectedPayment'],
    async mounted() {
        const response = await this.$http.get(API_ENDPOINTS.fluid.card.get.replace(
            '{customerId}',
            this.$store.getters.getCustomer.id
        ).replace('{sellerId}', this.$store.getters.getCart.seller_id));
        if (response.data.status) {
            this.cards = response.data.data.cards;
        }
        if(this.selectedPayment=='bluedog' && !this.selectedCard && this.cards.length > 0){
            this.selectedCard=0;
        }
    },

    methods: {
        submit(additional) {

            if (additional.token) {
                let newCard = {
                    nickname: additional.nickname,
                    last_four: '',
                }
                if (newCard.nickname === '') {
                    newCard.nickname = 'New card';
                }
                this.cards.push(newCard);
                this.selectedCard = this.cards.length-1;
            }
            this.$emit('setAdditionalInfo', additional);
            this.$emit('setValid', true);
        },
        getCardFullName(card) {
            if (card.nickname === '') return card.last_four;
            if (card.last_four === '') return card.nickname;
            return `${card.nickname} - ${card.last_four}`;
        },
        removeCard(index){
            if(typeof this.cards[index].id != 'undefined'){
                this.$http.delete(API_ENDPOINTS.bluedog.card.remove.replace('{cardId}', this.cards[index].id )).then((response) => {
                    if(response.status==200){
                        if (response.data.status) {
                            this.cards.splice(index, 1);
                            if(this.selectedCard==index){
                                this.selectedCard=null;
                                this.$store.commit('setStepToInactive', 'confirmation');
                                this.$store.commit('setStepToInactive', 'confirmed');
                            }
                        }
                    }
                }).catch(err => {

                })
            }else{
                this.cards.splice(index, 1);
                if(this.selectedCard==index){
                    this.selectedCard=null;
                    this.$store.commit('setStepToInactive', 'confirmation');
                    this.$store.commit('setStepToInactive', 'confirmed');
                }
            }
        }
    },

    watch: {
        selectedCard() {
            if (this.cards[this.selectedCard] // Card exist
                && this.cards[this.selectedCard].last_four !== '') { // It is not the new card with token
                const card = this.cards[this.selectedCard];
                this.submit({
                    card: {
                        id: card.fluid_customer_id,
                        payment_method_type: 'card',
                        payment_method_id: card.fluid_card_id,
                    }
                });
            }
        }
    }
}
</script>

<style scoped>
    .cards {
        padding-left: 2rem;
        margin-bottom: 0.5rem;
    }
    #add-bluedog-credit-card {
        margin-left: 2rem;
    }
</style>