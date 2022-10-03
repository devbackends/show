@extends('saas::super.layouts.content')

@section('page_title')
    Add Coupon
@endsection

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link"></i>
                    Add Coupon
                </h1>
            </div>
        </div>

        <div class="page-content">
            <form method="POST" action="{{ route('super.coupon.insert') }}">
                @csrf

                <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                    <label for="name" class="required">Name</label>
                    <input type="text" class="control" placeholder="name" name="name" data-vv-as="&quot; Name &quot;"
                           required>
                    <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                    <label for="description" class="required">Description</label>
                    <textarea class="control" placeholder="Description" name="description"
                              data-vv-as="&quot; Description &quot;" required></textarea>
                    <span class="control-error"
                          v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                </div>

                <div id="coupon_code_container" class="control-group"
                     :class="[errors.has('coupon_code') ? 'has-error' : '']">
                    <label for="coupon_code" class="required">Coupon Code</label>
                    <input type="text" class="control" id="coupon_code" pattern="[0-9]{4}" placeholder="ex:1111"
                           name="coupon_code"
                           data-vv-as="&quot; Coupon Code &quot;" required>
                    <span class="control-error"
                          v-if="errors.has('coupon_code')">@{{ errors.first('coupon_code') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('starts_from') ? 'has-error' : '']">
                    <label for="starts_from" class="required">Start Date</label>
                    <input type="datetime-local" class="control" placeholder="Start Date" name="starts_from"
                           data-vv-as="&quot; Start Date &quot;">
                    <span class="control-error"
                          v-if="errors.has('starts_from')">@{{ errors.first('starts_from') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('ends_till') ? 'has-error' : '']">
                    <label for="ends_till" class="required">End Date</label>
                    <input type="datetime-local" class="control" placeholder="Start Date" name="ends_till"
                           data-vv-as="&quot; End Date &quot;">
                    <span class="control-error" v-if="errors.has('ends_till')">@{{ errors.first('ends_till') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('coupon_type') ? 'has-error' : '']">
                    <label for="coupon_type" class="required">type</label>
                    <select class="control" name="coupon_type" v-validate="'required'"
                            data-vv-as="&quot; Coupon Type &quot;">
                        <option value="">Type</option>
                        @foreach($couponType as $couponType)
                            <option value="{{$couponType->type}}">{{$couponType->type}}</option>
                        @endforeach
                    </select>
                    <span class="control-error"
                          v-if="errors.has('coupon_type')">@{{ errors.first('coupon_type') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('usage_per_customer') ? 'has-error' : '']">
                    <label for="usage_per_customer" class="required">Usage/Company</label>
                    <input type="number" min="1" class="control" placeholder="Usage/Company Code"
                           name="usage_per_customer" data-vv-as="&quot; Usage/Company &quot;" required>
                    <span class="control-error" v-if="errors.has('usage_per_customer')">@{{ errors.first('usage_per_customer') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('uses_per_coupon') ? 'has-error' : '']">
                    <label for="uses_per_coupon" class="required">Usage/Coupon</label>
                    <input type="number" min="1" class="control" placeholder="Usage/Coupon Code" name="uses_per_coupon"
                           data-vv-as="&quot; Usage/Coupon &quot;" required>
                    <span class="control-error" v-if="errors.has('uses_per_coupon')">@{{ errors.first('uses_per_coupon') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('action_type') ? 'has-error' : '']">
                    <label for="action_type" class="required">Action</label>
                    <select class="control" name="action_type" v-validate="'required'">
                        <option value="">Action</option>
                        <option value="percentage">percentage</option>
                        <option value="amount">amount</option>
                    </select>
                    <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('discount_amount') ? 'has-error' : '']">
                    <label for="discount_amount" class="required">Discount % / Amount (usd)</label>
                    <input type="number" min="0" class="control" placeholder="Discount/Amount" name="discount_amount"
                           data-vv-as="&quot; Discount/Amount &quot;" required>
                    <span class="control-error" v-if="errors.has('discount_amount')">@{{ errors.first('uses_per_coupon') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                    <label for="status" class="required">status</label>

                    <select class="control" name="status" v-validate="'required'">
                        <option value="0">{{ __('saas::app.super-user.tenants.deactivate') }}</option>
                        <option value="1">{{ __('saas::app.super-user.tenants.activate') }}</option>
                    </select>

                    <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>

                </div>

                <button id="Coupon_button" class="btn btn-primary">
                    Save
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                var coupon_code_found=0;
                $(document).on('blur', '#coupon_code', function () {
                    var coupon_code = $("#coupon_code").val();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "check-coupon-code",
                        method: "POST",
                        dataType: "json",
                        data: {coupon_code: coupon_code},
                        success: function (data) {
                            if (data['status'] == 1) {
                                $("#coupon_code_container").addClass('has-error');
                                $("#coupon_code_container").append('<span id="coupon_code-control-error" class="control-error">The " Coupon Code " already found .</span>')
                                coupon_code_found=1;
                            } else {
                                $("#coupon_code_container").removeClass('has-error');
                                $('#coupon_code-control-error').remove();
                                coupon_code_found=0;
                            }
                        }
                    });
                });
                $(document).on('click','#Coupon_button',function(e){
                    if(coupon_code_found==1){e.preventDefault();}
                });
            });

        </script>
    @endpush

@endsection