ingfo pememsanan kursi  yang pesen ini: {{ $data['email'] }}
@foreach($data['purchased'] as $seat)
    <li>{{ $seat }}</li>
@endforeach


    @foreach($data['conflict'] as $seat)
        <h3>warning conflict</h3>
        <li>{{ $seat }}</li>
    @endforeach

