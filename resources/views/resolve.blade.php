<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

      <style>
          table {
              font-family: arial, sans-serif;
              border-collapse: collapse;
              width: 100%;
          }

          td, th {
              border: 1px solid #dddddd;
              text-align: left;
              padding: 8px;
          }

          tr:nth-child(even) {
              background-color: #dddddd;
          }
      </style>

  </head>
  <body>
    <h1>Hello, world!</h1>
    <table>
        <tr>
            <th>email</th>
            <th>phone</th>
            <th>first name</th>
            <th>seat</th>
            <th>price</th>
            <th>transc_id</th>
            <th>vendor</th>

            <th>confirmation</th>
        </tr>

            @foreach($orders as $order)
            <form action="./confirm" method="post">
                @csrf
                <tr>
                    <td>{{$order['buyer_email']}}</td>
                    <td>{{$order['buyer_phone']}}</td>
                    <td>{{$order['buyer_fname']}}</td>
                    <td>
                        @foreach($order['seats'] as $name)
                        <p>{{$name}}</p>
                        @endforeach
                    </td>
                    <td>{{$order['price']}}</td>
                    <td>{{$order['transaction_id']}}</td>
                    <td>{{$order['vendor']}}</td>
                    <td>{{$order['confirmation']}}</td>

                </tr>

            @endforeach


        </form>


    </table>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>
</html>
