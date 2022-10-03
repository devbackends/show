@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.channels.edit-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.channels.update', $channel->id) }}" @submit.prevent="onSubmit"
              enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link"
                           onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.settings.channels.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('admin::app.settings.channels.save-btn-title') }}
                    </button>
                </div>
            </div>
            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    {!! view_render_event('bagisto.admin.settings.channel.edit.before') !!}

                    <accordian :title="'{{ __('admin::app.settings.channels.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.settings.channels.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code"
                                       data-vv-as="&quot;{{ __('admin::app.settings.channels.code') }}&quot;"
                                       value="{{ $channel->code }}" disabled="disabled"/>
                                <input type="hidden" name="code" value="{{ $channel->code }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.settings.channels.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name"
                                       data-vv-as="&quot;{{ __('admin::app.settings.channels.name') }}&quot;"
                                       value="{{ old('name') ?: $channel->name }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="description">{{ __('admin::app.settings.channels.description') }}</label>
                                <textarea class="control" id="description"
                                          name="description">{{ old('description') ?: $channel->description }}</textarea>
                            </div>



                            <div class="control-group" :class="[errors.has('root_category_id') ? 'has-error' : '']">
                                <label for="root_category_id"
                                       class="required">{{ __('admin::app.settings.channels.root-category') }}</label>
                                <?php $selectedOption = old('root_category_id') ?: $channel->root_category_id ?>
                                <select v-validate="'required'" class="control" id="root_category_id"
                                        name="root_category_id"
                                        data-vv-as="&quot;{{ __('admin::app.settings.channels.root-category') }}&quot;">
                                    @foreach (app('Webkul\Category\Repositories\CategoryRepository')->getRootCategories() as $category)
                                        <option
                                            value="{{ $category->id }}" {{ $selectedOption == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('root_category_id')">@{{ errors.first('root_category_id') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('hostname') ? 'has-error' : '']">
                                <label for="hostname"
                                       class="required">{{ __('admin::app.settings.channels.hostname') }}</label>
                                <input type="text" class="control" id="hostname" v-validate="'required'" name="hostname"
                                       value="{{ $channel->hostname }}" placeholder="https://www.example.com"
                                       data-vv-as="&quot;{{ __('admin::app.settings.channels.hostname') }}&quot;"/>

                                <span class="control-error" v-if="errors.has('hostname')">@{{ errors.first('hostname') }}</span>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.settings.channels.currencies-and-locales') }}'"
                               :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('locales[]') ? 'has-error' : '']">
                                <label for="locales"
                                       class="required">{{ __('admin::app.settings.channels.locales') }}</label>
                                <?php $selectedOptionIds = old('locales') ?: $channel->locales->pluck('id')->toArray() ?>
                                <select v-validate="'required'" class="control js-example-basic-multiple" id="locales" name="locales[]"
                                        data-vv-as="&quot;{{ __('admin::app.settings.channels.locales') }}&quot;"
                                        multiple>
                                    @foreach (core()->getAllLocales() as $locale)
                                        <option
                                            value="{{ $locale->id }}" {{ in_array($locale->id, $selectedOptionIds) ? 'selected' : '' }}>
                                            {{ $locale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('locales[]')">@{{ errors.first('locales[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('default_locale_id') ? 'has-error' : '']">
                                <label for="default_locale_id"
                                       class="required">{{ __('admin::app.settings.channels.default-locale') }}</label>
                                <?php $selectedOption = old('default_locale_id') ?: $channel->default_locale_id ?>
                                <select v-validate="'required'" class="control" id="default_locale_id"
                                        name="default_locale_id"
                                        data-vv-as="&quot;{{ __('admin::app.settings.channels.default-locale') }}&quot;">
                                    @foreach (core()->getAllLocales() as $locale)
                                        <option
                                            value="{{ $locale->id }}" {{ $selectedOption == $locale->id ? 'selected' : '' }}>
                                            {{ $locale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('default_locale_id')">@{{ errors.first('default_locale_id') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('currencies[]') ? 'has-error' : '']">
                                <label for="currencies"
                                       class="required">{{ __('admin::app.settings.channels.currencies') }}</label>
                                <?php $selectedOptionIds = old('currencies') ?: $channel->currencies->pluck('id')->toArray() ?>
                                <select v-validate="'required'" class="control js-example-basic-multiple" id="currencies" name="currencies[]"
                                        data-vv-as="&quot;{{ __('admin::app.settings.channels.currencies') }}&quot;"
                                        multiple>
                                    @foreach (core()->getAllCurrencies() as $currency)
                                        <option
                                            value="{{ $currency->id }}" {{ in_array($currency->id, $selectedOptionIds) ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('currencies[]')">@{{ errors.first('currencies[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('base_currency_id') ? 'has-error' : '']">
                                <label for="base_currency_id"
                                       class="required">{{ __('admin::app.settings.channels.base-currency') }}</label>
                                <?php $selectedOption = old('base_currency_id') ?: $channel->base_currency_id ?>
                                <select v-validate="'required'" class="control" id="base_currency_id"
                                        name="base_currency_id"
                                        data-vv-as="&quot;{{ __('admin::app.settings.channels.base-currency') }}&quot;">
                                    @foreach (core()->getAllCurrencies() as $currency)
                                        <option
                                            value="{{ $currency->id }}" {{ $selectedOption == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('base_currency_id')">@{{ errors.first('base_currency_id') }}</span>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.settings.channels.design') }}'" :active="true">
                        <div slot="body">
{{--                            <div class="control-group">--}}
{{--                                <label--}}
{{--                                    for="home_page_content">{{ __('admin::app.settings.channels.home_page_content') }}</label>--}}
{{--                                <textarea class="control" id="home_page_content"--}}
{{--                                          name="home_page_content">{{ old('home_page_content') ?: $channel->home_page_content }}</textarea>--}}
{{--                            </div>--}}

                            {{--                          <div class="control-group">
                                                          <label for="footer_content">{{ __('admin::app.settings.channels.footer_content') }}</label>
                                                          <textarea class="control" id="footer_content" name="footer_content">{{ old('footer_content') ?: $channel->footer_content }}</textarea>
                                                      </div>--}}
                            <div class="control-group" id="footer-content-container">
                                <label
                                    class="padding_10">{{ __('admin::app.settings.channels.footer_content') }}</label>
                                <div id="data_content"></div>
                                <div class="add-icon fix_height">
                                    <input type="hidden" id="channel_id" value="{{$channel->id}}">
                                    <div id="new-icon-group" class="control-group upload-icon-container fix_height">
                                        <label>icon</label>
                                        <img class="padding-top-10" src="">
                                        <br>
                                        <label class="padding-top-10 size_label">(40 x 40)</label>
                                        <input id="new_icon_file" class="padding-top-10 upload-icon-image" type="file"
                                               name="icon">
                                        <span class="control-error">icon image is requierd</span>
                                    </div>
                                    <div id="new-url-group" class="control-group footer-icon-url-container fix_height">
                                        <label>URL</label>

                                        <input id="new_icon_url" type="url" name="url" class="control url_input"
                                               value="">
                                        <span class="control-error">The "URL" has a wrong format</span>


                                    </div>
                                    <div class="add-icon-container fix_height"><span id="add_footer_icon"
                                                                          class="icon expand-custom-icon fix_height"></span></div>
                                </div>

                            </div>

                            <div class="control-group" style="margin-top: 40px;">
                                <label>{{ __('admin::app.settings.channels.logo') }}</label>

                                <image-wrapper
                                    :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'"
                                    input-name="logo" :multiple="false"
                                    :images='"{{ $channel->logo_url }}"'></image-wrapper>
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.settings.channels.favicon') }}

                                    <image-wrapper
                                        :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'"
                                        input-name="favicon" :multiple="false"
                                        :images='"{{ $channel->favicon_url }}"'></image-wrapper>
                            </div>

                        </div>
                    </accordian>

                    @php
                        $seo = json_decode($channel->home_seo);
                    @endphp

                    <accordian :title="'{{ __('admin::app.settings.channels.seo') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('seo_title') ? 'has-error' : '']">
                                <label for="seo_title"
                                       class="required">{{ __('admin::app.settings.channels.seo-title') }}</label>
                                <input v-validate="'required'" class="control" id="seo_title" name="seo_title"
                                       data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-title') }}&quot;"
                                       value="{{ $seo->meta_title ?? old('seo_title') }}"/>
                                <span class="control-error" v-if="errors.has('seo_title')">@{{ errors.first('seo_title') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('seo_description') ? 'has-error' : '']">
                                <label for="seo_description"
                                       class="required">{{ __('admin::app.settings.channels.seo-description') }}</label>

                                <textarea v-validate="'required'" class="control" id="seo_description"
                                          name="seo_description"
                                          data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-description') }}&quot;">{{ $seo->meta_description ?? old('seo_description') }}</textarea>

                                <span class="control-error" v-if="errors.has('seo_description')">@{{ errors.first('seo_description') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('seo_keywords') ? 'has-error' : '']">
                                <label for="seo_keywords"
                                       class="required">{{ __('admin::app.settings.channels.seo-keywords') }}</label>

                                <textarea v-validate="'required'" class="control" id="seo_keywords" name="seo_keywords"
                                          data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-keywords') }}&quot;">{{ $seo->meta_keywords ?? old('seo_keywords') }}</textarea>

                                <span class="control-error" v-if="errors.has('seo_keywords')">@{{ errors.first('seo_keywords') }}</span>
                            </div>
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.settings.channel.edit.after') !!}
                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {

            $('.js-example-basic-multiple').select2();

            tinymce.init({
                selector: 'textarea#home_page_content,textarea#footer_content',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true,
                valid_elements: '*[*]'
            });
            fetch_footer_icons();

            function fetch_footer_icons() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/channels/get-footer-icons",
                    method: "POST",
                    dataType: "json",
                    data: {channel_id: $('#channel_id').val()},
                    success: function (data) {
                        console.log(data);
                        if (data.length > 0) {
                            $("#data_content").html(' ');
                            for (i = 0; i < data.length; i++) {
                                $("#data_content").append('              <div  class="add-icon fix_height" class="padding_10" id="add-icon-' + data[i]["id"] + '">\n' +
                                    '                                        <div id="image-group-' + data[i]["id"] + '" class="control-group upload-icon-container fix_height"><label>icon</label><img id="image-'+data[i]["id"]+'" class="icon-image padding-top-10"  src="{{ asset('') }}/' + data[i]["icon"] + '"><br> <label class="padding-top-10">(40 x 40)</label><input class="upload-icon-image icon_file" type="file" name="icon" data-icon="' + data[i]["id"] + '" ></div>\n' +
                                    '                                        <div id="url-group-' + data[i]["id"] + '" class="control-group footer-icon-url-container fix_height"><label>URL</label><input type="text" name="url" class="control icon_url url_input" data-iconid="' + data[i]["id"] + '" value="' + data[i]["url"] + '"><span class="control-error">The "URL" has a wrong format</span></div>\n' +
                                    '                                        <div class="delete-icon-container fix_height"><span data-iconid="' + data[i]["id"] + '" class="icon trash-icon"></span></div>\n' +
                                    '                                    </div>')
                            }
                        }
                    }
                });
            }

            $(document).on('click', '#add_footer_icon', function (e) {
                var valideForm = true;
                var url = $('#new_icon_url').val();
                if (!validURL(url)) {
                    valideForm = false;
                    $('#new-url-group').addClass('has-error');
                } else {
                    $('#new-url-group').removeClass('has-error');
                }
                var icon = $('#new_icon_file')[0].files[0];
                if (!$('#new_icon_file').val()) {
                    valideForm = false;
                    $("#new-icon-group").addClass('has-error');
                } else {
                    $("#new-icon-group").removeClass('has-error');
                }
                if (valideForm) {
                    var formData = new FormData();
                    formData.append('url', url);
                    formData.append('icon', icon);
                    formData.append('channel_id', $('#channel_id').val());
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "/admin/channels/add-footer-icon",
                        method: "POST",
                        dataType: "json",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            console.log(data);
                            if (data['status'] == 1) {
                                fetch_footer_icons();
                                $('#new_icon_url').val(' ');
                                $('#new_icon_file').val('')
                            }
                        }
                    });
                }


            });
            $(document).on('click', '.trash-icon', function (e) {
                if (confirm("Are you sure you want to delete the icon")) {
                    var icon_id = $(this).data('iconid');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "/admin/channels/delete-footer-icon",
                        method: "POST",
                        dataType: "json",
                        data: {icon_id: icon_id},
                        success: function (data) {
                            if (data['status'] == 1) {
                                $('#add-icon-' + icon_id).remove();
                            }
                        }
                    });

                }
            });
            $(document).on('blur', '.icon_url', function (e) {
                var url = $(this).val();
                var icon_id = $(this).data('iconid');
                if (validURL(url)) {
                    $("#url-group-" + icon_id).removeClass('has-error');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "/admin/channels/update-icon-url",
                        method: "POST",
                        /*dataType: "json",*/
                        data: {url: url, icon_id: icon_id},
                        success: function (data) {
                            console.log(data);
                            /*           if (data['status'] == 1) {

                                       }*/
                        }
                    });
                } else {
                    $("#url-group-" + icon_id).addClass('has-error');
                }
            });

            $(document).on('change', '.icon_file', function (e) {
                var icon_id=$(this).data('icon');
                // change image
                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg"))
                {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#image-'+icon_id).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }

                //upload icon
                var icon = $(this)[0].files[0];
                if ($(this).val()) {
                    var formData = new FormData();
                    formData.append('icon', icon);
                    formData.append('icon_id', $(this).data('icon'));
                    formData.append('channel_id', $('#channel_id').val());
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "/admin/channels/update-footer-icon",
                        method: "POST",
                        /*dataType: "json",*/
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            console.log(data);
                            /*           if (data['status'] == 1) {

                                       }*/
                        }
                    });
                }


            });

            function validURL(str) {
                var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                    '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                    '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                    '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
                return !!pattern.test(str);
            }
        });

    </script>
@endpush