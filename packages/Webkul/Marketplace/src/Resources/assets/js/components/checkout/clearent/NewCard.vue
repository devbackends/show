<template>
  <div
    class="modal fade credit-card-modal"
    id="newCardPopup"
    tabindex="-1"
    role="dialog"
    aria-labelledby="credit-card"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <button
            type="button"
            ref="closeModal"
            class="close credit-card"
            data-dismiss="modal"
            aria-label="Close"
            id="closeAddCard"
          >
            <i class="far fa-times" aria-hidden="true"></i>
          </button>
          <div class="container-fluid">
            <h1 class="modal-title font-weight-normal">Add a new credit card</h1>
            <div class="row">
              <div class="col">
                <form id="clearent_payment_form" action @submit.prevent="onSubmit">
                  <div class="row">
                    <div class="col-12">
                      <ul class="bg-warning" id="error_con"></ul>
                      <div class="form-group" :class="[errors.has('jwt_token') ? 'has-error' : '']">
                        <input
                          type="hidden"
                          v-validate="'required'"
                          name="jwt_token"
                          id="jwt_token"
                        />
                        <span
                          class="control-error"
                          v-if="errors.has('jwt_token')"
                        >{{ errors.first('jwt_token') }}</span>
                      </div>
                      <div class="form-group" :class="[errors.has('card_type') ? 'has-error' : '']">
                        <input
                          type="hidden"
                          v-validate="'required'"
                          name="card_type"
                          id="card_type"
                        />
                        <span
                          class="control-error"
                          v-if="errors.has('card_type')"
                        >{{ errors.first('card_type') }}</span>
                      </div>
                      <div class="form-group" :class="[errors.has('last_four') ? 'has-error' : '']">
                        <input
                          type="hidden"
                          v-validate="'required'"
                          name="last_four"
                          id="last_four"
                        />
                        <span
                          class="control-error"
                          v-if="errors.has('last_four')"
                        >{{ errors.first('last_four') }}</span>
                      </div>
                      <div id="payment-form"></div>
                    </div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-12 col-md">
                      <div class="form-check">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          value
                          id="save_credit_card"
                          name="save_credit_card"
                          v-model="save"
                        />
                        <label class="form-check-label" for="save_credit_card">Save card</label>
                      </div>
                    </div>
                    <div class="col-12 col-md-auto">
                      <button
                        type="submit"
                        id="submit_btn"
                        class="btn btn-primary"
                      >Add a new credit card</button>
                    </div>
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
import includeClearent from "./devvly_clearent";
export default {
  name: "NewCard",
  data() {
    return {
      save: true,
      selectedCard: null,
      settings: null,
    };
  },
  mounted() {
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
    submitCallback() {
      var arr = $(this.settings.form_selector).serializeArray();
      var formData = {};
      for (var i = 0; i < arr.length; i++) {
        formData[arr[i].name] = arr[i].value;
      }
      formData.save = this.save;
      var url = window.location.origin + "/clearent/account/store/card";
      this.$http
        .post(url, formData)
        .then(this.successCallback)
        .catch(this.errorCallback);
    },
    successCallback(data) {
      $(this.settings.submit_button).attr("disabled", false);
      $("#error_con").css("display: none");
      $("#clearentModal").css("display", "none");
      this.$refs.closeModal.click();
      this.$emit("newCard", data.data);
    },
    errorCallback(jqXHR, textStatus, errorThrown) {
      $(this.settings.submit_button).attr("disabled", false);
      var errorsEl = "";
      var json = jqXHR.responseJSON;
      var status;
      var statusCode = jqXHR.status;
      if (json.hasOwnProperty("message") && statusCode === 422) {
        status = "<li><b>" + json.message + "</b></li>";
      } else if (errorThrown && statusCode === 500) {
        status = "<li><b>" + errorThrown + "</b></li>";
      }
      errorsEl += status;

      if (json.hasOwnProperty("errors")) {
        var fields = Object.keys(json.errors);
        for (var i = 0; i < fields.length; i++) {
          var field = fields[i];
          for (var x = 0; x < json.errors[field].length; x++) {
            var errText = "<li>" + json.errors[field] + "</li>";
            errorsEl += errText;
          }
        }
      }
      $("#error_con").html(errorsEl);
      $("#error_con").css("display: block");
    },
  },
};
</script>