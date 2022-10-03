<template>
    <div class="modal fade credit-card-modal" id="newCardPopup" tabindex="-1" role="dialog"
         aria-labelledby="credit-card"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-xs-10 mt-5">
                                        <h1 class="modal-title font-weight-normal">Add a new credit card</h1>
                                    </div>
                                    <div class="col-xs-2">
                                        <button type="button"
                                                ref="closeModal"
                                                class="close credit-card"
                                                data-dismiss="modal"
                                                aria-label="Close" id="closeAddCard">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <form class="row" id="clearent_payment_form" action="" @submit.prevent="onSubmit">
                                    <div class="col-12">
                                        <ul class="bg-warning" id="error_con"></ul>
                                        <div class="control-group" :class="[errors.has('jwt_token') ? 'has-error' : '']">
                                            <input type="hidden" v-validate="'required'" name="jwt_token" id="jwt_token">
                                            <span class="control-error" v-if="errors.has('jwt_token')">{{ errors.first('jwt_token') }}</span>
                                        </div>
                                        <div class="control-group" :class="[errors.has('card_type') ? 'has-error' : '']">
                                            <input type="hidden" v-validate="'required'" name="card_type" id="card_type">
                                            <span class="control-error" v-if="errors.has('card_type')">{{ errors.first('card_type') }}</span>
                                        </div>
                                        <div class="control-group" :class="[errors.has('last_four') ? 'has-error' : '']">
                                            <input type="hidden" v-validate="'required'" name="last_four" id="last_four">
                                            <span class="control-error" v-if="errors.has('last_four')">{{ errors.first('last_four') }}</span>
                                        </div>
                                        <div id="payment-form"></div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb10 col-md-12 mt-5 save-card-checkbox">
                                                <span class="checkbox fs16 display-inbl no-margin custom-check-box">
                                                    <input class="ml0" type="checkbox" id="save_credit_card"
                                                           name="save_credit_card" v-model="save"/>
                                                    <label for="save_credit_card" class="custom-checkbox-view margin-r-10"></label>
                                                </span>
                                            <span class="paragraph-2 regular-font lbl">Save card</span>
                                        </div>
                                    </div>
                                    <div class="col-12 text-right mb-5">
                                        <button type="submit" id="submit_btn" class="theme-btn fs16 fw6 right modal-add-card-btn">Add a new credit card</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
	import includeClearent from "./devvly_clearent"
	export default {
    	name: "NewCard",
        data(){
    		return {
    			save: true,
                selectedCard: null,
                settings: null,
            }
        },
        mounted(){
            this.settings = {
                settings_path: "/clearent/settings",
                form_selector: "form#clearent_payment_form",
                submit_button: "#submit_btn",
                jwt_token_field: 'input[name="jwt_token"]',
                card_type_field: 'input[name="card_type"]',
                last_four_field: 'input[name="last_four"]',
                submit_callback: this.submitCallback,
            };
            includeClearent(this.settings);
        },
        methods: {
            submitCallback(){
                var arr = $(this.settings.form_selector).serializeArray();
                var formData = {};
                for(var i=0; i< arr.length; i++){
                    formData[arr[i].name] = arr[i].value;
                }
                formData.save = this.save;
                var url = window.location.origin + '/clearent/account/store/card';
                this.$http.post(url, formData)
                        .then(this.successCallback)
                        .catch(this.errorCallback);
            },
            successCallback(data){
                $(this.settings.submit_button).attr('disabled', false);
                $('#error_con').css('display: none');
                $('#clearentModal').css('display', 'none');
                this.$refs.closeModal.click();
                this.$emit('newCard', data.data);
            },
            errorCallback(jqXHR, textStatus, errorThrown){
                $(this.settings.submit_button).attr('disabled', false);
                var errorsEl = "";
                var json = jqXHR.responseJSON;
                var status;
                var statusCode = jqXHR.status;
                if(json.hasOwnProperty('message') && statusCode === 422){
                    status = "<li><b>" + json.message + "</b></li>";
                }else if(errorThrown && statusCode === 500){
                    status = "<li><b>" + errorThrown + "</b></li>";
                }
                errorsEl+= status;

                if(json.hasOwnProperty('errors')){
                    var fields = Object.keys(json.errors);
                    for(var i=0; i < fields.length; i++){
                        var field = fields[i];
                        for(var x=0; x < json.errors[field].length; x++){
                            var errText = "<li>" + json.errors[field] + "</li>";
                            errorsEl+= errText;
                        }
                    }
                }
                $('#error_con').html(errorsEl);
                $('#error_con').css('display: block');
            }
        },
    }
</script>