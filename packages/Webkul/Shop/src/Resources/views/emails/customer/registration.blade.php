@component('shop::emails.layouts.master')

<div style="margin-bottom: 32px; text-align: center;">
    <a href="https://www.2agunshow.com/" style="display: inline-block;">
        @include ('shop::emails.layouts.logo')
    </a>
</div>
<div style="text-align:center;">
    <p style="color: #111111;">{{ __('shop::app.mail.customer.registration.dear', ['customer_name' => $data['first_name']. ' ' .$data['last_name']]) }},</p>
    <p style="color: #111111;">{!! __('shop::app.mail.customer.registration.greeting') !!}</p>
    <p style="color: #111111;">{{ __('shop::app.mail.customer.registration.summary') }}</p>
    <p style="color: #111111;">{{ __('shop::app.mail.customer.registration.thanks') }}</p>
    <div style="text-align: center; margin-top: 30px;">
            <a href="https://www.2agunshow.com/customer/login" style="display: inline-block; padding: 10px 20px; border: 2px solid #FFC107; background-color: white; border-radius: 4px; color: #111111; font-weight: 700; text-decoration: none; margin: 5px;">{{ __('shop::app.mail.customer.registration.login') }}</a>
            <a href="https://www.2agunshow.com/buyall" style="display: inline-block; padding: 10px 20px; border: 2px solid #FFC107; background-color: #FFC107; border-radius: 4px; color: #111111; font-weight: 700; text-decoration: none; margin: 5px;">{{ __('shop::app.mail.customer.registration.buynow') }}</a>
        </div>
</div>

@endcomponent