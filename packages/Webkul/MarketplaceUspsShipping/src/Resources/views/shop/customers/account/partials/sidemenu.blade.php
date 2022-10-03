<div class="sidebar">
    @foreach ($menu->items as $menuItem)
        <div class="menu-block">
            <div class="menu-block-title">
                {{ trans($menuItem['name']) }}

                <i class="icon icon-arrow-down right" id="down-icon"></i>
            </div>

            <div class="menu-block-content">
                <ul class="menubar">

                    @if ($menuItem['key'] != 'marketplace')
                        @foreach ($menuItem['children'] as $subMenuItem)
                            <li class="menu-item {{ $menu->getActive($subMenuItem) }}">
                                <a href="{{ $subMenuItem['url'] }}">
                                    {{ trans($subMenuItem['name']) }}
                                </a>

                                <i class="icon angle-right-icon"></i>
                            </li>
                        @endforeach
                    @else
                        @if (app('Webkul\Marketplace\Repositories\SellerRepository')->isSeller(auth()->guard('customer')->user()->id))

                            @foreach ($menuItem['children'] as $key =>$subMenuItem)

                                @if ( strpos($key, 'mp') !== false && core()->getConfigData('sales.carriers')[$key])
                                    @if ( core()->getConfigData('sales.carriers.'.$key.'.active') == 0 )

                                        @continue;
                                    @elseif ( core()->getConfigData('sales.carriers.'.$key.'.allow_seller') != null && core()->getConfigData('sales.carriers.'.$key.'.allow_seller') == 0 )
                                            
                                        @continue;
                                    @endif
                                @else
                                    @if ($key == 'superset' && core()->getConfigData('sales.carriers.mptablerate.active') == 0 )
                                        @continue;
                                    @endif
                                @endif

                                <li class="menu-item {{ $menu->getActive($subMenuItem) }}">
                                    <a href="{{ $subMenuItem['url'] }}">
                                        {{ trans($subMenuItem['name']) }}
                                    </a>

                                    <i class="icon angle-right-icon"></i>
                                </li>
                            @endforeach

                        @else

                            <li class="menu-item {{ request()->route()->getName() == 'marketplace.account.seller.create' ? 'active' : '' }}">
                                <a href="{{ route('marketplace.account.seller.create') }}">
                                    {{ __('marketplace::app.shop.layouts.become-seller') }}
                                </a>

                                <i class="icon angle-right-icon"></i>
                            </li>

                        @endif
                    @endif
                </ul>
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $(".icon.icon-arrow-down.right").on('click', function(e){
            var currentElement = $(e.currentTarget);
            if (currentElement.hasClass('icon-arrow-down')) {
                $(this).parents('.menu-block').find('.menubar').show();
                currentElement.removeClass('icon-arrow-down');
                currentElement.addClass('icon-arrow-up');
            } else {
                $(this).parents('.menu-block').find('.menubar').hide();
                currentElement.removeClass('icon-arrow-up');
                currentElement.addClass('icon-arrow-down');
            }
        });
    });
</script>
@endpush