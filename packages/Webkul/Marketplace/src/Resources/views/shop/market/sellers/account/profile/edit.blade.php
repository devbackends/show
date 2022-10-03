@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.profile.edit-title') }}
@endsection

@section('content')

    <seller-profile></seller-profile>


@endsection

@push('scripts')
    <script type="text/x-template" id="seller-profile-template">
        <div class="settings-page">
            <form method="post" action="{{ route('marketplace.account.seller.update', $seller->id) }}"
                  @submit.prevent="onSellerProfileSubmit" enctype="multipart/form-data">
                {!! view_render_event('marketplace.sellers.account.profile.edit.before', ['seller' => $seller]) !!}
                @csrf()
                @method('PUT')

                <div class="settings-page__header">
                    <div class="settings-page__header-title">
                        <p>{{ __('marketplace::app.shop.sellers.account.profile.edit-title') }}</p>
                    </div>
                    <div class="settings-page__header-actions">
<!--                        <a href="{{ route('marketplace.seller.show', $seller->url) }}" target="_blank" class="btn btn-outline-primary ml-2">{{ __('marketplace::app.shop.sellers.account.profile.view-collection-page') }}</a>-->
                        <a href="{{ route('marketplace.seller.show', $seller->url) }}" target="_blank" class="btn btn-outline-primary ml-2">{{ __('marketplace::app.shop.sellers.account.profile.view-seller-page') }}</a>
                        <button type="submit" class="btn btn-primary ml-2" onclick="document.getElementById('myCheck').click();">
                            Save
                        </button>
                        <button  type="submit" class="btn btn-primary invisible position-absolute" id="myCheck">{{ __('marketplace::app.shop.sellers.account.profile.save-btn-title') }}</button>
                    </div>
                </div>


                <edit-page-error-alert></edit-page-error-alert>

                <div class="settings-page__body">
                    <!-- PROFILE LIST -->
                    <div class="accordion list-group list-group-flush list-group-accordion"
                         id="marketplace-profile-list">
                        <div class="wrap list-group-item">
                            <a class="list-group-accordion-btn" href="#" data-toggle="collapse"
                               data-target="#collapseProfile1" aria-expanded="false"
                               aria-controls="collapseProfile1"><span>{{ __('marketplace::app.shop.sellers.account.profile.general') }}</span>
                                <i class="fal fa-angle-right"></i></a>
                            <div id="collapseProfile1" class="collapse">
                                <div class="inner">
                                    <div class="form-group" :class="[errors.has('shop_title') ? 'has-error' : '']">
                                        <label
                                            for="shop_title">{{ __('marketplace::app.shop.sellers.account.profile.shop_title') }}</label>
                                        <input type="text" class="form-control" name="shop_title"
                                               v-validate="'required'"
                                               data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.shop_title') }}&quot;"
                                               value="{{ old('shop_title') ?: $seller->shop_title }}">
                                        <span class="control-error" v-if="errors.has('shop_title')">@{{ errors.first('shop_title') }}</span>
                                    </div>
                                    <div class="form-group" :class="[errors.has('url') ? 'has-error' : '']">
                                        <label
                                            for="url">{{ __('marketplace::app.shop.sellers.account.profile.url') }}</label>
                                        <input type="text" class="form-control" name="url" v-validate="'required'"
                                               data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.url') }}&quot;"
                                               value="{{ old('url') ?: $seller->url }}">
                                        <span class="control-help">Please use lowercase letters and numbers only.</span>
                                        <span class="control-error"
                                              v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="tax_vat">{{ __('marketplace::app.shop.sellers.account.profile.tax_vat') }}</label>
                                                <input type="text" class="form-control" name="tax_vat"
                                                       value="{{ old('tax_vat') ?: $seller->tax_vat }}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group" :class="[errors.has('phone') ? 'has-error' : '']">
                                                <label
                                                    for="phone">{{ __('marketplace::app.shop.sellers.account.profile.phone') }}</label>
                                                <input type="text" class="form-control" name="phone"
                                                       v-validate="'required'"
                                                       data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.phone') }}&quot;"
                                                       value="{{ old('phone') ?: $seller->phone }}">
                                                <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="[errors.has('address1') ? 'has-error' : '']">
                                        <label
                                            for="address1">{{ __('marketplace::app.shop.sellers.account.profile.address1') }}</label>
                                        <input type="text" class="form-control" name="address1" v-validate="'required'"
                                               data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.address1') }}&quot;"
                                               value="{{ old('address1') ?: $seller->address1 }}">
                                        <span class="control-error" v-if="errors.has('address1')">@{{ errors.first('address1') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="address2">{{ __('marketplace::app.shop.sellers.account.profile.address2') }}</label>
                                        <input type="text" class="form-control" name="address2"
                                               value="{{ old('address2') ?: $seller->address2 }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <div class="form-group" :class="[errors.has('city') ? 'has-error' : '']">
                                                <label
                                                    for="city">{{ __('marketplace::app.shop.sellers.account.profile.city') }}</label>
                                                <input type="text" class="form-control" name="city"
                                                       v-validate="'required'"
                                                       data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.city') }}&quot;"
                                                       value="{{ old('city') ?: $seller->city }}">
                                                <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                @include ('shop::customers.account.address.country-state', ['countryCode' => old('country') ?? $seller->country, 'stateCode' => old('state') ?? $seller->state])
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-group"
                                                 :class="[errors.has('postcode') ? 'has-error' : '']">
                                                <label
                                                    for="postcode">{{ __('marketplace::app.shop.sellers.account.profile.postcode') }}</label>
                                                <input type="text" class="form-control" name="postcode"
                                                       v-validate="'required'"
                                                       data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.postcode') }}&quot;"
                                                       value="{{ old('postcode') ?: $seller->postcode }}">
                                                <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrap list-group-item">
                            <a class="list-group-accordion-btn" href="#" data-toggle="collapse"
                               data-target="#collapseProfile7"
                               aria-expanded="false"
                               aria-controls="collapseProfile7"><span>{{ __('marketplace::app.shop.sellers.account.profile.certifications') }}</span>
                                <i class="fal fa-angle-right"></i></a>
                            <div id="collapseProfile7" class="collapse">
                                <div class="inner">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">Instructor Badges</h5>
                                                    <div class="form-group">
                                                        <label>I am USCCA certified instructor.</label>
                                                        <label class="switch"><input type="checkbox" v-on:click="isHidden = !isHidden"
                                                                                     @if($seller->uscca_certified==1) checked="checked"
                                                                                     @endif id="uscca_certified"
                                                                                     name="uscca_certified" value="1"
                                                                                     class="control"> <span
                                                                class="slider round"></span></label>
                                                        <div v-if="!isHidden" id="instructor_number_container" class="mt-3"
                                                             :class="[errors.has('instructor_number') ? 'has-error' : '']">
                                                            <input type="text" placeholder="Instructor number" class="form-control" name="instructor_number"
                                                                   v-validate="uscca" data-vv-as="&quot;Instructor Number&quot;"
                                                                   value="{{ old('instructor_number') ?: $seller->instructor_number }}">
                                                            <span class="control-error" v-if="errors.has('instructor_number')">@{{ errors.first('instructor_number') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>I am an NRA certified member.</label>
                                                        <label class="switch"><input type="checkbox"
                                                                                     @if($seller->nra_certified==1) checked="checked"
                                                                                     @endif id="nra_certified" name="nra_certified"
                                                                                     value="1" class="control"> <span class="slider round"></span></label>
                                                    </div>
                                                    <div>
                                                        <div class="custom-file">
                                                            <input v-validate="'ext:pdf'" accept=".pdf" type="file" class="custom-file-input" id="certification" name="certification">
                                                            <label class="custom-file-label" for="certification">{{ __('marketplace::app.shop.sellers.account.profile.certification') }} (Pdf format)</label>
                                                        </div>
                                                        <div class="mt-3">
                                                            @if(!empty($seller->certification))
                                                                <a href="{{getenv('WASSABI_STORAGE').'/'.$seller->certification}}" target="_blank"> my certification</a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>I'm not an instructor, but I participate in gun and knife
                                                    shows.</label>
                                                <label class="switch"><input type="checkbox"
                                                                             @if($seller->general_events_certified==1) checked="checked"
                                                                             @endif id="general_events_certified"
                                                                             name="general_events_certified" value="1"
                                                                             class="control"> <span
                                                        class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="description">About Instructor</label>
                                                <textarea class="form-control" id="instructor_description"
                                                          name="instructor_description">{{ old('instructor_description') ?: $seller->instructor_description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Retailer Badge</label>
                                                <label class="switch"><input type="checkbox"
                                                                             @if($seller->retailer_badge==1) checked="checked"
                                                                             @endif id="retailer_badge"
                                                                             name="retailer_badge" value="1"
                                                                             class="control"> <span
                                                        class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Competition Shooter Badge</label>
                                                <label class="switch"><input type="checkbox"
                                                                             @if($seller->competition_shooter_badge==1) checked="checked"
                                                                             @endif id="competition_shooter_badge"
                                                                             name="competition_shooter_badge" value="1"
                                                                             class="control"> <span
                                                        class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Promotor Badge</label>
                                                <label class="switch"><input type="checkbox"
                                                                             @if($seller->promotor_badge==1) checked="checked"
                                                                             @endif id="promotor_badge"
                                                                             name="promotor_badge" value="1"
                                                                             class="control"> <span
                                                        class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Veteran Badge</label>
                                                <label class="switch"><input type="checkbox"
                                                                             @if($seller->veteran_badge==1) checked="checked"
                                                                             @endif id="veteran_badge"
                                                                             name="veteran_badge" value="1"
                                                                             class="control"> <span
                                                        class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Influencer Badge</label>
                                                <label class="switch"><input type="checkbox"
                                                                             @if($seller->influencer_badge==1) checked="checked"
                                                                             @endif id="influencer_badge"
                                                                             name="influencer_badge" value="1"
                                                                             class="control"> <span
                                                        class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrap list-group-item">
                            <a class="list-group-accordion-btn" href="#" data-toggle="collapse"
                               data-target="#collapseProfile2" aria-expanded="false"
                               aria-controls="collapseProfile2"><span>{{ __('marketplace::app.shop.sellers.account.profile.media') }}</span>
                                <i class="fal fa-angle-right"></i></a>
                            <div id="collapseProfile2" class="collapse">
                                <div class="inner">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('marketplace::app.shop.sellers.account.profile.logo') }}</label>
                                                <label>(200 x 200)</label>
                                                <image-uploader
                                                    :button-label="'{{ __('marketplace::app.shop.sellers.account.profile.add-image-btn-title') }}'"
                                                    input-name="logo" :multiple="false"
                                                    :images='"{{ $seller->logo_url }}"'></image-uploader>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('marketplace::app.shop.sellers.account.profile.banner') }}</label>
                                                <label>(1440 x 260)</label>
                                                <image-uploader
                                                    :button-label="'{{ __('marketplace::app.shop.sellers.account.profile.add-image-btn-title') }}'"
                                                    input-name="banner" :multiple="false"
                                                    :images='"{{ $seller->banner_url }}"'></image-uploader>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrap list-group-item">
                            <a class="list-group-accordion-btn" href="#" data-toggle="collapse"
                               data-target="#collapseProfile3" aria-expanded="false"
                               aria-controls="collapseProfile3"><span>{{ __('marketplace::app.shop.sellers.account.profile.about') }}</span>
                                <i class="fal fa-angle-right"></i></a>
                            <div id="collapseProfile3" class="collapse">
                                <div class="inner">
                                    <div class="form-group">
                                        <label
                                            for="description">{{ __('marketplace::app.shop.sellers.account.profile.about') }}</label>
                                        <textarea class="form-control" id="description"
                                                  name="description">{{ old('description') ?: $seller->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrap list-group-item">
                            <a class="list-group-accordion-btn" href="#" data-toggle="collapse"
                               data-target="#collapseProfile4" aria-expanded="false"
                               aria-controls="collapseProfile4"><span>{{ __('marketplace::app.shop.sellers.account.profile.social_links') }}</span>
                                <i class="fal fa-angle-right"></i></a>
                            <div id="collapseProfile4" class="collapse">
                                <div class="inner">
                                    <div class="form-group">
                                        <label
                                            for="twitter">{{ __('marketplace::app.shop.sellers.account.profile.twitter') }}</label>
                                        <input type="text" class="form-control" name="twitter"
                                               value="{{ old('twitter') ?: $seller->twitter }}">
                                        <span class="control-error"
                                              v-if="errors.has('twitter')">@{{ errors.first('twitter') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="facebook">{{ __('marketplace::app.shop.sellers.account.profile.facebook') }}</label>
                                        <input type="text" class="form-control" name="facebook"
                                               value="{{ old('facebook') ?: $seller->facebook }}"
                                               v-validate="{url: {require_protocol: true }}">
                                        <span class="control-error"
                                              v-if="errors.has('facebook')">@{{ errors.first('facebook') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="youtube">{{ __('marketplace::app.shop.sellers.account.profile.youtube') }}</label>
                                        <input type="text" class="form-control" name="youtube"
                                               value="{{ old('youtube') ?: $seller->youtube }}"
                                               v-validate="{url: {require_protocol: true }}">
                                        <span class="control-error"
                                              v-if="errors.has('youtube')">@{{ errors.first('youtube') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="instagram">{{ __('marketplace::app.shop.sellers.account.profile.instagram') }}</label>
                                        <input type="text" class="form-control" name="instagram"
                                               value="{{ old('instagram') ?: $seller->instagram }}">
                                        <span class="control-error"
                                              v-if="errors.has('instagram')">@{{ errors.first('instagram') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="linked_in">{{ __('marketplace::app.shop.sellers.account.profile.linked_in') }}</label>
                                        <input type="text" class="form-control" name="linked_in"
                                               value="{{ old('linked_in') ?: $seller->linked_in }}">
                                        <span class="control-error"
                                              v-if="errors.has('linked_in')">@{{ errors.first('linked_in') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="parler">{{ __('marketplace::app.shop.sellers.account.profile.parler') }}</label>
                                        <input type="text" class="form-control" name="parler"
                                               value="{{ old('parler') ?: $seller->parler }}">
                                        <span class="control-error"
                                              v-if="errors.has('parler')">@{{ errors.first('parler') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="gab">{{ __('marketplace::app.shop.sellers.account.profile.gab') }}</label>
                                        <input type="text" class="form-control" name="gab"
                                               value="{{ old('gab') ?: $seller->gab }}">
                                        <span class="control-error"
                                              v-if="errors.has('gab')">@{{ errors.first('gab') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrap list-group-item">
                            <a class="list-group-accordion-btn" href="#" data-toggle="collapse"
                               data-target="#collapseProfile5" aria-expanded="false"
                               aria-controls="collapseProfile5"><span>{{ __('marketplace::app.shop.sellers.account.profile.policies') }}</span>
                                <i class="fal fa-angle-right"></i></a>
                            <div id="collapseProfile5" class="collapse">
                                <div class="inner">
                                    <div class="form-group">
                                        <label
                                            for="return_policy">{{ __('marketplace::app.shop.sellers.account.profile.return_policy') }}</label>
                                        <textarea class="form-control" id="return_policy"
                                                  name="return_policy">{{ old('return_policy') ?: $seller->return_policy }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="shipping_policy">{{ __('marketplace::app.shop.sellers.account.profile.shipping_policy') }}</label>
                                        <textarea class="form-control" id="shipping_policy"
                                                  name="shipping_policy">{{ old('shipping_policy') ?: $seller->shipping_policy }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="privacy_policy">{{ __('marketplace::app.shop.sellers.account.profile.privacy_policy') }}</label>
                                        <textarea class="form-control" id="privacy_policy"
                                                  name="privacy_policy">{{ old('privacy_policy') ?: $seller->privacy_policy }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrap list-group-item">
                            <a class="list-group-accordion-btn" href="#" data-toggle="collapse"
                               data-target="#collapseProfile6" aria-expanded="false"
                               aria-controls="collapseProfile6"><span>{{ __('marketplace::app.shop.sellers.account.profile.seo') }}</span>
                                <i class="fal fa-angle-right"></i></a>
                            <div id="collapseProfile6" class="collapse">
                                <div class="inner">
                                    <div class="form-group">
                                        <label
                                            for="meta_description">{{ __('marketplace::app.shop.sellers.account.profile.meta_description') }}</label>
                                        <textarea class="form-control" id="meta_description"
                                                  name="meta_description">{{ old('meta_description') ?: $seller->meta_description }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="meta_keywords">{{ __('marketplace::app.shop.sellers.account.profile.meta_keywords') }}</label>
                                        <textarea class="form-control" id="meta_keywords"
                                                  name="meta_keywords">{{ old('meta_keywords') ?: $seller->meta_keywords }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PROFILE LIST -->
                </div>

                {!! view_render_event('marketplace.sellers.account.profile.edit.after', ['seller' => $seller]) !!}
            </form>
        </div>
    </script>
    <script>
        Vue.component('seller-profile', {
            template: '#seller-profile-template',
            inject: ['$validator'],
            data: function () {
                return {
                    uscca:"",
                    isHidden: false
                }
            },
            mounted(){
                this.rules();
            },
            methods: {
                rules () {
                    if ($('#uscca_certified').is(":checked")) {
                       this.uscca="required";
                       this.isHidden=false;
                    }else{
                        this.uscca="";
                        this.isHidden=true;
                    }
                },
                onSellerProfileSubmit: function (event) {
                    event.preventDefault();
                    this.$validator.validateAll().then(result => {
                        if (result) {
                            event.target.submit();
                        } else {
                            this.toggleButtonDisability({event, actionType: false});
                            eventBus.$emit('onFormError')
                        }
                    });
                },
            },
        })
    </script>
    <script type="text/x-template" id="edit-page-error-alert-template">
        <div v-if="errors.items.length > 0" class="alert alert-danger" role="alert">
            <div class="alert__icon"><i class="far fa-exclamation-circle"></i></div>
            Please Fill Out The Missing Data In The Required Fields Below
        </div>
    </script>
    <script>
        Vue.component('edit-page-error-alert', {
            template: '#edit-page-error-alert-template',
            inject: ['$validator']
        })
    </script>
    <script>
        $(document).ready(function () {
            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
            ClassicEditor
                .create(document.querySelector('#return_policy'))
                .catch(error => {
                    console.error(error);
                });
            ClassicEditor
                .create(document.querySelector('#shipping_policy'))
                .catch(error => {
                    console.error(error);
                });
            ClassicEditor
                .create(document.querySelector('#privacy_policy'))
                .catch(error => {
                    console.error(error);
                });

            $('#certification').on('change',function(){
                //get the file name
                var fileName = $(this).val();
                //replace the "Choose a file" label
                $(this).next('.custom-file-label').html(fileName);
            })
        });
    </script>
@endpush