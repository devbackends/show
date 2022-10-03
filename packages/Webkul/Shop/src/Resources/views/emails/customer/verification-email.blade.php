@component('shop::emails.layouts.master')

    <div style="margin-bottom: 32px; text-align: center;">
        <a href="https://www.2agunshow.com/" style="display: inline-block;">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>
    <div>
        <p style="color: #111111;">{!! __('shop::app.mail.customer.verification.heading') !!}</p>
        <p style="color: #111111;">{!! __('shop::app.mail.customer.verification.hello') !!}</p>
        <p style="color: #111111;">{!! __('shop::app.mail.customer.verification.summary') !!}</p>
        <a href="{{ route('customer.verify', $data['token']) }}" style="color: #0C91A6; text-align: center; font-weight: bold; display: block; text-decoration: none;">
                {!! __('shop::app.mail.customer.verification.verify') !!}
            </a>
    </div>

@endcomponent