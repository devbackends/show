<template>



<div class="form-container">
    <div class="accordion-header-title paragraph-big">
        <div class="border-top padding-tb-20">
        Pricing
            <i id="accordion-chevron" class="far fa-1x fa-chevron-right f-right"></i>
        </div>
    </div>
    <div class="accordion-container-content">
    <div class="padding-tb-45">

        <div class="pricing-detail-container">
                <div class="control-group-third">
                    <h1 class="heading success-color align-center no-margin"> ${{monthlySubscription}} </h1>
                    <h3 class="heading align-center no-margin success-color"> Monthly subscription </h3>
                    <h4 class="heading align-center no-margin success-color"> (Already configured) </h4>
                </div>
                <div class="control-group-third">
                    <h1 class="heading info-dark align-center no-margin"> {{ pricing_item.flat_rate.toFixed(2) }}% </h1>
                    <h3 class="heading info-dark align-center no-margin"> Credit card processing </h3>
                </div>
                <div class="control-group-third">
                    <h1 class="heading info-dark align-center no-margin"> ${{ pricing_item.authorization_fee.toFixed(1) }} </h1>
                    <h3 class="heading info-dark align-center no-margin"> Transaction fee </h3>
                </div>
        </div>
    </div>





            <div class="pricing-fees-container">


                    <div class="width-100 h-overflow  padding-tb-45"  >
                        <h2 class="heading bold align-center poster-font">Pricing Detail</h2>
                    </div>


                    <div class="width-100 h-overflow padding-20" style="border-top:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;">
                        <span class="paragraph-big bold black no-margin ">Monthly subscription</span>
                        <a class="bordered-button light pricing-detail-button"><span>See detail</span></a>
                        <span class="paragraph-big bold black no-margin f-right ">${{monthlySubscription}}</span>
                        <span  class="already-configured paragraph bold black no-margin  success-color f-right padding-sides-20"> Already configured <span><i class="far fa-1x fa-check"></i></span> </span>

                    </div>



                    <form id="pricing_form">
                        <div class="form-container  margin-t-30">

                            <div class="">
                                <div class="full_width">
                                    <div class="padding-tb-45">
                                        <span class="paragraph-big bold black no-margin  padding-sides-20">Card processing and transaction fee</span>
                                    </div>



                                    <table class="pricing-table ">
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Flat Rate Credit Card Fee</p></td>
                                            <td class="second_column"><p>{{ pricing_item.flat_rate.toFixed(2) }}%</p></td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Authorization Fee</p>
                                            </td>
                                            <td class="second_column"><p>${{ pricing_item.authorization_fee.toFixed(1) }} per transaction</p></td>
                                        </tr>
                                    </table>
                                    <div class="padding-tb-45">
                                        <p class="paragraph-big bold black no-margin  padding-sides-20">Miscellaneous fees</p>
                                        <p class="paragraph  black no-margin  padding-sides-20">(Rarely Incurred)</p>
                                    </div>

                                        <table class="pricing-table ">
                                            <tr>
                                                <td class="first_column"><p class="table-main-text">Residency Fee</p></td>
                                                <td class="second_column"><p>${{pricing_item.monthly_account_fee}}</p></td>
                                            </tr>
                                            <tr>
                                                <td class="first_column"><p class="table-main-text">2agun Fee</p></td>
                                                <td class="second_column"><p>${{pricing_item.whalut_fee}}</p></td>
                                            </tr>
                                            <tr>
                                                <td class="first_column"><p class="table-main-text">Data Gardian Fee</p>
                                                </td>
                                                <td class="second_column"><p>${{pricing_item.monthly_data_guardian_fee}}</p>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td class="first_column"><p class="table-main-text">IVR/DialPay
                                                        Authorization</p>
                                                    <p class="table-description-text">Only if used</p></td>
                                                <td class="second_column"><p>${{pricing_item.ivr}}</p></td>
                                            </tr>
                                            <tr>
                                                <td class="first_column"><p class="table-main-text">Voice Authorization</p>
                                                    <p class="table-description-text">very rarely used</p></td>
                                                <td class="second_column"><p>${{pricing_item.voice_authorization}}</p></td>
                                            </tr>
                                            <tr>
                                                <td class="first_column"><p class="table-main-text">Non Complete PCI
                                                        Questionnaire Fee</p>
                                                    <p class="table-description-text">Only if non-compliant</p></td>
                                                <td class="second_column"><p>${{pricing_item.non_complete_pci_fee}}</p></td>
                                            </tr>
                                            <tr>
                                                <td class="first_column"><p class="table-main-text">Chargeback Fee</p>
                                                    <p class="table-description-text">Only if chargeback occurs</p></td>
                                                <td class="second_column"><p>${{pricing_item.chargback_fee}}</p></td>
                                            </tr>
                                            <tr>
                                                <td class="first_column"><p class="table-main-text">Retrieval Fee</p>
                                                    <p class="table-description-text">Only if customers bank requests copy
                                                        of sales draft to Substantiate transactio</p></td>
                                                <td class="second_column"><p>${{pricing_item.retrieval_fee}}</p></td>
                                            </tr>
                                            <tr>
                                                <td class="first_column"><p class="table-main-text">Express Merchant Funding
                                                        Fee</p></td>
                                                <td class="second_column"><p>{{pricing_item.express_merchant_funding}}%</p>
                                                </td>
                                            </tr>
                                        </table>



                                </div>
                            </div>


                        </div>
                    </form>


            </div>

            <div class="padding-tb-30 width-100 terms-container">
                <div class="subscription-terms-container padding-30">
                    <p class="paragraph bold black padding-5">Terms and conditions</p>
                    <p class="paragraph black padding-5">
                        ipsum dolor sit amet, consectetur adipiscing elit. Morbi porttitor, tortor vestibulum vulputate accumsan, est diam vestibulum purus, nec auctor sapien neque non neque. Sed ornare convallis risus ac ullamcorper. Maecenas at maximus sapien. Nulla non ipsum eget enim hendrerit fermentum. Vivamus faucibus risus ut tellus ullamcorper fermentum. Mauris sagittis sem nibh, et aliquam nibh efficitur id. Sed fringilla cursus neque, nec commodo est pretium non. Nulla facilisi. Morbi a ex in nulla maximus porttitor et varius sapien. Cras hendrerit mi eu ante vulputate placerat. In vitae eleifend massa. Sed tristique, justo eu eleifend tristique, ipsum justo finibus ipsum, vitae egestas dolor metus sed ex. Fusce hendrerit massa at lobortis accumsan. Curabitur finibus dolor vel consequat pharetra. Proin id aliquet massa, quis ultricies velit. Vivamus ultricies dolor et metus suscipit, nec sollicitudin dolor ornare. Maecenas tincidunt urna at massa congue, a vestibulum ante laoreet. Curabitur vehicula placerat lacus, id interdum erat malesuada vitae. Duis auctor luctus velit, non volutpat sapien aliquet sed. Nunc volutpat placerat volutpat. Donec eget enim et sapien ultrices tempor. Integer ut mattis ante, at scelerisque ex. Donec tempor purus est, sit amet rhoncus leo iaculis quis. Sed dapibus orci ex, id auctor lectus sagittis ut. Proin vitae ligula tristique, blandit nunc at, viverra erat. Vivamus lacinia erat quam, sed finibus eros dictum sit amet. Nulla mollis viverra est, in rutrum quam maximus et. Nullam viverra felis in varius accumsan. Phasellus tempus leo eu eros sollicitudin, in tincidunt libero consectetur. Pellentesque quis hendrerit leo, nec rhoncus velit. Donec nunc nulla, maximus ac vestibulum quis, pellentesque accumsan risus. Nunc ultrices ex ac libero posuere, vitae varius mi imperdiet. Cras venenatis mi eget dui feugiat, nec tincidunt nibh vestibulum. In hac habitasse platea dictumst. Nullam vel quam facilisis, posuere mauris venenatis, dapibus lorem. Nunc id purus auctor, volutpat ligula nec, iaculis augue. Ut id turpis ac libero auctor consequat. Mauris mauris velit, hendrerit nec urna at, tempus commodo erat. Vestibulum bibendum, quam quis aliquam laoreet, mi neque rutrum augue, in condimentum tortor massa at mauris. Duis suscipit magna eget felis porttitor finibus.
                    </p>
                </div>
            </div>
            <div class="padding-tb-30 width-100 agree-container h-overflow">
                <div class="first-step-submission">
                    <div class="checkbox">
                                        <span class="checkbox no-margin custom-check-box">
                                            <input type="checkbox" id="10" value="10">
                                            <label for="custom-checkbox-view " class="custom-checkbox-view dblock"></label>
                                        </span>
                        <span class="paragraph regular-font  subscription-alert-info">I ,  <input type="text" placeholder="Complete with your full name" class="control " id="user-validate-name"/> and agree with the terms and conditions</span>
                    </div>
                </div>
            </div>
            <div class="padding-tb-30 width-100">
                <div class="first-step-submission">
                    <a href=""    v-bind:disabled="signed.length !== 9" v-on:click="submit" class="btn btn-lg btn-primary">
                        Submit your application
                    </a>

                </div>
            </div>

</div>
</div>



</template>

<script>
	export default {
		props: ['pricing_item', 'merchant_number'],
    computed: {
			monthlySubscription(){
				var monthly_subscription =
            this.pricing_item.whalut_fee +
            this.pricing_item.monthly_data_guardian_fee +
            this.pricing_item.monthly_account_fee;
				return monthly_subscription;
      }
    },
    data(){
			return {
				terms: [],
        signed: [],
      }
    },
    mounted(){
			this.getTerms();
    },
    methods: {
			async getTerms(){
				this.$emit('loading', true);
				var error = null;
				var url = window.base_url + "/admin/on-boarding/terms/" + this.merchant_number;
				var response = await axios.get(url).catch(e => error = e);
				this.terms = response.data.content;
				this.$emit('loading', false);
      },
      async submit(e){
				this.$emit('loading', true);
				var contact = this.getSignerContact();
				var id = contact.business_contact_id;
				var data,error,url,response;
				data = {
					business_contact_id: id,
          merchant_number: this.merchant_number
        };
				// update signatures:
				url = window.base_url + "/admin/on-boarding/terms/signatures";
				response = await axios.put(url, data).catch(e => error = e);
				if (error) {
					this.showError(error);
					this.$emit('loading', false);
					return;
				}
				// submit the app:
				data = {
					merchant_number: this.merchant_number
				};
				url = window.base_url + "/admin/on-boarding/submit";
				response = await axios.post(url, data).catch(e => error = e);
				this.$emit('loading', false);
				if (error) {
					this.showError(error);
					return;
				}
      },
      getSignerContact(){
				var business_information = localStorage.getItem('business_information');
				business_information = JSON.parse(business_information);
				var contacts = [];
				for(let contact of business_information.business_contacts){
					for(let type of contact.contact_types){
						if(type.contact_type_id === 2){
							contacts.push(contact);
            }
					}
				}
				return contacts[0];
      },
			prevStep(){
				this.$emit('prev_step','pricing');
      },
			showError(error) {
				this.$emit('show_error', error);
			}
    }
	}
</script>