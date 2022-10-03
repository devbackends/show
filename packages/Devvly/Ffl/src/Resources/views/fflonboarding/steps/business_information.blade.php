<div class="business_info-wrapper" v-bind:class="{'d-none': currentStep !== 1}">

    <!-- Company and contact name -->
    <div class="row mt-5">

        <div class="col-sm-6" v-bind:class="[errors.has('company_name') ? 'has-error' : '']">
            <div class="form-group">
                <label for="company-name">{{__('ffl::app.steps.business_info.company_name_ffl')}}</label>
                <input v-validate="'required'" data-vv-as=" " v-model="form.company_name" type="text" name="company_name" id="company-name" class="form-control" placeholder="{{__('ffl::app.steps.business_info.company_name')}}">
                <span class="control-error" v-if="errors.has('company_name')">@{{ errors.first('company_name') }}</span>
            </div>
        </div>

        <div class="col-sm-6" v-bind:class="[errors.has('contact_name') ? 'has-error' : '']">
            <div class="form-group">
                <label for="contact-name">{{__('ffl::app.steps.business_info.contact_name_ffl')}}</label>
                <input v-validate="'required'" data-vv-as=" " v-model="
                   form.contact_name" type="text" name="contact_name" id="contact-name" class="form-control" placeholder="{{__('ffl::app.steps.business_info.contact_name')}}">
                <span class="control-error" v-if="errors.has('contact_name')">@{{ errors.first('contact_name') }}</span>
            </div>
        </div>
    </div>

    <!-- Radio buttons -->
    <div class="row">

        <div class="col-sm-3" id="mailing_address_same_container">
            <div class="form-group" v-bind:class="[errors.has('retail_store') ? 'has-error' : '']">
                <label class="w-100">{{__('ffl::app.steps.business_info.questions.retail')}}</label>
                <span class="control-error" v-if="errors.has('retail_store')">@{{ errors.first('retail_store') }}</span>

                <div class="radio-button">
                    <div class="radio-button__input">
                        <input data-vv-as=" " v-validate="'required'" v-model="form.retail_store" type="radio" value="true" name="retail_store">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.business_info.yes')}}</div>
                </div>
                <div class="radio-button">
                    <div class="radio-button__input">
                        <input data-vv-as=" " v-validate="'required'" v-model="form.retail_store" type="radio" value="false" name="retail_store">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.business_info.no')}}</div>
                </div>

            </div>
        </div>

        <div class="col-sm-3" id="receive_statements_online_container">
            <div class="form-group" v-bind:class="[errors.has('importer_exporter') ? 'has-error' : '']">
                <label class="w-100">{{__('ffl::app.steps.business_info.questions.importer_exporter')}}</label>
                <span class="control-error" v-if="errors.has('importer_exporter')">@{{ errors.first('importer_exporter') }}</span>

                <div class="radio-button">
                    <div class="radio-button__input">
                        <input data-vv-as=" " v-validate="'required'" v-model="form.importer_exporter" type="radio" value="true" name="importer_exporter">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.business_info.yes')}}</div>
                </div>
                <div class="radio-button">
                    <div class="radio-button__input">
                        <input data-vv-as=" " v-validate="'required'" v-model="form.importer_exporter" type="radio" value="false" name="importer_exporter">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.business_info.no')}}</div>
                </div>

            </div>
        </div>

        <div class="col-sm-3" id="receive_tax_online_container">
            <div class="form-group" v-bind:class="[errors.has('importer_exporter') ? 'has-error' : '']">
                <label class="w-100">{{__('ffl::app.steps.business_info.questions.website')}}</label>
                <span class="control-error" v-if="errors.has('website')">@{{ errors.first('website') }}</span>

                <div class="radio-button">
                    <div class="radio-button__input">
                        <input data-vv-as=" " v-validate="'required'" v-model="form.website" type="radio" value="true" name="website">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.business_info.yes')}}</div>
                </div>
                <div class="radio-button">
                    <div class="radio-button__input">
                        <input data-vv-as=" " v-validate="'required'" v-model="form.website" type="radio" value="false" name="website">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.business_info.no')}}</div>
                </div>

            </div>
            <!-- If has website -->
            <div class="form-group" v-bind:class="[errors.has('website_link') ? 'has-error' : '', this.form.website !== 'true' ? 'd-none' : '']">
                <label for="website-link">{{__('ffl::app.steps.business_info.website')}}</label>
                <input type="text" class="form-control" v-validate="{required: this.form.website === 'true',url: {require_protocol: true }}" data-vv-as=" " v-model="form.website_link" name="website_link" id="website-link" placeholder="{{__('ffl::app.steps.business_info.website')}}">
                <span class="control-error" v-if="errors.has('website_link')">@{{ errors.first('website_link') }}</span>
            </div>
        </div>

        <div class="col-sm-3" id="add_social_links_container">
            <div class="form-group" v-bind:class="[errors.has('social') ? 'has-error' : '']">
                <label class="w-100">{{__('ffl::app.steps.business_info.questions.social-links')}}</label>
                <span class="control-error" v-if="errors.has('social')">@{{ errors.first('social') }}</span>

                <div class="radio-button">
                    <div class="radio-button__input">
                        <input data-vv-as=" " v-validate="'required'" v-model="form.social" type="radio" value="true" name="social">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.business_info.yes')}}</div>
                </div>
                <div class="radio-button">
                    <div class="radio-button__input">
                        <input data-vv-as=" " v-validate="'required'" v-model="form.social" type="radio" value="false" name="social">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.business_info.no')}}</div>
                </div>

            </div>
            <!-- If has social -->
            <div class="form-group" v-bind:class="[errors.has('social_link') ? 'has-error' : '', this.form.social !== 'true' ? 'd-none' : '']">
                <label for="social-link">{{__('ffl::app.steps.business_info.social')}}</label>
                <input type="text" class="form-control" data-vv-as=" " v-model="form.social_link" name="social_link" id="social-link" v-validate="{required: this.form.social === 'true',url: {require_protocol: true }}" placeholder="www.facebook.com">
                <span class="control-error" v-if="errors.has('social_link')">@{{ errors.first('social_link') }}</span>
            </div>
            <div class="form-group" v-bind:class="[errors.has('social_link1') ? 'has-error' : '', this.form.social !== 'true' ? 'd-none' : '']">
                <input type="text" class="form-control" data-vv-as=" " v-model="form.social_link1" name="social_link1" id="social-link1" v-validate="{required: this.form.social === 'true',url: {require_protocol: true }}" placeholder="www.twitter.com">
                <span class="control-error" v-if="errors.has('social_link1')">@{{ errors.first('social_link1') }}</span>
            </div>
            <div class="form-group" v-bind:class="[errors.has('social_link2') ? 'has-error' : '', this.form.social !== 'true' ? 'd-none' : '']">
                <input type="text" class="form-control" data-vv-as=" " v-model="form.social_link2" name="social_link2" id="social-link2" v-validate="{required: this.form.social === 'true',url: {require_protocol: true }}" placeholder="www.gab.com">
                <span class="control-error" v-if="errors.has('social_link2')">@{{ errors.first('social_link2') }}</span>
            </div>
            <div class="form-group" v-bind:class="[errors.has('social_link3') ? 'has-error' : '', this.form.social !== 'true' ? 'd-none' : '']">
                <input type="text" class="form-control" data-vv-as=" " v-model="form.social_link3" name="social_link3" id="social-link3" v-validate="{required: this.form.social === 'true',url: {require_protocol: true }}" placeholder="www.parler.com">
                <span class="control-error" v-if="errors.has('social_link3')">@{{ errors.first('social_link3') }}</span>
            </div>
        </div>



    </div>

    <!-- FFL shipping address -->
    <div class="row form-section">
        <div class="col-sm-12">
            <h3 class="form-section__title">{{__('ffl::app.steps.business_info.address.title')}}</h3>
            <i data-toggle="tooltip" data-placement="top" title="{{__('ffl::app.steps.business_info.address.tool_tip')}}" class="form-section__icon fa fa-question-circle d-none d-md-inline-block"></i>
            <p class="form-section__help-text d-block d-md-none">{{__('ffl::app.steps.business_info.address.tool_tip')}}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12" v-bind:class="[errors.has('street_address') ? 'has-error' : '']">
            <div class="form-group">
                <label for="street-address">{{__('ffl::app.steps.business_info.address.street')}}</label>
                <input data-vv-as=" " v-validate="'required'" v-model="form.street_address" type="text" name="street_address" id="street-address" class="form-control" placeholder="{{__('ffl::app.steps.business_info.address.street')}}">
                <span class="control-error" v-if="errors.has('street_address')">@{{ errors.first('street_address') }}</span>
            </div>
        </div>
        <div class="col-sm-4" v-bind:class="[errors.has('city') ? 'has-error' : '']">
            <div class="form-group">
                <label for="city">{{__('ffl::app.steps.business_info.address.city')}}</label>
                <input data-vv-as=" " v-validate="'required'" v-model="form.city" type="text" name="city" id="city" class="form-control" placeholder="{{__('ffl::app.steps.business_info.address.city')}}">
                <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
            </div>
        </div>
        <div class="col-sm-4" v-bind:class="[errors.has('mailing_state') ? 'has-error' : '']">

            <div class="form-group">
                <label for="mailing-state">{{__('ffl::app.steps.business_info.address.state')}}</label>
                <select class="form-control" v-validate="'required'" data-vv-as=" " v-model="form.mailing_state" id="mailing-state" name="mailing_state">
                    @foreach($states as $state)
                    <option value="{{ $state->id }}">{{ $state->default_name}}</option>
                    @endforeach
                </select>
                <span class="control-error" v-if="errors.has('mailing_state')">@{{ errors.first('mailing_state') }}</span>
            </div>

        </div>
        <div class="col-sm-4" v-bind:class="[errors.has('zip_code') ? 'has-error' : '']">
            <div class="form-group">
                <label for="zip-code">{{__('ffl::app.steps.business_info.address.post')}}</label>
                <input data-vv-as=" " v-validate="'required|numeric'" v-model="form.zip_code" type="text" name="zip_code" id="zip-code" class="form-control" placeholder="{{__('ffl::app.steps.business_info.address.post')}}">
                <span class="control-error" v-if="errors.has('zip_code')">@{{ errors.first('zip_code') }}</span>
            </div>
        </div>
    </div>

    <!-- FFL contact info -->
    <div class="row form-section">
        <div class="col-sm-12">
            <h3 class="form-section__title">{{__('ffl::app.steps.business_info.contact.title')}}</h3>
            <i data-toggle="tooltip" data-placement="top" title="{{__('ffl::app.steps.business_info.contact.tool_tip')}}" class="form-section__icon fa fa-question-circle d-none d-md-inline-block"></i>
            <p class="form-section__help-text d-block d-md-none">{{__('ffl::app.steps.business_info.contact.tool_tip')}}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4" v-bind:class="[errors.has('phone') ? 'has-error' : '']">
            <div class="form-group">
                <label for="phone">{{__('ffl::app.steps.business_info.contact.phone')}}</label>
                <input data-vv-as=" " v-validate="'required|numeric'" v-model="form.phone" type="tel" name="phone" id="phone" class="form-control" placeholder="{{__('ffl::app.steps.business_info.contact.phone')}}">
                <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
            </div>
        </div>
        <div class="col-sm-4" v-bind:class="[errors.has('email') ? 'has-error' : '']">
            <div class="form-group">
                <label for="email">{{__('ffl::app.steps.business_info.contact.email')}}</label>
                <input data-vv-as=" " v-validate="'required|email'" v-model="form.email" type="email" name="email" id="email" class="form-control" placeholder="{{__('ffl::app.steps.business_info.contact.email')}}">
                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
            </div>
        </div>
        <div class="col-sm-4" v-bind:class="[errors.has('business_hours') ? 'has-error' : '']">
            <div class="form-group">
                <label for="business-hours">{{__('ffl::app.steps.business_info.contact.business_hrs')}}</label>
                <input data-vv-as=" " v-validate="'required'" v-model="form.business_hours" type="text" name="business_hours" id="business-hours" class="form-control" placeholder="{{__('ffl::app.steps.business_info.contact.example')}}">
                <span class="control-error" v-if="errors.has('business_hours')">@{{ errors.first('business_hours') }}</span>
            </div>
        </div>
    </div>
    <div class="form-actions d-flex">
        <button ref="cancel" data-url="{{route('ffl.onboarding.landing')}}" v-on:click="onCancel" class="btn btn-outline-dark">{{__('ffl::app.buttons.cancel')}}</button>
        <button v-on:click="onNextStep" class="btn btn-primary ml-auto">{{__('ffl::app.buttons.continue')}}</button>
    </div>
</div>