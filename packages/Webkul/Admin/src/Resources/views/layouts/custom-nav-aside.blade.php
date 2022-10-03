
<div class="aside-nav">
    <ul>
        <li
            @if(
                $pathInfo == '/admin/configuration/general/design' or
                $pathInfo == '/admin/configuration/megaheaderfooter/general' or
                request()->route()->getName() == 'company.profile.index' or
                request()->route()->getName() == 'company.address.index' or
                request()->route()->getName() == 'company.address.create' or
                request()->route()->getName() == 'company.address.edit'
                )
                class="active"
            @endif
        >
            <a href="{{ route('company.profile.index') }}">
                General
                @if(
                $pathInfo == '/admin/configuration/general/design' or
                $pathInfo == '/admin/configuration/megaheaderfooter/general' or
                request()->route()->getName() == 'company.profile.index' or
                request()->route()->getName() == 'company.address.index' or
                request()->route()->getName() == 'company.address.create' or
                request()->route()->getName() == 'company.address.edit'
                )
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
        <li
        @if(
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
            class="active"
        @endif
        >
            <a href="{{ route('admin.locales.index') }}">
                Regional Settings
                @if(
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
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
        <li
            @if(
                $pathInfo == '/admin/configuration/general/general' or
                $pathInfo == '/admin/configuration/emails/general' or
                $pathInfo == '/admin/configuration/megasmsnotifications/general' or
                request()->route()->getName() == 'pwa.pushnotification.create'
                )
            class="active"
            @endif
            >
            <a href="{{ route('admin.configuration.index', 'general', 'general') }}">
                Notifications
                @if(
                $pathInfo == '/admin/configuration/general/general' or
                $pathInfo == '/admin/configuration/emails/general' or
                $pathInfo == '/admin/configuration/megasmsnotifications/general' or
                request()->route()->getName() == 'pwa.pushnotification.create'
                )
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
        <li
            @if(
                $pathInfo == '/admin/configuration/catalog/inventory' or
                $pathInfo == '/admin/configuration/catalog/products' or
                $pathInfo == '/admin/configuration/sales/rma' or
                $pathInfo == '/admin/configuration/sales/paymentmethods' or
                $pathInfo == '/admin/configuration/sales/orderSettings' or
                $pathInfo == '/admin/configuration/sales/ffl'
                )
            class="active"
            @endif
            >
            <a href="{{ route('admin.configuration.index', 'catalog', 'inventory') }}">
                Store
                @if(
                    $pathInfo == '/admin/configuration/catalog/inventory' or
                    $pathInfo == '/admin/configuration/catalog/products' or
                    $pathInfo == '/admin/configuration/sales/rma' or
                    $pathInfo == '/admin/configuration/sales/paymentmethods' or
                    $pathInfo == '/admin/configuration/sales/orderSettings' or
                    $pathInfo == '/admin/configuration/sales/ffl'
                    )
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
        <li
            @if(request()->route()->getName() == 'admin.channels.index' or
                request()->route()->getName() == 'admin.channels.create' or
                request()->route()->getName() == 'admin.channels.edit')
            class="active"
            @endif
            >
            <a href="{{ route('admin.channels.index') }}">
                Channels
                @if(request()->route()->getName() == 'admin.channels.index' or
                    request()->route()->getName() == 'admin.channels.create' or
                    request()->route()->getName() == 'admin.channels.edit')
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
        <li
            @if(
                request()->route()->getName() == 'admin.users.index' or
                request()->route()->getName() == 'admin.roles.index' or
                request()->route()->getName() == 'admin.users.create' or
                request()->route()->getName() == 'admin.users.edit'
                )
            class="active"
            @endif
            >
            <a href="{{ route('admin.users.index') }}">
                Users
                @if(
                request()->route()->getName() == 'admin.users.index' or
                request()->route()->getName() == 'admin.roles.index' or
                request()->route()->getName() == 'admin.users.create'
                )
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
        <li
            @if(
                $pathInfo == '/admin/configuration/customer/settings' or
                $pathInfo == '/admin/configuration/megaPhoneLogin/general'
                )
            class="active"
            @endif
            >
            <a href="{{ route('admin.configuration.index', 'customer', 'settings') }}">
                Customer
                @if(
                $pathInfo == '/admin/configuration/customer/settings' or
                $pathInfo == '/admin/configuration/megaPhoneLogin/general'
                    )
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
        <li
            @if(
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
            class="active"
            @endif
            >
            <a href="{{ route('admin.configuration.index', 'sales', 'shipping') }}">
                Shipping
                @if(
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
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
        <li
            @if(request()->route()->getName() == 'admin.tax-categories.index' or
                request()->route()->getName() == 'admin.tax-categories.show' or
                request()->route()->getName() == 'admin.tax-categories.edit')
            class="active"
            @endif
        >
            <a href="{{ route('admin.tax-categories.index') }}">
                Taxes
                @if(request()->route()->getName() == 'admin.tax-categories.index' or
                    request()->route()->getName() == 'admin.tax-categories.show' or
                    request()->route()->getName() == 'admin.tax-categories.edit')
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
        <li
            @if($pathInfo == '/admin/configuration/pwa/settings')
            class="active"
            @endif
        >
            <a href="{{ route('admin.configuration.index', 'pwa', 'settings') }}">
                PWA
                @if($pathInfo == '/admin/configuration/pwa/settings')
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
        <li
            @if($pathInfo == '/admin/configuration/marketplace/settings')
            class="active"
            @endif
        >
            <a href="{{ route('admin.configuration.index', 'marketplace', 'settings') }}">
                Marketplace
                @if($pathInfo == '/admin/configuration/marketplace/settings')
                    <i class="angle-right-icon-active"></i>
                @else
                    <i class="angle-right-icon"></i>
                @endif
            </a>
        </li>
    </ul>
</div>
