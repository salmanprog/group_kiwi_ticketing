<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    <script src="https://js.braintreegateway.com/web/dropin/1.42.0/js/dropin.js"></script>
</head>
<body>
    <div id="dropin-container"></div>
    <button id="submit-button" class="button button--small button--green">Purchase</button>
    <script>
        var button = document.querySelector('#submit-button');

        braintree.dropin.create({
          authorization: '{{ $client_token }}',
          container: '#dropin-container'
        }, function (createErr, instance) {
          button.addEventListener('click', function () {
            instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {
                // Submit payload.nonce to your server
                console.log('card token: ',payload.nonce);
            });
          });
        });
      </script>
</body>
</html>
