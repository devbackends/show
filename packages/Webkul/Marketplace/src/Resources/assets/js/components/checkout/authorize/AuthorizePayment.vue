<template>
    <div>
        <div class="cards">
            <div class="custom-control custom-radio" :key="index" v-for="(card, index) in cards">
                <input type="radio" :id="`card-authorize-`+index" name="card-authorize" :value="index" class="custom-control-input" v-model="selectedCard">
                <label class="custom-control-label" :for="`card-authorize-`+index">{{ getCardFullName(card) }}<a href="#"><i
                    class="far fa-times ml-2"></i></a></label>
            </div>
        </div>
        <button id="add-authorize-credit-card" v-on:click="simulateAuthorizeButton" class="btn btn-outline-dark btn-sm mt-1">Add a new card</button>
        <!--<NewCard :authorize="authorize" :card="card" @submit="submit"/>-->
    </div>
</template>

<script>
import {API_ENDPOINTS} from "../../../constant";
import NewCard from "./NewCard";

export default {
    name: 'AuthorizePayment',
    components: {NewCard},
    data: () => ({
        authorizeMode: null,
        authorize: null,
        card: null,
        cards: [],
        selectedCard: null,
        token: null
    }),
    props :['sellerId'],
    async mounted() {

        const getCardResponse = await this.$http.get(API_ENDPOINTS.authorize.card.get);
        console.log(getCardResponse);
        if (getCardResponse.status==200) {
            this.cards = getCardResponse.data;
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
        simulateAuthorizeButton(){

            $('#authorizePay').click();
        },
        responseHandler(response) {

            if (response.messages.resultCode === "Error") {
                var i = 0;
                while (i < response.messages.message.length) {
                    i = i + 1;
                }
            } else {
                this.paymentFormUpdate(response);
            }
        },
        async paymentFormUpdate(response) {
            document.getElementById("dataDescriptor").value = response.opaqueData.dataDescriptor;
            document.getElementById("dataValue").value = response.opaqueData.dataValue;
            const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const sendTokenResponse = await this.$http.post('/checkout/authorize/sendtoken',{_token: csrf_token, response: response});
            console.log('sendTokenResponse');
            console.log(sendTokenResponse);
            if(sendTokenResponse.status==200){
                let newCard = {
                    'nickname':  response.customerInformation.firstName,
                    'last_four': response.encryptedCardData.cardNumber.substr(response.encryptedCardData.cardNumber, - 4)
                }

                this.cards.push(newCard);
                this.selectedCard = this.cards.length-1;
                const additional={
                    'token': response.opaqueData.dataValue,
                    'nickname': response.customerInformation.firstName
                };
                this.$emit('setAdditionalInfo', additional);
                this.$emit('setValid', true);
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
                        'nickname':  card.nickname,
                        'last_four': card.last_four,
                        'token': card.token,
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
#add-authorize-credit-card {
    margin-left: 2rem;
}
</style>