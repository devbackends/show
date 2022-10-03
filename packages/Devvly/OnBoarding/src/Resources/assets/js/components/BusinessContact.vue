<template>
  <div v-if="!loading">
    <div  v-for="(business_contact, contact_key) in business_contacts" :key="contact_key">
      <div class="row">
        <div class="col-lg-6 padding-tb-10">
          <div class="control-group onboarding-field radio-container">
            <label>Contact Type</label>
            <div v-for="contact_type in contact_types">
              <span  class="checkbox no-margin custom-check-box">
                        <input type="checkbox"
                               v-bind:value="{ contact_type_id: contact_type.contactTypeID }"
                               v-model="business_contacts[contact_key].contact_types"/>
                        <label  for="custom-checkbox-view " class="custom-checkbox-view dblock"></label>
               </span>
              <span class="checkbox-title">{{contact_type.contactTypeDescription}}</span>
            </div>
          </div>
          <label class="onboarding-helping-text"></label>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field radio-container">
            <label>Primary contact</label>
            <span class="onboadrding-radio-box">
                    <input type="radio" :name="'is_primary_' + contact_key" :value="true" v-model="business_contact.is_authorized_to_purchase" class="form-control control" required/>
                    <label class="onboadrding-radio-view"></label>
            </span>
            <span class="radio-title">Yes</span>

            <span class="onboadrding-radio-box">
                    <input type="radio" :name="'is_primary_' + contact_key" :value="false" v-model="business_contact.is_authorized_to_purchase" class="form-control control" required/>
                    <label class="onboadrding-radio-view"></label>
                </span>
            <span class="radio-title">No</span>
          </div>
          <label class="onboarding-helping-text d-none"></label>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field radio-container">
            <label>Is compass user?</label>

            <span class="onboadrding-radio-box">
                    <input type="radio" :name="'is_compass_user_' + contact_key" :value="true" v-model="business_contact.is_compass_user" class="form-control control" required/>
                    <label class="onboadrding-radio-view"></label>
            </span>
            <span class="radio-title">Yes</span>

            <span class="onboadrding-radio-box">
                    <input type="radio" :name="'is_compass_user_' + contact_key" :value="false" v-model="business_contact.is_compass_user" class="form-control control" required/>
                    <label class="onboadrding-radio-view"></label>
                </span>
            <span class="radio-title">No</span>
          </div>
          <label class="onboarding-helping-text d-none"></label>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field">
            <label v-bind:for="'cp_first_name_' + contact_key">First name </label>
            <input type="text" v-bind:id="'cp_first_name_' + contact_key"  v-model="business_contact.contact.first_name" class="form-control control" required />
            <label class="onboarding-helping-text"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field">
            <label :for="'cp_last_name_' + contact_key">Last name</label>
            <input type="text" :id="'cp_last_name_' + contact_key" v-model="business_contact.contact.last_name" value="" class="form-control control" required />
            <label class="onboarding-helping-text"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field">
            <label :for="'cp_email_' + contact_key" class="">Email</label>
            <input type="email" :id="'cp_email_' + contact_key" placeholder="Email" v-model="business_contact.email_address" class="form-control control" required />
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10" v-for="(phoneVal, key) in business_contact.phone_numbers">
          <div class="row">
            <div class="col-12">
              <div class="control-group onboarding-field">
                <label :for="'cp_phone_' + contact_key + '_' + (key +1)" class="">Phone {{(key +1)}}</label>
                <input type="text"
                       placeholder="123-12345678-123"
                       pattern="[0-9]{3}-([0-9]{7}-[0-9]{3}|[0-9]{7})"
                       :id="'cp_phone_' + contact_key + '_' + (key +1)"
                       :value="getPhone(phoneVal)"
                       v-on:change="setPhone($event, phoneVal)"
                       class="form-control control"
                       required />
                <label class="onboarding-helping-text">Area - phone number - extension</label>
              </div>
            </div>
            <div class="col-lg-2" v-if="key">
            <label class="fixed_height no-margin"></label>
              <button type="button" class="btn btn-primary" v-on:click="removePhone(contact_key, key)"><i class="fa fa-minus"></i></button>
            </div>
          </div>
        </div>
        <div class="col-lg-12 mb-2  padding-tb-10">
          <button type="button" class="btn btn-primary" v-on:click="addPhone(contact_key)"><i class="fa fa-plus"></i>Add phone</button>
        </div>
      </div>
      <div class="row" v-if="isOwner(contact_key)">
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field">
            <label v-bind:for="'cp_title_' + contact_key">Title </label>
            <input type="text" v-bind:id="'cp_title_' + contact_key"  v-model="business_contact.title" class="form-control control" required />
            <label class="onboarding-helping-text"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field">
            <label :for="'cp_address_1_' + contact_key">Address</label>
            <input type="text" :id="'cp_address_1_' + contact_key" placeholder="" v-model="business_contact.contact.address.line1" value="" class="form-control control" required />
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field">
            <label :for="'cp_address_2' + contact_key" class="">Address 2</label>
            <input type="text" :id="'cp_address_2' + contact_key" placeholder="" v-model="business_contact.contact.address.line2" value="" class="form-control control"/>
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field">
            <label :for="'cp_city_' + contact_key">City</label>
            <input type="text" :id="'cp_city_' + contact_key" placeholder="City" v-model="business_contact.contact.address.city" class="form-control control" required />
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-3 d-none  padding-tb-10">
          <div class="control-group select onboarding-field">
            <label :for="'cp_country_' + contact_key">Country</label>
            <label class="select_label">
              <select :id="'cp_country_' + contact_key" name="cp_country" class="form-control control customSelect" v-model="business_contact.contact.address.country_code" required>
                <option value="">Country</option>
                <option v-for="country in countries" :selected="country.countryCode === '840'? true : false" v-bind:value="country.countryCode">{{ country.countryName }}
                </option>
              </select>
            </label>
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group select onboarding-field">
            <label :for="'cp_state_' + contact_key">State</label>
            <label class="select_label">
              <select :id="'cp_state_' + contact_key" name="cp_state" class="form-control control customSelect" v-model="business_contact.contact.address.state_code" required>
                <option value="">State</option>
                <option v-for="state in states" v-bind:value="state.stateCode">{{ state.stateName}}</option>
              </select>
            </label>
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group select onboarding-field">
            <label :for="'cp_zip_' + contact_key">Zip Code</label>
            <input type="text" :id="'cp_zip_' + contact_key" placeholder="Zip Code" v-model="business_contact.contact.address.zip" class="form-control control" required />
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field">
            <label :for="'cp_ownership_percentage_' + contact_key" class="">Ownership percentage</label>
            <input type="text" pattern="^[1-9][0-9]?$|^100$" :id="'cp_ownership_percentage_' + contact_key" v-model="business_contact.ownership_amount" class="form-control control" placeholder="number between 1 and 100" required />
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field">
            <label :for="'cp_ssn_' + contact_key" class="">Social security number</label>
            <input type="text"
                   :id="'cp_ssn_' + contact_key"
                   pattern="[0-9]{9}"
                   placeholder="123-12-1234"
                   v-model="business_contact.contact.ssn"
                   class="form-control control"
                   required />
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group onboarding-field">
            <label :for="'date_of_birth_' + contact_key" class="">Date of birth</label>
            <input type="date" pattern="\d{4}-\d{2}-\d{2}" placeholder="123-12-1234" :id="'date_of_birth_' + contact_key" v-model="business_contact.contact.date_of_birth" class="form-control control" required />
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
        <div class="col-lg-3  padding-tb-10">
          <div class="control-group select onboarding-field">
            <label :for="'citizenship_country_' + contact_key">Country of citizenship</label>
            <label class="select_label">
              <select :id="'citizenship_country_' + contact_key" class="form-control control customSelect" v-model="business_contact.contact.country_of_citizenship_code" required>
                <option value="">Country</option>
                <option v-for="country in countries" v-bind:value="country.countryCode">{{ country.countryName }}
                </option>
              </select>
            </label>
            <label class="onboarding-helping-text d-none"></label>
          </div>
        </div>
      </div>
      <div class="col-lg-12 mb-2  padding-tb-10" v-if="!business_contact.is_default">
        <button type="button" class="btn btn-primary" v-on:click="removeContact(contact_key)"><i class="fa minus"></i>remove</button>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 mb-2  padding-tb-10">
        <button type="button" class="btn btn-primary" v-on:click="addContact()"><i class="fa fa-plus"></i> Add Contact</button>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
  	props: ['business_contacts','contact_types', 'countries', 'states'],
    data(){
  		return {
  			change_interval: null,
  			loading: true,
      }
    },
    mounted(){
  		this.init();
  		this.loading = false;
    },
    methods: {
  		init(){
  			if(this.business_contacts.length){
					var contacts = this.business_contacts;
					contacts[0].is_default = true;
					for (let contact of contacts) {
						contact.contact.date_of_birth = contact.contact.date_of_birth.split('T')[0];
					}
  				return;
        }
  			var contact = {
					is_default: true,
					title: "",
					is_compass_user: null,
					is_authorized_to_purchase: null,
					email_address: "",
					ownership_amount: "",
					phone_numbers: [] = [
            {
							phone_type_code_id: 5,
							area_code: "",
							phone_number: "",
							extension:"",
            }
          ],
					contact_types: [] = [],
					contact: {
						first_name: "",
						last_name: "",
						ssn: "",
						date_of_birth: "",
						country_of_citizenship_code: "",
						address: {
							line1: "",
							line2: "",
							line3: "",
							city: "",
							state_code: "",
							zip: "",
							country_code: ""
						},
					}
				};
				this.business_contacts.push(contact);
			},
  		addPhone(contactKey){
  			var phone = JSON.parse(JSON.stringify(this.business_contacts[contactKey].phone_numbers[0]));
				phone.area_code = "";
				phone.phone_number = "";
				phone.extension = "";
  			this.business_contacts[contactKey].phone_numbers.push(phone);
      },
			removePhone(contactKey, key){
				var items = this.business_contacts[contactKey].phone_numbers.filter((item,index) => {
					return index !== key;
        });
				this.business_contacts[contactKey].phone_numbers = items;
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
      addContact(){
  			var last_contact = JSON.stringify(this.business_contacts[this.business_contacts.length -1]);
  			last_contact = JSON.parse(last_contact);
  			last_contact.is_default = false;
  			this.business_contacts.push(last_contact);
			},
			removeContact(contact_key){
				Vue.delete(this.business_contacts, contact_key);
			},
  		isOwner(contactKey){
				var items = this.business_contacts[contactKey].contact_types.filter((item,index) => {
					return item.contact_type_id === 1 || item.contact_type_id === 2;
				});
				return items.length
      },
    },
  }
</script>