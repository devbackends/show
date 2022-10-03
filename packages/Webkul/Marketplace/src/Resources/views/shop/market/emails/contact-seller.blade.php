@component('shop::emails.layouts.master')
    <div style="background-color: #eeeeee; padding: 48px 64px;">
        <div style="background-color: white; padding: 48px;">
            <div style="margin-bottom: 32px; text-align: center;">
                <a href="https://www.2agunshow.com/" style="display: inline-block;">
                    @include ('shop::emails.layouts.logo')
                </a>
            </div>
            <div>
                <div style="font-size: 20px;color: #242424;line-height: 30px;">
                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                        <span style="font-weight: bold;">{{ __('marketplace::app.shop.sellers.mails.contact-seller.customer-name') }}:</span><br>{{ $name }}
                    </p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                    <span style="font-weight: bold;">{{ __('marketplace::app.shop.sellers.mails.contact-seller.customer-email') }}:</span><br>{{ $email }}
                    </p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                    <span style="font-weight: bold;">{{ __('marketplace::app.shop.sellers.mails.contact-seller.subject') }}:</span><br>{{ $subject }}
                    </p>

                    <p style="font-weight: bold; font-size: 16px; color: #5E5E5E;">
                    <span style="font-weight: bold;">{{ __('marketplace::app.shop.sellers.mails.contact-seller.message') }}:</span><br>{{ $query }}
                    </p>

                </div>
            </div>
        </div>
    </div>
@endcomponent