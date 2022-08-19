<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">




</head>
<body>



<h1>Kursi yg Anda pilih</h1>
<form action="./order" method="post" enctype="multipart/form-data">
    @csrf
    @foreach($seats['seat'] as $seat)
        <h3>{{$seat}}</h3><br>
    @endforeach
    @foreach($seats['price'] as $seat)
        <h3>{{$seat}}</h3><br>
    @endforeach

    @error('first_name')
        <h3>{{$message}}</h3>
    @enderror
    <label>First name:</label><br>
    <input type="text"  name="first_name" value="John"><br>


    @error('last_name')
        <h3>{{$message}}</h3>
    @enderror
    <label>Last name:</label><br>
    <input type="text" name="last_name" value="Doe"><br>

    @error('email')
        <h3>{{$message}}</h3>
    @enderror
    <label>email:</label><br>
    <input type="text" name="email" value="nismara.chandra@gmail.com"><br>

    @error('phone')
        <h3>{{$message}}</h3>
    @enderror
    <label>phone:</label><br>
    <input type="number" name="phone" value="123"><br>

    @error('file')
        <h3>{{$message}}</h3>
    @enderror
    <input type="file" name="tf_proof"><br>

    <input type="submit" value="Submit">
</form>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>
