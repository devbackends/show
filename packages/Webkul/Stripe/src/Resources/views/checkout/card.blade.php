<?php $cart = cart()->getCart(); ?>

@if ($cart)
    @if(request()->is('checkout/redirect/stripe'))
        <html>
            <head>
                <title>Bagisto Stripe Payment</title>
                <script src="https://js.stripe.com/v3/"></script>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                <link href="{{ asset('vendor/webkul/stripe/assets/css/stripe.css') }}" rel="stylesheet"/>
                <link rel="stylesheet" href="{{ asset('vendor/webkul/ui/assets/css/ui.css') }}">
                <link rel="stylesheet" href="{{ asset('themes/default/assets/css/shop.css') }}">
                <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

                <style>
                    .spinner {
                        margin:auto;
                        top:20%;
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        margin-left: -50px;
                        margin-top: -50px;
                      }
                        .badge.badge-md {
                            padding: 13px 10px;
                        }
                </style>
            </head>

            <body style="background-color: #f8f9fa;">
                @include('stripe::components.stripe-form')
                <div class="cp-spinner cp-round spinner" id="loader"> </div>
            </body>


            <script>
                    @if(core()->getConfigData('sales.paymentmethods.stripe.debug') == '1')
                        var stripe = Stripe('{{ core()->getConfigData('sales.paymentmethods.stripe.api_test_publishable_key') }}');
                    @else
                        var stripe = Stripe('{{ core()->getConfigData('sales.paymentmethods.stripe.api_publishable_key') }}');
                    @endif

                    @if(auth()->guard('customer')->check())
                        var isUser = 'true';
                    @else 
                        var isUser = 'false';
                    @endif

                var stripeModalIsOpen = false;
                var stripeTokenReceived = false;
                var savedCardSelected = false;
                var savedCardSelectedId = null;
                var rememberCard = false;
                var data = {};
                var stripeGlobal = stripe; 
                var isSavedCard = 'false';
 

                $('.cp-spinner').css({'display': 'none'});
                $('.saved-old-card').css({'display': 'none'});

                var elements = stripe.elements({
                    fonts: [
                        {
                            cssSrc: 'https://fonts.googleapis.com/css?family=Montserrat',
                        }
                    ]
                });

                var styles = {
                        base: {
                            color: '#333333',
                            fontWeight: 600,
                            fontFamily: 'Montserrat, sans-serif',
                            fontSize: '16px',
                            fontSmoothing: 'antialiased',

                            ':focus': {
                                color: '#0d0d0d',
                            },

                            '::placeholder': {
                                color: '#C7C7C7',
                            },

                            ':focus::placeholder': {
                                color: '#666666',
                            },
                        },

                        invalid: {
                            color: '#333333',
                            ':focus': {
                                color: '#FF5252',
                            },

                            '::placeholder': {
                                color: '#FF5252',
                            },
                        },
                    };

                    var elementClasses = {
                        focus: 'focus',
                        empty: 'empty',
                        invalid: 'invalid',
                    };

                    //mount the elements to stripe payment form
                    var cardNumber = elements.create('cardNumber', { style: styles });
                    cardNumber.mount('#card-number');

                    var cardExpiry = elements.create('cardExpiry', { style: styles });
                    cardExpiry.mount('#card-expiry');

                    var cardCvc = elements.create('cardCvc', { style: styles });
                    cardCvc.mount('#card-cvc');

                    // Handle real-time validation errors from the card number.
                    cardNumber.addEventListener('change', function(event) {
                        var displayError = document.getElementById('card-number-error');

                        if (event.error) {
                            displayError.textContent = event.error.message;
                        } else {
                            displayError.textContent = '';
                        }
                    });

                    // Handle real-time validation errors from the card expiry.
                    cardExpiry.addEventListener('change', function(event) {
                        var displayError = document.getElementById('card-expiration-error');

                        if (event.error) {
                            displayError.textContent = event.error.message;
                        } else {
                            displayError.textContent = '';
                        }
                    });

                    function saveCard (result, token, paymentMethodId,isSavedCard) {
                        _token = "{{ csrf_token() }}",
                        $.ajax({
                            type: "POST",

                            url : "{{ route('stripe.save.card') }}",

                            data : {
                                result:result,
                                _token:_token,
                                stripetoken:token,
                                paymentMethodId:paymentMethodId,
                                isSavedCard:this.isSavedCard
                            },

                            success:function(response) {
                                if (response.message) {
                                    window.location.href = "{{ route('shop.checkout.cart.index')}}";
                                } else {
                                    paymentIntent();
                                }
                            }
                        });
                    }

                    function paymentIntent() {
                        $.ajax({
                            url :"{{ route('stripe.get.token') }}",
                            success:function(response) {
                                if (response.success != 'false') {

                                    client_secret = response.client_secret;

                                    stripe.handleCardPayment(client_secret,cardNumber).then(function(result) {
                                        if (result.error) {
                                            paymentCancel();
                                        } else {
                                            console.log(result);
                                            saveOrder();
                                        }
                                    });
                                } else {
                                    window.location.href = "{{ route('shop.checkout.cart.index')}}";
                                }
                                
                            },
                        });
                    }

                    function paymentCancel() {
                        $.ajax({
                            url : "{{ route('stripe.payment.cancel') }}",

                            success: function(response) {
                                window.location.href = response.data.route;
                            }
                        });
                    }

                    function saveOrder () {
                        $.ajax({
                            url : "{{ route('stripe.make.payment') }}",

                            success: function(response) {
                                window.location.href = response.data.route;
                            }
                        });
                    }
                    
                    //when you click on cross button
                    $(document).on('click','.payment-cancel',function(){
                        paymentCancel();
                    });

                    $('#stripe-payment-form').submit(function(event) {
                        
                        stripeMethod = stripe.method;

                        event.preventDefault();

                        var token = stripe.createToken(cardNumber);

                        stripe.createPaymentMethod('card', cardNumber).then(function(result) {
                            if (result.error) {    

                                if(result.error.type ==  'validation_error') {
                                    console.log('validation error');
                                } else {
                                    paymentCancel();
                                }

                            } else {
                                
                                if (isUser == 'true') {
                                    isSavedCard = confirm('Do you want to save card for future payment ?');
                                    
                                    if (isSavedCard) {
                                        isSavedCard = 'true';
                                    } else {
                                        isSavedCard = 'false';
                                    }
                                }
                                
                              
                                var paymentMethodId = result.paymentMethod.id;
                                var token = stripe.createToken(cardNumber);

                                token.then(function(token) {
                                    if (token.error) {
                                        var errorElement = document.getElementById('card-errors');
                                        errorElement.textContent = token.error.message;

                                        return false;
                                    } else {
                                        $('.cp-spinner').css({'display': 'block', 'z-index': '12', 'bottom': '600px'});

                                        $("#stripe-pay-button").attr("disabled", true);
                                        saveCard(result,token.token.id,paymentMethodId, stripeMethod, isSavedCard);
                                    }
                                });
                            }
                        });
                    });
                              

                    $(document).on("click","#delete-card",function() {
                        var result = confirm("Do you want to delete this card ?");
                        if (result) {
                            var deleteId = $(this).data('id');
                            $.ajax({
                                type: 'GET',
                                url: '{{ route('stripe.delete.saved.cart') }}',
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
                        }
                    }); 


                    // this disables all the methods when the stripe token is generated
                    function disableAllMethodsExceptStripe() {
                        $(".line-one .radio-container input:radio").not('.line-one .radio-container #stripe').attr('disabled', true);

                        $('.stripe-cards-block').css('display', 'none');

                        $('.add-card').remove();
                    }

                    function removeSavedCardNode(deleteId) {
                        nodeId = $('.stripe-card-info').each(function() {
                            if($(this).attr('id') == deleteId) {
                                $(this).remove();
                            }
                        });
                    }

                    $(document).on('click','.saved-card-payment', function(){
                        $('.new-card').hide();
                        $('.old-card').show();
                        $('.saved-old-card').show();
                    });

                    $(document).on('click','.old-card', function(){
                        $('.new-card').show();
                        $('.old-card').hide();
                        $('.saved-old-card').hide();

                    });

                    $(document).on('click','.old-stripe-button', function() {
                        //disable button during payment
                        $('.old-stripe-button').attr('disabled', 'disabled');

                        savedCardSelectedId = $('.stripe-card-info > .radio-container > input[type="radio"][name=saved-card]:checked').attr('id');
                    
                        _token = "{{csrf_token()}}";

                        $.ajax({
                            type: 'POST',
                            url:'{{ route('stripe.saved.card.payment') }}',
                            data : { _token:_token, savedCardSelectedId:savedCardSelectedId},
                            async: true,
                            success: function(response) {
                                if (response.success == 'false') {
                                    paymentCancel();
                                } else {
                                    clientSecret = response.savedCardPayment.client_secret;
                                    customerId = response.customer_id;
                                    paymentMethodId = response.payment_method_id;
                                    paymentUsingSavedCard(clientSecret,paymentMethodId);
                                }
                            }
                        });
                    });

                    // js for payment using saved card
                    function paymentUsingSavedCard (clientSecret, paymentMethodId) {
                        stripeGlobal.confirmCardPayment(clientSecret, {
                            payment_method: paymentMethodId
                            }).then(function(result) {
                            if (result.error) {
                                // Show error to your customer
                                paymentCancel();
                            } else {
                                if (result.paymentIntent.status === 'succeeded') {
                                    $('.cp-spinner').css({'display': 'block', 'z-index': '12', 'bottom': '600px'});
                                    saveOrder();
                                }
                            }
                        });
                    }
              

                    $('.old-stripe-button').attr('disabled', 'disabled');

                    $(document).on('click','.saved-card-list',function(){
                        var isChecked = $("input[name=saved-card]:checked").length > 0;

                        if (isChecked) {
                            $('.old-stripe-button').removeAttr('disabled');
                        } else {
                            $('.old-stripe-button').attr('disabled', 'disabled');
                        }
                    });
                
            </script>
        </html>
    @endif
@endif
