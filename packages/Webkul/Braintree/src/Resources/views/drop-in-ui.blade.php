<head>
  <meta charset="utf-8">
  <script src="https://js.braintreegateway.com/web/dropin/1.21.0/js/dropin.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>

  <div class="container" id="dropin-container"></div>
  <button class="btn" id="submit-button">Proceed to Payment</button>
  <input type="hidden" id="clientToken" value="{{$clientToken}}"><br><br>
  <center><b>** Please do not reload the page while payment is processing **</b></center>
  <script>
    var button = document.querySelector('#submit-button');
    var clientToken = document.getElementById('clientToken').value;
    var returnRoute = "{{route('shop.checkout.success')}}"

    braintree.dropin.create({
      authorization: clientToken,
      container: '#dropin-container'
    }, function (createErr, instance) {
      button.addEventListener('click', function () {
        instance.requestPaymentMethod(function (err, payload) {
          // Submit payload.nonce to your server

          $.get('{{ route('braintree.payment.transaction') }}', {payload}, function (response) {

            if(response.success) {
              location.href = returnRoute;
            }
          }, 'json');

        });
      });
    });
  </script>

  <style>
    .container {
      width: 50%;
      margin-left: 25%;
    }
    .btn {
      margin-left: 25%;
      height: 5%;
      background-color: #4CAF50;;
      border:none;
      color: aliceblue;
    }

    [data-braintree-id="toggle"] {
      display: none;
    }

    b{
      color:red;
    }
  </style>
</body>