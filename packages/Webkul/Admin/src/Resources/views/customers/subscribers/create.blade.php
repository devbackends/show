@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.subscribers.title-edit') }}
@stop

@section('content')

    <div class="content">
        <form method="POST" action="{{ route('admin.customers.subscribers.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h3>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.customers.subscribers.title-add') }}
                    </h3>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('admin::app.customers.subscribers.add-btn-title') }}
                    </button>
                </div>
            </div>
            <div style="padding:25px;" class="">
                @csrf
                
                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="title">{{ __('admin::app.customers.subscribers.email') }}</label>
                    <input type="text" class="control" name="email" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.customers.subscribers.email') }}&quot;" value="">
                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('is_subscribed') ? 'has-error' : '']">
                    <label for="title">{{ __('admin::app.customers.subscribers.is_subscribed') }}</label>

                    <select class="control" name="is_subscribed" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.customers.subscribers.is_subscribed') }}&quot;">
                        <option value="1" >{{ __('admin::app.common.true') }}</option>
                        <option value="0" >{{ __('admin::app.common.false') }}</option>
                    </select>

                    <span class="control-error" v-if="errors.has('is_subscribed')">@{{ errors.first('is_subscribed') }}</span>
                </div>

            </div>
        </form>
    </div>
@endsection