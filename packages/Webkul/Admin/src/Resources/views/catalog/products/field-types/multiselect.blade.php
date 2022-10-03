<select  v-validate="'{{$validations}}'" class="form-control js-example-basic-multiple" id="{{ $attribute->code }}" name="{{ $attribute->code }}[]" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" multiple>

    <?php $selected = $product[$attribute->code] ?>

    @foreach ($attribute->options as $option)
        <option value="{{ $option->id }}" {{ in_array($option->id, explode(',', $selected)) ? 'selected' : ''}}>
            {{ $option->admin_name }}
        </option>
    @endforeach

</select>
