@extends("ffl::fflonboarding.layouts.master")
@section('navbar')
    @include('ffl::fflonboarding.layouts.nav')
@endsection
@section('content')
    <div class="container mb-5">
        <div class="row">
        <form ref="form" data-url="{{route('ffl.onboarding.form.store')}}" id="ffl_form" enctype="multipart/form-data">
            @csrf
            @include('ffl::fflonboarding.steps.business_information')
            @include('ffl::fflonboarding.steps.license')
            @include('ffl::fflonboarding.steps.transfer_fees')
        </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('vendor/devvly/ffl/assets/js/app.js')}}" type="text/javascript"></script>
@endpush
