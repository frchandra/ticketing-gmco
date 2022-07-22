ingfo pememsanan kursi  yang pesen ini: {{ $data['email'] }}
@foreach($data['seats'] as $seat)
    <li>{{ $seat }}</li>
@endforeach
