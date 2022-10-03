@extends('admin::layouts.master')

@section('page_title')
{{ __('admin::app.dashboard.title') }}
@stop

@section('content-wrapper')

<div class="content full-page dashboard {{get_app_class_prefix()}}">
    <div class="page-header">
        <div class="page-title">
            <h3>{{ __('admin::app.dashboard.title') }}</h3>
        </div>

        <div class="page-action">
            <date-filter></date-filter>
        </div>
    </div>

    <div class="container-fluid page-content">
        <div class="dashboard__tips">
            <div class="row">
                <div class="col-lg-3 pr-lg-0">
                    <div class="dashboard__tips-title bg-gray-lighter p-3 p-lg-4 h-100">
                        <h2>Get ready to sell online. Follow these steps to get started.</h2>
                    </div>
                </div>
                <div class="col-lg-9 pl-lg-0">
                    <a id="close-tips" class="dashboard__tips-close">
                        <i class="far fa-times"></i>
                    </a>
                    <div class="dashboard__tips-content p-3 p-lg-4 pb-lg-5 h-100">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="setup-payment-tab" data-toggle="tab" href="#setup-payment" role="tab" aria-controls="home" aria-selected="true">Setup payment processing</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="ffl-license-tab" data-toggle="tab" href="#ffl-license" role="tab" aria-controls="profile" aria-selected="false">Add your FFL license</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="add-product-tab" data-toggle="tab" href="#add-product" role="tab" aria-controls="contact" aria-selected="false">Add product</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="custom-domain-tab" data-toggle="tab" href="#custom-domain" role="tab" aria-controls="contact" aria-selected="false">Custom domain</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="setup-payment" role="tabpanel" aria-labelledby="setup-payment">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="dashboard__tips-content-icon d-flex justify-content-center align-items-center">
                                            <i class="fal fa-cash-register"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p class="dashboard__tips-content-big-gray">Getting setup is easy! The tabs at the top of this box will guide you through the first 4 things you need to do to start selling!</p>
                                        <h3 class="dashboard__tips-content-big">Step 1: Payment setup is easy and consists of two steps. Setting up your 2A Gun Show Subscription and then your payment processing. Click the button below and we will walk you through the process.</h3>
                                        <a class="btn btn-primary" href="/admin/configuration/sales/paymentmethods">Setup payment processing</a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="ffl-license" role="tabpanel" aria-labelledby="ffl-license">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="dashboard__tips-content-icon d-flex justify-content-center align-items-center">
                                            <i class="fal fa-address-card"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p class="dashboard__tips-content-big-gray">If you are not selling firearms you can skip this step.</p>
                                        <h3 class="dashboard__tips-content-big">STEP 2: Make onlie gun sales easy by configuring your FFL settings.</h3>
                                        <a class="btn btn-primary" href="/admin/configuration/sales/ffl">Add your FFL license</a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="add-product" role="tabpanel" aria-labelledby="add-product">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="dashboard__tips-content-icon d-flex justify-content-center align-items-center">
                                            <i class="fal fa-tags"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p class="dashboard__tips-content-big-gray">Your payment processing is set up, now the fun begins!</p>
                                        <h3 class="dashboard__tips-content-big">STEP 3" Begin adding products by clicking on the button below.</h3>
                                        <a class="btn btn-primary" href="{{route('admin.catalog.products.create')}}">Add A Product</a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-domain" role="tabpanel" aria-labelledby="custom-domain">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="dashboard__tips-content-icon d-flex justify-content-center align-items-center">
                                            <i class="fal fa-globe"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p class="dashboard__tips-content-big-gray">Last but not least!</p>
                                        @php $channel_id=core()->getCurrentChannel()->id;  @endphp
                                        <h3 class="dashboard__tips-content-big">Step 4: Now that you have set up your store you are ready to configure your custom domain and begin letting the world know about your brand! Don't have your own domain? Not a problem. You are welcome to continue using your 2A Gun Show domain.</h3>
                                        <a class="btn btn-primary" href="/admin/channels/edit/{{ $channel_id }}">Setup your custom domain</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row dashboard-stats">
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="dashboard__card-title">
                        {{ __('admin::app.dashboard.total-customers') }}
                    </div>
                    <div class="dashboard__card-data">
                        <div class="row">
                            <div class="col">
                                <p class="dashboard__card-data-value">{{ $statistics['total_customers']['current'] }}</p>
                            </div>
                            <div class="col col-md-auto">
                                <div class="dashboard__card-data-progress">
                                    @if ($statistics['total_customers']['progress'] < 0)
                                    <div class="dashboard__card-data-progress--down"><i class="far fa-arrow-down"></i>{{ __('admin::app.dashboard.decreased', ['progress' => -number_format($statistics['total_customers']['progress'], 1)])}}</div>
                                    @else
                                    <div class="dashboard__card-data-progress--up"><i class="far fa-arrow-up"></i>{{ __('admin::app.dashboard.increased', ['progress' => number_format($statistics['total_customers']['progress'], 1)])}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="dashboard__card-title">
                        {{ __('admin::app.dashboard.total-orders') }}
                    </div>
                    <div class="dashboard__card-data">
                        <div class="row">
                            <div class="col">
                                <p class="dashboard__card-data-value">{{ $statistics['total_orders']['current'] }}</p>
                            </div>
                            <div class="col col-md-auto">
                                <div class="dashboard__card-data-progress">

                                    @if ($statistics['total_orders']['progress'] < 0)
                                    <div class="dashboard__card-data-progress--down"><i class="far fa-arrow-down"></i>{{ __('admin::app.dashboard.decreased', ['progress' => -number_format($statistics['total_orders']['progress'], 1)])}}</div>
                                    @else
                                    <div class="dashboard__card-data-progress--up"><i class="far fa-arrow-up"></i>{{ __('admin::app.dashboard.increased', ['progress' => number_format($statistics['total_orders']['progress'], 1)])}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="dashboard__card-title">
                        {{ __('admin::app.dashboard.total-sale') }}
                    </div>
                    <div class="dashboard__card-data">
                        <div class="row">
                            <div class="col">
                                <p class="dashboard__card-data-value">{{ core()->formatBasePrice($statistics['total_sales']['current']) }}</p>
                            </div>
                            <div class="col col-md-auto">
                                <div class="dashboard__card-data-progress">
                                    @if ($statistics['total_sales']['progress'] < 0)
                                    <div class="dashboard__card-data-progress--down"><i class="far fa-arrow-down"></i>{{ __('admin::app.dashboard.decreased', ['progress' => -number_format($statistics['total_sales']['progress'], 1)])}}</div>
                                    @else
                                    <div class="dashboard__card-data-progress--up"><i class="far fa-arrow-up"></i>{{ __('admin::app.dashboard.increased', ['progress' => number_format($statistics['total_sales']['progress'], 1)])}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="dashboard__card-title">
                        {{ __('admin::app.dashboard.average-sale') }}
                    </div>
                    <div class="dashboard__card-data">
                        <div class="row">
                            <div class="col">
                                <p class="dashboard__card-data-value">{{ core()->formatBasePrice($statistics['avg_sales']['current']) }}</p>
                            </div>
                            <div class="col col-md-auto">
                                <div class="dashboard__card-data-progress">
                                    @if ($statistics['avg_sales']['progress'] < 0)
                                    <div class="dashboard__card-data-progress--down"><i class="far fa-arrow-down"></i>{{ __('admin::app.dashboard.decreased', ['progress' => -number_format($statistics['avg_sales']['progress'], 1)])}}</div>
                                    @else
                                    <div class="dashboard__card-data-progress--up"><i class="far fa-arrow-up"></i>{{ __('admin::app.dashboard.increased', ['progress' => number_format($statistics['avg_sales']['progress'], 1)])}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row graph-stats">
            <div class="col-lg-8 col-md-9">
                <div class="card" style="overflow: hidden;">
                    <div class="dashboard__card-title">
                        {{ __('admin::app.dashboard.sales') }}
                    </div>

                    <div class="card-info" style="height: 100%;">

                        <canvas id="myChart" style="width: 100%; height: 87%"></canvas>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-3">
                <div class="card">
                    <div class="dashboard__card-title">
                        {{ __('admin::app.dashboard.top-performing-categories') }}
                    </div>

                    <div class="card-info {{ !count($statistics['top_selling_categories']) ? 'center' : '' }}">
                        <ul>

                            @foreach ($statistics['top_selling_categories'] as $item)

                            <li>
                                <a href="{{ route('admin.catalog.categories.edit', $item->category_id) }}">
                                    <div class="description">
                                        <div class="name">
                                            {{ $item->name }}
                                        </div>

                                        <div class="info">
                                            {{ __('admin::app.dashboard.product-count', ['count' => $item->total_products]) }}
                                            &nbsp;.&nbsp;
                                            {{ __('admin::app.dashboard.sale-count', ['count' => $item->total_qty_invoiced]) }}
                                        </div>
                                    </div>

                                    <span class="icon angle-right-icon"></span>
                                </a>
                            </li>

                            @endforeach

                        </ul>

                        @if (! count($statistics['top_selling_categories']))

                        <div class="no-result-found">

                            <i class="icon no-result-icon"></i>
                            <p>{{ __('admin::app.common.no-result-found') }}</p>

                        </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>

        @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

        <div class="row sale-stock">
            <div class="col-md-6">
                <div class="card">
                    <div class="dashboard__card-title">
                        {{ __('admin::app.dashboard.top-selling-products') }}
                    </div>

                    <div class="card-info {{ !count($statistics['top_selling_products']) ? 'center' : '' }}">
                        <ul>

                            @foreach ($statistics['top_selling_products'] as $item)

                            <li>
                                <a href="{{ route('admin.catalog.products.edit', $item->product_id) }}">
                                    <div class="product image">
                                        <?php $productBaseImage = $productImageHelper->getProductBaseImage($item->product); ?>

                                        <img class="item-image" src="{{ $productBaseImage['small_image_url'] }}" />
                                    </div>

                                    <div class="description">
                                        <div class="name">
                                            @if (isset($item->name))
                                            {{ $item->name }}
                                            @endif
                                        </div>

                                        <div class="info">
                                            {{ __('admin::app.dashboard.sale-count', ['count' => $item->total_qty_invoiced]) }}
                                        </div>
                                    </div>

                                    <span class="icon angle-right-icon"></span>
                                </a>
                            </li>

                            @endforeach

                        </ul>

                        @if (! count($statistics['top_selling_products']))

                        <div class="no-result-found">

                            <i class="icon no-result-icon"></i>
                            <p>{{ __('admin::app.common.no-result-found') }}</p>

                        </div>

                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="dashboard__card-title">
                        {{ __('admin::app.dashboard.customer-with-most-sales') }}
                    </div>

                    <div class="card-info {{ !count($statistics['customer_with_most_sales']) ? 'center' : '' }}">
                        <ul>

                            @foreach ($statistics['customer_with_most_sales'] as $item)

                            <li>
                                @if ($item->customer_id)
                                <a href="{{ route('admin.customer.edit', $item->customer_id) }}">
                                    @endif

                                    <div class="image">
                                        <span class="icon profile-pic-icon"></span>
                                    </div>

                                    <div class="description">
                                        <div class="name">
                                            {{ $item->customer_full_name }}
                                        </div>

                                        <div class="info">
                                            {{ __('admin::app.dashboard.order-count', ['count' => $item->total_orders]) }}
                                            &nbsp;.&nbsp;
                                            {{ __('admin::app.dashboard.revenue', [
                                                'total' => core()->formatBasePrice($item->total_base_grand_total)
                                                ])
                                            }}
                                        </div>
                                    </div>

                                    <span class="icon angle-right-icon"></span>

                                    @if ($item->customer_id)
                                </a>
                                @endif
                            </li>

                            @endforeach

                        </ul>

                        @if (! count($statistics['customer_with_most_sales']))

                        <div class="no-result-found">

                            <i class="icon no-result-icon"></i>
                            <p>{{ __('admin::app.common.no-result-found') }}</p>

                        </div>

                        @endif
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="dashboard__card-title">
                        {{ __('admin::app.dashboard.stock-threshold') }}
                    </div>

                    <div class="card-info {{ !count($statistics['stock_threshold']) ? 'center' : '' }}">
                        <ul>

                            @foreach ($statistics['stock_threshold'] as $item)

                            <li>
                                <a href="{{ route('admin.catalog.products.edit', $item->product_id) }}">
                                    <div class="image">
                                        <?php $productBaseImage = $productImageHelper->getProductBaseImage($item->product); ?>

                                        <img class="item-image" src="{{ $productBaseImage['small_image_url'] }}" />
                                    </div>

                                    <div class="description">
                                        <div class="name">
                                            @if (isset($item->product->name))
                                            {{ $item->product->name }}
                                            @endif
                                        </div>

                                        <div class="info">
                                            {{ __('admin::app.dashboard.qty-left', ['qty' => $item->total_qty]) }}
                                        </div>
                                    </div>

                                    <span class="icon angle-right-icon"></span>
                                </a>
                            </li>

                            @endforeach

                        </ul>

                        @if (! count($statistics['stock_threshold']))

                        <div class="no-result-found">

                            <i class="icon no-result-icon"></i>
                            <p>{{ __('admin::app.common.no-result-found') }}</p>

                        </div>

                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@stop

@push('scripts')

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script type="text/x-template" id="date-filter-template">
    <div>
            <div class="control-group date">
                <date @onChange="applyFilter('start', $event)"><input type="text" class="control" id="start_date"
                                                                      value="{{ $startDate->format('Y-m-d') }}"
                                                                      placeholder="{{ __('admin::app.dashboard.from') }}"
                                                                      v-model="start"/></date>
            </div>

            <div class="control-group date">
                <date @onChange="applyFilter('end', $event)"><input type="text" class="control" id="end_date"
                                                                    value="{{ $endDate->format('Y-m-d') }}"
                                                                    placeholder="{{ __('admin::app.dashboard.to') }}"
                                                                    v-model="end"/></date>
        </div>
    </div>
</script>

<script>
    Vue.component('date-filter', {

        template: '#date-filter-template',

        data: function() {
            return {
                start: "{{ $startDate->format('Y-m-d') }}",
                end: "{{ $endDate->format('Y-m-d') }}",
            }
        },

        methods: {
            applyFilter: function(field, date) {
                this[field] = date;

                window.location.href = "?start=" + this.start + '&end=' + this.end;
            }
        }
    });

    $(document).ready(function() {
        $(document).on('click', '.tab', function(e) {
            $(".tips-tabs-container").addClass('dnone');
            var tab = $(this).data('tab');
            $('#' + tab).removeClass('dnone');
            $('.tab').removeClass('black_span');
            $(this).addClass('black_span')
        });
        $(document).on('click', '#close-tips', function(e) {
            $('.dashboard__tips').addClass('dnone');
        });
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
                        label: function(tooltipItem, dataTemp) {
                            return data['formated_total'][tooltipItem.index];
                        }
                    }
                }
            }
        });

    });
</script>

@endpush