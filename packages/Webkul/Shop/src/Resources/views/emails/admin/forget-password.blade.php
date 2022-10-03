@component('shop::emails.layouts.master')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
</style>

<div style="margin-bottom: 32px; text-align: center;">
    <a href="{{ config('app.url') }}" style="display: inline-block;">
        @if (core()->getConfigData('general.design.admin_logo.logo_image'))
        <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('general.design.admin_logo.logo_image')) }}" alt="{{ config('app.name') }}" style="width: 170px; margin-bottom: 15px;" />
        @else
        {{--<img src="{{ asset('images/store-logo.png') }}" alt="{{ config('app.name') }}"/>--}}
        <span class="icon gun-logo-icon"></span>
        @endif
    </a>
</div>
<div>
    <p style="color: #111111;">{{ __('shop::app.mail.forget-password.dear', ['name' => $user_name]) }},</p>
    <p style="color: #111111;">{{ __('shop::app.mail.forget-password.info') }}</p>
    <a href="{{ route('admin.reset-password.create', $token) }}" style="color: #0C91A6; text-align: center; font-weight: bold; display: block; text-decoration: none;">
        {{ __('shop::app.mail.forget-password.reset-password') }}
    </a>
    <p style="color: #111111;">{{ __('shop::app.mail.forget-password.final-summary') }}</p>
    <p style="color: #111111;">{{ __('shop::app.mail.forget-password.thanks') }}</p>
</div>

@endcomponent