<div class="tabs">
    <ul>
        @if(
            $pathInfo == '/admin/configuration/general/design' or
            $pathInfo == '/admin/configuration/megaheaderfooter/general' or
            request()->route()->getName() == 'company.profile.index' or
            request()->route()->getName() == 'company.address.index' or
            request()->route()->getName() == 'company.address.create' or
            request()->route()->getName() == 'company.address.edit'
            )
            <li @if (request()->route()->getName() == 'company.profile.index') class="active" @endif>
                <a href="{{ route('company.profile.index') }}">
                    Profile
                </a>
            </li>
            <li @if (request()->route()->getName() == 'company.address.index' or
                     request()->route()->getName() == 'company.address.create' or
                     request()->route()->getName() == 'company.address.edit') class="active" @endif>
                <a href="{{ route('company.address.index') }}">
                    Address Details
                </a>
            </li>
            <li @if ($pathInfo == '/admin/configuration/general/design') class="active" @endif>
                <a href="{{ url('/admin/configuration/general/design') }}">
                    Logo
                </a>
            </li>
            <li @if ($pathInfo == '/admin/configuration/megaheaderfooter/general') class="active" @endif>
                <a href="{{ route('admin.configuration.index', 'megaheaderfooter', 'general') }}">
                    Add Custom Code
                </a>
            </li>
        @elseif(
            request()->route()->getName() == 'admin.locales.index' or
            request()->route()->getName() == 'admin.locales.create' or
            request()->route()->getName() == 'admin.locales.edit' or
            request()->route()->getName() == 'admin.currencies.index' or
            request()->route()->getName() == 'admin.currencies.create' or
            request()->route()->getName() == 'admin.currencies.edit' or
            request()->route()->getName() == 'admin.exchange_rates.index' or
            request()->route()->getName() == 'admin.exchange_rates.create' or
            request()->route()->getName() == 'admin.exchange_rates.edit'
            )
            <li @if (request()->route()->getName() == 'admin.locales.index' or
                     request()->route()->getName() == 'admin.locales.create' or
                     request()->route()->getName() == 'admin.locales.edit') class="active" @endif>
                <a href="{{ route('admin.locales.index') }}">
                    Locales
                </a>
            </li>
            <li @if (request()->route()->getName() == 'admin.currencies.index' or
                     request()->route()->getName() == 'admin.currencies.create' or
                     request()->route()->getName() == 'admin.currencies.edit') class="active" @endif>
                <a href="{{ route('admin.currencies.index') }}">
                    Currencies
                </a>
            </li>
            <li @if (request()->route()->getName() == 'admin.exchange_rates.index' or
            request()->route()->getName() == 'admin.exchange_rates.create' or
            request()->route()->getName() == 'admin.exchange_rates.edit') class="active" @endif>
                <a href="{{ route('admin.exchange_rates.index') }}">
                    Exchange Rates
                </a>
            </li>
        @elseif(
                $pathInfo == '/admin/configuration/general/general' or
                $pathInfo == '/admin/configuration/emails/general' or
                $pathInfo == '/admin/configuration/megasmsnotifications/general' or
                request()->route()->getName() == 'pwa.pushnotification.create'
                )
            <li @if ($pathInfo == '/admin/configuration/general/general') class="active" @endif>
                <a href="{{ route('admin.configuration.index', 'general', 'general') }}">
                    Email
                </a>
            </li>
            <li @if ($pathInfo == '/admin/configuration/emails/general') class="active" @endif>
                <a href="{{ route('admin.configuration.index', 'emails', 'general') }}">
                    Notifications
                </a>
            </li>
            <!-- <li @if ($pathInfo == '/admin/configuration/megasmsnotifications/general') class="active" @endif>
                <a href="{{ route('admin.configuration.index', 'megasmsnotifications', 'general') }}">
                    SMS Notifications
                </a>
            </li> -->
            <!-- <li @if (request()->route()->getName() == 'pwa.pushnotification.create') class="active" @endif>
                <a href="{{ route('pwa.pushnotification.create') }}">
                    Push Notifications
                </a>
            </li> -->
        @elseif(
                $pathInfo == '/admin/configuration/catalog/inventory' or
                $pathInfo == '/admin/configuration/catalog/products' or
                $pathInfo == '/admin/configuration/sales/rma' or
                $pathInfo == '/admin/configuration/sales/paymentmethods' or
                $pathInfo == '/admin/configuration/sales/orderSettings' or
                $pathInfo == '/admin/configuration/sales/ffl'
                )
            <li @if ($pathInfo == '/admin/configuration/catalog/inventory') class="active" @endif>
                <a href="{{ route('admin.configuration.index', 'catalog', 'inventory') }}">
                    Inventory
                </a>
            </li>
            <li @if ($pathInfo == '/admin/configuration/catalog/products') class="active" @endif>
                <a href="{{ url('/admin/configuration/catalog/products') }}">
                    Products
                </a>
            </li>
            <li @if ($pathInfo == '/admin/configuration/sales/rma') class="active" @endif>
                <a href="{{ url('/admin/configuration/sales/rma') }}">
                    Returns
                </a>
            </li>
            <li @if ($pathInfo == '/admin/configuration/sales/paymentmethods') class="active" @endif>
                <a href="{{ url('/admin/configuration/sales/paymentmethods') }}">
                    Payment Methods
                </a>
            </li>
            <li @if ($pathInfo == '/admin/configuration/sales/orderSettings') class="active" @endif>
                <a href="{{ url('/admin/configuration/sales/orderSettings') }}">
                    Order Settings
                </a>
            </li>
            <li @if ($pathInfo == '/admin/configuration/sales/ffl') class="active" @endif>
                {{--                <a href="{{url('/admin/configuration/sales/ffl')}}">--}}
                <a href="{{route('ffl.admin.configuration')}}">
                    FFL
                </a>
            </li>
        @elseif(
            $pathInfo == '/admin/configuration/customer/settings' or
            $pathInfo == '/admin/configuration/megaPhoneLogin/general'
            )
            <li @if ($pathInfo == '/admin/configuration/customer/settings') class="active" @endif>
                <a href="{{ route('admin.configuration.index', 'customer', 'settings') }}">
                    Settings
                </a>
            </li>
            <li @if ($pathInfo == '/admin/configuration/megaPhoneLogin/general') class="active" @endif>
                <a href="{{ route('admin.configuration.index', 'megaPhoneLogin', 'general') }}">
                    Two Factor Authentication
                </a>
            </li>
        @elseif(
                $pathInfo == '/admin/configuration/sales/shipping' or
                $pathInfo == '/admin/configuration/sales/carriers' or
                request()->route()->getName() == 'admin.inventory_sources.index' or
                request()->route()->getName() == 'admin.inventory_sources.create' or
                request()->route()->getName() == 'admin.inventory_sources.edit' or
                request()->route()->getName() == 'admin.tablerate.supersets.index' or
                request()->route()->getName() == 'admin.tablerate.supersets.create' or
                request()->route()->getName() == 'admin.tablerate.supersets.edit' or
                request()->route()->getName() == 'admin.tablerate.superset_rates.index' or
                request()->route()->getName() == 'admin.tablerate.superset_rates.create' or
                request()->route()->getName() == 'admin.tablerate.superset_rates.edit' or
                request()->route()->getName() == 'admin.tablerate.shipping_rates.index' or
                request()->route()->getName() == 'admin.tablerate.shipping_rates.create' or
                request()->route()->getName() == 'admin.tablerate.shipping_rates.edit'
            )
            <li @if ($pathInfo == '/admin/configuration/sales/shipping') class="active" @endif>
                <a href="{{ route('admin.configuration.index', 'sales', 'shipping') }}">
                    Settings
                </a>
            </li>
            <li @if ($pathInfo == '/admin/configuration/sales/carriers') class="active" @endif>
                <a href="{{ url('/admin/configuration/sales/carriers') }}">
                    Shipping Methods
                </a>
            </li>
            <li @if (request()->route()->getName() == 'admin.tablerate.supersets.index' or
                     request()->route()->getName() == 'admin.tablerate.supersets.create' or
                     request()->route()->getName() == 'admin.tablerate.supersets.edit') class="active" @endif>
                <a href="{{ route('admin.tablerate.supersets.index') }}">
                    SuperSets
                </a>
            </li>
            <li @if (request()->route()->getName() == 'admin.tablerate.superset_rates.index' or
                     request()->route()->getName() == 'admin.tablerate.superset_rates.create' or
                     request()->route()->getName() == 'admin.tablerate.superset_rates.edit') class="active" @endif>
                <a href="{{ route('admin.tablerate.superset_rates.index') }}">
                    SuperSet Rates
                </a>
            </li>
            <li @if (request()->route()->getName() == 'admin.tablerate.shipping_rates.index' or
                     request()->route()->getName() == 'admin.tablerate.shipping_rates.create' or
                     request()->route()->getName() == 'admin.tablerate.shipping_rates.edit') class="active" @endif>
                <a href="{{ route('admin.tablerate.shipping_rates.index') }}">
                    SuperSet Shipping Rates
                </a>
            </li>
        @endif
    </ul>
</div>
