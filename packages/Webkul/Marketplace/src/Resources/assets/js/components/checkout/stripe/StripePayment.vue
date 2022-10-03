    <template>
        <div>
            <div class="cards">
                <div class="custom-control custom-radio" :key="index" v-for="(card, index) in cards">
                    <input type="radio" :id="`card-stripe-`+index" name="card-stripe" :value="index" class="custom-control-input"
                           v-model="selectedCard">
                    <label class="custom-control-label" :for="`card-stripe-`+index">{{ getCardFullName(card) }}<a href="#"><i
                        class="far fa-times ml-2"></i></a></label>
                </div>
            </div>

            <button data-toggle="modal"
                    data-target="#newStripeCardPopup" id="add-stripe-credit-card"
                    class="btn btn-outline-dark btn-sm mt-1">
                Add a new card
            </button>
            <NewCard :stripe="stripe" :card="card" @submit="submit"/>
            <div id="test"></div>
        </div>
    </template>

    <script>
    import {API_ENDPOINTS} from "../../../constant";
    import NewCard from "./NewCard";

    export default {
        name: 'StripePayment',
        components: {NewCard},
        data: () => ({
            stripeMode: null,
            stripe: null,
            card: null,
            cards: [],
            selectedCard: null,
            token: null
        }),
        props :['sellerId'],
        async mounted() {
            const stripeModeResponse = await this.$http.get('/checkout/get-stripe-mode/'+this.sellerId);
            if (stripeModeResponse.status == 200) {
                this.stripeMode = stripeModeResponse.data.key;
                this.stripe = Stripe(this.stripeMode);
                const elements = this.stripe.elements();
                // Custom styling can be passed to options when creating an Element.
                const style = {
                    base: {
                        // Add your base input styles here. For example:
                        fontSize: '16px',
                        color: '#32325d',
                    },
                };

    // Create an instance of the card Element.
                this.card = elements.create('card', {style: style});
    // Add an instance of the card Element into the `card-element` <div>.
                this.card.mount('#card-element');
            }

            const getCardResponse = await this.$http.get(API_ENDPOINTS.stripe.card.get);

            if (getCardResponse.status==200) {
                this.cards = getCardResponse.data;
            }


        },

        methods: {
           async submit(additional) {
               var this_this = this;

               if(this.selectedCard != null){
                   if(typeof this.cards[this.selectedCard].misc != 'undefined'){
                       const result = JSON.parse(this.cards[this.selectedCard].misc);
                       const paymentMethodId = result.attachedCustomer.id;
                       this_this.checkCard(result,additional,paymentMethodId,this.cards[this.selectedCard].token);
                   }
               }else{
                   this.stripe.createPaymentMethod('card', this.card).then(function (result) {
                       if (result.error) {
                           if (result.error.type == 'validation_error') {
                               console.log('validation error');
                           } else {
                               console.log('payment cancel');
                               /*this.paymentCancel();*/
                           }

                       } else {
                           const last_four='';
                           const paymentMethodId = result.paymentMethod.id;
                           const token = this_this.stripe.createToken(this_this.card);
                           token.then(async function (token) {
                               if (token.error) {
                                   const errorElement = document.getElementById('card-errors');
                                   errorElement.textContent = token.error.message;
                                   return false;
                               } else {
                                   this_this.checkCard(result,additional,paymentMethodId,token.token.id);
                               }
                           });
                       }
                   });
               }

            },
            async checkCard(result,additional,paymentMethodId,token){
                const last_four = '';
                const isSavedCard = true;
                let newCard = {
                    'nickname': additional.nickname,
                    'last_four': last_four
                }
                if (newCard.nickname === '') {
                    newCard.nickname = 'New card';
                }
                const x = await this.saveCard(result,token, paymentMethodId, isSavedCard, newCard.nickname)
                    .then(async function (saveCardResponse) {
                        return saveCardResponse;
                    });

                if (x.code == 200) {
                    if(x.message == 'success'){
                        let newCard = {
                            'nickname': additional.nickname,
                            'last_four': x.last_four
                        }
                        if (newCard.nickname === '') {
                            newCard.nickname = 'New card';
                        }
                        this.cards.push(newCard);
                        this.selectedCard = this.cards.length - 1;
                    }
                    this.$emit('setAdditionalInfo', additional);
                    this.$emit('setValid', true);
                }
            }
            ,
             async saveCard(result, token, paymentMethodId, isSavedCard,cardNickname) {
                let cart = this.$store.getters.getCart;
                 const saveCard= await this.$http.post("/checkout/save/card", {
                    result: result,
                    cart_id: cart.id,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    stripetoken: token,
                    paymentMethodId: paymentMethodId,
                    isSavedCard: isSavedCard,
                    nickname: cardNickname,
                    sellerId: this.sellerId
                },{ headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } })
                    .then(async function(response) {
                       return response;
                    })
                    .catch(function (error) {
                      console.log(error);
                    });
                 if(saveCard.status==200){
                     return saveCard.data;
                 }else{
                     return 'false';
                 }
            },
            getCardFullName(card) {
                if (card.nickname === '') return card.last_four;
                if (card.last_four === '') return card.nickname;
                return `${card.nickname} - ${card.last_four}`;
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

    #add-stripe-credit-card {
        margin-left: 2rem;
    }
    </style>