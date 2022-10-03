@section('navbar')
<div class="row ffl-form__navbar">
    <div class="col-12">
        <div class="container ffl-form__navbar-container">
            <div class="row py-4">
                <div class="col-sm-6 mb-4 mb-sm-0">
                    <h3 class="promo mb-0">
                        {{__('ffl::app.nav.join')}}
                        <span class="yellow">{{__('ffl::app.nav.preffered_ffl')}}</span>
                        {{__('ffl::app.nav.3_steps')}}
                    </h3>
                </div>
                <div class="steps-container col-sm-6">
                    <span id="business-information-step" class="step_title active">{{__('ffl::app.steps.business_info.title')}}</span>
                    <span id="ffl-license-step" class="step_title" v-bind:class="{'active': currentStep >= 2}">{{__('ffl::app.steps.license.title')}}</span>
                    <span id="transfers-fees-step" v-bind:class="{'active': currentStep >= 3}" class="step_title">{{__('ffl::app.steps.fees.nav_title')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection