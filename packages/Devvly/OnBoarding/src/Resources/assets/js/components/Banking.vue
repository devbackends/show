<template>

  <div class="form-container">
      <div class="accordion-header-title paragraph-big">
          <div class="border-top padding-tb-20">
          Banking
              <i id="accordion-chevron" class="far fa-1x fa-chevron-right f-right"></i>
          </div>
      </div>
      <div class="accordion-container-content">
  <form @submit="submit" v-on:change="saveLocally">
      <div class="form-container">
        <div class="row">
          <div class="col-lg-6">
            <div class="control-group onboarding-field">
              <label for="bank_name">Bank name </label>
              <input type="text" id="bank_name" v-model="bank_name" class="form-control control" placeholder="Bank name" required>
              <label class="onboarding-helping-text "></label>
            </div>
          </div>
          <div class="col-lg-6">
                    <div class="control-group onboarding-field radio-container">
                      <label>Is the Merchant's account under Legal Name or DBA?</label>
                      <span class="onboadrding-radio-box">
                          <input type="radio" name="account_name_type" :value="1" v-model="bank_account_name_type_id" required>
                          <label class="onboadrding-radio-view"></label>
                      </span>
                      <span class="radio-title">Legal Name</span>

                      <span class="onboadrding-radio-box">
                          <input type="radio" name="account_name_type" :value="2" v-model="bank_account_name_type_id" >
                          <label class="onboadrding-radio-view"></label>
                      </span>
                      <span class="radio-title" >DBA</span>

                      <span class="onboadrding-radio-box">
                              <input type="radio" name="account_name_type" :value="3" v-model="bank_account_name_type_id" >
                              <label class="onboadrding-radio-view"></label>
                      </span>
                      <span class="radio-title" >Other</span>
                    </div>
                    <label class="onboarding-helping-text"></label>

          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="control-group onboarding-field">
              <label for="name_on_account" class="">Name on account</label>
              <input type="text"
                     id="name_on_account"
                     v-model="name_on_account"
                     placeholder="Name on account"
                     class="form-control control"
                     required>
              <label class="onboarding-helping-text "></label>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="control-group onboarding-field">
              <label for="routing_number">Routing number</label>
              <input type="text"
                     id="routing_number"
                     v-model="aba"
                     placeholder="Routing number"
                     class="form-control control"
                     required>
              <label class="onboarding-helping-text "></label>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="control-group onboarding-field">
              <label for="account_number" class="">Account number</label>
              <input type="text"
                     id="account_number"
                     v-model="account_number"
                     placeholder="Account number"
                     class="form-control control" required>
              <label class="onboarding-helping-text "></label>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="control-group onboarding-field radio-container">
              <label>Account type</label>
              <span class="onboadrding-radio-box">
                      <input type="radio" name="account_type" :value="1" v-model="bank_account_type_id" required>
                      <label class="onboadrding-radio-view"></label>
                  </span>
              <span class="radio-title" >Checking</span>

              <span class="onboadrding-radio-box">
                      <input type="radio" name="account_type" :value="2" v-model="bank_account_type_id" >
                      <label class="onboadrding-radio-view"></label>
                  </span>
              <span class="radio-title" >Saving</span>
            </div>
            <label class="onboarding-helping-text"></label>

          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="control-group onboarding-field radio-container">
              <label>Account Use(All Options must be accounted for by your merchants bank account(s))</label>
              <span class="checkbox no-margin custom-check-box">
                     <input type="checkbox" v-model="has_funds">
                     <label  for="custom-checkbox-view " class="custom-checkbox-view dblock"></label>
              </span>
              <span class="checkbox-title" >Deposits</span>


              <span  class="checkbox no-margin custom-check-box">
                    <input type="checkbox" v-model="has_fees">
                    <label for="custom-checkbox-view " class="custom-checkbox-view dblock"></label>
              </span>
              <span class="checkbox-title" >Fees</span>


              <span  class="checkbox no-margin custom-check-box">
                   <input type="checkbox" v-model="has_chargebacks">
                   <label  for="custom-checkbox-view " class="custom-checkbox-view dblock"></label>
              </span>
              <span class="checkbox-title" >Chargebacks</span>


            </div>
            <label class="onboarding-helping-text"></label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="upload-container">
              <input class="custom_upload" id="license_id" name="license_id" type="file"  v-on:change="uploadFile('license_id')"/>
              <div><span class="icon license-icon"></span></div>
              <div><span id="license_file_name" class="onboarding-upload-text">Upload License ID</span></div>
              <div class="onboard-upload-button-container"><span class="onboarding-upload-button"><span class="onboarding-upload-button-text">Upload</span></span></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="upload-container">
              <input class="custom_upload" id="voided_check" name="voided_check" type="file"   v-on:change="uploadFile('voided_check')" />
              <div><span class="icon voided-check-icon"></span></div>
              <div><span id="voided_check_file_name" class="onboarding-upload-text">Upload Voided Check</span></div>
              <div class="onboard-upload-button-container"><span class="onboarding-upload-button"><span class="onboarding-upload-button-text">Upload</span></span></div>
            </div>
          </div>
          <div class="col-lg-4"></div>
        </div>
        <div class="row form-bottom-container">
          <div class="col-lg-12">
            <div class="onboarding-button-container">
              <button type="submit" class="onboarding-next-button">
                Save and continue
              </button>
            </div>
          </div>

        </div>
      </div>
    </form>
      </div>
  </div>


</template>

<script>
	export default {
		props: ['merchant_number'],
		data(){
			return {
				current_step: "banking",
                bank_account_id: null,
				bank_name: null,
				name_on_account: null,
				account_holder_first_name: null,
				account_holder_last_name: null,
				bank_account_type_id: 0,
				bank_account_name_type_id: 0,
				aba: null,
				account_number: null,
				voided_check_document_path: null,
				license_id_document_path: null,
				has_fees: null,
				has_funds: null,
				has_chargebacks: null,
				is_name_same_as_legal_or_dba_name: null
			}
    },
    mounted(){
			this.loadSavedData();
    },
		methods: {

			async init(){
				var error = null;
				this.$emit('loading', true);
				var url = window.base_url + "/admin/on-boarding/banking/" + this.merchant_number;
				var response = await axios.get(url).catch(e => error = e);
				if(error){
					this.showError(error);
        }
				this.appendRemoteData(response.data);
				this.$emit('loading', false);
			},
      appendRemoteData(data){
				for(let key of Object.keys(data)){
					this[key] = data[key];
				}
				this.saveLocally();
			},
      loadSavedData(){
				var banking = localStorage.getItem('banking');
				if (banking) {
					banking = JSON.parse(banking);
					for(let key of Object.keys(banking)){
						this[key] = banking[key];
					}
				}
      },
      saveLocally(){
				var data = JSON.stringify(this.$data);
				localStorage.setItem('banking', data);
			},
			async submit(e){
				e.preventDefault();
				if(this.has_funds){
					this.is_name_same_as_legal_or_dba_name = true;
        }else{
        					this.is_name_same_as_legal_or_dba_name = false;
        }
				var error = null;
				var banking = {};
				var ignore = [];
				for(let key of Object.keys(this.$data)){
					if(ignore.includes(key)){
						continue;
					}
					banking[key] = this.$data[key];
				}
				this.$emit('loading', true);
				var url = window.base_url + "/admin/on-boarding/banking/" + this.merchant_number;
				var response = await axios.put(url, banking).catch(e => error = e);
				this.$emit('loading', false);
				if (error) {
					this.showError(error);
					return;
				}
				this.appendRemoteData(response.data);
				this.$emit('next_step', this.current_step);
			},
      prevStep(){
				this.$emit('prev_step', 'banking');
      },
			showError(error) {
				this.$emit('show_error', error);
			},
			async uploadFile(file){
				var error = null;
				if(file === 'license_id'){
					await this.uploadLicenseId().catch(err => error = err);
        }
				if(file === 'voided_check'){
					await this.uploadVoidedCheck().catch(err => error = err);
        }
				this.$emit('loading', false);
				if(error){
					this.showError(error);
				}
			},
			uploadLicenseId(){
				return new Promise((resolve, reject) => {
					var files = $('#license_id').prop('files');
					if(!files.length){
						resolve();
						return;
					}
					if(!files.length){
						console.log('no file selected');
						return;
					}
					var success = (response) => {
						this.license_id_document_path = response.data.result.license_id;
						this.saveLocally();
						resolve();
					};
					var error = (err) => {
						reject(err);
					};
					this.$emit('loading');
					this.$emit('upload_file', files[0],'license_id', success, error);
				})
			},
			uploadVoidedCheck(){
				return new Promise((resolve, reject) => {
					var files = $('#voided_check').prop('files');
					if(!files.length){
						resolve();
						return;
					}
					if(!files.length){
						console.log('no file selected');
						return;
					}
					var success = (response) => {
						this.voided_check_document_path = response.data.result.voided_check;
						this.saveLocally();
						resolve();
					};
					var error = (err) => {
						reject(err);
					};
					this.$emit('loading');
					this.$emit('upload_file', files[0],'voided_check', success, error);
				})
			},
    },
	}
</script>