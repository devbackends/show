<template>
    <div class="modal fade credit-card-modal" id="newAuthorizeCardPopup" tabindex="-1" role="dialog"
         aria-labelledby="credit-card" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <form @submit="submit" id="paymentForm" method="POST" action="">
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
                        <div class="errors" v-if="authorizeErrors.length > 0">
                            <p class="text-danger" v-for="error in authorizeErrors">{{ error }}</p>
                        </div>



                        <div id="authorize-form" class="mb-3"></div>
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
        authorizeErrors: [],
    }),
    props: {
        authorize: Object,
        card: Object
    },
    async mounted() {

    },
    methods: {
        async submit(e) {
            e.preventDefault();

            const {token, error} = await this.authorize.createToken(this.card);

            if (error) {
                // Inform the customer that there was an error.
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                // Send the token to your server.

            }
        },

    }
}
</script>
