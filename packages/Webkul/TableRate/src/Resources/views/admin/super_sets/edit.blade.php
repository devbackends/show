@extends('admin::layouts.content')

@section('page_title')
    {{ __('tablerate::app.admin.supersets.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.tablerate.supersets.update', $superset->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            @csrf
            <input name="_method" type="hidden" value="PUT">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/tablerate/supersets') }}';"></i>

                        {{ __('tablerate::app.admin.supersets.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('tablerate::app.admin.supersets.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    <accordian :title="'{{ __('tablerate::app.admin.supersets.general') }}'" :active="true">
                        <div slot="body">
                            @csrf()

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="text" class="required">{{ __('tablerate::app.admin.supersets.code') }}<i class="export-icon"></i> </label>
                                <input type="text" v-validate="'required'" class="control" name="code" value="{{ old('code') ?: $superset->code }}" data-vv-as="&quot;{{ __('tablerate::app.admin.supersets.code') }}&quot;">
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="text" class="required">{{ __('tablerate::app.admin.supersets.name') }}<i class="export-icon"></i> </label>
                                <input type="text" v-validate="'required'" class="control" name="name" value="{{ old('name') ?: $superset->name }}" data-vv-as="&quot;{{ __('tablerate::app.admin.supersets.name') }}&quot;">
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="status">{{ __('tablerate::app.admin.supersets.status') }}</label>
                                <span class="checkbox">
                                    <input type="checkbox" id="status" name="status" value="1" {{ $superset->status == 1 ? 'checked' : '' }}>
                                    <label class="checkbox-view" for="status"></label>
                                    {{ __('tablerate::app.admin.supersets.superset-is-active') }}
                                </span>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop