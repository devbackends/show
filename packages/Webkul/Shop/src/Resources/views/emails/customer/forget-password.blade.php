@component('shop::emails.layouts.master')

    <div style="margin-bottom: 32px; text-align: center;">
        <a href="https://www.2agunshow.com/" style="display: inline-block;">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>
    <div>
        <p style="color: #111111;">{{ __('shop::app.mail.forget-password.dear', ['name' => $user_name]) }},</p>
        <p style="color: #111111;">{{ __('shop::app.mail.forget-password.info') }}</p>
        <a href="{{ route('customer.reset-password.create', $token) }}" style="color: #0C91A6; text-align: center; font-weight: bold; display: block; text-decoration: none;">
            {{ __('shop::app.mail.forget-password.reset-password') }}
        </a>
        <p style="color: #111111;">{{ __('shop::app.mail.forget-password.final-summary') }}</p>
        <p style="color: #111111;">{{ __('shop::app.mail.forget-password.thanks') }}</p>
    </div>

@endcomponent