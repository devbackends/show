@extends('admin::layouts.content')

@section('page_title')
    {{__('ffl::app.super.admin.upload_data_set.title')}}
@stop

@section('content')
    <div class="content">
        <form enctype="multipart/form-data" method="POST" action="{{route('ffl.store_data_set')}}">
            @csrf
        <div class="page-header">
            <div class="page-title">
                <h1>{{__('ffl::app.super.admin.upload_data_set.title')}}</h1>
            </div>
            <div class="page-action">
                    <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
        <div class="page-content">
            <div class="form-group">
                <label for="upload_data_set">{{__('ffl::app.super.admin.upload_data_set.title')}}</label>
                <input name="data" type="file" class="form-control-file" id="upload_data_set">
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    <h2>{{ session()->get('message') }}</h2>
                </div>
            @endif
        </div>
        </form>
    </div>
@endsection
