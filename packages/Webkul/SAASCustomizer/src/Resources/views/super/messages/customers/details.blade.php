@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.configurations.title') }}
@stop

@section('content')

    <div class="content" style="height: 100%;">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    Message Details
                </h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content" style="height: 100%;">

            <div style="height: 100%;">
                <div class="messaging-thread" style="height: 100%;">
                    <div class="row" style="height: 100%;">
                        <div class="col-12" style="height: 100%;">
                            <div class="messaging-thread__subject">
                                <p>Subject: {{$message['subject']}}</p>
                            </div>
                            <div class="messaging-thread__chat d-flex flex-column">
                                <div id="message-details-body" class="messaging-thread__chat-body flex-grow-1">
                                    @php
                                        $from=$message['from'];
                                        $to=$message['to'];
                                        $initial_sender= app('Webkul\Customer\Repositories\CustomerRepository')->where('id', $from)->first();
                                        $initial_sender_name=$initial_sender->first_name.' '.$initial_sender->last_name;
                                        $initial_receiver= app('Webkul\Customer\Repositories\CustomerRepository')->where('id', $to)->first();
                                        $initial_receiver_name=$initial_receiver->first_name.' '.$initial_receiver->last_name;
                                        $i=0;
                                    @endphp
                                    @foreach($message['message_details'] as $message_detail)
                                        @if($message_detail['from']==$from)
                                            @php $message_status='out' @endphp
                                        @else
                                            @php $message_status='in' @endphp
                                        @endif
                                        @php
                                            if($message_detail['from']==$from){
                                                   $name=$initial_sender_name;
                                            }else{
                                                   $name=$initial_receiver_name;
                                           }
                                                $date=explode(' ',$message_detail['created_at'])[0];
                                                $old_date='';

                                                if($i > 0){
                                                $old_date=explode(' ',$message['message_details'][$i - 1]['created_at'])[0];

                                                }

                                        @endphp
                                        <div>
                                            <div class="messaging-thread__chat-date-separator">
                                                @if($date!=$old_date)
                                                    @php
                                                        $date = new DateTime($date);
                                                    @endphp
                                                    <p> {{ $date->format('M d, Y')  }} </p>
                                                @endif
                                            </div>
                                            <div
                                                class="messaging-thread__chat-message messaging-thread__chat-message--{{$message_status}} d-flex">

                                                <div
                                                    class="messaging-thread__chat-message-text"><span class="messaging-thread__chat-message-text-name">{{$name}} says:</span><br>{{$message_detail['body']}}</div>

                                            </div>
                                        </div>
                                        @php  $i+=1; @endphp
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

@stop

@push('scripts')

@endpush