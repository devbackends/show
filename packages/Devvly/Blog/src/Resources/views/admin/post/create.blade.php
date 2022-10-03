@extends('admin::layouts.content')

@section('page_title')
    {{ __('blog::app.post.add-post') }}
@stop
@section('content')
    <div class="content">

        <form method="POST" id="page-form" action="{{ route('blog.admin.post.store') }}" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h3>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('blog::app.post.add-post') }}
                    </h3>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('blog::app.post.next') }}
                    </button>
                </div>
            </div>
            <div class="page-content">

                <div class="form-container">
                    @csrf()
                    <accordian :title="'{{ __('blog::app.general.general') }}'" :active="true">
                        <div slot="body">
                            <div class="form-row">
                                <div class="control-group col-md-6" :class="[errors.has('title') ? 'has-error' : '']">
                                    <label for="title" class="required">{{ __('blog::app.post.title') }}</label>
                                    <input type="text" class="control"
                                           name="title"
                                           value="{{ old('title') }}"
                                           v-validate="'required'"
                                           data-vv-as="&quot;{{ __('blog::app.post.title') }}&quot;">

                                    <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                                </div>
                                <div class="control-group col-md-6 published-field">
                                    <label for="published">{{ __('blog::app.general.published') }}</label>

                                    <label class="switch">
                                        @php
                                            $published = old('published')? (int) old('published'): 0;
                                        @endphp
                                        <input type="checkbox" id="published" name="published" value="{{ $published }}" {{ $published? "checked": "" }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group" :class="[errors.has('uploaded_image') ? 'has-error' : '']">
                                <label for="uploaded_image">{{ __('blog::app.post.thumbnail-image') }}</label>
                                <image-wrapper input-name="uploaded_image" :multiple="false"></image-wrapper>
                                <span class="control-error" v-if="errors.has('image')">@{{ errors.first('image') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('post_category_id') ? 'has-error' : '']">
                                @php
                                    $tr_cat_id = old('post_category_id');
                                @endphp
                                <label for="post_category_id">{{ __('blog::app.post_category.category') }}</label>
                                <select id="post_category_id" name="post_category_id" class="control" value="{{ $tr_cat_id  }}" data-vv-as="&quot;{{ __('blog::app.post_category.category') }}&quot;">
                                    <option {{ ! $tr_cat_id? 'selected' : '' }}></option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{ $tr_cat_id == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('post_category_id')">@{{ errors.first('post_category_id') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('tags') ? 'has-error' : '']">
                                <label for="tags">{{ __('blog::app.tag.tags') }}</label>

                                @php $selectedTags = old('tags[]')?? []; @endphp

                                <select id="tags" type="text" class="control js-example-basic-multiple"
                                        name="tags[]" value="{{ old('tags[]') }}"
                                        data-vv-as="&quot;{{ __('blog::app.tag.tags') }}&quot;"
                                        multiple="multiple">
                                    @foreach($tags as $tag)
                                        <option
                                            value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <span class="control-error" v-if="errors.has('tags')">@{{ errors.first('tags') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('created_at') ? 'has-error' : '']">
                                @php
                                    $created_at = old('created_at')? old('created_at'): \Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
                                @endphp
                                <label for="created_at">{{ __('blog::app.post.created-at') }}</label>
                                <input type="date" id="created_at" class="control" name="created_at"
                                       value="{{ $created_at }}"
                                       data-vv-as="&quot;{{ __('blog::app.posts.created-at') }}&quot;">
                                <span class="control-error" v-if="errors.has('created_at')">@{{ errors.first('created_at') }}</span>
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

                    <accordian :title="'{{ __('blog::app.general.seo') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('url_key') ? 'has-error' : '']">
                                <label for="url-key" class="required">{{ __('blog::app.general.url-key') }}</label>

                                <input type="text" class="control" name="url_key" v-validate="'required'"
                                       value="{{ old('url_key') }}"
                                       data-vv-as="&quot;{{ __('blog::app.general.url-key') }}&quot;">
                                <span class="control-error" v-if="errors.has('url_key')">@{{ errors.first('url_key') }}</span>
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