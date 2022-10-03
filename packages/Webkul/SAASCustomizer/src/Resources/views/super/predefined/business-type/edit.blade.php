@extends('saas::super.layouts.content')

@section('page_title')
   Update Business Type
@endsection

@section('content')

    <business-type-registration></business-type-registration>

    @push('scripts')
        <script type="text/x-template" id ="business-type-details-form">

            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link" ></i>
                            Update Business Type
                        </h1>
                    </div>
                </div>

                <div class="page-content">
                    <form method="POST" action="{{ route('super.predefined.business-type.store', $businessType->id) }}">
                        @csrf
                        <div class="control-group" :class="[errors.has('business_type') ? 'has-error' : '']">
                            <label for="code" class="required">Business Type</label>
                            <input type="text" v-model="business_type" v-validate="'required'" class="control" name="business_type"  placeholder="Business Type" data-vv-as="&quot; Business Type &quot;">
                            <span class="control-error" v-if="errors.has('business_type')">@{{ errors.first('business_type') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                            <label for="status" class="required">status</label>

                            <select class="control" v-model="status" name="status"  v-validate="'required'" >
                                <option value="0"  >{{ __('saas::app.super-user.tenants.deactivate') }}</option>
                                <option value="1"  >{{ __('saas::app.super-user.tenants.activate') }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>

                        </div>

                        <button  class="btn btn-primary">
                            {{ __('saas::app.super-user.tenants.btn-update') }}
                        </button>
                    </form>
                </div>
            </div>
            </script>
            <script>
                Vue.component('business-type-registration', {
                    template: '#business-type-details-form',
                    inject: ['$validator'],

                    data: () => ({
                    business_type: '{{ $businessType->business_type }}',
                    status: '{{ $businessType->status }}'
                }),

                    mounted: function () {
                    @if ($businessType->status)
                        this.status = 1;
                    @else
                        this.status = 0;
                    @endif
                }
                });
            </script>
    @endpush
@endsection