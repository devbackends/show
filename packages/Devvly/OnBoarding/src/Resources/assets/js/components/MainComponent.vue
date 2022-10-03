<template>
    <div>
        <div class="onboarding-steps-container d-none">
            <div class="onboarding-steps">
                <span id="business_information_step" class="step_title" v-bind:class="{active: active_step === 'business_information'}">Business Information</span>
                <span class="icon right-arrow"></span>
                <span id="merchant_profile_step" class="step_title" v-bind:class="{active: active_step === 'merchant_profile'}">Merchant Profile</span>
                <span class="icon right-arrow"></span>
                <span id="banking_step" class="step_title" v-bind:class="{active: active_step === 'banking'}">Banking</span>
                <span class="icon right-arrow"></span>
                <span id="pricing_step" class="step_title" v-bind:class="{active: active_step === 'pricing'}">Pricing</span>
            </div>
        </div>
        <div class="form-container " v-if="errors.length">
          <div class="row">
            <div class="col-lg-6">
              <div class="bg-warning text-danger clearant-errors">
                <span>{{error_message}}</span>
                <ul>
                  <li v-for="error in errors">{{error}}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div v-if="!initializing">
          <business-information
                                v-bind:merchant_number="merchant_number"
                                v-bind:mccs="mcc"
                                v-bind:states="states"
                                v-bind:contact_types="contactTypes"
                                v-bind:countries="countries"
                                v-bind:business_types="businessTypes"
                                v-on:next_step="nextStep"
                                v-on:data_submitted="dataSubmitted"
                                v-on:upload_file="onUploadFile"
                                v-on:loading="toggleLoading"
                                v-on:show_error="showError">
          </business-information>

          <merchant-profile :class="{'disabled': active_step != 'merchant_profile' && active_step != 'banking' && active_step != 'pricing' }"
                            v-bind:merchant_number="merchant_number"
                            v-bind:future_delivery_types="futureDeliveryTypes"
                            v-on:prev_step="prevStep"
                            v-on:next_step="nextStep"
                            v-on:show_error="showError"
                            v-on:loading="toggleLoading">
          </merchant-profile>

          <banking :class="{'disabled':  active_step != 'banking' && active_step != 'pricing' }"
                   v-bind:merchant_number="merchant_number"
                   v-on:next_step="nextStep"
                   v-on:data_submitted="dataSubmitted"
                   v-on:upload_file="onUploadFile"
                   v-on:loading="toggleLoading"
                   v-on:show_error="showError"
                   v-on:prev_step="prevStep">
          </banking>

          <pricing :class="{'disabled': active_step != 'pricing' }"
                   v-for="pricing_item in pricing_items"
                   v-bind:key="pricing_item.id"
                   v-bind:pricing_item="pricing_item"
                   v-bind:merchant_number="merchant_number"
                   v-on:next_step="nextStep"
                   v-on:data_submitted="dataSubmitted"
                   v-on:upload_file="onUploadFile"
                   v-on:loading="toggleLoading"
                   v-on:show_error="showError"
                   v-on:prev_step="prevStep">
          </pricing>
        </div>
      <div class="loader-container" v-if="loading">
        <div class="loader loader-fixed"></div>
      </div>
    </div>
</template>

<script>
    export default {
    	  data(){
    	  	return {
    	  		errors: [] = [],
            error_message: null,
    	  		initializing: true,
						loading: true,
            active_step: "",
            merchant_number: null,
						mcc: [] = [],
						states: [] = [],
						contactTypes: [] = [],
						countries: [] = [],
						businessTypes: [] = [],
						futureDeliveryTypes: [] = [],
            business_information_data: {},
            merchant_profile_data: {},
            banking_data: {},
            pricing_data: {},
            pricing_items: [] = [],
          }
        },
        mounted() {
          this.init();
        },
        methods: {
    	  	async init(){
						await this.getData();
						var merchant_number = localStorage.getItem('merchant_number');
						if(!merchant_number){
							var result = await this.createApp();
							if(result){
								merchant_number = result.merchant_number;
								localStorage.setItem('merchant_number', merchant_number);
              }
            }
						this.merchant_number = merchant_number;
						var active_step = localStorage.getItem('active_step');
						if (!active_step) {
							active_step = "business_information";
            }
						this.active_step = active_step;
						this.initializing = false;
						this.loading = false;
          },
					dataSubmitted(data){
						switch (data.current_step) {
							case "business_information":
								this.business_information_data = data;
								break;
							case "merchant_profile":
								this.merchant_profile_data = data;
								break;
							case "banking":
								this.banking_data = data;
								break;
							case "pricing":
								this.pricing_data = data;
								this.submitData();
						}
          },
    	  	nextStep(current_step){
    	  		var step_found = true;
    	  		switch (current_step) {
              case "business_information":
              	this.active_step = "merchant_profile";
              	break;
              case "merchant_profile":
              	this.active_step = "banking";
              	break;
              case "banking":
              	this.active_step = "pricing";
              	break;
              default:
              	step_found = false;
                break;
						}
						if (step_found) {
							localStorage.setItem('active_step', this.active_step);
            }
					},
					prevStep(current_step){
						var step_found = true;
						switch (current_step) {
							case "merchant_profile":
								this.active_step = "business_information";
								break;
							case "banking":
								this.active_step = "merchant_profile";
								break;
							case "pricing":
								this.active_step = "banking";
								break;
							default:
								step_found = false;
								break;
						}
						if (step_found) {
							localStorage.setItem('active_step', this.active_step);
						}
					},
          submitData(){

          },
          onUploadFile(file, field_name, success, error){
						this.uploadFile(file, field_name).then(success).catch(error);
          },
          toggleLoading(show = true){
						this.loading = show;
          },
          async getData(){
            var url = window.base_url + "/admin/on-boarding/general_data";
            var response = await axios.get(url).catch(() => {});
            if(response){
                            this.mcc = response.data.mcc.sort((a, b) => a.mccDescription.localeCompare(b.mccDescription))
							this.states = response.data.states;
							this.contactTypes = response.data.contact_types;
							this.countries = response.data.countries;
							this.businessTypes = response.data.business_types;
							this.futureDeliveryTypes = response.data.future_delivery_types;
							this.pricing_items = response.data.pricing;
            }
          },
          async createApp(){
						var url = window.base_url + "/admin/on-boarding/create_app";
						var error = null;
						var response = await axios.post(url,{}).catch(e => error = e);
						if (error) {
							this.showError(error);
							return;
            }
						return response.data.result;
          },
          uploadFile(file, field_name){
						var url = window.base_url + "/admin/on-boarding/documents/upload";
						var formData = new FormData();
						formData.append(field_name, file);
						var options = {
							url: url,
							method: "POST",
							data: formData,
							contentType: false,
							processData: false,
            };
						return axios(options);
          },
					showError(error){
						const errData = error.response.data;
						this.error_message = errData.message;
						var fields = Object.keys(errData.errors);
						var errors = [];
						for(let field of fields){
							if(Array.isArray(errData.errors[field])){
								for(let error of errData.errors[field]){
									errors.push(error);
								}
              }else if(typeof errData.errors[field] === "object"){
								errors.push(errData.errors[field].message);
							}else{
								errors.push(errData.errors[field]);
							}
						}
						this.errors = errors;
					},
        },
    }
</script>
