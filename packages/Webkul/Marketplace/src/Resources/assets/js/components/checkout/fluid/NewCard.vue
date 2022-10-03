<template>
    <div class="modal fade credit-card-modal" id="newFluidCardPopup" tabindex="-1" role="dialog"
        aria-labelledby="credit-card" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-header-content">
                        <i class="fal fa-credit-card d"></i>
                        <h5 class="modal-title">Add a new credit card</h5>
                    </div>
                    <button type="button" class="close" ref="closeModal" data-dismiss="modal" aria-label="Close" id="closeAddCard">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="errors" v-if="fluidErrors.length > 0">
                        <p class="text-danger" v-for="error in fluidErrors">{{error}}</p>
                    </div>
                    <div id="fluid-form" class="mb-3"></div>
                    <div class="form-group form-field">
                        <label class="mandatory" for="nickname">Card Nickname</label>
                        <input placeholder="Card Nickname" class="form-control" type="text" id="nickname"
                            name="nickname" v-model="nickname" data-vv-as="&quot;Nickname&quot;"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-primary"
                        @click="tokenizer.submit()"
                    >Select</button>
                </div>
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
        fluidErrors: [],
    }),
    async mounted() {
        const response = await this.$http.get(API_ENDPOINTS.fluid.getTokenizerinfo
            .replace('{sellerId}', this.$store.getters.getCart.seller_id)
        );
        if (response.data.status) {
            this.tokenizer = new Tokenizer({
                url: response.data.data.url,
                apikey: response.data.data.public_key,
                container: document.querySelector('#fluid-form'),
                submission: (res) => {
                    if (res.status === 'success') {
                        $('#newFluidCardPopup').modal('toggle');
                        this.$emit('submit', {
                            token: res.token,
                            nickname: this.nickname,
                        })
                    } else {
                        this.fluidErrors.push(res.message);
                        setTimeout(() => {
                            this.fluidErrors = [];
                        }, 3000)
                    }
                },
            })
        }
    },
}
</script>

<style scoped>

</style>