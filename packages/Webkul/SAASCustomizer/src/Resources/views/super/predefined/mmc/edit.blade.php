@extends('saas::super.layouts.content')

@section('page_title')
   Update MMC
@endsection

@section('content')

    <mmc-registration></mmc-registration>

    @push('scripts')
        <script type="text/x-template" id ="mmc-details-form">

            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link" ></i>
                            Update MMC
                        </h1>
                    </div>
                </div>

                <div class="page-content">
                    <form method="POST" action="{{ route('super.predefined.mmc.store', $mmc->id) }}">
                        @csrf
                        <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                            <label for="code" class="required">Code</label>
                            <input type="text" v-validate="'required'" class="control" name="code" v-model="code" placeholder="code" data-vv-as="&quot;Code&quot;">
                            <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                        </div>
                        <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                            <label for="name" class="required">Name</label>
                            <input type="text" v-model="name"  class="control"  placeholder="name" name="name" data-vv-as="&quot; Name &quot;"  required>
                            <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                            <label for="status" class="required">status</label>

                            <select class="control" v-model="status" name="status"  v-validate="'required'" >
                                <option value="0"  >{{ __('saas::app.super-user.tenants.deactivate') }}</option>
                                <option value="1"  >{{ __('saas::app.super-user.tenants.activate') }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>

                        </div>

                        <button  class="btn btn-primary">
                            {{ __('saas::app.super-user.tenants.btn-update') }}
                        </button>
                    </form>
                </div>
            </div>
            </script>
            <script>
                Vue.component('mmc-registration', {
                    template: '#mmc-details-form',
                    inject: ['$validator'],

                    data: () => ({
                    code: '{{ $mmc->code }}',
                    name: '{{ $mmc->name }}',
                    status: '{{ $mmc->status }}'
                }),

                    mounted: function () {
                    @if ($mmc->status)
                        this.status = 1;
                    @else
                        this.status = 0;
                    @endif
                }
                });
            </script>
    @endpush
@endsection