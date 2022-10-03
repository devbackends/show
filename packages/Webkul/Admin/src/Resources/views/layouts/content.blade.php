@extends('admin::layouts.master')

@section('content-wrapper')
    <div class="inner-section">

        @if (!isset($flag))
            @include ('admin::layouts.nav-aside')
        @elseif ($flag = 1)
            @include('admin::layouts.custom-nav-aside')
        @endif

        <div class="content-wrapper">

            @if (!isset($flag))
                @include ('admin::layouts.tabs')
            @elseif ($flag = 1)
                @include('admin::layouts.custom-tabs')
            @endif

            @yield('content')

        </div>

    </div>
@stop