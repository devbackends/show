<template>
  <div class="form-container">
      <div class="accordion-header-title paragraph-big">
          <div class="border-top padding-tb-20">
          Business Information
              <i id="accordion-chevron" class="far fa-1x fa-chevron-right f-right"></i>
          </div>
      </div>
      <div class="accordion-container-content">
  <div v-if="!loading" v-on:change="saveLocally">
    <div class="row">
      <div class="col-lg-12  padding-tb-10">
        <ul>
          <li v-for="error in errors">{{error}}</li>
        </ul>
      </div>
    </div>
    <form @submit="postData">
      <div class="form-container">
        <div class="row d-none" id="error_container">
          <div class="col-md-6  padding-tb-10">
            <ul class="bg-warning text-danger d-none" id="errors_container"></ul>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6  padding-tb-10" id="dba_name_container">
            <div class="control-group onboarding-field">
              <label for="dba_name" class="">DBA Name</label>
              <input type="text" id="dba_name" name="dba_name" v-model="merchant.dba_name" value="" class="form-control control" required>
              <label class="onboarding-helping-text d-none"></label>
            </div>
          </div>
          <div class="col-lg-6  padding-tb-10" id="mcc_container">
            <div class="control-group select onboarding-field">
              <label for="mcc">Industry (MCC)</label>
              <label class="select_label">
                <select id="mcc" name="mcc_code" class="form-control control customSelect" v-model="sales_profile.mcc_code" required>
                  <option v-for="mccItem in mccs" v-bind:value="mccItem.mccCode">{{ mccItem.mccDescription }}</option>
                </select>
              </label>
              <label class="onboarding-helping-text">
                  "Sporting Goods Store" is recommended for gun stores
              </label>
            </div>
          </div>
          <div class="col-lg-6 padding-tb-10">
            <div class="control-group onboarding-field radio-container">
              <label>Does the store sell firearms?</label>

              <span class="onboadrding-radio-box">
                  <input type="radio" :value="true" name="sells_firearms" v-model="sales_profile.sells_firearms" class="form-control control" required />
                  <label class="onboadrding-radio-view"></label>
               </span>
              <span class="radio-title">Yes</span>

              <span class="onboadrding-radio-box">
                    <input type="radio"  :value="false" name="sells_firearms" v-model="sales_profile.sells_firearms" class="form-control control"/>
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">No</span>
            </div>
            <label class="onboarding-helping-text d-none"></label>
          </div>
          <div class="col-lg-6 padding-tb-10" id="sells_firearms_accessories_container">
            <div class="control-group onboarding-field radio-container">
              <label>Does the store sell firearms accessories (bullets, scopes, magizines)?</label>

              <span class="onboadrding-radio-box">
                    <input type="radio" :value="true" name="sells_firearm_accessories" v-model="sales_profile.sells_firearm_accessories" class="form-control control" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">Yes</span>

              <span class="onboadrding-radio-box">
                    <input type="radio" :value="false" name="sells_firearm_accessories" v-model="sales_profile.sells_firearm_accessories" class="form-control control" required />
                    <label class="onboadrding-radio-view"></label>
              </span>
              <span class="radio-title">No</span>
            </div>
            <label class="onboarding-helping-text d-none"></label>
          </div>
          <div class="col-lg-6 padding-tb-10" v-if="sales_profile.sells_firearms">
            <div class="control-group onboarding-field">
              <label for="ffl_number">Federal firearms license number</label>
              <input type="text" id="ffl_number" v-model="sales_profile.fire_arms_license" v-on:change="onLicenseChange" class="form-control control" required />
              <label class="onboarding-helping-text d-none"></label>
            </div>
          </div>
          <div class="col-lg-12 padding-tb-10" v-if="sales_profile.sells_firearms && license_image_required">
            <div class="upload-container">
              <input type="file" class="custom_upload" id="ffl_image" v-on:change="uploadFiles" name="ffl_image" accept="image/png, image/jpeg" required />
              <div><span class="icon license-icon"></span></div>
              <div><span id="license_file_name" class="onboarding-upload-text">Actual license image</span></div>
              <div class="onboard-upload-button-container">
                <span class="onboarding-upload-button" id="upload_ffl_image">
                  <span class="onboarding-upload-button-text">Upload</span>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 padding-tb-10">
            <div class="control-group onboarding-field">
              <label for="physical_address_line1" class="">Business address</label>
              <input type="text" id="physical_address_line1" placeholder="Business address" v-model="physical_address.line1" class="form-control control" required />
              <label class="onboarding-helping-text d-none"></label>
            </div>
          </div>
          <div class="col-lg-12 padding-tb-10">
            <div class="control-group onboarding-field">
              <label for="physical_address_line2" class="">Business address 2</label>
              <input type="text" id="physical_address_line2" placeholder="Business address" v-model="physical_address.line2" class="form-control control"/>
              <label class="onboarding-helping-text d-none"></label>
            </div>
          </div>
          <div class="col-lg-12 padding-tb-10">
            <div class="row">
              <div class="col-lg-4">
                <div class="control-group onboarding-field">
                  <label for="business_city" class="">City</label>
                  <input type="text" id="business_city" placeholder="City" v-model="physical_address.city" class="form-control control" required />
                  <label class="onboarding-helping-text d-none"></label>
                </div>
              </div>
              <div class="col-lg-4" id="business_state_container">
                <div class="control-group select onboarding-field">
                  <label for="state">State</label>
                  <label class="select_label">
                    <select id="state" v-model="physical_address.state_code" class="form-control control customSelect" required>
                      <option value="">State</option>
                      <option v-for="state in states" v-bind:value="state.stateCode">{{ state.stateName}}</option>
                    </select>
                  </label>
                  <label class="onboarding-helping-text d-none"></label>
                </div>
              </div>
              <div class="col-lg-4 d-none">
                <div class="control-group select onboarding-field">
                  <label for="business_country">Country</label>
                  <label class="select_label">
                    <select id="business_country" name="business_country" class="form-control control customSelect" v-model="physical_address.country_code" required>
                      <option value="">Country</option>
                      <option v-for="country in countries" :selected="country.countryCode === '840'? true : false" v-bind:value="country.countryCode">{{ country.countryName }}
                      </option>
                    </select>
                  </label>
                  <label class="onboarding-helping-text d-none"></label>
                </div>
              </div>
              <div class="col-lg-4" id="business_zip_container">
                <div class="control-group select onboarding-field">
                  <label for="business_zip" class="">Zip Code</label>
                  <input type="text" id="business_zip" placeholder="Zip Code" v-model="physical_address.zip" class="form-control control" required />
                  <label class="onboarding-helping-text d-none"></label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 padding-tb-10" v-for="(phone, key) in merchant.phones" :key="key">
            <div class="control-group onboarding-field">
              <label for="phone" class="">Phone </label>
              <input type="text"
                     placeholder="123-12345678-123"
                     pattern="[0-9]{3}-([0-9]{7}-[0-9]{3}|[0-9]{7})"
                     id="phone"
                     v-on:change="setPhone($event, phone)"
                     :value="getPhone(phone)"
                     class="form-control control"
                     required />
              <label class="onboarding-helping-text">Area - phone number - extension</label>
            </div>
          </div>
          <div class="col-lg-4 padding-tb-10" id="business_email_container">
            <div class="control-group onboarding-field">
              <label for="email" class="">Email</label>
              <input type="email" id="email" placeholder="Email" v-model="merchant.email_address" class="form-control control" required />
              <label class="onboarding-helping-text"></label>
            </div>
          </div>
          <div class="col-lg-4 padding-tb-10" id="business_website_container">
            <div class="control-group onboarding-field">
              <label for="website" class="">Website</label>
              <input type="url" id="website" placeholder="https://www.devvly.com" v-model="merchant.web_site" class="form-control control" required />
              <label class="onboarding-helping-text"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 padding-tb-10" id="mailing_address_same_container">
            <div class="control-group onboarding-field radio-container">
              <label>My mailing address is the same as above</label>

              <span class="onboadrding-radio-box">
                    <input type="radio" name="mailing_address_same" :value="true" v-model="mailing_address_same" class="form-control control" required />
                    <label class="onboadrding-radio-view"></label>
              </span>
              <span class="radio-title">Yes</span>

              <span class="onboadrding-radio-box">
                    <input type="radio" name="mailing_address_same" :value="false" v-model="mailing_address_same" class="form-control control" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">No</span>
            </div>
            <label class="onboarding-helping-text d-none"></label>
          </div>
          <div class="col-lg-4 padding-tb-10">
            <div class="control-group onboarding-field radio-container">
              <label>Receive statements online only</label>
              <span class="onboadrding-radio-box">
                    <input type="radio" name="accepts_paper_statements" :value="true" v-model="merchant.accepts_paper_statements" class="form-control control" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">Yes</span>

              <span class="onboadrding-radio-box">
                    <input type="radio" name="accepts_paper_statements" :value="false" v-model="merchant.accepts_paper_statements" class="" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">No</span>
            </div>
            <label class="onboarding-helping-text d-none"></label>
          </div>
          <div class="col-lg-4 padding-tb-10">
            <div class="control-group onboarding-field radio-container">
              <label>Receive tax forms online only</label>
              <span class="onboadrding-radio-box">
                    <input type="radio" name="accepts_paper_tax_forms" :value="true" v-model="merchant.accepts_paper_tax_forms" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">Yes</span>

              <span class="onboadrding-radio-box">
                    <input type="radio" name="accepts_paper_tax_forms" :value="false" v-model="merchant.accepts_paper_tax_forms" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">No</span>
            </div>
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="row" v-if="!mailing_address_same">
          <div class="col-lg-12 padding-tb-10">
            <div class="control-group onboarding-field">
              <label for="mailing_address_line1" class="">Address</label>
              <input type="text" id="mailing_address_line1" v-model="mailing_address.line1" placeholder="Mailing address" class="form-control control" required />
              <label></label>
            </div>
          </div>
          <div class="col-lg-12 padding-tb-10">
            <div class="control-group onboarding-field">
              <label for="mailing_address_line2" class="">Address 2</label>
              <input type="text" id="mailing_address_line2" v-model="mailing_address.line2" placeholder="Mailing address" class="form-control control"/>
              <label></label>
            </div>
          </div>
          <div class="col-lg-12 padding-tb-10">
            <div class="row">
              <div class="col-lg-4"  id="mailing_city_container">
                <div class="control-group select onboarding-field">
                  <label for="mailing_city" class="">City</label>
                  <input type="text" id="mailing_city" placeholder="City" v-model="mailing_address.city" class="form-control control" required />
                  <label class="onboarding-helping-text d-none"></label>
                </div>
              </div>
              <div class="col-lg-4" id="mailing_state_container">
                <div class="control-group select onboarding-field">
                  <label for="mailing_state">State</label>
                  <label class="select_label">
                    <select id="mailing_state" v-model="mailing_address.state_code" class="form-control control customSelect" required>
                      <option value="">State</option>
                      <option v-for="state in states" v-bind:value="state.stateCode">{{ state.stateName}}</option>
                    </select>

                  </label>
                  <label class=""></label>
                </div>
              </div>
              <div class="col-lg-4 d-none">
                <div class="control-group select onboarding-field">
                  <label for="mailing_country">Country</label>
                  <label class="select_label">
                    <select  id="mailing_country" name="mailing_country" class="form-control control customSelect" v-model="mailing_address.country_code" required>
                      <option value="">Country</option>
                      <option v-for="country in countries" :selected="country.countryCode === '840'? true : false" v-bind:value="country.countryCode">{{ country.countryName }}
                      </option>
                    </select>
                  </label>
                  <label class="onboarding-helping-text d-none"></label>
                </div>
              </div>
              <div class="col-lg-4" id="mailing_zip_container">
                <div class="control-group select onboarding-field">
                  <label for="mailing_zip" class="">Zip Code</label>
                  <input type="text" id="mailing_zip" placeholder="Zip Code" v-model="mailing_address.zip" class="form-control control" required />
                  <label class="onboarding-helping-text d-none"></label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row onboarding-title" style="margin-top: 40px;margin-bottom: 30px;">
             <div class="col-lg-12  padding-tb-10"><span class="paragraph bold">Contact Person</span></div>
        </div>
        <business-contact v-bind:business_contacts.sync="business_contacts"
                          v-bind:contact_types="contact_types"
                          v-bind:countries="countries"
                          v-bind:states="states">
        </business-contact>
        <div class="row onboarding-title" style="margin-top: 40px;margin-bottom: 30px;">
                    <div class="col-lg-12  padding-tb-10"><span class="paragraph bold">Legal Informations</span></div>
        </div>
        <div class="row">
          <div class="col-lg-3 padding-tb-10">
            <div class="control-group select onboarding-field">
              <label for="business_type">Business Type</label>
              <label class="select_label">
                <select id="business_type" class="form-control control customSelect" v-model="merchant.company_type_id" required>
                  <option value="">Business Type</option>
                  <option v-for="type in business_types" v-bind:value="type.id">{{ type.description }}</option>
                </select>
              </label>
              <label class=""></label>
            </div>
          </div>
          <div class="col-lg-9 padding-tb-10" id="same_as_dba_container">
            <div class="control-group onboarding-field radio-container">
              <label>My legal name listed above is the same on DBA</label>
              <span class="onboadrding-radio-box">
                    <input type="radio" name="same_as_dba" v-model="same_as_dba" :value="true" class="same_as_dba" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">Yes</span>

              <span class="onboadrding-radio-box">
                    <input type="radio" name="same_as_dba" v-model="same_as_dba" :value="false" class="same_as_dba" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">No</span>
            </div>
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 padding-tb-10" v-if="!same_as_dba">
            <div class="control-group onboarding-field">
              <label for="legal_name" class="">Legal name</label>
              <input type="text" id="legal_name" placeholder="Legal name" v-model="tax_payer.business_legal_name" class="form-control control" required />
              <label class="onboarding-helping-text d-none"></label>
            </div>
          </div>
          <div class="col-lg-3 padding-tb-10">
            <div class="control-group onboarding-field">
              <label for="federal_tax_id" class="">Federal tax ID</label>
              <input type="text" id="federal_tax_id" pattern="[0-9]{9}" placeholder="Federal tax ID" v-model="tax_payer.tin" class="form-control  control" required />
              <label class="onboarding-helping-text d-none"></label>
            </div>
          </div>
          <div class="col-lg-2 padding-tb-10" id="legal_state_container">
            <div class="control-group select onboarding-field">
              <label for="legal_state">State</label>
              <label class="select_label">
                <select id="legal_state" v-model="tax_payer.state_incorporated_code" class="form-control  control customSelect" required>
                  <option value="">State</option>
                  <option v-for="state in states" v-bind:value="state.stateCode">{{ state.stateName}}</option>
                </select>
              </label>
              <label class="onboarding-helping-text d-none"></label>
            </div>
          </div>
          <div class="col-lg-3 padding-tb-10"></div>
        </div>

        <div class="row">
          <div class="col-lg-4 padding-tb-10">
            <div class="control-group onboarding-field  radio-container aligned-radio">
              <label>Do you currently accept or have you previously accepted credit
                cards.</label>
              <span class="onboadrding-radio-box">
                    <input type="radio" name="accept_credit_cards" v-model="sales_profile.previously_accepted_payment_cards" :value="true" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">Yes</span>

              <span class="onboadrding-radio-box">
                    <input type="radio" name="accept_credit_cards" v-model="sales_profile.previously_accepted_payment_cards" :value="false" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">No</span>
            </div>
            <label class="onboarding-helping-text d-none"></label>
          </div>
          <div class="col-lg-4 padding-tb-10">
            <div class="control-group onboarding-field  radio-container aligned-radio">
              <label>Have you ever had a merchant account terminated or have you ever been
                identified by a risk monitoring company</label>
              <span class="onboadrding-radio-box">
                    <input type="radio"  name="terminated" :value="true" v-model="sales_profile.previously_terminated_or_identified_by_risk_monitoring" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">Yes</span>

              <span class="onboadrding-radio-box">
                    <input type="radio" name="terminated" :value="false" v-model="sales_profile.previously_terminated_or_identified_by_risk_monitoring" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">No</span>
            </div>
            <label class="onboarding-helping-text d-none"></label>
          </div>
          <div class="col-lg-4 padding-tb-10">
            <div class="control-group onboarding-field  radio-container aligned-radio">
              <label>Currently open for business.</label>
              <span class="onboadrding-radio-box">
                    <input type="radio" name="currently_open_for_business" v-model="sales_profile.currently_open_for_business" :value="true" required>
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">Yes</span>

              <span class="onboadrding-radio-box">
                    <input type="radio" name="currently_open_for_business" v-model="sales_profile.currently_open_for_business" :value="false" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">No</span>
            </div>
            <label class="onboarding-helping-text d-none"></label>
          </div>
          <div class="col-lg-12 padding-tb-10" v-if="sales_profile.previously_terminated_or_identified_by_risk_monitoring">
            <div class="control-group onboarding-field">
              <label for="termination_cause" class="">Please explain why</label>
              <textarea v-model="sales_profile.reason_previously_terminated_or_identified_by_risk"
                        id="termination_cause"
                        cols="40"
                        rows="10" placeholder=""
                        class="form-control control"
                        required>
              </textarea>
              <label class="onboarding-helping-text d-none"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 padding-tb-10" id="seasonal_business_container">
            <div class="control-group onboarding-field  radio-container">
              <label>Seasonal business?</label>
              <span class="onboadrding-radio-box">
                    <input type="radio" name="seasonal_business" :value="true" v-model="seasonal_business" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">Yes</span>

              <span class="onboadrding-radio-box">
                    <input type="radio" name="seasonal_business" :value="false" v-model="seasonal_business" required />
                    <label class="onboadrding-radio-view"></label>
                </span>
              <span class="radio-title">No</span>
            </div>
            <label class="onboarding-helping-text d-none"></label>
          </div>
                    <div class="col-lg-9 padding-tb-10" v-if="seasonal_business">
                      <div class="control-group select onboarding-field">
                        <label for="business_months">Seasonal months</label>
                        <select id="business_months" class="form-control control customSelect" multiple v-model="merchant.seasonal_schedule" required>
                          <option v-for="month in months" v-bind:value="month" class="text-capitalize">{{month}}</option>
                        </select>
                        <label class="onboarding-helping-text d-none"></label>
                      </div>
                    </div>
        </div>



        <div class="row form-bottom-container">
          <div class="col-lg-12 padding-tb-10">
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
  </div>
</template>

<script>
	export default {
		props: ['merchant_number','mccs', 'states', 'contact_types', 'countries', 'business_types'],
    data(){
			return {
				errors: [] = [],
				error_message: null,
				current_step: "business_information",
				same_as_dba: null,
        last_license: null,
        license_image_required: true,
				change_interval: null,
				seasonal_business: null,
        loading: true,
        months: [] = [
        		'january',
            'february',
            'march',
            'april',
            'may',
            'june',
            'july',
            'august',
            'september',
            'october',
            'november',
            'december'
        ],
				mailing_address_same: null,
        merchant: {
        	merchant_number: null,
					dba_name: "",
					phones: [] = [
            {
            	phone_type_code_id: 5,
              area_code: "",
							phone_number:"",
              extension:"",
            }
          ],
					email_address: "",
					web_site: "",
					accepts_paper_statements: "",
					accepts_paper_tax_forms: "",
					company_type_id: "",
					seasonal_schedule: [] = [],
        },
				physical_address: {
					line1: "",
          line2: "",
          line3: "",
          city: "",
          country_code: "",
					state_code: "",
          zip: "",
        },
				mailing_address: {
					line1: "",
					line2: "",
					line3: "",
					city: "",
					country_code: "",
					state_code: "",
					zip: "",
				},
				business_contacts: [] = [],
        tax_payer: {
					tin: "",
					tin_type_id: "",
					state_incorporated_code: "",
					business_legal_name: ""
        },
				sales_profile: {
					is_ecommerce: true,
					mcc_code: "5941",
					card_present_percentage: 0,
					return_refund_policy: "",
					products_sold: "",
					previously_accepted_payment_cards: null,
					previous_processor_id: "",
					previously_terminated_or_identified_by_risk_monitoring: null,
					reason_previously_terminated_or_identified_by_risk: null,
					currently_open_for_business: null,
					annual_volume: 0,
					average_ticket: 0,
					high_ticket: 0,
					sells_firearms: null,
					sells_firearm_accessories: null,
					future_delivery_type_id: "",
					other_delivery_type: "",
					future_delivery_percentage: 0,
					fire_arms_license: "",
					firearms_license_document_path: null,
					card_brands: [] = [],
					ebt_number: "",
					amex_mid: ""
				},
			}
    },
    mounted(){
			this.init()
    },
    methods: {
			async init(){
				this.$emit('loading', true);
				this.merchant.merchant_number = this.merchant_number;
				this.tax_payer.tin_type_id = 2;
				this.loadSavedData();
				this.$emit('loading', false);
				this.loading = false;

				setTimeout(() => {
                    $('#mcc').select2();
                }, 1000)
			},
			appendRemoteData(data){
				for(let key of Object.keys(data)){
					this[key] = data[key];
				}
				this.saveLocally();
			},
			loadSavedData(){
				var data = localStorage.getItem('business_information');
				if (data) {
					data = JSON.parse(data);
					for(let key of Object.keys(data)){
						this[key] = data[key];
					}
				}
			},
			saveLocally(){
				var data = JSON.stringify(this.$data);
				localStorage.setItem('business_information', data);
			},
      async postData(e){
				// check if the data actually changed since the last post:
				e.preventDefault();
				this.errors = [];
				this.error_message = "";
				this.$emit('loading', true);
				var profile = {
					merchant: this.merchant,
					physical_address: this.physical_address,
          mailing_address: this.mailing_address,
					business_contacts: this.business_contacts,
					tax_payer: this.tax_payer,
        };
				if(this.mailing_address_same){
					profile.mailing_address = this.physical_address;
        }
				if(this.same_as_dba){
					profile.tax_payer.business_legal_name = this.merchant.dba_name;
        }
				if(!this.seasonal_business){
					for(let month of this.months){
						profile.merchant.seasonal_schedule.push(month);
          }
        }
				profile.physical_address.country_code = profile.physical_address.country_code.toString();
				profile.mailing_address.country_code = profile.mailing_address.country_code.toString();
				var error = null;
				var url = window.base_url + "/admin/on-boarding/business_information/" + this.merchant_number;
				var response = await axios.put(url, profile).catch(e => error = e);
				this.$emit('loading', false);
				if (error) {
					this.showError(error);
					return;
				}
				this.errors = [];
				this.error_message = "";
				this.appendRemoteData(response.data);
				this.$emit('next_step', 'business_information');
      },
			onLicenseChange(){
				if(this.change_interval){
					clearInterval(this.change_interval);
        }
				this.change_interval = setInterval(() => {
					this.license_image_required = this.sales_profile.fire_arms_license !== this.last_license;
					clearInterval(this.change_interval);
					this.change_interval = null;
        },100);
      },
			getPhone(phone){
				if(!phone.area_code.length || !phone.phone_number.length){
					return "";
				}
				var value = phone.area_code + "-" + phone.phone_number;
				if(phone.extension){
					value+= "-" + phone.extension;
				}
				return value;
			},
			setPhone(e, phone){
				var phone_chunks = e.target.value.split('-');
				if(phone_chunks.length < 2 ){
					return;
				}
				phone.area_code = phone_chunks[0];
				phone.phone_number = phone_chunks[1];
				if(phone_chunks.length >= 3){
					phone.extension = phone_chunks[2];
				}
			},
      async uploadFiles(){
				var error = null;
				await this.uploadFFLImage().catch(err => error = err);
				this.$emit('loading', false);
				if(error){
					this.showError(error);
        }
			},

			uploadFFLImage(){
				return new Promise((resolve, reject) => {
					var files = $('#ffl_image').prop('files');
					if(!files.length){
						resolve();
						return;
					}
					if(!files.length){
						console.log('no file selected');
						return;
					}
					var success = (response) => {
						this.sales_profile.firearms_license_document_path = response.data.result.firearms_license;
						resolve();
					};
					var error = (err) => {
						reject(err);
					};
					this.$emit('loading');
					this.$emit('upload_file', files[0],'firearms_license', success, error);
        })
			},
			showError(error) {
				this.$emit('show_error', error);
      }
    }
	}
</script>
