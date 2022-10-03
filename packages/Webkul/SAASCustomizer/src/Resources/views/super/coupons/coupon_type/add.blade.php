@extends('saas::super.layouts.content')

@section('page_title')
    Coupon Type
@endsection

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link"></i>
                    Coupon Type
                </h1>
            </div>
        </div>

        <div class="page-content">
            <form method="POST" action="{{ route('super.coupons-type.insert') }}">
                @csrf
                <div class="control-group" :class="[errors.has('type') ? 'has-error' : '']">
                    <label for="type" class="required">Type</label>
                    <input type="text" v-validate="'required'" class="control" name="type" placeholder="Type"
                           data-vv-as="&quot;Coupn Type&quot;">
                    <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                </div>
                <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                    <label for="code" class="required">Description</label>
                    <textarea v-validate="'required'" class="control" name="description" placeholder="Description"
                              data-vv-as="&quot;Description&quot;"></textarea>
                    <span class="control-error"
                          v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                </div>


                <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                    <label for="status" class="required">status</label>

                    <select class="control" name="status" v-validate="'required'">
                        <option value="0">{{ __('saas::app.super-user.tenants.deactivate') }}</option>
                        <option value="1" selected="selected">{{ __('saas::app.super-user.tenants.activate') }}</option>
                    </select>

                    <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>

                </div>

                <button class="btn btn-primary">
                    Add
                </button>
            </form>
        </div>
    </div>

@endsection