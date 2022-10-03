@if($attribute->code === 'sku')
    <input type="text" v-validate="{ required: true, regex: /^[aA-zZ0-9]+(?:-[aA-zZ0-9]+)*$/ }" class="form-control" id="{{ $attribute->code }}" name="{{ $attribute->code }}" value="{{ old($attribute->code) ?: $product[$attribute->code] }}" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" />
@else
    <input type="text" v-validate="'{{$validations}}'" class="form-control" id="{{ $attribute->code }}" name="{{ $attribute->code }}" value="{{ old($attribute->code) ?: $product[$attribute->code] }}" {{ in_array($attribute->code, ['url_key']) ? 'v-slugify' : '' }} data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" {{ $attribute->code == 'name' ? 'v-slugify-target=\'url_key\'' : ''  }} />
@endif


