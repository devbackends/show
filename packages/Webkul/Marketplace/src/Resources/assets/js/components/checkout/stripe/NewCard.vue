<template>
    <div class="modal fade credit-card-modal" id="newStripeCardPopup" tabindex="-1" role="dialog"
         aria-labelledby="credit-card" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <form @submit="submit" method="post" id="payment-form">
                    <div class="modal-header">
                        <div class="modal-header-content">
                            <i class="fal fa-credit-card d"></i>
                            <h5 class="modal-title">Add a new credit card</h5>
                        </div>
                        <button type="button" class="close" ref="closeModal" data-dismiss="modal" aria-label="Close"
                                id="closeAddCard">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="errors" v-if="stripeErrors.length > 0">
                            <p class="text-danger" v-for="error in stripeErrors">{{ error }}</p>
                        </div>

                        <div id="card-element">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>

                        <div id="stripe-form" class="mb-3"></div>
                        <div class="form-group form-field">
                            <label class="mandatory" for="nickname">Card nick name</label>
                            <input placeholder="Card Nickname" class="form-control" type="text" id="nickname"
                                   name="nickname" v-model="nickname" data-vv-as="&quot;Nickname&quot;"/>
                        </div>



                        <!-- Used to display Element errors. -->
                        <div id="card-errors" role="alert"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Select</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {API_ENDPOINTS} from "../../../constant";

export default {
    name: "NewCard",
    data: () => ({
        tokenizer: null,
        nickname: '',
        stripeErrors: [],
    }),
    props: {
        stripe: Object,
        card: Object
    },
    async mounted() {

    },
    methods: {
        async submit(e) {
            e.preventDefault();
            const {token, error} = await this.stripe.createToken(this.card);

            if (error) {
                // Inform the customer that there was an error.
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                // Send the token to your server.
                this.stripeTokenHandler(token);
            }
        },
        stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            const form = document.getElementById('payment-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            this.$emit('submit', {
                'token': token.id,
                'nickname': this.nickname
            });
            $('#newStripeCardPopup').modal('hide');
        }
    }
}
</script>

<style scoped>

</style>