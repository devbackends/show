<div class="header" id="header">
    <div class="header-top">
        <div class="left-content">
            <ul class="logo-container">
                <li>
                        <a href="{{ route('shop.home.index') }}">
                        @if ( isset($channel->logo_url))
                            <img src="{{ $channel->logo_url }}" alt="{{ core()->getCurrentChannel()->title }}" style="max-height: 50px;" />
                        @else
                            <span class="icon gun-logo-icon"></span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        <div class="right-content">
            <ul class="right-content-menu">

                {!! view_render_event('bagisto.saas.companies.header.currency-item.before') !!}

                @if (isset($channel->currencies) && $channel->currencies->count() > 1)
                    <li class="currency-switcher">
                        <span class="dropdown-toggle">
                            {{ core()->getCurrentCurrencyCode() }}

                            <i class="icon arrow-down-icon"></i>
                        </span>

                        <ul class="dropdown-list currency">
                            @foreach (core()->getCurrentChannel()->currencies as $currency)
                                <li>
                                    @if (isset($serachQuery))
                                        <a href="?{{ $serachQuery }}&currency={{ $currency->code }}">{{ $currency->code }}</a>
                                    @else
                                        <a href="?currency={{ $currency->code }}">{{ $currency->code }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                {!! view_render_event('bagisto.saas.companies.header.currency-item.after') !!}

                {!! view_render_event('bagisto.saas.companies.header.menu.before') !!}

                <li>
                    <span class="dropdown-toggle">
                        <i class="icon account-icon"></i>
                        <span class="name nav-top-right-span">{{ __('saas::app.tenant.layouts.nav-top.menu.account') }}</span>
                        <i class="icon arrow-down-icon"></i>
                    </span>

                    @guest('admin')
                        <ul class="dropdown-list account customer">
                            <li>
 {{--                               <div>
                                    <label style="color: #9e9e9e; font-weight: 700; text-transform: uppercase; font-size: 15px;">
                                        {{ __('saas::app.tenant.layouts.nav-top.menu.account') }}
                                    </label>
                                </div>--}}
                            </li>
                        </ul>
                    @endguest
                </li>

                {!! view_render_event('bagisto.saas.companies.header.menu.after') !!}
            </ui>
        </div>
    </div>
</div>
