<template>
    <div class="bordered card-payment mb-3" id="card-payment-section">
        <template v-for="card in cards">
            <div class="clearent-card-info" :id="card.id + '_card_con'">

                <div class="form-group form-check">
                    <div class="radio-button">
                        <div class="radio-button__input">
                            <input type="radio" :name="'card_' + card.id" v-model="selectedCard" :value="card" :id="'card_' + card.id">
                            <label :for="'card_' + card.id" class="form-check-label"></label>
                        </div>
                        <div class="radio-button__label">************{{ card.last_four }}</div>
                    </div>
                </div>

            </div>
        </template>
        <button data-toggle="modal"
                data-target="#newCardPopup" id="add-credit-card"
                class="btn btn-outline-dark btn-sm mt-1">
            Add a new card
        </button>
        <new-card v-on:newCard="newCardAdded"></new-card>
    </div>
</template>

<script>
	import NewCard from "./NewCard";
    export default {
        name: "ClearentPayment",
        components: {
            NewCard
        },
        data(){
            return {
                cards: [] = [],
                selectedCard: null,
            };
        },
        watch: {
        	selectedCard: function(card){
                // set the payment method as valid:
                const valid = !!card;
                if(valid){
					this.submitSelected()
                        .then(() => {
                            this.$emit('setValid', valid);
                        })
                        .catch(console.log);
                }
                else{
                    this.$emit('setValid', valid);
                }
          }
        },
        mounted(){
        	this.getCards();
        },
        methods: {
        	async getCards(){
        		// get user cards:
                const url = window.location.origin + "/clearent/account/cards";
                const result = await this.$http.get(url).catch(console.log);
                if(result){
                	this.cards = result.data;
                }
                // select the default card:
                for(let card of this.cards){
                    if(card.hasOwnProperty('is_default')){
                        this.selectedCard = card;
                    }
                }
                // if there is no default card, select any card:
                if (!this.selectedCard && this.cards.length) {
                    this.selectedCard = this.cards[0];
                }

            },
        	newCardAdded(card){
        		this.cards.push(card);
        		this.selectedCard = card;
            },
            async submitSelected(){
        		if(!this.selectedCard){
        			return;
                }
                const url = window.location.origin + "/checkout/clearent/create_cart";
        		const data = {
                    savedCardSelectedId: this.selectedCard.id,
                };
                await this.$http.post(url, data).catch(console.log);
            },
        },
    }
</script>

<style scoped>
    .clearent-card-info,.add-card-btn{
        margin-left: 38px;
    }
    .radio-input-container {
        width: 100%;
    }

    .radio-text-container {
        float: none;
        margin-left: 3rem;
    }
</style>
