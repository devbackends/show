<?php $selectedOption = old($attribute->code) ?: $product[$attribute->code] ?>

<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input boolean-input" name="{{ $attribute->code }}" id="{{$attribute->code}}"  data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" value="{{ $selectedOption ? 1 : 0}}" {{ $selectedOption ? 'checked' : ''}}>
    <label class="custom-control-label" for="{{$attribute->code}}">{{$attribute->admin_name}}</label>
</div>


@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('change', '.boolean-input', function () {

                if ($(this).prop("checked") == true) {

                    $(this).val(1);
                    $(this).prop('checked', true);
                } else if ($(this).prop("checked") == false) {

                    $(this).val(0);
                    $(this).removeAttr('checked');
                }
            });
        });
    </script>
@endpush
