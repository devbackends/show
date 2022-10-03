@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.configuration.title') }}
@stop

@section('head')
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-xVVam1KS4+Qt2OrFa+VdRUoXygyKIuNWUUUBZYv+n27STsJ7oDOHJgfF0bNKLMJF" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('vendor/devvly/ffl/assets/css/fflonboarding.css')}}">
    <link rel="stylesheet" href="{{ asset('vendor/devvly/ffl/assets/css/admin.css') }}">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
@stop

@section('content')
    <ffl-form :company_domain="'{{core()->getCurrentChannel()->hostname}}'" :ffl="{{json_encode($ffl)}}"
              :check_enabled_url="'{{route('ffl.admin.disable')}}'" :api_url="'{{route('ffl.onboarding.form.store')}}'">
    </ffl-form>
@endsection
@push('scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <script src="{{asset('vendor/devvly/ffl/assets/js/admin.js')}}" type="text/javascript"></script>
@endpush
