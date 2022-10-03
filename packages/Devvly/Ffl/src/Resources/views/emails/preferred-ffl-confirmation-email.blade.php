@component('shop::emails.layouts.master')

    <div style="margin-bottom: 32px; text-align: center;">
        <a href="https://www.2agunshow.com/" style="display: inline-block;">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>

    <div style=" text-align: center;">
        <p style="font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #111111;">{{ __('shop::app.mail.ffl.congratulations') }}</p>
        <p style="color: #111111; font-weight: 700;">
        {{ __('shop::app.mail.ffl.approved') }}<br>{{ __('shop::app.mail.ffl.business_priority') }}
        </p>
    </div>

    <div
        style="margin-top: 40px; border-top: 1px solid #ccc; padding-top: 40px;">
        <p style="font-weight: 700; font-size: 24px; margin-bottom: 32px; color: #111111;">{{ __('shop::app.mail.ffl.whats_next') }}</p>
        <p style="color: #111111;">{{ __('shop::app.mail.ffl.start_setting_up') }}</p>
        <ul>
            <li>
                <p>{{ __('shop::app.mail.ffl.sell_and_promote') }}</p>
            </li>
            <li>
                <p>{{ __('shop::app.mail.ffl.manage_trainings') }}</p>
            </li>
            <li>
                <p>{{ __('shop::app.mail.ffl.host_an_event') }}</p>
            </li>
        </ul>

        <p style="color: #111111;">
        {{ __('shop::app.mail.ffl.help') }}<a style="color:#0C91A6; font-weight: 700;" href="mailto:support@2agunshow.com">support@2agunshow.com</a>
        </p>
    </div>

@endcomponent