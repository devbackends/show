@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.dashboard.title') }}
@endsection

@section('content')
    <div class="settings-page">
        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('marketplace::app.shop.sellers.account.dashboard.title') }}</p>
            </div>
            <div class="settings-page__header-actions">
                <date-filter></date-filter>
                <!-- <div class="form-group form-date">
                          <input type="text" class="form-control" placeholder="05-18-2020">
                          <i class="far fa-calendar-alt"></i>
                        </div>
                        <div class="form-group form-date">
                          <input type="text" class="form-control" placeholder="05-18-2020">
                          <i class="far fa-calendar-alt"></i>
                        </div> -->
            </div>
        </div>

        <div class="settings-page__body">
            @php($fluidCustomer = \Devvly\FluidPayment\Models\FluidCustomer::query()->where('seller_id', $seller->id)->first())
            @if($fluidCustomer && !$fluidCustomer->is_approved)
                <div style="margin-bottom: 20px;background: #F9D94D;border-radius: 5px;display: flex;">
                    <div style="padding: 20px;border-right: 1px solid #dabd40;">
                        <i class="far fa-info-circle fa-3x"></i>
                    </div>
                    <div style="padding: 20px;">
                        <p style="margin: 0;">Getting underwriting for your credit card processing can sometimes take a
                            few days, so be patient while we get this set up for you. In the meantime, you can still get
                            your store set up by adding products. You will be able to begin accepting cash payments
                            immediately.</p>
                    </div>
                </div>
            @endif
            @include('shop::sellers.account.dashboard.dashboard-steps')
            {!! view_render_event('marketplace.sellers.account.dashboard.before') !!}
            <div class="row">
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="dashboard-box dashboard-box--number">
                        <div class="title">{{ __('admin::app.dashboard.total-orders') }}</div>
                        <div class="data">
                            <span class="sum">{{ $statistics['total_orders']['current'] }}</span>

                            @if ($statistics['total_orders']['progress'] < 0) <span class="stat text-danger"><i
                                    class="far fa-arrow-down"></i> {{ __('admin::app.dashboard.decreased', ['progress' => -number_format($statistics['total_orders']['progress'], 1)])}}</span>
                            @else
                                <span class="stat text-success"><i class="far fa-arrow-up"></i> {{ __('admin::app.dashboard.increased', ['progress' => number_format($statistics['total_orders']['progress'], 1)])}}</span>

                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="dashboard-box dashboard-box--number">
                        <div class="title">{{ __('admin::app.dashboard.total-sale') }}</div>
                        <div class="data">
                            <span
                                class="sum">{{ core()->formatBasePrice($statistics['total_sales']['current']) }}</span>
                            @if ($statistics['total_sales']['progress'] < 0) <span class="stat text-danger"><i
                                    class="far fa-arrow-down"></i> {{ __('admin::app.dashboard.decreased', ['progress' => -number_format($statistics['total_sales']['progress'], 1)])}}</span>

                            @else
                                <span class="stat text-success"><i class="far fa-arrow-up"></i> {{ __('admin::app.dashboard.increased', ['progress' => number_format($statistics['total_sales']['progress'], 1)])}}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="dashboard-box dashboard-box--number">
                        <div class="title">{{ __('admin::app.dashboard.average-sale') }}</div>
                        <div class="data">
                            <span class="sum">{{ core()->formatBasePrice($statistics['avg_sales']['current']) }}</span>
                            @if ($statistics['avg_sales']['progress'] < 0) <span class="stat text-danger"><i
                                    class="far fa-arrow-down"></i> {{ __('admin::app.dashboard.decreased', ['progress' => -number_format($statistics['avg_sales']['progress'], 1)])}}</span>
                            @else
                                <span class="stat text-success"><i class="far fa-arrow-up"></i> {{ __('admin::app.dashboard.increased', ['progress' => number_format($statistics['avg_sales']['progress'], 1)])}}</span>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="graph-stats">
                <div class="row">
                    <div class="col-12">
                        <div class="dashboard-box">
                            <div class="title">{{ __('admin::app.dashboard.sales') }}</div>

                            <div class="data">

                                <canvas id="myChart" style="width: 100%; height: 87%"></canvas>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
            <div class="sale-stock">
                <div class="row">
                    <div class="col-12 col-md-6 col-xl-4">
                        <div
                            class="dashboard-box dashboard-box--stock {{ !count($statistics['top_selling_products']) ? 'dashboard-box--empty' : '' }}">
                            <div class="title">{{ __('admin::app.dashboard.top-selling-products') }}</div>

                            <div class="data {{ !count($statistics['top_selling_products']) ? 'center' : '' }}">
                                <div class="inner">
                                    <ul>

                                        @foreach ($statistics['top_selling_products'] as $item)

                                            <li>
                                                <a href="{{ route('marketplace.account.products.edit', $item->product_id) }}"
                                                   class="d-block w-100">
                                                    <?php $productBaseImage = $productImageHelper->getProductBaseImage($item->product); ?>

                                                    <img src="{{ $productBaseImage['small_image_url'] }}"
                                                         alt="List item image"/>

                                                    <div class="wrap d-inline-block">
                                                        <p class="mb-1">{{ $item->name }}</p>
                                                        <p>{{ __('admin::app.dashboard.sale-count', ['count' => $item->total_qty_ordered]) }}</p>
                                                    </div>
                                                </a>
                                            </li>

                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                            @if (! count($statistics['top_selling_products']))
                                <div class="text-center">
                                    <i class="fal fa-exclamation-triangle"></i>
                                    <span class="h4">{{ __('admin::app.common.no-result-found') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div
                            class="dashboard-box dashboard-box--stock {{ !count($statistics['top_selling_products']) ? 'dashboard-box--empty' : '' }}">
                            <div class="title">
                                {{ __('admin::app.dashboard.customer-with-most-sales') }}
                            </div>

                            <div class="data {{ !count($statistics['customer_with_most_sales']) ? 'center' : '' }}">
                                <div class="inner">
                                    <ul>
                                        @foreach ($statistics['customer_with_most_sales'] as $item)
                                            <li>
                                                <i class="far fa-user-circle"></i>

                                                <div class="wrap d-inline-block pt-0">
                                                    <p class="font-weight-bold mb-1">{{ $item->customer_full_name }}</p>

                                                    <p>
                                                        {{ __('admin::app.dashboard.order-count', ['count' => $item->total_orders]) }}
                                                        &nbsp;.&nbsp;
                                                        {{ __('admin::app.dashboard.revenue', [
                                                                        'total' => core()->formatBasePrice($item->total_base_grand_total)
                                                                        ])
                                                                    }}
                                                    </p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @if (! count($statistics['customer_with_most_sales']))
                                <div class="text-center">
                                    <i class="fal fa-exclamation-triangle"></i>
                                    <span class="h4">{{ __('admin::app.common.no-result-found') }}</span>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div
                            class="dashboard-box dashboard-box--stock {{ !count($statistics['top_selling_products']) ? 'dashboard-box--empty' : '' }}">
                            <div class="title">
                                {{ __('admin::app.dashboard.stock-threshold') }}
                            </div>

                            <div class="data {{ !count($statistics['stock_threshold']) ? 'center' : '' }}">

                                <div class="inner">
                                    <ul>
                                        @foreach ($statistics['stock_threshold'] as $item)
                                            <li>
                                                <a href="{{ route('marketplace.account.products.edit', $item->product_id) }}"
                                                   class="d-block w-100">
                                                    <?php $productBaseImage = $productImageHelper->getProductBaseImage($item->product); ?>
                                                    <img src="{{ $productBaseImage['small_image_url'] }}"
                                                         alt="List item image">
                                                    <div class="wrap d-inline-block">
                                                        <p>{{ $item->product->name }}</p>
                                                        <b>{{ __('admin::app.dashboard.qty-left', ['qty' => $item->total_qty]) }}</b>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @if (! count($statistics['stock_threshold']))
                                    <div class="text-center">
                                        <i class="fal fa-exclamation-triangle"></i>
                                        <span class="h4">{{ __('admin::app.common.no-result-found') }}</span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            {!! view_render_event('marketplace.sellers.account.dashboard.after') !!}

            @endsection

            @push('scripts')

                <script type="text/javascript"
                        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

                <script type="text/x-template" id="date-filter-template">
                    <div class="row mx-n2">
                        <div class="col-6 px-2">
                        <div class="form-group date d-inline-block">
                            <input type="date" class="form-control" @onChange="applyFilter('start', $event)" id="start_date" value="{{ $startDate->format('Y-m-d') }}" placeholder="{{ __('admin::app.dashboard.from') }}" v-model="start">
                        </div>
                        </div>
                        <div class="col-6 px-2">
                        <div class="form-group date d-inline-block">
                            <input type="date" class="form-control" @onChange="applyFilter('end', $event)" id="end_date" value="{{ $endDate->format('Y-m-d') }}" placeholder="{{ __('admin::app.dashboard.to') }}" v-model="end">
                        </div>
                        </div>
                    </div>
                </script>

                <script>
                    Vue.component('date-filter', {

                        template: '#date-filter-template',

                        data: () => ({
                            start: "{{ $startDate->format('Y-m-d') }}",
                            end: "{{ $endDate->format('Y-m-d') }}",
                        }),

                        methods: {
                            applyFilter(field, date) {
                                this[field] = date;

                                window.location.href = "?start=" + this.start + '&end=' + this.end;
                            }
                        }
                    });

                    $(document).ready(function () {

                        var ctx = document.getElementById("myChart").getContext('2d');

                        var data = @json($statistics['sale_graph']);

                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data['label'],
                                datasets: [{
                                    data: data['total'],
                                    backgroundColor: 'rgba(34, 201, 93, 1)',
                                    borderColor: 'rgba(34, 201, 93, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                legend: {
                                    display: false
                                },
                                scales: {
                                    xAxes: [{
                                        maxBarThickness: 20,
                                        gridLines: {
                                            display: false,
                                            drawBorder: false,
                                        },
                                        ticks: {
                                            beginAtZero: true,
                                            fontColor: 'rgba(162, 162, 162, 1)'
                                        }
                                    }],
                                    yAxes: [{
                                        gridLines: {
                                            drawBorder: false,
                                        },
                                        ticks: {
                                            padding: 20,
                                            beginAtZero: true,
                                            fontColor: 'rgba(162, 162, 162, 1)'
                                        }
                                    }]
                                },
                                tooltips: {
                                    mode: 'index',
                                    intersect: false,
                                    displayColors: false,
                                    callbacks: {
                                        label: function (tooltipItem, dataTemp) {
                                            return data['formated_total'][tooltipItem.index];
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
    @endpush