@auth('customer')
    @php
        $seller = app('Webkul\Marketplace\Repositories\SellerRepository')->isSeller(auth()->guard('customer')->user()->id);
        $count_unread_messages=app('Webkul\Marketplace\Repositories\MessageRepository')->getUnreadMessages()['unread'];


    @endphp
    <div class="dropdown float-right">
        <div id="account">
            <div class="welcome-content dropdown-toggle" id="profileMenuButton" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">
                <i class="fal fa-user-circle messaging__nav-notification">
                @if($count_unread_messages > 0) <span class="messaging__nav-notification-dot"></span> @endif
                </i>
                <span class="title">{{ __('velocity::app.header.welcome-message', ['customer_name' => auth()->guard('customer')->user()->first_name]) }}!</span>
                <span class="select-icon fal fa-angle-down"></span>
            </div>
            <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="profileMenuButton">
                <a href="{{ route('customer.profile.index') }}"
                   class="dropdown-item">{{ __('shop::app.header.profile') }}</a>

                <a href="{{ route('customer.orders.index') }}"
                   class="dropdown-item">{{ __('shop::app.header.order') }}</a>
                <a href="{{ route('customer.wishlist.index') }}"
                   class="dropdown-item">{{ __('shop::app.header.wishlist') }}</a>
                <a href="{{ route('marketplace.account.messages.index') }}"
                   class="dropdown-item">  Messages @if($count_unread_messages > 0) <span class="messaging__dropdown-notification"><span class="messaging__dropdown-notification-dot">{!! $count_unread_messages !!}</span></span> @endif</a>
                {{-- TODO: cleanup  --}}
                @php
                    if($seller){
                @endphp

                <a href="{{ route('marketplace.account.dashboard.index') }}" class="dropdown-item">Selling</a>
                @php
                    }
                @endphp

                <div class="dropdown-divider"></div>
                <a href="{{ route('customer.session.destroy') }}"
                   class="dropdown-item">{{ __('shop::app.header.logout') }}</a>
            </div>
        </div>
    </div>
@endauth
@guest('customer')
    <div class="action float-right">
        <a href="{{ route('customer.register.index') }}" class="btn btn-link">{{ __('shop::app.header.sign-up') }}</a>
        <a href="{{ route('customer.session.index') }}" class="btn btn-primary">{{ __('shop::app.header.sign-in') }}</a>
    </div>
@endguest

