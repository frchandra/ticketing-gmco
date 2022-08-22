<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx"
          crossorigin="anonymous">
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key={{config('midtrans.client_key')}}></script>
</head>
<body>

    <h1>Silakan periksa kembali</h1>
    <button id="pay-button">Pay!</button>

    <script type="text/javascript">
        // For example trigger on button clicked, or any time you need
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token

            window.snap.pay("{{$snap_token}}", {
                onSuccess: function(result){
                    alert("payment success!"); console.log(result);
                    location.replace("http://localhost/reserve");
                },
                onPending: function(result){

                    alert("wating your payment!"); console.log(result);
                },
                onError: function(result){

                    alert("payment failed!"); console.log(result);
                },
                onClose: function(){
                    alert('you closed the popup without finishing the payment');
                }
            })
        });
    </script>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
            crossorigin="anonymous"></script>
</body>
</html>
