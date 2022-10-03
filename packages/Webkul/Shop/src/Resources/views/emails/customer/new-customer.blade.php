@component('shop::emails.layouts.master')

<div style="padding: 45px 20px; border: 5px solid #DDDDDD; max-width: 640px; margin: 15px auto;">
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}" style="display: inline-block;">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>
    <div>

        <p style="font-weight: bold; font-size: 20px; color: #111111;  text-align:center;">{{ __('shop::app.mail.customer.new.dear', ['customer_name' => $customer['name']]) }},</p>

        <p style="font-size: 16px; color: #5E5E5E; text-align: center;">{!! __('shop::app.mail.customer.new.summary') !!}</p>

        <p style="font-size: 16px; color: #5E5E5E; text-align: center;">{!! __('shop::app.mail.customer.new.temporary-username') !!} {{ $customer['email'] }}, {!! __('shop::app.mail.customer.new.change') !!} <a href="" style="color: #0C91A6; font-weight: bold;">{!! __('shop::app.mail.customer.new.profile-setup') !!}</a></p>

        <!-- <p style="font-size: 16px; color: #5E5E5E; text-align: center; border: 1px solid #DDDDDD; padding: 20px;">{!! __('shop::app.mail.customer.new.username-email') !!} - {{ $customer['email'] }} <br>{!! __('shop::app.mail.customer.new.password') !!} - {{ $password}}</p> -->

        <!-- <p style="font-size: 16px; color: #5E5E5E; text-align: center;">{{ __('shop::app.mail.customer.new.thanks') }}</p> -->
        <a href="{{ route('customer.verify', $data['token']) }}" style="font-size: 16px; color: #0C91A6; text-align: center; font-weight: bold; display: block; text-decoration: none;">
        {!! __('shop::app.mail.customer.new.latest') !!}
            </a>

    </div>
</div>

@endcomponent