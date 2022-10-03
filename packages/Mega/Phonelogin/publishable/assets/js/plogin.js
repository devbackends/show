$(document).ready(function () {
    $('button.btn').prop('id','registration-btn');
    $('#registration-btn').prop('disabled',true);
    $("#megaphone").intlTelInput({
        initialCountry: "IN",
        geoIpLookup: function(callback) {
            $.get('http://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        utilsScript: "utils.js"
    });

    jQuery('.iti__flag-container').click(function(){
        jQuery('#megaphone').val('');
    })

    $('#megaphone').change(function(){
        $('#registration-btn').prop('disabled',true);
        $('#megaOtpContainer').hide();
        $('.modal-overlay').hide();
        $('#megavcode').val();
        var value = $(this).val();
        var fullPhone = $('#megaphone').intlTelInput('getNumber');
        var numberIsValid = $('#megaphone').intlTelInput('isValidNumber');
        if(!numberIsValid){
            $(this).val('');
            return;
        }
        var countryCode = $('#megaphone').intlTelInput('getSelectedCountryData');
        var dialCode = countryCode.dialCode;
        if(dialCode == null){
            window.location.reload();
            return;
        }
        $(this).val(fullPhone);

        var requestUrl = $('#megaOtpUrl').val();
        var param = {phone:fullPhone,code:dialCode};
        var phoneInput = this;
        $.ajax({
            showLoader: true,
            url: requestUrl,
            data: param,
            type: "POST",
            dataType: 'json'
        }).done(function (data) {
            if(data.status){
                $('#megaOtpContainer').show();
                $('.modal-overlay').show();
            }else{
                alert(data.message);
            }
        });

    });


    $(document).on('click', '#megaverify', function(){
        var otp = $('#megaotp').val();
        if(!otp || otp == ""){
            $('#megaotp').parent().addClass('has-error');
            $('#vcode-error').show();
            return;
        }
        $('#megaotp').parent().removeClass('has-error');
        $('#vcode-error').hide();
        var requestUrl = $('#megaOtpVurl').val();
        var phone = $('#megaphone').val();
        var param = {phone:phone,otp:otp};
        $.ajax({
            showLoader: true,
            url: requestUrl,
            data: param,
            type: "POST",
            dataType: 'json'
        }).done(function (data) {
            $('#megaOtpContainer').show();
            $('.modal-overlay').hide();
            if(data.status){
                $('#megaOtpContainer').hide();
                $('.modal-overlay').hide();
                $('#megavcode').val(otp);
                alert(data.message);
                $('#registration-btn').prop('disabled',false);
            }else{
                $('#megaOtpContainer').show();
                $('.modal-overlay').show();
                $('#megaotp').parent().addClass('has-error');
                $('#vresp-error').html(data.message).show();

            }
        });

    });
    $(document).on('click', '#megaresend', function(){
        $('#megaphone').trigger('change');
    });

    $(document).on('click','#hidePopup',function () {
        $('#megaOtpContainer').hide();
        $('#megaotp').val();
    })

})