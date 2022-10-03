<!-- SIDEBAR SECTION -->
<div class="sidebar js-user-nav h-100">
    <a href="#" class="sidebar__toggle js-toggle-user-nav">
        <i class="far fa-user-cog"></i>
    </a>
    <div class="close-wrapper"></div>
    <div class="customer-sidebar">
        <div class="account-details">
            @if(auth()->guard('customer')->check())
            <div class="customer-image">
                <div>
                    <form id="customer_profile_picture_form" method="post">
                        <input class="customer_profile_picture" name="image" type="file"
                               accept="image/x-png,image/gif,image/jpeg">
                        @if(!empty(auth()->guard('customer')->user()->image))
                            @php $src=getenv('WASSABI_STORAGE').'/'.auth('customer')->user()->image; @endphp
                            <img src="{{ $src  }}" alt="">
                        @else
                            <img src="/images/user-avatar.png" alt="">
                        @endif

                        <div class="customer-image__overlay">
                            <div><i class="far fa-plus"></i>
                                <p>Change photo</p></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="customer-name-text">{{ auth()->guard('customer')->user()->first_name . ' ' . auth()->guard('customer')->user()->last_name}}</div>
            <div class="customer-email">{{ auth()->guard('customer')->user()->email }}</div>
            <a class="btn btn-outline-gray text-center mt-3 w-100" href="{{ route('marketplace.account.messages.index') }}"><span class="w-100"><i class="far fa-comments-alt mr-2"></i>Messages</span></a>
            <a class="btn btn-outline-gray text-center mt-3 w-100" href="{{ route('marketplace.account.products.create') }}"><span class="w-100">Create Product</span></a>
            @endif
        </div>

        @php
            $show = 1;
            $seller=false;
            if(auth()->guard('customer')->check()){
             $seller = app('Webkul\Marketplace\Repositories\SellerRepository')->findWhere(['customer_id' => auth()->guard('customer')->user()->id])->first();
            }
        @endphp

        @foreach (array_reverse($menu->items) as $menuItem)

            @if (!$seller && strtolower($menuItem['key'])=='marketplace')
                <div class="d-none">
                    @else
                        <div>
                            @endif
                            @if (!$seller)
                                <div class="px-3 pb-4">
                                    <a class="btn btn-primary text-center w-100" href="{{ route('marketplace.account.seller.edit') }}"><span class="w-100"><i class="far fa-store mr-2"></i>Start Selling!</span></a>
                                </div>
                            @endif
                            <div>
                                <span class="paragraph bold">{{ __( $menuItem['name'] ) }}</span>
                            </div>
                            <ul type="none" class="navigation">
                                {{-- rearrange menu items --}}
                                @php
                                    $subMenuCollection = [];

                                    try {
                                        $subMenuCollection['profile'] = $menuItem['children']['profile'];
                                        $subMenuCollection['profile']['font-icon']='user';
                                        $subMenuCollection['orders'] = $menuItem['children']['orders'];
                                        $subMenuCollection['orders']['font-icon']='receipt';
                                        $subMenuCollection['coupons'] = [
                                        'key' => 'marketplace.coupons',
                                        'url' => route('marketplace.account.coupons.index'),
                                        'name' => 'Coupons',
                                        ];
                                        $subMenuCollection['coupons']['font-icon']='far fa-badge-percent';

                                        // $subMenuCollection['downloadables'] = $menuItem['children']['downloadables'];
                                        // $subMenuCollection['downloadables']['font-icon']='arrow-to-bottom';
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
                                        $subMenuCollection['rma']['font-icon']='undo-alt';

                                    } catch (\Exception $exception) {
                                        $subMenuCollection = $menuItem['children'];
                                        $subMenuCollection['seller']['font-icon']='user';
                                        $subMenuCollection['dashboard']['font-icon']='tachometer-alt-fast';
                                        $subMenuCollection['products']['font-icon']='clipboard-list-check';
                                        $subMenuCollection['orders']['font-icon']='receipt';
                                        /*$subMenuCollection['transactions']['font-icon']='cash-register';*/
                                        $subMenuCollection['reviews']['font-icon']='star';
                                        $subMenuCollection['settings']['font-icon']='store';
                                        $subMenuCollection['coupons'] = [
                                        'key' => 'marketplace.coupons',
                                        'url' => route('marketplace.account.coupons.index'),
                                        'name' => 'Coupons',
                                        ];
                                        $subMenuCollection['coupons']['font-icon']='far fa-badge-percent';

                                    }

                                @endphp

                                @foreach ($subMenuCollection as $index => $subMenuItem)
                                    <li class="{{ $menu->getActive($subMenuItem) }}" title="{{ trans($subMenuItem['name']) }}">
                                        <a class="unset customer-sidemenu__navigation-item" href="{{ $subMenuItem['url'] }}">
                                            @if(isset($subMenuItem['font-icon']))
                                                <i class="far fa-{{ $subMenuItem['font-icon'] }} customer-sidemenu__navigation-item-icon"></i>
                                            @else
                                                <i class="icon {{ $index }}"></i>
                                            @endif
                                            <span>{{ trans($subMenuItem['name']) }}</span>
                                            <i class="fal fa-angle-right"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
        @endforeach

    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function () {
            $(".customer_profile_picture").change(function () {
                var file = this.files[0];
                var fileType = file["type"];
                var validImageTypes = ["image/jpg", "image/gif", "image/jpeg", "image/png"];
                if ($.inArray(fileType, validImageTypes) < 0) {
                    alert('Please try to upload a valid image');
                } else {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var form_data = new FormData();
                    form_data.append('image', $(this)[0].files[0]);
                    $.ajax({
                        url: "/upload-customer-profile-picture",
                        method: "POST",
                        dataType: "json",
                        data: form_data,
                        processData: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        success: function (data) {
                            if (data['status'] == 'success') {
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush

@push('css')
@endpush
<!-- END SIDEBAR SECTION -->
