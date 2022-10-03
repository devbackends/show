@extends('admin::layouts.content')

@section('page_title')
    {{'Review'}}
@stop

@section('content')
    <div class="content">
        <div class="page-content">
            {!! app('Devvly\Ffl\DataGrid\FflGrid')->render() !!}
        </div>
    </div>
@endsection
