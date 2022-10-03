<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <title>@yield('page_title')</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-xVVam1KS4+Qt2OrFa+VdRUoXygyKIuNWUUUBZYv+n27STsJ7oDOHJgfF0bNKLMJF" crossorigin="anonymous">
        @php
            $channel = core()->getCurrentChannel();
        @endphp
        @if ( $channel && $channel->favicon_url)
            <link rel="icon" sizes="16x16" href="{{ $channel->favicon_url }}" />
        @else
            <link rel="icon" sizes="16x16" href="{{ asset('images/favicon.ico') }}" />
        @endif

        <link rel="stylesheet" href="{{ asset('vendor/webkul/ui/assets/css/ui.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/webkul/saas/assets/css/tenant.css') }}">

        @yield('css')

        {!! view_render_event('bagisto.saas.companies.layout.head') !!}
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    </head>
    @php
        $locale = core()->getCurrentLocale();
    @endphp
    <body @if ( isset($locale->direction) && $locale->direction == 'rtl') class="rtl" @endif style="scroll-behavior: smooth;">
        <div id="app">
            {!! view_render_event('bagisto.saas.companies.body.before') !!}

            <flash-wrapper ref='flashes'></flash-wrapper>

            <div class="main-container-wrapper {{get_app_class_prefix()}}">
                @include ('saas::companies.layouts.nav-top')

                <div class="content-container">
                    @yield('content-wrapper')
                </div>
            </div>

            {!! view_render_event('bagisto.saas.companies.layout.footer.before') !!}

            @include('saas::companies.layouts.footer.footer')

            {!! view_render_event('bagisto.saas.companies.layout.footer.after') !!}

            @php($config = \Webkul\SAASCustomizer\Models\SuperConfig::query()->firstWhere('code', 'general.content.footer.footer_toggle'))
            @if ($config && $config->value)
            <div class="footer">
                <p style="text-align: center;">
                    @if (\Webkul\SAASCustomizer\Models\SuperConfig::query()->firstWhere('code', 'general.content.footer.footer_content')->value)
                        {{\Webkul\SAASCustomizer\Models\SuperConfig::query()->firstWhere('code', 'general.content.footer.footer_content')->value}}
                    @else
                        {!! trans('admin::app.footer.copy-right') !!}
                    @endif
                </p>
            </div>
        @endif
        </div>

        <script type="text/javascript">
            window.flashMessages = [];

            @if ($success = session('success'))
                window.flashMessages = [{'type': 'alert-success', 'message': "{{ $success }}" }];
            @elseif ($warning = session('warning'))
                window.flashMessages = [{'type': 'alert-warning', 'message': "{{ $warning }}" }];
            @elseif ($error = session('error'))
                window.flashMessages = [{'type': 'alert-error', 'message': "{{ $error }}" }];
            @elseif ($info = session('info'))
                window.flashMessages = [{'type': 'alert-error', 'message': "{{ $info }}" }];
            @endif

            window.serverErrors = [];

            @if (isset($errors))
                @if (count($errors))
                    window.serverErrors = @json($errors->getMessages());
                @endif
            @endif
        </script>

        <script type="text/javascript" src="{{ asset('vendor/webkul/admin/assets/js/admin.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>

        @stack('scripts')

        <div class="modal-overlay"></div>

        {!! view_render_event('bagisto.saas.companies.body.after') !!}
    </body>
</html>