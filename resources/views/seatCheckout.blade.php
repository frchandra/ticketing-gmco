<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body>



<h1>Hello, world!</h1>
<h3>{{$data['fname']}}</h3>
<h3>{{$data['lname']}}</h3>
<h3>{{$data['email']}}</h3>
<h3>{{$data['seat']}}</h3>
<h3>{{$data['warning']}}</h3>

<form action="/attend/{{$data['unique']}}" method="post">
    @csrf
    <input type="submit" value="setHadir">
</form>

<form action="/non-attend/{{$data['unique']}}" method="post">
    @csrf
    <input type="submit" value="setTidakHadir">
</form>

<form action="/get-ticket/{{$data['unique']}}" method="post">
    @csrf
    <input type="submit" value="setPunyaTiket">
</form>

<form action="/not-get-ticket/{{$data['unique']}}" method="post">
    @csrf
    <input type="submit" value="setTidakPunyaTiket">
</form>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>
