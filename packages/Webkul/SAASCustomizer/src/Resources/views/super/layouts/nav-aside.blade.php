<div class="aside-nav">
    <ul>
        <?php $keys = explode('.', $menu->currentKey);  ?>

        @if(isset($keys) && strlen($keys[0]))

            @foreach (array_get($menu->items, current($keys) . '.children') as $item)

                @if($menu->currentKey=="configuration.sales.carriers" && $item['name']=='Sales')
                    <li class="{{ $menu->getActive($item) }}">
                        <a href="{{url('/super/configuration/sales/carriers')}}">
                            {{ trans($item['name']) }}
                            @if ($menu->getActive($item))
                                <i class="angle-right-icon"></i>
                            @endif
                        </a>
                    </li>
                @else
                    <li class="{{ $menu->getActive($item) }}">
                        <a href="{{ $item['url'] }}">
                            {{ trans($item['name']) }}

                            @if ($menu->getActive($item))
                                <i class="angle-right-icon"></i>
                            @endif
                        </a>
                    </li>
                @endif

            @endforeach
        @endif
    </ul>
</div>
