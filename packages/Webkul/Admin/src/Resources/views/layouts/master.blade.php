<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <title>@yield('page_title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" sizes="16x16" href="{{ asset('images/favicon.ico') }}"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-xVVam1KS4+Qt2OrFa+VdRUoXygyKIuNWUUUBZYv+n27STsJ7oDOHJgfF0bNKLMJF" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('vendor/webkul/ui/assets/css/ui.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/webkul/admin/assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/devvly/customblocks/assets/css/app.css') }}">

@yield('head')

@yield('css')
{!! view_render_event('bagisto.admin.layout.head') !!}

<!-- <link rel="stylesheet" href="{{ asset('css/custom.css') }}"> -->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="{{ asset('vendor/webkul/admin/assets/css/custom-admin.css') }}">

</head>

<body @if (core()->getCurrentLocale() && core()->getCurrentLocale()->direction == 'rtl') class="rtl"
      @endif style="scroll-behavior: smooth;">
{!! view_render_event('bagisto.admin.layout.body.before') !!}

<div id="app">

    <flash-wrapper ref='flashes'></flash-wrapper>

    {!! view_render_event('bagisto.admin.layout.nav-top.before') !!}

    @include ('admin::layouts.nav-top')

    {!! view_render_event('bagisto.admin.layout.nav-top.after') !!}


    {!! view_render_event('bagisto.admin.layout.nav-left.before') !!}

    @include ('admin::layouts.nav-left')

    {!! view_render_event('bagisto.admin.layout.nav-left.after') !!}


    <div class="content-container {{get_app_class_prefix()}}">

        {!! view_render_event('bagisto.admin.layout.content.before') !!}

        @yield('content-wrapper')

        {!! view_render_event('bagisto.admin.layout.content.after') !!}

    </div>

</div>
<script src="https://unpkg.com/react@16.8.6/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@16.8.6/umd/react-dom.production.min.js"></script>

<script type="text/javascript">
    window.flashMessages = [];

    @foreach (['success', 'warning', 'error', 'info'] as $key)
    @if ($value = session($key))
    window.flashMessages.push({'type': 'alert-{{ $key }}', 'message': "{{ $value }}"});
    @endif
            @endforeach

        window.serverErrors = [];
    @if (isset($errors))
            @if (count($errors))
        window.serverErrors = @json($errors->getMessages());
    @endif
    @endif
</script>

<script type="text/javascript" src="{{ asset('vendor/webkul/admin/assets/js/admin.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

<script type="text/javascript">
    window.addEventListener('DOMContentLoaded', function () {
        moveDown = 60;
        moveUp = -60;
        count = 0;
        countKeyUp = 0;
        pageDown = 60;
        pageUp = -60;
        scroll = 0;
        let LarabergCategoryTitle = 'Custom blocks';
        let LarabergCategorySlug = 'custom-blocks';
        Laraberg.registerCategory(LarabergCategoryTitle, LarabergCategorySlug);

        const featuredProductsCustomBlock = {
            title: 'Featured Products',
            icon: 'products',
            category: 'custom-blocks',

            edit() {
                return "* Featured Products *";
            },

            save() {
                return "[featured_products]";
            }
        };

        const newProductCustomBlock = {
            title: 'New Products',
            icon: 'products',
            category: 'custom-blocks',

            edit(){
                return "* New Products *";
            },

            save() {
                return "[new_products]";
            }
        };

        const recentlyViewedCustomBlock = {
            title: 'Recently Viewed Products',
            icon: 'products',
            category: 'custom-blocks',

            edit() {
                return "* Recently Viewed Products *";
            },

            save() {
                return "[recently_viewed_products]";
            }
        };

        const productPolicyCustomBlock = {
            title: 'Product Policy',
            icon: 'products',
            category: 'custom-blocks',

            edit() {
                return "* Product Policy *";
            },

            save() {
                return "[product_policy]";
            }
        };

        Laraberg.registerBlock('custom-blocks/featured-products', featuredProductsCustomBlock);
        Laraberg.registerBlock('custom-blocks/new-products', newProductCustomBlock);
        Laraberg.registerBlock('custom-blocks/recently-viewed', recentlyViewedCustomBlock);
        Laraberg.registerBlock('custom-blocks/product-policy', productPolicyCustomBlock);

        listLastElement = $('.menubar li:last-child').offset();

        if (listLastElement) {
            lastElementOfNavBar = listLastElement.top;
        }

        navbarTop = $('.navbar-left').css("top");
        menuTopValue = $('.navbar-left').css('top');
        menubarTopValue = menuTopValue;

        documentHeight = $(document).height();
        menubarHeight = $('ul.menubar').height();
        navbarHeight = $('.navbar-left').height();
        windowHeight = $(window).height();
        contentHeight = $('.content').height();
        innerSectionHeight = $('.inner-section').height();
        gridHeight = $('.grid-container').height();
        pageContentHeight = $('.page-content').height();

        if (menubarHeight <= windowHeight) {
            differenceInHeight = windowHeight - menubarHeight;
        } else {
            differenceInHeight = menubarHeight - windowHeight;
        }

        if (menubarHeight > windowHeight) {
            document.addEventListener("keydown", function (event) {
                if ((event.keyCode == 38) && count <= 0) {
                    count = count + moveDown;

                    $('.navbar-left').css("top", count + "px");
                } else if ((event.keyCode == 40) && count >= -differenceInHeight) {
                    count = count + moveUp;

                    $('.navbar-left').css("top", count + "px");
                } else if ((event.keyCode == 33) && countKeyUp <= 0) {
                    countKeyUp = countKeyUp + pageDown;

                    $('.navbar-left').css("top", countKeyUp + "px");
                } else if ((event.keyCode == 34) && countKeyUp >= -differenceInHeight) {
                    countKeyUp = countKeyUp + pageUp;

                    $('.navbar-left').css("top", countKeyUp + "px");
                } else {
                    $('.navbar-left').css("position", "fixed");
                }
            });

            $("body").css({minHeight: $(".menubar").outerHeight() + 100 + "px"});

            window.addEventListener('scroll', function () {
                documentScrollWhenScrolled = $(document).scrollTop();

                if (documentScrollWhenScrolled <= differenceInHeight + 200) {
                    $('.navbar-left').css('top', -documentScrollWhenScrolled + 60 + 'px');
                    scrollTopValueWhenNavBarFixed = $(document).scrollTop();
                }
            });
        }
    });
</script>
<script src="{{ asset('vendor/devvly/customblocks/assets/js/app.js') }}"></script>


{!! view_render_event('bagisto.admin.layout.body.after') !!}


@stack('scripts')
<div class="modal-overlay"></div>
</body>
</html>
