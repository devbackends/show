@extends('shop::layouts.master')
@section('page_title')
    {{ $page->page_title }}
@endsection

@section('head')
    @isset($page->meta_title)
        <meta name="title" content="{{ $page->meta_title }}" />
    @endisset

    @isset($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}" />
    @endisset

    @isset($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}" />
    @endisset
@endsection

@section('content-wrapper')
    @if($page->path_hero_image)
        <div class="row">
            <div class="col-md-12">
                @if($page->hero_image_link)
                    <a href="{{$page->hero_image_link}}" target="_blank" class="hero-image">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($page->path_hero_image) }}" alt="">
                    </a>
                @else
                    <div class="hero-image">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($page->path_hero_image) }}" alt="">
                    </div>
                @endif
            </div>
        </div>
    @endif
    <div class="container cms-page-container py-5">
        <div id="lb_content">
            {!! DbView::make($page)->field('laraberg')->render() !!}
        </div>
    </div>
@endsection