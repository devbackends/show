@extends('admin::layouts.content')

@section('page_title')
    {{ __('marketplace::app.admin.user-help-requests.title') }}
@stop

@section('content')
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                    {{ __('marketplace::app.admin.user-help-requests.handle-title') }}
                </h1>
            </div>
        </div>

        <div class="page-content">

            <p>Name: {{$message->name}}</p>
            <p>Email: {{$message->email}}</p>
            <p>Text: {{$message->text}}</p>

            <form action="{{route('admin.user-help-requests.update', $message->id)}}" method="POST">
                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    <div class="form-group select">
                        <label for="flatrate_type">
                            {{ __('marketplace::app.admin.user-help-requests.status') }}
                        </label>
                        <select class="form-control js-example-basic-single" id="status" name="status" data-vv-as="&quot;{{ __('marketplace::app.admin.user-help-requests.status') }}&quot;">
                            <option value data-isdefault="true">- select -</option>
                            @foreach($statuses as $status)
                                <option value="{{$status}}" {{($message->status ?? '') == $status ? 'selected' : ''}}>
                                    {{__('marketplace::app.admin.user-help-requests.'.$status)}}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-lg">
                            {{ __('marketplace::app.admin.user-help-requests.save-btn-title') }}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@stop