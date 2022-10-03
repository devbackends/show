@extends('saas::super.layouts.content')

@section('page_title')
    Add MMC
@endsection

@section('content')

            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link" ></i>
                            Add MMC
                        </h1>
                    </div>
                </div>

                <div class="page-content">
                    <form method="POST" action="{{ route('super.predefined.mmc.insert') }}">
                        @csrf
                        <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                            <label for="code" class="required">Code</label>
                            <input type="text" v-validate="'required'" class="control" name="code"  placeholder="code" data-vv-as="&quot;Code&quot;">
                            <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                        </div>
                        <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                            <label for="name" class="required">Name</label>
                            <input type="text"  class="control"  placeholder="name" name="name" data-vv-as="&quot; Name &quot;" >
                            <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
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