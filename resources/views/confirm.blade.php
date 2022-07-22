your order of {{ $data['first_name'] }} {{$data["last_name"]}} is confirm by admin, todo send qrcode
@foreach($data['seats'] as $seat)
    <li>{{ $seat }}</li>
@endforeach

