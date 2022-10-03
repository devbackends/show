<div class="license-wrapper" v-bind:class="{'d-none': currentStep !== 2}">
       <div class="row mt-5">
              <div class="col-sm-12 form-group d-flex flex-wrap">
                     <label for="license-number" class="w-100">{{__('ffl::app.steps.license.ffl_number')}}</label>
                     <input name="license_number_first" v-bind:class="[errors.has('license_number_first') ? 'has-error' : '']" v-model="form.license_number_parts.first" v-validate="'required|min:1|max:1|licenseRegion'" maxlength="1" placeholder="X" type="text" class="form-control col-sm-1 dash-after text-center" />
                     <span class="input-delimiter"> - </span>
                     <input name="license_number_second" v-bind:class="[errors.has('license_number_second') ? 'has-error' : '']" v-model="form.license_number_parts.second" v-validate="'required|min:2|max:2'" maxlength="2" placeholder="XX" type="text" class="form-control col-sm-1 dash-after text-center" />
                     <span class="input-delimiter"> - </span>
                     <input name="license_number_third" v-bind:class="[errors.has('license_number_third') ? 'has-error' : '']" v-model="form.license_number_parts.third" v-validate="'required|min:3|max:3'" maxlength="3" placeholder="XXX" type="text" class="form-control col-sm-1 dash-after text-center" />
                     <span class="input-delimiter"> - </span>
                     <input name="license_number_fourth" v-bind:class="[errors.has('license_number_fourth') ? 'has-error' : '']" v-model="form.license_number_parts.fourth" v-validate="'required|min:2|max:2|licenseType'" maxlength="2" placeholder="XX" type="text" class="form-control col-sm-1 dash-after text-center" />
                     <span class="input-delimiter"> - </span>
                     <input name="license_number_fifth" v-bind:class="[errors.has('license_number_fifth') ? 'has-error' : '']" v-model="form.license_number_parts.fifth" v-validate="'required|min:2|max:2|licenseExpire'" maxlength="2" placeholder="XX" type="text" class="form-control col-sm-1 dash-after text-center" />
                     <span class="input-delimiter"> - </span>
                     <input name="license_number_sixth" v-bind:class="[errors.has('license_number_sixth') ? 'has-error' : '']" v-model="form.license_number_parts.sixth" v-validate="'required|min:3|max:6'" maxlength="6" placeholder="XXXXX" type="text" class="form-control col-sm-2 dash-after text-center" />
              </div>
       </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 form-group control-group" v-bind:class="[errors.has('form.license_name') ? 'has-error' : '']">
            <label for="license-name">License name</label>
            <input v-validate="'required'"
                   data-vv-as=" "
                   v-model="form.license_name"
                   type="text" name="license_name" id="license-name"
                   class="form-control"
                   placeholder="License name">
        </div>
    </div>
       <div class="row form-section">
              <div class="col-sm-12">
                     <h3 class="form-section__title">{{__('ffl::app.steps.license.upload_ffl')}}</h3>
                     <i data-toggle="tooltip" data-placement="top" title="{{__('ffl::app.steps.license.tool_tip')}}" class="form-section__icon fa fa-question-circle d-none d-md-inline-block"></i>
                     <p class="form-section__help-text d-block d-md-none">{{__('ffl::app.steps.business_info.address.tool_tip')}}</p>
              </div>
       </div>

       <div class="row mt-3">
              <div class="col-sm-12" v-bind:class="[errors.has('license_image') ? 'has-error' : '']">
                     <div class="upload-container d-flex align-items-center justify-content-center">
                            <div>
                                   <input v-bind:class="[errors.has('license_image') ? 'has-error' : '']" data-vv-as=" " v-on:change="onFileChange" v-validate="'required|ext:png,jpg,gif,pdf'" data-vv-validate-on="change" class="custom_upload" id="license_image" name="license_image" type="file" />
                                   <div><span class="icon license-icon"></span></div>
                                   <div v-if="form.license_image.name">
                                          <span id="license_file_name" class="text-dark">@{{form.license_image.name}}</span>
                                   </div>
                                   <div v-else>
                                          <span id="license_file_name" class="upload-text">{{__('ffl::app.steps.license.upload_ffl')}}</span>
                                   </div>
                                   <div class="btn btn-outline-gray mt-2">
                                          {{__('ffl::app.buttons.upload')}}
                                   </div>
                                   <p class="text-gray mb-0 mt-2">Or drag it here</p>
                                   <div class="control-error" v-if="errors.has('license_image')">@{{ errors.first('license_image')}}</div>
                            </div>
                     </div>
              </div>
       </div>

       <div class="form-actions d-flex">
              <button v-on:click="onPrevStep" class="btn btn-outline-dark">{{__('ffl::app.buttons.back')}}</button>
              <button v-on:click="onNextStep($event, true); composeLicenseNumber();" class="btn btn-primary ml-auto">{{__('ffl::app.buttons.continue')}}</button>
       </div>
</div>
