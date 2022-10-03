@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.configuration.title') }}
@stop

@section('head')
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-xVVam1KS4+Qt2OrFa+VdRUoXygyKIuNWUUUBZYv+n27STsJ7oDOHJgfF0bNKLMJF" crossorigin="anonymous">
@stop

@section('content')
    <div class="content">
        <?php $locale = request()->get('locale') ?: app()->getLocale(); ?>
        <?php $channel = request()->get('channel') ?: core()->getDefaultChannelCode(); ?>

        <?php
            $showAll = (request()->has('showall') && request()->get('showall') === 'true') ? '?showall=true' : ''
        ?>

        <form method="POST" action="{{\Illuminate\Support\Facades\URL::current() . $showAll}}" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">

                <div class="page-title">
                    <h3>
                        {{ __('admin::app.configuration.title') }}
                    </h3>

                    <div class="control-group">
                        <select class="control" id="channel-switcher" name="channel">
                            @foreach (core()->getAllChannels() as $channelModel)

                                <option
                                    value="{{ $channelModel->code }}" {{ ($channelModel->code) == $channel ? 'selected' : '' }}>
                                    {{ $channelModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" name="locale">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option
                                    value="{{ $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('admin::app.configuration.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    @if(request()->route('slug2') == 'paymentmethods')
                        <div id="configure-payment-processing-container">
                            <div class="width-100 h-overflow clearant-payment-container">

                                <div class="payment-first-container padding-sides-45 ">
                                    <span class="icont brown-triangle triangle-position"></span>
                                    <div class="padding-tb-20">
                                        <span class="icon commerce-icon"></span>
                                    </div>
                                    <div class="a-commerce-container padding-tb-20">
                                        <span><strong>2A COMMERCE</strong> is second amendment friendly payment processing that gives you the freedom you need</span>
                                    </div>
                                    <div class="padding-tb-10">
                                        <h2 class="heading no-margin success-color"> $39 <span><i
                                                    class="far fa-1x fa-check"></i></span></h2>
                                        <h4 class="heading no-margin success-color"> Monthly subscription </h4>
                                        <h4 class="heading no-margin success-color regular-font"> (Already
                                            configured) </h4>
                                    </div>
                                    <div class="padding-tb-10">
                                        <h2 class="heading no-margin info-dark"> 2.9% </h2>
                                        <h4 class="heading no-margin info-dark"> Credit card processing </h4>
                                    </div>
                                    <div class="padding-tb-10">
                                        <h2 class="heading no-margin info-dark"> $0.30 </h2>
                                        <h4 class="heading no-margin info-dark"> Transaction Fee </h4>
                                    </div>

                                </div>
                                <div class="payment-second-container padding-45">
                                    <div class="inner-configure-payment">
                                        <div class="align-center padding-tb-10">
                                            <span><i class="far fa-3x fa-check-circle info-dark"></i></span>
                                        </div>
                                        <div class="padding-tb-10">
                                            <p class="paragraph-big bold  align-center no-margin">Your monthly
                                                subscription is all set!</p>
                                            <p class="paragraph align-center  no-margin">The last thing you need to do
                                                is set up your payment processing to receive payments through your
                                                store. </p>
                                        </div>
                                        <div class="padding-tb-10 align-center">
                                            <a href="{{route('admin.configuration.index',['slug' => 'sales', 'slug2' => 'paymentmethods', 'payment_processing' => 'clearent'])}}"
                                               class="btn btn-primary" id="configure-payment-processing">
                                                Configure payment processing
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <div class="payment-powered-container">
                                    <span class="s-paragraph payment-powered-position">Powered By</span><span
                                        class="icon clearant-icon"></span>
                                </div>
                            </div>
                            <div class="align-center padding-45">
                                <p class="paragraph-big  no-margin padding-tb-5">Or select your existing payment service
                                    from the list below</p>
                                <p class="paragrpah no-margin padding-tb-5">(Users who choose external payment services
                                    are billed a 2% transaction fee + your $39 monthly subscription fee.)</p>
                            </div>

                        </div>





                    @endif
                    @if ($groups = \Illuminate\Support\Arr::get($config->items, request()->route('slug') . '.children.' . request()->route('slug2') . '.children'))
                        @php $accordionActive='true'; @endphp
                        @if(count($groups) > 1)
                            @php $accordionActive='false'; @endphp
                        @endif

                        @foreach ($groups as $key => $item)
                            @php $itemName=explode('::',$item['name']);   @endphp
                            <accordian :title="'{{ __($item['name']) }}'" :active="{{$accordionActive}}">
                                <div slot="body">

                                    @foreach ($item['fields'] as $field)

                                        @include ('admin::configuration.field-type', ['field' => $field])

                                        @php ($hint = $field['title'] . '-hint')
                                        @if ($hint !== __($hint))
                                            {{ __($hint) }}
                                        @endif

                                    @endforeach

                                </div>
                            </accordian>
                        @endforeach

                    @endif

                </div>
            </div>

        </form>
    </div>
@stop

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#channel-switcher, #locale-switcher').on('change', function (e) {
                $('#channel-switcher').val()
                var query = '?channel=' + $('#channel-switcher').val() + '&locale=' + $('#locale-switcher').val();

                window.location.href = "{{ route('admin.configuration.index', [request()->route('slug'), request()->route('slug2')]) }}" + query;
            });

        });
    </script>
@endpush