<?php
$value = old($attribute->code) ?: $product[$attribute->code];
if (!is_null($value)) {
    $value = round($value, 2);
}

?>
<input type="text" v-validate="'{{$validations}}'" class="form-control" id="{{ $attribute->code }}" name="{{ $attribute->code }}" value="{{ $value }}" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;"/>