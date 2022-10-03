@extends('shop::layouts.master')

@section('page_title')
    {{ __('blog::app.general.blog')  }}
@endsection
@section('head')
    <meta name="title" content="{{ __('blog::app.general.blog')  }}" />
@endsection
@push('css')
    <link rel="stylesheet" href="{{ bagisto_asset('vendor/devvly/blog/assets/css/app.css') }}">
@endpush
@section('content-wrapper')
    <div class="container" style="padding-top:60px;padding-bottom:60px;">
        <div class="row mx-n1 posts">
            <div class="col-12">
                <div class="row">
                    <div class="col-10">
                        <h1 class="page-title">{{ __('blog::app.general.blog') }}</h1>
                    </div>
                    <div class="col-2">
                        <select name="post_filter" id="post_filter" class="form-control">
                            <option value="{{ route('blog.front.post.index') }}">{{ __('blog::app.general.all') }}</option>
                            @foreach($categories as $category)
                                <option {{\Illuminate\Support\Facades\URL::current() === route('blog.front.post.category', ['slug' => $category->url_key])? "selected": ""}}
                                        value="{{ route('blog.front.post.category', ['slug' => $category->url_key]) }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @foreach($posts as $post)
                <div class="col-md-4 col-sm-12 col-xs-12 post-item__card">
                    <div class="post-item card">
                        <div class="post-item__image card-img-top">
                            <a title="{{$post->title}}" href="{{ route('blog.front.post.view', ['slug' => $post->url_key]) }}">
                                @if($post->image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($post->image) }}" alt="image">
                                @elseif($post->image_link)
                                    <img src="{{ $post->image_link }}" alt="image">
                                @endif
                            </a>
                        </div>
                        <div class="post-item__body card-body">
                            <div class="tags">
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('blog.front.post.tag', ['slug' => $tag->url_key]) }}" class="tag-item">{{$tag->name}}</a>
                                @endforeach
                            </div>
                            <p class="date">{{ $post->created_at->isoFormat('MMM Do, YYYY') }}</p>
                            <a href="{{ route('blog.front.post.view', ['slug' => $post->url_key]) }}">
                                <p class="title mb-1">{{ ucfirst($post->title) }}</p>
                            </a>
                            <a href="{{ route('blog.front.post.view', ['slug' => $post->url_key]) }}"
                               class="btn btn-outline-warning read-more">{{ __('blog::app.general.read-more') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-12">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('vendor/devvly/blog/assets/js/app.js') }}"></script>
    <script type="application/javascript">
        $(document).ready(function (){
            $('#post_filter').on('change', function (){
                var url = $(this).val();// get selected value
                console.log(window.location);
                if (url && url !== window.location.href) { // require a URL
                    window.location = url; // redirect
                }
                return false;
            });
        })
    </script>
@endpush