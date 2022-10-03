@if(request()->is('checkout/onepage'))
@php
if (core()->getConfigData('sales.paymentmethods.authorize.debug') == '1') {
    $merchantLoginId = core()->getConfigData('sales.paymentmethods.authorize.test_api_login_ID');
    $merchantAuthentication = core()->getConfigData('sales.paymentmethods.authorize.test_transaction_key');
} else {
    $merchantLoginId = core()->getConfigData('sales.paymentmethods.authorize.api_login_ID');
    $merchantAuthentication = core()->getConfigData('sales.paymentmethods.authorize.transaction_key');
}
@endphp
    <!-- JQ is needed to get the multiple document on ready instances and the rest part for the stripe payments integration works swiftly, plain js was creating delay and blocking of events on the ui which was hindering all the required code to be executed -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    @if(core()->getConfigData('sales.paymentmethods.authorize.debug') == '1')
    <script type="text/javascript" src="{{asset('vendor/webkul/authorize/assets/js/AcceptUITest.js')}}" charset="utf-8"></script>
    @else
    <script type="text/javascript" src="{{asset('vendor/webkul/authorize/assets/js/AcceptUI.js')}}" charset="utf-8"></script>
    @endif

    <form id="paymentForm" method="POST" action="">
        <input type="hidden" name="dataValue" id="dataValue" />
        <input type="hidden" name="dataDescriptor" id="dataDescriptor" />
        <button type="button" id="authorizePay" style="display:none"
            class="AcceptUI"
            data-billingAddressOptions='{"show":true, "required":false}'
            data-apiLoginID="{{$merchantLoginId}}"
            data-clientKey="{{core()->getConfigData('sales.paymentmethods.authorize.client_key')}}"
            data-acceptUIFormBtnTxt="Submit"
            data-acceptUIFormHeaderTxt="Card Information"
            data-responseHandler="responseHandler">Pay
        </button>
    </form>

    <script>
        eventBus.$on('after-checkout-payment-section-added', function() {
            // this part in this ready function will be executed on the basis of the event fired from the payment section's mounted hook and it will inject this code in to the window's event bus and the rest part of the code will be fired after that

            savedCardSelectedCard = false;

            $(document).ready(function() {

                $('input[type="radio"]').not('#saved-cards input[type="radio"]').on('click', function() {
                    if ($(this).attr('id') == 'authorize') {
                        $('.authorize-add-card').css('display', 'block');
                        $('#checkout-payment-continue-button').attr("disabled","disabled");
                        $('.authorize-cards-block').css('display', 'block');
                            if($('.authroizenet-card-info > .radio-container > input[type="radio"]').is(':checked')) {
                                radioID = $('.authroizenet-card-info > .radio-container > input[type="radio"]:checked').attr('id');
                                savedCardSelectedId = radioID;
                                savedCardSelectedCard = true;
                                $('#checkout-payment-continue-button').removeAttr("disabled","disabled");
                            }
                    }  else {
                        $('.authorize-add-card').css('display', 'none');
                        $('#checkout-payment-continue-button').removeAttr("disabled","disabled");
                        $('.authorize-cards-block').css('display', 'none');
                    }

                });


                $(document).on("click","#open-authorize-modal",function() {
                    $("#authorizePay").trigger('click');
                });

                $(document).on("click","#delete-card",function() {
                    var deleteId = $(this).data('id');
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('authorize.delete.saved.cart') }}',
                        data: {
                            id: deleteId
                        },

                        success: function (data) {
                            if(data == 1) {
                                removeSavedCardNode(deleteId);
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                });

                $(document).on('click','.authroizenet-card-info > .radio-container > input[type="radio"]',function() {
                    savedCardSelectedId = $(this).attr('id');
                    $('#checkout-payment-continue-button').removeAttr("disabled","disabled");
                    savedCardSelectedCard = true;
                });

                $('#checkout-payment-continue-button').on('click', function() {
                    //Submit the form
                    if(savedCardSelectedCard) {
                        _token = "{{csrf_token()}}";
                        $.ajax({
                            type: "POST",
                            url: "{{route('authorize.get.token')}}",
                            data: {_token:_token,savedCardSelectedId:savedCardSelectedId},
                            success: function( response ) {
                                if(response.success == 'true') {
                                    console.log('true');
                                } else {
                                    console.log('false')
                                }
                            }
                        });
                    }
                });

                function removeSavedCardNode(deleteId) {
                    nodeId = $('.authroizenet-card-info').each(function() {
                        if($(this).attr('id') == deleteId) {
                            $(this).remove();
                        }
                    });
                }
            });
        });


        function responseHandler(response) {
            if (response.messages.resultCode === "Error") {
                var i = 0;
                while (i < response.messages.message.length) {
                    console.log(
                        response.messages.message[i].code + ": " +
                        response.messages.message[i].text
                    );
                    i = i + 1;
                }
            } else {
                paymentFormUpdate(response);
            }
        }


        function paymentFormUpdate(response) {

            document.getElementById("dataDescriptor").value = response.opaqueData.dataDescriptor;
            document.getElementById("dataValue").value = response.opaqueData.dataValue;

            _token = "{{csrf_token()}}";

            $.ajax({
                type: "POST",
                url: "{{route('authorize.get.token')}}",
                data: {_token:_token,response:response},
                success: function( response ) {
                    if(response.success == 'true') {
                        $('#checkout-payment-continue-button').removeAttr("disabled","disabled");
                        $('.authorize-cards-block').css('display', 'none');
                        $('.authorize-add-card').css('display', 'none');
                    } else {
                        $('#checkout-payment-continue-button').attr("disabled","disabled");
                    }
                }
            });
        }

    </script>


@endif