
<div class="customer-sidemenu__content">
    <div class="account-details">
        <div class="d-flex justify-content-center">
            <div class="customer-name text-uppercase">
                {{ substr(auth('customer')->user()->first_name, 0, 1) }}
            </div>
        </div>
        <div>
            <p class="customer-sidemenu__details-name">
                {{ auth('customer')->user()->first_name . ' ' . auth('customer')->user()->last_name}}
            </p>
        </div>
        <div>
            <p class="customer-sidemenu__details-email">
                {{ auth('customer')->user()->email }}
            </p>
        </div>
    </div>

    @foreach ($menu->items as $menuItem)
        <div>
            <p class="customer-sidemenu__subtitle">{{ $menuItem['key']  }}</p>
        </div>
        <ul type="none" class="customer-sidemenu__navigation">
            {{-- rearrange menu items --}}
            @php
                $subMenuCollection = [];

                try {
                $subMenuCollection['profile'] = $menuItem['children']['profile'];
                $subMenuCollection['profile']['font-icon']='user';
                $subMenuCollection['orders'] = $menuItem['children']['orders'];
                $subMenuCollection['orders']['font-icon']='receipt';
                $subMenuCollection['downloadables'] = $menuItem['children']['downloadables'];
                $subMenuCollection['downloadables']['font-icon']='arrow-to-bottom';
                $subMenuCollection['wishlist'] = $menuItem['children']['wishlist'];
                $subMenuCollection['wishlist']['font-icon']='heart';
                $subMenuCollection['compare'] = [
                'key' => 'account.compare',
                'url' => route('velocity.customer.product.compare'),
                'name' => 'velocity::app.customer.compare.text',
                ];
                $subMenuCollection['compare']['font-icon']='compress-alt';
                $subMenuCollection['reviews'] = $menuItem['children']['reviews'];
                $subMenuCollection['reviews']['font-icon']='star';
                $subMenuCollection['address'] = $menuItem['children']['address'];
                $subMenuCollection['address']['font-icon']='map-marker-alt';

                $subMenuCollection['rma'] = $menuItem['children']['rma'];

                } catch (\Exception $exception) {
                $subMenuCollection = $menuItem['children'];
                }
            @endphp

            @foreach ($subMenuCollection as $index => $subMenuItem)
                <li class="customer-sidemenu__navigation-item {{ $menu->getActive($subMenuItem) }}"
                    title="{{ trans($subMenuItem['name']) }}">
                    <a class="unset customer-sidemenu__navigation-item" href="{{ $subMenuItem['url'] }}">
                        @if(isset($subMenuItem['font-icon']))
                            <i class="far fa-{{ $subMenuItem['font-icon'] }} customer-sidemenu__navigation-item-icon"></i>
                        @else
                            <i class="icon {{ $index }}"></i>
                        @endif
                        <span>{{ trans($subMenuItem['name']) }}</span>
                        <i class="fas fa-chevron-right ml-auto"></i>
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</div>

@push('css')
    <style type="text/css">
        .main-content-wrapper {
            margin-bottom: 0px;
            min-height: 100vh;
        }
    </style>
@endpush