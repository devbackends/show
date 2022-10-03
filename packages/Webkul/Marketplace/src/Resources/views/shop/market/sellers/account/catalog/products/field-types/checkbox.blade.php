<div class="control-group" style="margin-top: 5px;">

    @foreach ($attribute->options as $option)
        <span class="checkbox" style="margin-top: 5px;">
            <input  type="checkbox" class="checkbox" name="{{ $attribute->code }}[]" value="{{ $option->id }}" {{ in_array($option->id, explode(',', $product[$attribute->code])) ? 'checked' : ''}}>
            </input>

            <label class="checkbox-view"></label>
            {{ $option->admin_name }}
        </span>
    @endforeach

</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('change', '.checkbox', function () {

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