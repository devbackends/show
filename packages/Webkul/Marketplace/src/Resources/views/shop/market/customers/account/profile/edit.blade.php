@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@section('page-detail-wrapper')

    {!! view_render_event('bagisto.shop.customers.account.profile.view.before', ['customer' => $customer]) !!}



<div class="settings-page">
    <form id="profile-form" method="POST" @submit.prevent="onSubmit" class="account-table-content" action="{{ route('customer.profile.edit') }}">

        @csrf
        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('shop::app.customer.account.profile.index.title') }}</p>
            </div>
            <div class="settings-page__header-actions"></div>
        </div>

        <div class="settings-page__body">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group" :class="`${errors.has('first_name') ? 'has-error' : ''}`">
                        <label>{{ __('shop::app.customer.account.profile.fname') }}</label>
                        <input value="{{ $customer->first_name }}" name="first_name" type="text" class="form-control" v-validate="'required'" />
                        <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>{{ __('shop::app.customer.account.profile.lname') }}</label>
                        <input value="{{ $customer->last_name }}" name="last_name" type="text" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group" :class="`${errors.has('gender') ? 'has-error' : ''}`">
                        <div class="form-label">{{ __('shop::app.customer.account.profile.gender') }}</div>

                        <div>
                            <div class="form-check form-check-inline">
                                <input type="radio" name="gender" value="Male" id="profileGender1" v-validate="'required'" @if ($customer->gender == "Male") checked="checked" @endif data-vv-as="&quot;{{ __('shop::app.customer.account.profile.gender') }}&quot;">
                                <label class="form-check-label" for="profileGender1">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" name="gender" value="Female" id="profileGender2" v-validate="'required'" @if ($customer->gender == "Female") checked="checked" @endif data-vv-as="&quot;{{ __('shop::app.customer.account.profile.gender') }}&quot;">
                                <label class="form-check-label" for="profileGender2">Female</label>
                            </div>
                        </div>
                        <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-date" :class="`${errors.has('date_of_birth') ? 'has-error' : ''}`">
                        <label>{{ __('shop::app.customer.account.profile.dob') }}</label>
                        <input class="form-control" type="date" name="date_of_birth" placeholder="dd/mm/yyyy" value="{{ old('date_of_birth') ?? $customer->date_of_birth }}" v-validate="" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.dob') }}&quot;" />
                        <span class="control-error" v-if="errors.has('date_of_birth')">@{{ errors.first('date_of_birth') }}</span>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>{{ __('shop::app.customer.account.profile.email') }}</label>
                        <input value="{{ $customer->email }}" name="email" type="text" v-validate="'required'" class="form-control" />
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group" :class="`${errors.has('oldpassword') ? 'has-error' : ''}`">
                        <label>{{ __('velocity::app.shop.general.enter-current-password') }}</label>
                        <input value="" name="oldpassword" type="password" class="form-control" />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group" :class="`${errors.has('password') ? 'has-error' : ''}`">
                        <label>{{ __('velocity::app.shop.general.new-password') }}</label>
                        <input class="form-control" value="" name="password" type="password" v-validate="'min:6|max:30'" />
                        <span class="control-error" v-if="errors.has('password')">
                            @{{ errors.first('password') }}
                        </span>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group" :class="`${errors.has('password_confirmation') ? 'has-error' : ''}`">
                        <label>{{ __('velocity::app.shop.general.confirm-new-password') }}</label>
                        <input value="" name="password_confirmation" type="password" class="form-control" v-validate="'min:6|max:30'" data-vv-as="confirm password" />
                        <span class="control-error" v-if="errors.has('password_confirmation')">
                            @{{ errors.first('password_confirmation') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-action d-flex justify-content-between">
                <a onclick="document.getElementById('profile-submit-button').click();" class="btn btn-outline-primary"><i class="far fa-save"></i><span>Save</span></a>
                <a data-toggle="modal" data-target="#deleteProfile" class="btn btn-outline-gray"><i class="far fa-trash-alt"></i><span>Delete account</span></a>

                <button id="profile-submit-button" type="submit" class="hide">
                    {{ __('velocity::app.shop.general.update') }}
                </button>
            </div>
        </div>
    </form>
</div>


<div class="modal fade" id="deleteProfile" tabindex="-1" aria-labelledby="deleteProfileLabel" aria-hidden="true" :is-open="modalIds.deleteProfile">
    <div class="modal-dialog">
        <form class="profile-modal" method="POST" action="{{ route('customer.profile.destroy') }}" >
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <div class="modal-header-content">
                        <i class="fal fa-trash-alt"></i>
                        <h5 class="modal-title" id="deleteProfileLabel">Are you sure you want to delete your account? Please confirm by entering your password</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" :class="[errors.has('password1') ? 'has-error' : '']">
                        <input type="password" class="form-control" id="account-password" placeholder="Password" name="password" data-vv-as="&quot;{{ __('admin::app.users.users.password') }}&quot;" />
                        <p id="password-error" class="control-error hide" >The field 'password' is required</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                    <button type="submit"  class="btn btn-danger" id="delete-button">Yes, delete account</button>
                </div>
            </div>
        </form>
    </div>
</div>


{!! view_render_event('bagisto.shop.customers.account.profile.view.after', ['customer' => $customer]) !!}

@push('scripts')
<script type="text/javascript">
    $( document ).ready(function() {
        $( document ).on('submit','.profile-modal',function(e){
             if(!$('#account-password').val()){
                 e.preventDefault();
                 $("#password-error").removeClass('hide');
             }else{
                 $("#password-error").addClass('hide');
             }
        });
    });
</script>
@endpush
@endsection