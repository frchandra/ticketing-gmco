ingfo pememsanan kursi  yang pesen ini: {{ $data['email'] }}, {{$data['first_name']}}  {{$data['last_name']}}
@foreach($data['purchased'] as $seat)
    <li>{{ $seat }}</li>
@endforeach





