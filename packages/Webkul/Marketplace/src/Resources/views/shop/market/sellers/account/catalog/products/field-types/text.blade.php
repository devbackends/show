<input type="text" placeholder="{{$attribute->description}}" v-validate="'{{$validations}}'" class="form-control"
       id="{{ $attribute->code }}" name="{{ $attribute->code }}" value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
       {{ in_array($attribute->code, ['url_key']) ? 'v-slugify v-on:blur=validateUrlKey()' : '' }} {{ in_array($attribute->code, ['name']) ? 'v-on:blur=validateUrlKey()' : '' }}  data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" {{ $attribute->code == "name" ? "v-slugify-target=\"url_key\" " : ""  }}   />




