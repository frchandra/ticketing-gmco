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
        <th>last name</th>
        <th>seat</th>
        <th>price</th>
        {{--            <th>is_confirmed</th>--}}
        <th>link</th>
        <th>is attend?</th>
    </tr>

    @foreach($orders as $order)
        <form action="./confirm" method="post">
            @csrf
            <tr>
                <td>{{$order['email']}}</td>
                <td>{{$order['phone']}}</td>
                <td>{{$order['first_name']}}</td>
                <td>{{$order['last_name']}}</td>
                <td>{{$order['name']}}</td>
                <td>{{$order['price']}}</td>


                {{--                    <th>{{$order['is_confirmed']}}</th>--}}
                <td>{{$order['link']}}</td>
                <td>{{$order['is_attend']}}</td>

            </tr>

            @endforeach


        </form>


</table>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>
