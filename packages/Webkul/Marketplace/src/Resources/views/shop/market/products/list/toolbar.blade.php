@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

{!! view_render_event('bagisto.shop.products.list.toolbar.before') !!}
<toolbar-component></toolbar-component>
{!! view_render_event('bagisto.shop.products.list.toolbar.after') !!}

@push('scripts')
<script type="text/x-template" id="toolbar-template">
    <div class="row mb-4">
        <div class="col-md mb-md-3 mb-lg-0 d-none d-md-block">
            @if (
                ! ($toolbarHelper->isModeActive('grid')
                || $toolbarHelper->isModeActive('list'))
            )
                <a href="#" class="view__item active btn btn-dark">
                    <i class="far fa-th"></i>
                </a>
            @else
                @if ($toolbarHelper->isModeActive('grid'))
                    <a href="#" class="view__item active btn btn-dark">
                        <i class="far fa-th"></i>
                    </a>
                @else
                    <a href="{{ $toolbarHelper->getModeUrl('grid') }}" class="view__item active btn">
                        <i class="far fa-th"></i>
                    </a>
                @endif
            @endif

            @if ($toolbarHelper->isModeActive('list'))
                <a href="#" class="view__item active btn btn-dark">
                    <i class="far fa-list"></i>
                </a>
            @else
                <a href="{{ $toolbarHelper->getModeUrl('list') }}" class="view__item btn">
                    <i class="far fa-list"></i>
                </a>
            @endif
        </div>
        <div class="col-12 col-md-12 col-lg-auto">
            <div class="row">
                <div class="col-12 col-sm mb-3 mb-lg-0">
                    <div class="form-group toolbar-sort">
                        <label for="filter-select-1">{{ __('shop::app.products.sort-by') }}</label>
                        <select name="select" class="form-control" onchange="window.location.href = this.value">
                            @foreach ($toolbarHelper->getAvailableOrders(request()->get('type')) as $key => $order)
                                <option
                                    value="{{ $toolbarHelper->getOrderUrl($key) }}" {{ $toolbarHelper->isOrderCurrent($key) ? 'selected' : '' }}>
                                    {{ __('shop::app.products.' . $order) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm">
                    <div class="form-group toolbar-sort">
                        <label for="filter-select-2">{{ __('shop::app.products.show') }}</label>
                        <select name="select" class="form-control" onchange="window.location.href = this.value">
                            @foreach ($toolbarHelper->getAvailableLimits() as $limit)
                                <option
                                    value="{{ $toolbarHelper->getLimitUrl($limit) }}" {{ $toolbarHelper->isLimitCurrent($limit) ? 'selected' : '' }}>
                                    {{ $limit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/javascript">
    (() => {
        Vue.component('toolbar-component', {
            template: '#toolbar-template',

            data: function() {
                return {
                    'layeredNavigation': false,
                }
            },

            watch: {
                layeredNavigation: function(value) {
                    if (value) {
                        document.body.classList.add('open-hamburger');
                    } else {
                        document.body.classList.remove('open-hamburger');
                    }
                }
            },

            methods: {
                toggleLayeredNavigation: function({
                    event,
                    actionType
                }) {
                    this.layeredNavigation = !this.layeredNavigation;
                }
            }
        })
    })()
</script>
@endpush