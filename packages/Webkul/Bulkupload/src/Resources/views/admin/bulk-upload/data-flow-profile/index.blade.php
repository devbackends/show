@extends('admin::layouts.content')

@section('page_title')
{{ __('bulkupload::app.admin.bulk-upload.run-profile') }}
@stop

@section('content')

<!-- Import New products -->




<div class="">
    <div class="page-header">
        <div class="page-title">
            <h3>
                Add Profile
            </h3>
            <p>Profile Information</p>
        </div>

        <div class="page-action"></div>
    </div>

    <div class="page-content">
        <form method="POST" action="{{ route('bulkupload.bulk-upload.dataflow.add-profile') }}">
            @csrf
            <?php $familyId = app('request')->input('family') ?>

            <div class="control-group {{ $errors->first('profile_name') ? 'has-error' :'' }}">
                <label for="profile_name" class="required">{{ __('admin::app.catalog.categories.name') }}</label>
                <input type="text" class="control" id="profile_name" name="profile_name" value="" />
                <span class="control-error">{{ $errors->first('profile_name') }}</span>
            </div>

            <div class="control-group {{ $errors->first('attribute_family') ? 'has-error' :'' }}">
                <label for="attribute_family" class="required">{{ __('admin::app.catalog.products.familiy') }}</label>

                <select class="control" id="attribute_family" name="attribute_family" {{ $familyId ? 'disabled' : '' }}>
                    <option value="">Please Select</option>
                    @foreach ($families as $family)
                    <option value="{{ $family->id }}" {{ ($familyId == $family->id || old('attribute_family') == $family->id) ? 'selected' : '' }}>{{ $family->name }}</option>
                    @endforeach
                </select>

                @if ($familyId)
                <input type="hidden" name="attribute_family" value="{{ $familyId }}" />
                @endif

                <span class="control-error">{{ $errors->first('attribute_family') }}</span>
            </div>

            <div class="page-action mb-5">
                <button type="submit" class="btn btn-primary">
                    {{ __('bulkupload::app.shop.bulk-upload.save-profile')  }}
                </button>
            </div>
        </form>

        <accordian :title="'{{ __('bulkupload::app.shop.profile.admin-grid') }}'" :active="true">
        <div slot="body">
                {!! app('Webkul\Bulkupload\DataGrids\Admin\ProfileDataGrid')->render() !!}
        </div>
    </accordian>
    </div>
</div>
@stop