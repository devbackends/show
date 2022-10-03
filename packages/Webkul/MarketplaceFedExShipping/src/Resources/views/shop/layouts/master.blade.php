@extends('shop::layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ bagisto_asset('css/marketplace.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ bagisto_asset('js/marketplace.js') }}"></script>
@endpush