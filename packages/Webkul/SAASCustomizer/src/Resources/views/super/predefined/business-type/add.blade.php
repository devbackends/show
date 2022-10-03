@extends('saas::super.layouts.content')

@section('page_title')
    Add Business Type
@endsection

@section('content')

            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link" ></i>
                            Add Business Type
                        </h1>
                    </div>
                </div>

                <div class="page-content">
                    <form method="POST" action="{{ route('super.predefined.business-type.insert') }}">
                        @csrf
                        <div class="control-group" :class="[errors.has('business_type') ? 'has-error' : '']">
                            <label for="code" class="required">Business Type</label>
                            <input type="text" v-validate="'required'" class="control" name="business_type"  placeholder="Business Type" data-vv-as="&quot; Business Type &quot;">
                            <span class="control-error" v-if="errors.has('business_type')">@{{ errors.first('business_type') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                            <label for="status" class="required">status</label>

                            <select class="control"  name="status"  v-validate="'required'" >
                                <option value="0"  >{{ __('saas::app.super-user.tenants.deactivate') }}</option>
                                <option value="1" selected="selected" >{{ __('saas::app.super-user.tenants.activate') }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>

                        </div>

                        <button  class="btn btn-primary">
                            Add
                        </button>
                    </form>
                </div>
            </div>

@endsection