$(document).ready(function () {
    $("#megaphone").intlTelInput({
        initialCountry: "IN",
        geoIpLookup: function (callback) {
            $.get('http://ipinfo.io', function () {
            }, "jsonp").always(function (resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        utilsScript: "utils.js"
    });

    $('#megaSendcode').click(function () {
        var value = $(this).val();
        var fullPhone = $('#megaphone').intlTelInput('getNumber');
        var numberIsValid = $('#megaphone').intlTelInput('isValidNumber');
        if(!numberIsValid){
            $('#megaphone').val('');
            return;
        }
        var countryCode = $('#megaphone').intlTelInput('getSelectedCountryData');
        var dialCode = countryCode.dialCode;
        if(dialCode == null){
            alert('invalid');
            window.location.reload();
            return;
        }
        $('#megaphone').val(fullPhone);

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
            console.log(data);
            if(data.status){
                alert(data.message);
            }else{
                alert(data.message);
            }
        });
    });

    $('#megaverify').click(function () {
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
            console.log(data);
            $('#megaOtpContainer').show();
            $('.modal-overlay').hide();
            if(data.status){
                alert(data.message);
                window.location = $('#successUrl').val();
            }else{
                alert(data.message);
            }
        });
    });
})