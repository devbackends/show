@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.profile.edit-title') }}
@endsection

@section('content')
    <div class="account-layout right m10">

        <form method="post" action="{{ route('marketplace.account.seller.update', $seller->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data" class="account-table-content padding-sides-15">
            <div class="account-head seller-profile-edit mb-10 margin-t-20">

                <span class="paragraph-big bold">{{ __('marketplace::app.shop.sellers.account.profile.edit-title') }}</span>

                <div class="account-action">

                    <a href="{{ route('marketplace.seller.show', $seller->url) }}" target="_blank" class="light-black-bordered-button" style="cursor: pointer;">
                        <span>  {{ __('marketplace::app.shop.sellers.account.profile.view-collection-page') }}</span>
                    </a>

                    <a href="{{ route('marketplace.seller.show', $seller->url) }}" target="_blank" class="light-black-bordered-button" style="cursor: pointer;">
                        <span>{{ __('marketplace::app.shop.sellers.account.profile.view-seller-page') }}</span>
                    </a>
                    <a onclick="document.getElementById('myCheck').click();" class="light-black-bordered-button" style="cursor: pointer;">
                        <span><i class="far fa-save padding-sides-5"></i></span>
                        <span>Save</span>
                    </a>
                    <button type="submit" class="btn btn-lg theme-btn hide" id="myCheck">
                        {{ __('marketplace::app.shop.sellers.account.profile.save-btn-title') }}
                    </button>

                </div>

                <div class="horizontal-rule"></div>

            </div>

            {!! view_render_event('marketplace.sellers.account.profile.edit.before', ['seller' => $seller]) !!}

            <div class="account-table-content">

                @csrf()

                <input type="hidden" name="_method" value="PUT">

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.general') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group control-group col-md-4" :class="[errors.has('shop_title') ? 'has-error' : '']">
                            <label for="shop_title" class="required mandatory paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.shop_title') }}</label>
                            <input type="text" class="form-style control" name="shop_title" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.shop_title') }}&quot;" value="{{ old('shop_title') ?: $seller->shop_title }}">
                            <span class="control-error" v-if="errors.has('shop_title')">@{{ errors.first('shop_title') }}</span>
                        </div>

                        <div class="form-group  control-group col-md-4" :class="[errors.has('url') ? 'has-error' : '']">
                            <label for="url" class="required mandatory  mandatory paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.url') }}</label>
                            <input type="text" class="form-style control" name="url" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.url') }}&quot;" value="{{ old('url') ?: $seller->url }}">
                            <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                        </div>

                        <div class="form-group  control-group col-md-4">
                            <label for="tax_vat" class="mandatory paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.tax_vat') }}</label>
                            <input type="text" class="form-style control" name="tax_vat" value="{{ old('tax_vat') ?: $seller->tax_vat }}">
                        </div>

                        <div class="form-group  control-group col-md-4" :class="[errors.has('phone') ? 'has-error' : '']">
                            <label for="phone" class="required mandatory  mandatory paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.phone') }}</label>
                            <input type="text" class="form-style control" name="phone" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.phone') }}&quot;" value="{{ old('phone') ?: $seller->phone }}">
                            <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                        </div>

                        <div class="form-group  control-group col-md-4" :class="[errors.has('address1') ? 'has-error' : '']">
                            <label for="address1" class="required mandatory  mandatory paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.address1') }}</label>
                            <input type="text" class="form-style" name="address1" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.address1') }}&quot;" value="{{ old('address1') ?: $seller->address1 }}">
                            <span class="control-error" v-if="errors.has('address1')">@{{ errors.first('address1') }}</span>
                        </div>

                        <div class="form-group   control-group col-md-4">
                            <label for="address2" class=" mandatory paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.address2') }}</label>
                            <input type="text" class="form-style control" name="address2" value="{{ old('address2') ?: $seller->address2 }}">
                        </div>

                        <div class="form-group   control-group col-md-4" :class="[errors.has('city') ? 'has-error' : '']">
                            <label for="city" class="required mandatory  paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.city') }}</label>
                            <input type="text" class="form-style conntrol" name="city" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.city') }}&quot;" value="{{ old('city') ?: $seller->city }}">
                            <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                        </div>
                        <div class="col-md-4">
                            @include ('shop::customers.account.address.country-state', ['countryCode' => old('country') ?? $seller->country, 'stateCode' => old('state') ?? $seller->state])

                        </div>

                        <div class="form-group  control-group col-md-4" :class="[errors.has('postcode') ? 'has-error' : '']">
                            <label for="postcode" class="required mandatory  paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.postcode') }}</label>
                            <input type="text" class="form-style control" name="postcode" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.postcode') }}&quot;" value="{{ old('postcode') ?: $seller->postcode }}">
                            <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                        </div>


                    </div>
                </accordian>

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.media') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group control-group col-md-6">
                            <label class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.logo') }}</label>

                            <image-wrapper :button-label="'{{ __('marketplace::app.shop.sellers.account.profile.add-image-btn-title') }}'" input-name="logo" :multiple="false" :images='"{{ $seller->logo_url }}"'></image-wrapper>
                        </div>

                        <div class="form-group control-group col-md-6">
                            <label class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.banner') }}</label>

                            <image-wrapper :button-label="'{{ __('marketplace::app.shop.sellers.account.profile.add-image-btn-title') }}'" input-name="banner" :multiple="false" :images='"{{ $seller->banner_url }}"'></image-wrapper>
                        </div>

                    </div>
                </accordian>

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.about') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group control-group col-md-12">
                            <label for="description" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.about') }}</label>
                            <textarea class="form-style control" id="description" name="description">{{ old('description') ?: $seller->description }}</textarea>
                        </div>

                    </div>
                </accordian>

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.social_links') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group control-group col-md-3">
                            <label for="twitter" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.twitter') }}</label>
                            <input type="text" class="form-style control" name="twitter" value="{{ old('twitter') ?: $seller->twitter }}">
                        </div>

                        <div class="form-group control-group col-md-3">
                            <label for="facebook" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.facebook') }}</label>
                            <input type="text" class="form-style control" name="facebook" value="{{ old('facebook') ?: $seller->facebook }}">
                        </div>

                        <div class="form-group control-group col-md-3">
                            <label for="youtube" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.youtube') }}</label>
                            <input type="text" class="form-style control" name="youtube" value="{{ old('youtube') ?: $seller->youtube }}">
                        </div>

                        <div class="form-group control-group col-md-3">
                            <label for="instagram" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.instagram') }}</label>
                            <input type="text" class="form-style control" name="instagram" value="{{ old('instagram') ?: $seller->instagram }}">
                        </div>

                        <div class="form-group control-group col-md-3">
                            <label for="skype" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.skype') }}</label>
                            <input type="text" class="form-style control" name="skype" value="{{ old('skype') ?: $seller->skype }}">
                        </div>

                        <div class="form-group control-group col-md-3">
                            <label for="linked_in" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.linked_in') }}</label>
                            <input type="text" class="form-style control" name="linked_in" value="{{ old('linked_in') ?: $seller->linked_in }}">
                        </div>

                        <div class="form-group control-group col-md-3">
                            <label for="pinterest" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.pinterest') }}</label>
                            <input type="text" class="form-style control" name="pinterest" value="{{ old('pinterest') ?: $seller->pinterest }}">
                        </div>

                    </div>
                </accordian>

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.policies') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group control-group col-md-12">
                            <label for="return_policy" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.return_policy') }}</label>
                            <textarea class="form-style control" id="return_policy" name="return_policy">{{ old('return_policy') ?: $seller->return_policy }}</textarea>
                        </div>

                        <div class="form-group control-group col-md-12">
                            <label for="shipping_policy" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.shipping_policy') }}</label>
                            <textarea class="form-style control" id="shipping_policy" name="shipping_policy">{{ old('shipping_policy') ?: $seller->shipping_policy }}</textarea>
                        </div>

                        <div class="form-group control-group col-md-12">
                            <label for="privacy_policy" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.privacy_policy') }}</label>
                            <textarea class="form-style control" id="privacy_policy" name="privacy_policy">{{ old('privacy_policy') ?: $seller->privacy_policy }}</textarea>
                        </div>

                    </div>
                </accordian>

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.seo') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group control-group col-md-12">
                            <label for="meta_description" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.meta_description') }}</label>
                            <textarea class="form-style control" id="meta_description" name="meta_description">{{ old('meta_description') ?: $seller->meta_description }}</textarea>
                        </div>

                        <div class="form-group control-group col-md-12">
                            <label for="meta_keywords" class="paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.meta_keywords') }}</label>
                            <textarea class="form-style control" id="meta_keywords" name="meta_keywords">{{ old('meta_keywords') ?: $seller->meta_keywords }}</textarea>
                        </div>

                    </div>
                </accordian>

            </div>

            {!! view_render_event('marketplace.sellers.account.profile.edit.after', ['seller' => $seller]) !!}

        </form>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea#description,textarea#return_policy,textarea#shipping_policy,textarea#privacy_policy',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true,
                valid_elements : '*[*]',
                templates: [
                    { title: 'Test template 1', content: 'Test 1' },
                    { title: 'Test template 2', content: 'Test 2' }
                ],
            });
        });
    </script>
@endpush