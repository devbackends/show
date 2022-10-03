<div  class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
    <label for="phone" class="required">{{ __('megaSmsNotifications::app.customer.signup-form.phone') }}</label>
    @if(isset($customer))
        <input type="text" id="megaphone" class="control" name="phone" v-validate="'required|numeric|max:12|min:10'" value="{{$customer->phone }}" data-vv-as="&quot;{{ __('megaSmsNotifications::app.customer.signup-form.phone') }}&quot;">
    @else
        <input type="text" id="megaphone" class="control" name="phone" v-validate="'required|numeric|max:12|min:10'" value="{{ old('phone')}}" data-vv-as="&quot;{{ __('megaSmsNotifications::app.customer.signup-form.phone') }}&quot;">
    @endif
        <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {

    });


</script>
@push('scripts')
    <script src="{{ bagisto_asset('vendor/mega/smsnotification/assets/js/intlTelInput-jquery.js')}}"></script>

    <script>
    var input = document.querySelector("#megaphone");

    $(document).ready(function () {
        $("#megaphone").intlTelInput({
            initialCountry: "IN",
            geoIpLookup: function(callback) {
                $.get('http://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
        });
    })
    $('body').on('change','#megaphone',function(){
        var phone = $(this).val();
        phone = phone.substr(phone.length - 10);
        if (!$.isNumeric(phone)) {
            $('#megaphone').attr('aria-invalid',true);
            return;
        }
        var countryCode = $('#megaphone').intlTelInput('getSelectedCountryData');
        var dialCode = countryCode.dialCode;
        var fullPhone = dialCode + phone;
        $(this).val(fullPhone);
    })
    </script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{ bagisto_asset('vendor/mega/smsnotification/assets/css/intlTelInput.min.css')}}">
@endpush
