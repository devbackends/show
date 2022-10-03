@extends('admin::layouts.content')

@section('page_title')
    {{ __('velocity::app.admin.meta-data.title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" enctype="multipart/form-data"
              @if ($metaData) action="{{ route('velocity.admin.store.meta-data', ['id' => $metaData->id]) }}"
              @else action="{{ route('velocity.admin.store.meta-data', ['id' => 'new']) }}" @endif>

            @csrf

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('velocity::app.admin.meta-data.title') }}</h1>
                </div>
                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('velocity::app.admin.meta-data.update-meta-data') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">

                    <accordian :title="'{{ __('velocity::app.admin.meta-data.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label>{{ __('velocity::app.admin.meta-data.sidebar-categories') }}</label>

                                <input type="text" class="control" id="sidebar_category_count"
                                       name="sidebar_category_count"
                                       value="{{ $metaData ? $metaData->sidebar_category_count : '12' }}"/>
                            </div>

                            <div class="control-group">
                                <label>{{ __('shop::app.home.featured-products') }}</label>

                                <input type="text" class="control" id="featured_product_count"
                                       name="featured_product_count"
                                       value="{{ $metaData ? $metaData->featured_product_count : 12 }}"/>
                            </div>

                            <div class="control-group">
                                <label>{{ __('shop::app.home.new-products') }}</label>

                                <input type="text" class="control" id="new_products_count" name="new_products_count"
                                       value="{{ $metaData ? $metaData->new_products_count : 12 }}"/>
                            </div>

                            <div class="control-group" style="margin-top: 40px;">
                                <label>Hero Image</label>

                                <image-wrapper
                                    input-name="path_hero_image" :multiple="false"
                                    @if ($metaData->path_hero_image && $metaData->path_hero_image !== 'default')
                                        :images='"{{ \Illuminate\Support\Facades\Storage::url($metaData->path_hero_image) }}"'
                                    @endif
                                ></image-wrapper>
                                <br>
                                <label or="hero_image_link">Hero Image Link</label><br><br><br>
                                <input id="hero_image_link" type="url" name="hero_image_link"
                                       value="{{ $metaData->hero_image_link? $metaData->hero_image_link: '' }}" class="control url_input">
                            </div>

                            <div class="control-group" style="width: 100%;">
                                <label>{{ __('velocity::app.admin.meta-data.home-page-content') }}</label>
                                <a href="{{route('admin.page_builder.edit',['route' => 'home'])}}" target="_blank" class="btn btn-primary">Edit Content</a>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('velocity::app.admin.meta-data.footer') }}'" :active="false">
                        <div slot="body">
                            <div class="control-group">
                                <label>{{ __('velocity::app.admin.meta-data.footer-content') }}</label>
                                <a href="{{route('admin.page_builder.edit',['route' => 'footer'])}}" target="_blank" class="btn btn-primary">Edit Content</a>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            tinymce.init({
                height: 200,
                width: "100%",
                image_advtab: true,
                valid_elements: '*[*]',
                selector: 'textarea#footer_left_content,textarea#subscription_bar_content,textarea#footer_middle_content,textarea#product-policy',
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
            });
        });
    </script>
@endpush