<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        <title>2A Gun Show |
        @hasSection('page_title')
        @yield('page_title')
        @else
        The worlds largest marketplace for buyers and sellers in the shooting sports industry!
        @endif
        </title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="google-site-verification" content="nYbc0piqE2ubxr6LAfVqYeroWCBEf0am-bR-6K5XuL4" />
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-xVVam1KS4+Qt2OrFa+VdRUoXygyKIuNWUUUBZYv+n27STsJ7oDOHJgfF0bNKLMJF" crossorigin="anonymous">
        <!-- FlatPickr -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <!-- END FlatPickr -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="{{ asset('themes/market/assets/css/market.css') }}" />

        @if (core()->getCurrentLocale()->direction == 'rtl')

        @endif

        @if ($favicon = core()->getCurrentChannel()->favicon_url)
            <link rel="icon" sizes="16x16" href="{{ $favicon }}" />
        @else
            <link rel="icon" sizes="16x16" href="{{ asset('images/favicon.svg') }}" />
        @endif

        <!-- jquery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <!-- END jquery -->

        <script baseUrl="{{ url()->to('/') }}" src="{{ asset('themes/market/assets/js/marketplace.js') }}"></script>


        @yield('head')

        @section('seo')
            <meta name="description" content="{{ core()->getCurrentChannel()->description }}" />
        @show
        @stack('css')

        {!! view_render_event('bagisto.shop.layout.head') !!}

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
          new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
          j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
          'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
          })(window,document,'script','dataLayer','GTM-K34N5RV');</script>
        <!-- End Google Tag Manager -->

        <!-- Hotjar Tracking Code for http://www.2agunshow.com -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:2593081,hjsv:6};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>

        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '1495486543978336');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=1495486543978336&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code -->

    </head>

<body @if (core()->getCurrentLocale()->direction == 'rtl') class="rtl" @endif @if(str_contains(url()->current(), '/gun-giveaway')) class="body-gun-giveaway" @endif>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K34N5RV"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
      <!-- End Google Tag Manager (noscript) -->

    {!! view_render_event('bagisto.shop.layout.body.before') !!}

    @include('shop::UI.particals')

    <div id="app">
        <div class="main-content-wrapper">

            @section('body-header')
                @if(Route::currentRouteName() !== 'shop.checkout.onepage.index')
                    <!-- Site wide announcement -->
                    <!-- @if (!str_contains(url()->current(), '/customer') && !str_contains(url()->current(), '/marketplace/account'))
                        <div class="d-none d-md-block">
                        @include('shop::layouts.header.site-wide-announcement')
                        </div>
                    @endif -->
                    <!-- END Site wide announcement -->
                    @include('shop::layouts.top-nav.index')
                    @include('shop::layouts.header.index')
                @endif

                <div class="sidebar__wrapper">

                    @php
                        $velocityContent = app('Webkul\Velocity\Repositories\ContentRepository')->getAllContents();
                    @endphp

                    @if(Route::currentRouteName() !== 'shop.checkout.onepage.index')
                        <content-header
                            url="{{ url()->to('/') }}"
                            :header-content="{{ json_encode($velocityContent) }}"
                        ></content-header>
                    @endif

                    <categories-menu-component
                        main-sidebar=true
                        id="sidebar-level-0"
                        url="{{ url()->to('/') }}"
                        category-count="{{ $velocityMetaData ? $velocityMetaData->sidebar_category_count : 20 }}"
                        add-class="sidebar__categories category-list-container pt10">
                    </categories-menu-component>

                    @include('shop::UI.header')
                    @yield('content-wrapper')
                </div>
            @show
        </div>
    </div>

    @section('footer')
    {!! view_render_event('bagisto.shop.layout.footer.before') !!}

    @include('shop::layouts.footer.index')

    {!! view_render_event('bagisto.shop.layout.footer.after') !!}
    @show

    {!! view_render_event('bagisto.shop.layout.body.after') !!}

    <div id="alert-container"></div>

    <!-- Session About to expire Modal  -->
    <div class="modal fade" id="sessionAboutToExpireModal" tabindex="-1" aria-labelledby="sessionAboutToExpireModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <div class="modal-header-content">
                <i class="fal fa-hourglass-end"></i>
                <h5 class="modal-title" id="sessionAboutToExpireModalLabel">Your session is about to expire</h5>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body modal-body-center">
            <p>Your session will expire in 5 minutes, please save your work or reload the page.</p>
          </div>
          <div class="modal-footer">
            <button type="button" onClick="window.location.reload();" class="btn btn-outline-gray-dark" data-dismiss="modal">Reload the page</button>
            <a type="button" href="/customer/login" data-dismiss="modal" class="btn btn-primary">Got it</a>
          </div>
        </div>
      </div>
    </div>
    <!-- END Session About to expire Modal  -->

    <!-- Session Expired Modal  -->
    <div class="modal fade" id="sessionExpiredModal" tabindex="-1" aria-labelledby="sessionExpiredModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <div class="modal-header-content">
                <i class="fal fa-hourglass-end"></i>
                <h5 class="modal-title" id="sessionExpiredModalLabel">Your session has expired</h5>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body modal-body-center">
            <p>Please login again or refresh the page.</p>
          </div>
          <div class="modal-footer">
            <button type="button" onClick="window.location.reload();" class="btn btn-outline-gray-dark" data-dismiss="modal">Reload the page</button>
            <a type="button" href="/customer/login" class="btn btn-primary">Login</a>
          </div>
        </div>
      </div>
    </div>
    <!-- END Session Expired Modal  -->

    <script type="text/javascript" src="https://cdn.rawgit.com/igorlino/elevatezoom-plus/1.1.6/src/jquery.ez-plus.js"></script>
    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <script>
        setTimeout(() => {
            $('#sessionAboutToExpireModal').modal('show');
        }, 1000 * 60 * 55)
        setTimeout(() => {
            $('#sessionAboutToExpireModal').modal('hide');
            $('#sessionExpiredModal').modal('show');
        }, 1000 * 60 * 60)
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $("[data-toggle=popover]").popover();
        })
    </script>

    <!-- End Bootstrap js -->

    <script baseUrl="{{ url()->to('/') }}" src="{{ asset('themes/market/assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
            $('.js-example-basic-multiple').select2();
            // toggle user nav
            $('.js-toggle-user-nav').on('click', function(e) {
                e.preventDefault();
                var $this = $(this);

                $('.js-user-nav').toggleClass('active');

                $(document).on('click', function(e) {
                if ($(e.target).closest('.customer-sidebar').length == 0 && $(e.target).closest('.js-toggle-user-nav').length == 0) {
                    $('.js-user-nav').removeClass('active');
                }
                });
            });
        });
    </script>

    <script type="text/javascript">
        (() => {
            window.showAlert = (messageType, messageLabel, message) => {
                if (messageType && message !== '') {
                    let alertId = Math.floor(Math.random() * 1000);

                    let html = `<div class="alert ${messageType} alert-dismissible" id="${alertId}">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close"><i class="far fa-times"></i></a>
                            <strong>${messageLabel ? messageLabel + '!' : ''} </strong> ${message}.
                            <div class="alert__icon"><i class="far ${messageType == 'alert-success' ? 'fa-check-circle' : messageType == 'alert-danger' ? 'fa-exclamation-circle' : 'fa-info-circle' }"></i></div>
                        </div>`;

                    $('#alert-container').append(html).ready(() => {
                        window.setTimeout(() => {
                            $(`#alert-container #${alertId}`).remove();
                        }, 10000);
                    });
                }
            }

            let messageType = '';
            let messageLabel = '';

            @if ($message = session('success'))
                messageType = 'alert-success';
            messageLabel = "{{ __('velocity::app.shop.general.alert.success') }}";
            @elseif ($message = session('warning'))
                messageType = 'alert-warning';
            messageLabel = "{{ __('velocity::app.shop.general.alert.warning') }}";
            @elseif ($message = session('error'))
                messageType = 'alert-danger';
            messageLabel = "{{ __('velocity::app.shop.general.alert.error') }}";
            @elseif ($message = session('info'))
                messageType = 'alert-info';
            messageLabel = "{{ __('velocity::app.shop.general.alert.info') }}";
            @endif

            if (messageType && '{{ $message }}' !== '') {
                window.showAlert(messageType, messageLabel, '{{ $message }}');
            }

            window.serverErrors = [];
            @if (isset($errors))
                @if (count($errors))
                window.serverErrors = @json($errors->getMessages());
            @endif
                @endif

                window._translations = @json(app('Webkul\Velocity\Helpers\Helper')->jsonTranslations());
        })();
    </script>
    @if(Request::path() != 'gun-giveaway')
      <script type="text/javascript" id="zsiqchat">var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode: "28555913f1419a4568e23eff67b6896c02fa6a173b024d0be13bf9c6143cb0d7", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);</script>
    @endif
    <script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>
    @stack('scripts')


</body>

</html>