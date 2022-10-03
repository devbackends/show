@extends('saas::super.layouts.content')

@section('page_title')
   Update Coupon
@endsection

@section('content')

    <coupon-registration></coupon-registration>

    @push('scripts')
        <script type="text/x-template" id ="coupon-details-form">

            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link" ></i>
                            Update Coupon
                        </h1>
                    </div>
                </div>

                <div class="page-content">
                    <form method="POST" action="{{ route('super.coupon.store', $coupon->id) }}">
                        @csrf

                        <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                            <label for="name" class="required">Name</label>
                            <input type="text" v-model="name"  class="control"  placeholder="name" name="name" data-vv-as="&quot; Name &quot;"  required>
                            <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                            <label for="description" class="required">Description</label>
                            <textarea  v-model="description"  class="control"  placeholder="Description" name="description" data-vv-as="&quot; Description &quot;"  required></textarea>
                            <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                        </div>

                        <div id="coupon_code_container" class="control-group" :class="[errors.has('coupon_code') ? 'has-error' : '']">
                            <label for="coupon_code" class="required">Coupon Code</label>
                            <input type="text" v-model="coupon_code"  class="control"  placeholder="Coupon Code" name="coupon_code" data-vv-as="&quot; Coupon Code &quot;"  required>
                            <span class="control-error" v-if="errors.has('coupon_code')">@{{ errors.first('coupon_code') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('starts_from') ? 'has-error' : '']">
                            <label for="starts_from" class="required">Start Date</label>
                            <input type="datetime-local"  v-model="starts_from"  class="control"  placeholder="Start Date" name="starts_from" data-vv-as="&quot; Start Date &quot;"  >
                            <span class="control-error" v-if="errors.has('starts_from')">@{{ errors.first('starts_from') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('ends_till') ? 'has-error' : '']">
                            <label for="ends_till" class="required">End Date</label>
                            <input type="datetime-local"  v-model="ends_till"  class="control"  placeholder="Start Date" name="ends_till" data-vv-as="&quot; End Date &quot;"  >
                            <span class="control-error" v-if="errors.has('ends_till')">@{{ errors.first('ends_till') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('coupon_type') ? 'has-error' : '']">
                            <label for="coupon_type" class="required">type</label>
                            <select class="control" v-model="coupon_type" name="coupon_type"  v-validate="'required'" data-vv-as="&quot; Coupon Type &quot;" >
                                <option value=""  >Type</option>
                                @foreach($couponType as $couponType)
                                    <option value="{{$couponType->type}}"  >{{$couponType->type}}</option>
                                @endforeach
                            </select>
                            <span class="control-error" v-if="errors.has('coupon_type')">@{{ errors.first('coupon_type') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('usage_per_customer') ? 'has-error' : '']">
                            <label for="usage_per_customer" class="required">Usage/Company</label>
                            <input type="number" min="1" v-model="usage_per_customer"  class="control"  placeholder="Usage/Company Code" name="usage_per_customer" data-vv-as="&quot; Usage/Company &quot;"  required>
                            <span class="control-error" v-if="errors.has('usage_per_customer')">@{{ errors.first('usage_per_customer') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('uses_per_coupon') ? 'has-error' : '']">
                            <label for="uses_per_coupon" class="required">Usage/Coupon</label>
                            <input type="number" min="1" v-model="uses_per_coupon"  class="control"  placeholder="Usage/Coupon Code" name="uses_per_coupon" data-vv-as="&quot; Usage/Coupon &quot;"  required>
                            <span class="control-error" v-if="errors.has('uses_per_coupon')">@{{ errors.first('uses_per_coupon') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('action_type') ? 'has-error' : '']">
                            <label for="action_type" class="required">Action</label>
                            <select class="control" v-model="action_type" name="action_type"  v-validate="'required'" >
                                <option value=""  >Action</option>
                                <option value="percentage"  >percentage</option>
                                <option value="amount"  >amount</option>
                            </select>
                            <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('discount_amount') ? 'has-error' : '']">
                            <label for="discount_amount" class="required">Discount % / Amount (usd)</label>
                            <input type="number" min="0"  v-model="discount_amount"  class="control"  placeholder="Discount/Amount" name="discount_amount" data-vv-as="&quot; Discount/Amount &quot;"  required>
                            <span class="control-error" v-if="errors.has('discount_amount')">@{{ errors.first('uses_per_coupon') }}</span>
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
                Vue.component('coupon-registration', {
                    template: '#coupon-details-form',
                    inject: ['$validator'],

                    data: () => ({
                    name: '{{ $coupon->name }}',
                    description: '{{ $coupon->description }}',
                    coupon_code: '{{ $coupon->coupon_code }}',
                    starts_from: '{{ date("Y-m-d\TH:i:s", strtotime($coupon->starts_from )) }}',
                    ends_till: '{{ date("Y-m-d\TH:i:s", strtotime($coupon->ends_till )) }}',
                    status: '{{ $coupon->status }}',
                    coupon_type: '{{ $coupon->coupon_type }}',
                    usage_per_customer: '{{ $coupon->usage_per_customer }}',
                    uses_per_coupon: '{{ $coupon->uses_per_coupon }}',
                    action_type: '{{ $coupon->action_type }}',
                    discount_amount: '{{ $coupon->discount_amount }}'
                }),

                    mounted: function () {
                    @if ($coupon->status)
                        this.status = 1;
                    @else
                        this.status = 0;
                    @endif
                }
                });
            </script>
    @endpush
@endsection