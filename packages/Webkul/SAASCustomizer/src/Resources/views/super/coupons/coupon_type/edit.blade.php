@extends('saas::super.layouts.content')

@section('page_title')
    Update Coupon Type
@endsection

@section('content')

    <coupon-type-registration></coupon-type-registration>

    @push('scripts')
        <script type="text/x-template" id="coupon-type-details-form">

            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link"></i>
                            Update Coupon Type
                        </h1>
                    </div>
                </div>
                <div class="page-content">
                    <form method="POST" action="{{ route('super.coupons-type.store',$couponType->id) }}">
                        @csrf
                        <div class="control-group" :class="[errors.has('type') ? 'has-error' : '']">
                            <label for="type" class="required">Type</label>
                            <input type="text" v-model="type" v-validate="'required'" class="control" name="type"
                                   placeholder="Type" data-vv-as="&quot;Coupn Type&quot;">
                            <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                        </div>
                        <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                            <label for="code" class="required">Description</label>
                            <textarea v-model="description" v-validate="'required'" class="control" name="description"
                                      placeholder="Description" data-vv-as="&quot;Description&quot;"></textarea>
                            <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                        </div>


                        <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                            <label for="status" class="required">status</label>

                            <select v-model="status" class="control" name="status" v-validate="'required'">
                                <option value="0">{{ __('saas::app.super-user.tenants.deactivate') }}</option>
                                <option value="1"
                                        selected="selected">{{ __('saas::app.super-user.tenants.activate') }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>

                        </div>

                        <button class="btn btn-primary">
                            Update
                        </button>
                    </form>
                </div>

            </div>
        </script>
        <script>
            Vue.component('coupon-type-registration', {
                template: '#coupon-type-details-form',
                inject: ['$validator'],
                data: () => ({
                type: '{{ $couponType->type }}',
                description: '{{ $couponType->description }}',
                status: '{{ $couponType->status }}'
            }),
                mounted:function () {
                @if ($couponType->status)
                    this.status = 1;
                @else
                    this.status = 0;
                @endif
            }
            });
        </script>
    @endpush
@endsection