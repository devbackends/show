@extends('shop::layouts.master')

@section('page_title')
    {{ $post->title  }}
@endsection
@section('head')
    <meta name="title" content="{{ __('blog::app.general.blog')  }}" />
@endsection
@push('css')
    <link rel="stylesheet" href="{{ bagisto_asset('vendor/devvly/blog/assets/css/app.css') }}">
@endpush
@section('content-wrapper')
    <div class="view-page">
        <div class="container" style="padding-top:60px;padding-bottom:60px;">
            <div class="row blog-post__title-wrapper">
                <div class="col-md-4 blog-post__title mb-4 mb-md-0">
                @if($display_back)
                    <a class="d-inline-block mb-5" href="{{ \Illuminate\Support\Facades\URL::previous() }}">
                            <span class="back-icon">
                                <i class="far fa-angle-left"></i>
                            </span>
                        {{ __('blog::app.general.back') }}
                    </a>
                @endif
                    <p class="date">{{ $post->created_at->isoFormat('MMMM Do, YYYY') }}</p>
                    <h1 class="page-title">{{ $post->title }}</h1>
                    <div class="tags">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.front.post.tag', ['slug' => $tag->url_key]) }}" class="tag-item">{{$tag->name}}</a>
                        @endforeach
                    </div>
                    <div class="share">
                        <p>{{ __('blog::app.general.share-on') }}</p>
                        <div class="share-networks">
                            <share network="facebook"
                                   title="{{$post->title}}"
                                   url="{{ \Illuminate\Support\Facades\Route::current()->uri }}"
                                   description="{{ $post->summary }}"
                                   img_src="{{ bagisto_asset('vendor/devvly/blog/assets/css/images/facebook-icon.svg') }}"
                                   img_alt="{{ __('blog::app.general.share-on-alt', ['network' => 'Facebook']) }}">
                            </share>
                            <share network="twitter"
                                   title="{{$post->title}}"
                                   url="{{ \Illuminate\Support\Facades\Route::current()->uri }}"
                                   description="{{ $post->summary }}"
                                   img_src="{{ bagisto_asset('vendor/devvly/blog/assets/css/images/twitter-icon.svg') }}"
                                   img_alt="{{ __('blog::app.general.share-on-alt', ['network' => 'Twitter']) }}">
                            </share>
                            <share network="linkedin"
                                   title="{{$post->title}}"
                                   url="{{ \Illuminate\Support\Facades\Route::current()->uri }}"
                                   description="{{ $post->summary }}"
                                   img_src="{{ bagisto_asset('vendor/devvly/blog/assets/css/images/linkedin.svg') }}"
                                   img_alt="{{ __('blog::app.general.share-on-alt', ['network' => 'LinkedIn']) }}">
                            </share>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="post-img">
                                @if($post->image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($post->image) }}" alt="image">
                                @elseif($post->image_link)
                                    <img src="{{ $post->image_link }}" alt="image">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            {!! DbView::make($post)->field('content')->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('vendor/devvly/blog/assets/js/app.js') }}"></script>
    <script type="text/x-template" id="networks-template">
        <ShareNetwork
            :network="network"
            :key="network"
            :title="title"
            :url="url"
            :description="description">
            <span><img :src="img_src" :alt="img_alt"></span>
        </ShareNetwork>
    </script>

    <script>
        Vue.component('share', {
            template: '#networks-template',
            props: ['network', 'title', 'url', 'description', 'img_src', 'img_alt'],
        });
    </script>
@endpush