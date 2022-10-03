<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <title>On Boarding</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" sizes="16x16" href="{{ asset('images/favicon.ico') }}" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="{{ asset('vendor/webkul/ui/assets/css/ui.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('vendor/devvly/ffl/assets/css/fflonboarding.css') }}">
</head>

<body>
    <div id="ffl-root" class="fflonboarding container-fluid">
        @section('header')
        <div class="row header">
            <div class="col-12">
                <div class="container header__content">
                    <div class="navbar-brand">
                        <a href="/">
                            <img src="{{asset('images/ffllogo.svg')}}" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @show
        @section('navbar')
        @show
        @section('content')
        @show
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    @stack('js')
</body>

</html>