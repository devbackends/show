<?php $selectedOption = old($attribute->code) ?: $product[$attribute->code] ?>

<label class="switch">
    <input type="checkbox" class="control switch-checkbox" id="{{ $attribute->code }}" name="{{ $attribute->code }}"
           data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" value="{{ $selectedOption ? 1 : 0}}"
        {{ $selectedOption ? 'checked' : ''}} >
    <span class="slider round"></span>
</label>
@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('change', '.switch-checkbox', function () {

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
