@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cms.pages.edit-title') }}
@stop

@section('content')
    <div class="content">
        <?php $locale = request()->get('locale') ?: app()->getLocale(); ?>

        <form method="POST" id="page-form" action="" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h3>
                        <i class="icon angle-left-icon back-link"
                           onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.cms.pages.edit-title') }}
                    </h3>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option
                                    value="{{ route('admin.cms.edit', $page->id) . '?locale=' . $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    @if ($page->translate($locale))
                        <a href="{{ route('shop.cms.page', $page->translate($locale)['url_key']) }}"
                           class="btn btn-primary" target="_blank">
                            {{ __('admin::app.cms.pages.preview') }}
                        </a>
                    @endif

                    <button type="submit" id="save_page_btn" class="btn btn-primary">
                        {{ __('admin::app.cms.pages.edit-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()
                    <accordian :title="'{{ __('admin::app.cms.pages.general') }}'" :active="true">
                        <div slot="body">
                            <div class="form-row">
                                <div class="control-group col-md-6"
                                     :class="[errors.has('{{$locale}}[page_title]') ? 'has-error' : '']">
                                    <label for="page_title"
                                           class="required">{{ __('admin::app.cms.pages.page-title') }}</label>

                                    <input type="text" class="control" name="{{$locale}}[page_title]"
                                           v-validate="'required'"
                                           value="{{ old($locale)['page_title'] ?? ($page->translate($locale)['page_title'] ?? '') }}"
                                           data-vv-as="&quot;{{ __('admin::app.cms.pages.page-title') }}&quot;">

                                    <span class="control-error" v-if="errors.has('{{$locale}}[page_title]')">@{{ errors.first('{!!$locale!!}[page_title]') }}</span>
                                </div>

                                <div class="control-group col-md-6 published-field" :class="[errors.has('published') ? 'has-error' : '']">
                                    <label for="published">{{ __('admin::app.cms.pages.published') }}</label>
                                    <label class="switch">
                                        @php
                                            $published = old('published')? (int) old('published'): $page->published;
                                        @endphp
                                        <input type="checkbox" class="control" id="published" name="published" value="{{$published}}" {{ $published? "checked": "" }}>
                                        <span class="slider round"></span>
                                        <span class="control-error" v-if="errors.has('published')">@{{ errors.first('published') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group col-md-12 login-field" :class="[errors.has('login') ? 'has-error' : '']">
                                <label for="login">Login Required</label>
                                <label class="switch">
                                    @php
                                        $login = old('login')? (int) old('login'): $page->is_login_required;
                                    @endphp
                                    <input type="checkbox" class="control" id="login" name="is_login_required" value="{{$login}}" {{ $login? "checked": "" }}>
                                    <span class="slider round"></span>
                                    <span class="control-error" v-if="errors.has('login')">@{{ errors.first('login') }}</span>
                                </label>
                            </div>
                            <div class="control-group" :class="[errors.has('channels[]') ? 'has-error' : '']">
                                <label for="url-key" class="required">{{ __('admin::app.cms.pages.channel') }}</label>

                                <?php $selectedOptionIds = old('inventory_sources') ?: $page->channels->pluck('id')->toArray() ?>

                                <select type="text" class="control js-example-basic-multiple" name="channels[]"
                                        v-validate="'required'" value="{{ old('channel[]') }}"
                                        data-vv-as="&quot;{{ __('admin::app.cms.pages.channel') }}&quot;"
                                        multiple="multiple">
                                    @php $i=1; @endphp
                                    @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                        <option @if($i==1) selected="selected" @endif
                                            value="{{ $channel->id }}" {{ in_array($channel->id, $selectedOptionIds) ? 'selected' : '' }}>
                                            {{ $channel->name }}
                                        </option>
                                        @php $i+=1; @endphp
                                    @endforeach
                                </select>

                                <span class="control-error" v-if="errors.has('channels[]')">@{{ errors.first('channels[]') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.cms.pages.seo') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label for="meta_title">{{ __('admin::app.cms.pages.meta_title') }}</label>

                                <input type="text" class="control" name="{{$locale}}[meta_title]"
                                       value="{{ old($locale)['meta_title'] ?? ($page->translate($locale)['meta_title'] ?? '') }}">
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[url_key]') ? 'has-error' : '']">
                                <label for="url-key" class="required">{{ __('admin::app.cms.pages.url-key') }}</label>

                                <input type="text" class="control" name="{{$locale}}[url_key]" v-validate="'required'"
                                       value="{{ old($locale)['url_key'] ?? ($page->translate($locale)['url_key'] ?? '') }}"
                                       data-vv-as="&quot;{{ __('admin::app.cms.pages.url-key') }}&quot;">
                                <input type="hidden" class="control" name="{{$locale}}[old_url_key]"
                                       value="{{ old($locale)['url_key'] ?? ($page->translate($locale)['url_key'] ?? '') }}"/>
                                <span class="control-error" v-if="errors.has('{{$locale}}[url_key]')">@{{ errors.first('{!!$locale!!}[url_key]') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="meta_keywords">{{ __('admin::app.cms.pages.meta_keywords') }}</label>

                                <textarea type="text" class="control"
                                          name="{{$locale}}[meta_keywords]">{{ old($locale)['meta_keywords'] ?? ($page->translate($locale)['meta_keywords'] ?? '') }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="meta_description">{{ __('admin::app.cms.pages.meta_description') }}</label>

                                <textarea type="text" class="control"
                                          name="{{$locale}}[meta_description]">{{ old($locale)['meta_description'] ?? ($page->translate($locale)['meta_description'] ?? '') }}</textarea>

                            </div>
                        </div>
                    </accordian>
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
                selector: 'textarea#content',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true,
                valid_elements : '*[*]'
            });
        });
    </script>
@endpush