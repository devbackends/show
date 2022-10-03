@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cms.pages.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cms.store') }}" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h3>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.cms.pages.add-title') }}
                    </h3>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('admin::app.cms.pages.create-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    {!! view_render_event('bagisto.admin.cms.pages.create_form_accordian.general.before') !!}

                    <accordian :title="'{{ __('admin::app.cms.pages.general') }}'" :active="true">
                        <div slot="body">
                            <div class="form-row">
                                <div class="control-group col-md-6" :class="[errors.has('page_title') ? 'has-error' : '']">
                                    <label for="page_title" class="required">{{ __('admin::app.cms.pages.page-title') }}</label>

                                    <input type="text" class="control" name="page_title" v-validate="'required'" value="{{ old('page_title') }}" data-vv-as="&quot;{{ __('admin::app.cms.pages.page-title') }}&quot;">

                                    <span class="control-error" v-if="errors.has('page_title')">@{{ errors.first('page_title') }}</span>
                                </div>
                                <div class="control-group col-md-6 published-field" :class="[errors.has('published') ? 'has-error' : '']">
                                    <label for="published">{{ __('admin::app.cms.pages.published') }}</label>
                                    <label class="switch">
                                        @php
                                            $published = old('published')? (int) old('published'): 0;
                                        @endphp
                                        <input type="checkbox" class="control" id="published" name="published" value="{{$published}}" {{ $published? "checked": "" }}>
                                        <span class="slider round"></span>
                                        <span class="control-error" v-if="errors.has('published')">@{{ errors.first('published') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group" :class="[errors.has('channels[]') ? 'has-error' : '']">
                                <label for="channels" class="required">{{ __('blog::app.general.channel') }}</label>

                                <?php $selectedOptionIds = old('inventory_sources')?? [] ?>

                                <select id="channels" type="text" class="control js-example-basic-multiple" name="channels[]"
                                        v-validate="'required'" value="{{ old('channel[]') }}"
                                        data-vv-as="&quot;{{ __('blog::app.general.channel') }}&quot;"
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

                    {!! view_render_event('bagisto.admin.cms.pages.create_form_accordian.general.after') !!}


                    {!! view_render_event('bagisto.admin.cms.pages.create_form_accordian.seo.before') !!}

                    <accordian :title="'{{ __('admin::app.cms.pages.seo') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label for="meta_title">{{ __('admin::app.cms.pages.meta_title') }}</label>

                                <input type="text" class="control" name="meta_title" value="{{ old('meta_title') }}">
                            </div>

                            <div class="control-group" :class="[errors.has('url_key') ? 'has-error' : '']">
                                <label for="url-key" class="required">{{ __('admin::app.cms.pages.url-key') }}</label>

                                <input type="text" class="control" name="url_key" v-validate="'required'" value="{{ old('url_key') }}" data-vv-as="&quot;{{ __('admin::app.cms.pages.url-key') }}&quot;" v-slugify>

                                <span class="control-error" v-if="errors.has('url_key')">@{{ errors.first('url_key') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="meta_keywords">{{ __('admin::app.cms.pages.meta_keywords') }}</label>

                                <textarea type="text" class="control" name="meta_keywords">{{ old('meta_keywords') }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="meta_description">{{ __('admin::app.cms.pages.meta_description') }}</label>

                                <textarea type="text" class="control" name="meta_description">{{ old('meta_description') }}</textarea>

                            </div>
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.cms.pages.create_form_accordian.seo.after') !!}
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
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true,
                valid_elements : '*[*]'
            });
        });
    </script>
@endpush