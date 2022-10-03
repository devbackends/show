<template>
    <div class="form-container">
        <div class="accordion-header-title paragraph-big">
            <div class="border-top padding-tb-20">
            Merchant Profile
                <i id="accordion-chevron" class="far fa-1x fa-chevron-right f-right"></i>
            </div>
        </div>
        <div class="accordion-container-content">
  <form @submit="submit">
    <div class="form-container">
      <div class="row">
        <div class="col-lg-4 padding-tb-10">
          <div class="control-group onboarding-field">
            <label for="estimated_annual_volume" class="">Estimated annual volume</label>
            <input type="number" id="estimated_annual_volume" v-model="annual_volume" class="form-control control number_input currency_input" required>
            <label class="onboarding-helping-text "></label>
          </div>
        </div>
        <div class="col-lg-4  padding-tb-10">
          <div class="control-group onboarding-field">
            <label for="estimated_average_ticket" class="">Estimated average ticket (Price per item)</label>
            <input type="number" id="estimated_average_ticket" v-model="average_ticket" class="form-control control number_input currency_input" required>
            <label class="onboarding-helping-text "></label>
          </div>
        </div>
        <div class="col-lg-4  padding-tb-10">
          <div class="control-group onboarding-field">
            <label for="highest_ticket" class="">Highest ticket (Highest priced items)</label>
            <input type="number" id="highest_ticket" v-model="high_ticket" class="form-control control number_input currency_input" required>
            <label class="onboarding-helping-text" id="highest_ticket_label"></label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4  padding-tb-10">
          <div class="control-group onboarding-field">
            <label for="products_sold" class="">Products/Services sold</label>
            <input type="text" id="products_sold" v-model="products_sold" class=" form-control control number_input" required>
            <label class="onboarding-helping-text "></label>
          </div>
        </div>
        <div class="col-lg-4  padding-tb-10">
          <div class="control-group onboarding-field">
            <label for="amex_mid" class="">AMEX MID</label>
            <input type="text" pattern="[0-9]{10}" id="amex_mid" v-model="amex_mid" placeholder="3025824968" class="form-control control number_input">
            <label class="onboarding-helping-text ">If your merchant supports American Express ESA Direct, please enter the number.</label>
          </div>
        </div>
        <div class="col-lg-4  padding-tb-10">
          <div class="control-group onboarding-field">
            <label for="ebt_number" class="">EBT number</label>
            <input type="text" pattern="[0-9]{20}" id="ebt_number" v-model="ebt_number" placeholder="56790257923456671059" class="form-control control number_input">
            <label class="onboarding-helping-text ">If your merchant accepts EBT, please enter the number.</label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4  padding-tb-10" id="future_delivery_container">
          <div class="control-group onboarding-field radio-container">
            <label>Does any of the merchant sales result in product or service being delivered beyond the time of sale?</label>
            <span class="onboadrding-radio-box">
                    <input type="radio" :value="true" name="has_future_delivery" v-model="has_future_delivery" class="future_delivery" required>
                    <label class="onboadrding-radio-view"></label>
                </span>
            <span class="radio-title">Yes</span>

            <span class="onboadrding-radio-box">
                    <input type="radio" :value="false" name="has_future_delivery" v-model="has_future_delivery" class="future_delivery" required>
                    <label class="onboadrding-radio-view"></label>
                </span>
            <span class="radio-title">No</span>
          </div>
          <label class="onboarding-helping-text d-none"></label>
        </div>

        <div class="col-lg-4  padding-tb-10" v-if="has_future_delivery">
          <div class="control-group select onboarding-field">
            <label for="future_delivery_type">Future delivery type</label>
            <label class="select_label">
              <select id="future_delivery_type" required v-model="future_delivery_type_id" class="form-control control customSelect future_delivery_type" >
                <option value="">Type</option>
                <option v-for="type in future_delivery_types" v-bind:value="type.futureDeliveryTypeID">{{ type.futureDeliveryTypeDescription }}</option>
              </select>
            </label>
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-4  padding-tb-10" v-if="future_delivery_type_id === 3">
          <div class="control-group onboarding-field">
            <label for="future_delivery_timing" class="">What is the time frame for future delivery?</label>
            <input type="text" id="future_delivery_timing" placeholder="8-14 days, 15+..etc" v-model="other_delivery_type" value="" class="form-control control">
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-4  padding-tb-10" v-if="has_future_delivery ">
          <div class="control-group onboarding-field">
            <label for="future_delivery_percentage" class="">Future delivery transactions %</label>
            <input type="number" id="future_delivery_percentage" placeholder="0" v-model="future_delivery_percentage" value="" class="form-control control">
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-4  padding-tb-10">
          <div class="control-group onboarding-field">
            <label for="refund_policy" class="">Enter here the refund policy</label>
            <input type="text" id="refund_policy" v-model="return_refund_policy" value="" class="form-control control">
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
      </div>
      <div class="row form-bottom-container">
        <div class="col-lg-12">
          <div class="onboarding-button-container">
            <button type="submit" class="onboarding-next-button">Save and continue</button>
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
		props: ['merchant_number','future_delivery_types'],
		data(){
			return {
				has_future_delivery: null,
				is_ecommerce: true,
				mcc_code: "",
				card_present_percentage: 0,
				return_refund_policy: "",
				products_sold: "",
				previously_accepted_payment_cards: null,
				previously_terminated_or_identified_by_risk_monitoring: null,
				reason_previously_terminated_or_identified_by_risk: null,
				currently_open_for_business: null,
				annual_volume: 0,
				average_ticket: 0,
				high_ticket: 0,
				sells_firearms: null,
				sells_firearm_accessories: null,
				future_delivery_type_id: null,
				other_delivery_type: "",
				future_delivery_percentage: 0,
				fire_arms_license: "",
				firearms_license_document_path: "",
				card_brands: [] = [],
				ebt_number: "",
				amex_mid: ""
      }
    },
		mounted(){
			this.init();
		},
    methods: {
			init(){
				this.loadSavedData();
			},
			prevStep(){
				this.$emit('prev_step', 'merchant_profile');
      },
			appendRemoteData(data){
				for(let key of Object.keys(data)){
					this[key] = data[key];
				}
				this.saveLocally();
			},
			loadSavedData(){
				var data = localStorage.getItem('merchant_profile');
				if (!data) {
					var business_information = localStorage.getItem('business_information');
					if(!business_information){
						return;
					}
					business_information = JSON.parse(business_information);
					var sales_profile = business_information.sales_profile;
					var attrs = Object.keys(sales_profile);
					for(let attr of attrs){
						this[attr] = sales_profile[attr];
					}
					return;
				}
				data = JSON.parse(data);
				for(let key of Object.keys(data)){
					this[key] = data[key];
				}
			},
			saveLocally(){
				var data = JSON.stringify(this.$data);
				localStorage.setItem('merchant_profile', data);
			},
			async submit(e){
				e.preventDefault();
				this.$emit('loading', true);
				this.is_ecommerce = true;
				this.card_present_percentage = 0;
				var profile = {};
				var card_brands = [2,3,4,5];
				if (this.ebt_number.length) {
					card_brands.push(6);
        }
				if (this.amex_mid.length) {
					card_brands.push(7);
        }
				else{
					card_brands.push(1);
        }
				//1 American Express OptBlue
				//2 Debit Network
				//3 Discover
				//4 MasterCard
				//5 Visa
				//6 EBT
				//7 American Express ESA
				var error = null;
				var ignore = ['has_future_delivery'];
				for(let key of Object.keys(this.$data)){
					if(ignore.includes(key)){
						continue;
          }
					profile[key] = this.$data[key];
        }
				profile.card_brands = card_brands;
				var url = window.base_url + "/admin/on-boarding/sales_profile/" + this.merchant_number;
				var response = await axios.put(url, profile).catch(e => error = e);
				this.$emit('loading', false);
				if (error) {
					this.showError(error);
					return;
				}
				profile.has_future_delivery = this.has_future_delivery;
				this.$emit('next_step', 'merchant_profile');
      },
			showError(error) {
				this.$emit('show_error', error);
			}
    },
	}
</script>