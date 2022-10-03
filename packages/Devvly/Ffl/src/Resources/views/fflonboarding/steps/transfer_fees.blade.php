<div class="fees-wrapper" v-bind:class="{'d-none': currentStep !== 3}">

    <div class="row form-section">
        <div class="col-sm-12">
            <h3 class="form-section__title">{{__('ffl::app.steps.fees.title')}}</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="long-gun">{{__('ffl::app.steps.fees.guns.long')}}</label>
                <div class="row">
                    <div class="col-sm-3 control-group mb-3 mb-sm-0" v-bind:class="[errors.has('long_gun') ? 'has-error' : '']">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span id="basic-addon" class="input-group-text bg-white border-right-0">$</span>
                            </div>
                            <input v-model="form.long_gun" data-vv-as=" " v-validate="'required|decimal'" type="text" name="long_gun" id="long-gun" class="form-control border-left-0" placeholder="0.00" aria-describedby="basic-addon">
                        </div>
                        <span class="control-error" v-if="errors.has('long_gun')">@{{ errors.first('long_gun') }}</span>
                    </div>
                    <div class="col-sm-9 control-group" v-bind:class="[errors.has('long_gun_description') ? 'has-error' : '']">
                        <input v-model="form.long_gun_description" data-vv-as=" " type="text" name="long_gun_description" class="form-control" placeholder="{{__('ffl::app.steps.fees.add_description')}}">
                        <span class="control-error" v-if="errors.has('long_gun_description')">@{{ errors.first('long_gun_description') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="hand-gun">{{__('ffl::app.steps.fees.guns.hand')}}</label>
                <div class="row">
                    <div class="col-sm-3 control-group mb-3 mb-sm-0" v-bind:class="[errors.has('hand_gun') ? 'has-error' : '']">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span id="basic-addon2" class="input-group-text bg-white border-right-0">$</span>
                            </div>
                            <input v-model="form.hand_gun" data-vv-as=" " v-validate="'required|decimal'" type="text" name="hand_gun" id="hand-gun" class="form-control border-left-0" placeholder="0.00" aria-describedby="basic-addon2">
                        </div>
                        <span class="control-error" v-if="errors.has('hand_gun')">@{{ errors.first('hand_gun') }}</span>
                    </div>
                    <div class="col-sm-9 control-group" v-bind:class="[errors.has('hand_gun_description') ? 'has-error' : '']">
                        <input v-model="form.hand_gun_description" data-vv-as=" " type="text" name="hand_gun_description" class="form-control" placeholder="{{__('ffl::app.steps.fees.add_description')}}">
                        <span class="control-error" v-if="errors.has('hand_gun_description')">@{{ errors.first('hand_gun_description') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="nics">{{__('ffl::app.steps.fees.guns.nics')}}</label>
                <div class="row">
                    <div class="col-sm-3 control-group mb-3 mb-sm-0" v-bind:class="[errors.has('nics') ? 'has-error' : '']">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span id="basic-addon3" class="input-group-text bg-white border-right-0">$</span>
                            </div>
                            <input v-model="form.nics" data-vv-as=" " v-validate="'required|decimal'" type="text" name="nics" id="nics" class="form-control border-left-0" placeholder="0.00" aria-describedby="basic-addon3">
                        </div>
                        <span class="control-error" v-if="errors.has('nics')">@{{ errors.first('nics') }}</span>
                    </div>
                    <div class="col-sm-9 control-group" v-bind:class="[errors.has('nics_description') ? 'has-error' : '']">
                        <input v-model="form.nics_description" data-vv-as=" " type="text" name="nics_description" class="form-control" placeholder="{{__('ffl::app.steps.fees.add_description')}}">
                        <span class="control-error" v-if="errors.has('nics_description')">@{{ errors.first('nics_description') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="other">{{__('ffl::app.steps.fees.guns.other')}}</label>
                <div class="row">
                    <div class="col-sm-3 control-group mb-3 mb-sm-0" v-bind:class="[errors.has('other') ? 'has-error' : '']">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span id="basic-addon4" class="input-group-text bg-white border-right-0">$</span>
                            </div>
                            <input v-model="form.other" data-vv-as=" " type="text" name="other" id="other" class="form-control border-left-0" placeholder="0.00" aria-describedby="basic-addon4">
                        </div>
                        <span class="control-error" v-if="errors.has('other')">@{{ errors.first('other') }}</span>
                    </div>
                    <div class="col-sm-9 control-group" v-bind:class="[errors.has('other_description') ? 'has-error' : '']">
                        <input v-model="form.other_description" data-vv-as=" " type="text" name="other_description" class="form-control" placeholder="{{__('ffl::app.steps.fees.add_description')}}">
                        <span class="control-error" v-if="errors.has('other_description')">@{{ errors.first('other_description') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row form-section">
        <div class="col-sm-12">
            <h3 class="form-section__title">{{__('ffl::app.steps.fees.payment.title')}}</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12" id="mailing_address_same_container">
            <div class="form-group" v-bind:class="[errors.has('payment') ? 'has-error' : '']">
                <label class="w-100">{{__('ffl::app.steps.business_info.questions.retail')}}</label>
                <span class="control-error" v-if="errors.has('payment')">@{{ errors.first('payment') }}</span>

                <div class="radio-button">
                    <div class="radio-button__input">
                        <input v-model="form.payment" data-vv-as=" " v-validate="'required'" type="radio" value="cc_cash" name="payment">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.fees.payment.both')}}</div>
                </div>
                <div class="radio-button">
                    <div class="radio-button__input">
                        <input v-model="form.payment" data-vv-as=" " v-validate="'required'" type="radio" value="cc" name="payment">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.fees.payment.cc')}}</div>
                </div>
                <div class="radio-button">
                    <div class="radio-button__input">
                        <input v-model="form.payment" data-vv-as=" " v-validate="'required'" type="radio" value="cash" name="payment">
                        <label for="onboadrding-radio-view"></label>
                    </div>
                    <div class="radio-button__label">{{__('ffl::app.steps.fees.payment.cash')}}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12" v-bind:class="[errors.has('comments') ? 'has-error' : '']">
            <div class="form-group form-field__step-comments">
                <label for="comments">{{__('ffl::app.steps.fees.comments')}}</label>
                <textarea v-model="form.comments" data-vv-as=" " class="form-control" name="comments" id="comments" rows="3"></textarea>
                <span class="control-error" v-if="errors.has('comments')">@{{ errors.first('comments') }}</span>
            </div>
        </div>
    </div>

    <div class="form-actions d-flex">
        <button v-on:click="onPrevStep" class="btn btn-outline-dark">{{__('ffl::app.buttons.back')}}</button>
        <button ref="submit" data-url="{{route('ffl.onboarding.finish')}}" v-on:click="onSubmit" class="btn btn-primary ml-auto">{{__('ffl::app.buttons.submit')}}</button>
        <div>
            <div v-if="http_error" v-bind:class="[http_error ? 'has-error' : '']" class="col-sm-12 control-group">
                <span class="control-error" v-for="error in http_error.data.errors ">@{{ error[0] }}</span>
            </div>
        </div>
    </div>

</div>