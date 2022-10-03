@component('shop::emails.layouts.master')

    <div style="margin-bottom: 32px; text-align: center;">
        <a href="https://www.2agunshow.com/" style="display: inline-block;">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>
    <div>
        <p style="color: #111111;">{!! __('shop::app.mail.customer.subscription.greeting') !!}</p>
        <p style="color: #111111;">{!! __('shop::app.mail.customer.subscription.summary') !!}</p>
        <a href="{{ route('shop.unsubscribe', $data['token']) }}" style="color: #0C91A6; text-align: center; font-weight: bold; display: block; text-decoration: none;">
        {!! __('shop::app.mail.customer.subscription.unsubscribe') !!}
        </a>
    </div>

@endcomponent