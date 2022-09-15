<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">




</head>
<body>



<h1>silakan pilih kursi</h1>
<form action="/ticketing/booking" method="post">
    @csrf
    @foreach($seats as $seat)
    <input type="checkbox"  name="seat[]" value={{$seat['name']}}>
    <label
        @if($seat['is_reserved'] === config('constants.MAX_VALUE'))
            style="color:red;"
        @elseif($seat['is_reserved'] >= \Carbon\Carbon::now()->timestamp)
            style="color:yellow;"
        @else
            style="color:green;"
        @endif
    >{{$seat['name']}}</label><br>
    @endforeach

    <input type="submit" value="Submit">
</form>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>
