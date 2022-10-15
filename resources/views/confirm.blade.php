your order of {{ $data['first_name'] }} {{$data["last_name"]}} is confirmed.
@foreach($data['seats'] as $seat)
    <li>{{ $seat }}</li>
    <img src="{{$message->embed("/var/www/storage/app/qr/{$seat}.png")}}">
@endforeach

