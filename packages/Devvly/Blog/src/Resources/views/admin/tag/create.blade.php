@extends('admin::layouts.content')

@section('page_title')
    {{ __('blog::app.tag.add-tag') }}
@stop
@section('content')
    <div class="content">

        <form method="POST" id="page-form" action="{{ route('blog.admin.tag.store') }}" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h3>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('blog::app.tag.add-tag') }}
                    </h3>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('blog::app.tag.save-tag') }}
                    </button>
                </div>
            </div>
            <div class="page-content">

                <div class="form-container">
                    @csrf()
                    <accordian :title="'{{ __('blog::app.general.general') }}'" :active="true">
                        <div slot="body">
                            @if($errors->any())
                                <div class="control-group">
                                    <p>{{$errors->first()}}</p>
                                </div>
                            @endif
                            <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('blog::app.tag.name') }}</label>
                                <input type="text" class="control"
                                       name="name"
                                       id="name"
                                       value="{{ old('name') }}"
                                       v-validate="'required'"
                                       data-vv-as="&quot;{{ __('blog::app.tag.name') }}&quot;">

                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endpush