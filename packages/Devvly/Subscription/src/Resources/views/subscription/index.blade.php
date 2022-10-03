@extends('admin::layouts.content')

@section('page_title')
    {{ __('subscription::app.subscription.subscription') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('subscription::app.subscription.subscription') }}</h1>
            </div>
            <div class="page-action">

            </div>
        </div>

        <div class="page-content">
            <div class="dashboard">
                <div class="card">
                  <div class="card-title">Current Subscription</div>
                  <div class="card-info">
                    @if($current_plan->isValid)
                      <p><b>Plan Type:</b> {{$current_plan->frequency}}</p>
                      <p><b>Start Date:</b> {{$current_plan->pivot->start_date}}</p>
                      <p><b>End Date:</b> {{$current_plan->pivot->end_date}}</p>
                      <p><b>Next Billing Date:</b> {{$next_billing}}</p>
                    @else
                      <p class="text-center">Your subscription is not ready yet</p>
                      <p class="text-center">If you didn't complete the Clearent on-boarding application, please do, if you did, it might take few days for your subscription to become active.</p>
                    @endif
                  </div>
                </div>
            </div>
        </div>
    </div>
@stop