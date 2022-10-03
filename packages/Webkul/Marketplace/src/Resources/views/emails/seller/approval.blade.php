@component('shop::emails.layouts.master')

    <div style="padding: 45px 20px; border: 5px solid #DDDDDD; max-width: 640px; margin: 15px auto; font-family: 'Montserrat' ">
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}" style="display: inline-block;">
                <img src="{{ bagisto_asset('images/logo.png') }}">
            </a>
        </div>
        <div>
            <p style="font-weight: bold; font-size: 20px; color: #111111;  text-align:center;">{{ __('marketplace::app.mail.seller.approval.dear', ['name' => $seller->customer->name]) }},</p>
            <p style="font-size: 16px; color: #5E5E5E; text-align: center;">{{ __('marketplace::app.mail.seller.approval.info') }}</p>
            <div style="text-align: center; margin-top: 30px; margin-bottom: 30px;">
                <a href="{{ route('customer.session.index') }}" style="padding: 10px 20px; border: 2px solid #FFC107; background-color: white; border-radius: 4px; color: #111111; font-weight: bold; text-decoration: none; display: inline-block;">{{ __('marketplace::app.mail.seller.approval.login') }}</a>
            </div>
            <p style="font-size: 16px; color: #5E5E5E; text-align: center; display: block; text-decoration: none;">{!!
                    __('shop::app.mail.order.help', [
                        'support_email' => '<a style="color:#0C91A6" href="mailto:' . config('mail.from.address') . '">' . config('mail.from.address'). '</a>'
                        ])
                !!}</p>
            <p style="font-size: 16px; color: #5E5E5E; text-align: center;">{{ __('shop::app.mail.order.thanks') }}</p>
        </div>
    </div>

@endcomponent