your order of {{ $data['first_name'] }} {{$data["last_name"]}} is confirmed.
@foreach($data['seats'] as $seat)
    <li>{{ $seat }}</li>
    <img src="{{$message->embed("/home/u1545269/public_html/api.gmco-event.com/storage/app/qr/{$seat}.png")}}">
@endforeach

