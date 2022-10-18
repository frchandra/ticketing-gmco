{{--your order of {{ $data['first_name'] }} {{$data["last_name"]}} is confirmed.--}}
<h1>THANKS FOR PURCHASING! - GMCO LIVE</h1>
<h2>Terima Kasih {{$data['first_name']}} {{$data["last_name"]}}</h2>
<p>Anda telah mendapatkan tiket konser “GMCO Live from the Living Room” pada :
</p>
<ul>
    <li>Hari/Tanggal: Sabtu, 12 November 2022</li>
    <li>Waktu: 17:00 (Open Gate) 18:30 (Start Show)</li>
    <li>Venue: Auditorium MM UGM Sukadji Ranuwihardjo https://goo.gl/maps/pBNoktpBpUZfXCaz8</li>
    <li>Alamat: Jl. Teknika Utara No.2, Kocoran, Caturtunggal, Depok, Sleman,  D.I.Y. 55281
    </li>
</ul>
<p>
    Penonton diwajibkan untuk menunjukkan QR Code yang terdapat pada e-mail ini untuk ditukarkan dengan gelang tiket. Penukaran QR Code menjadi gelang tiket dilaksanakan pada Hari-H konser (Sabtu, 12 November 2022 pk. 16:00).
</p>
<br>
<p>
    Setiap pembeli tiket dilarang menyebarluaskan QR Code yang telah didapatkan untuk menghindari adanya duplikasi oleh pihak yang tidak bertanggung jawab. Apabila terjadi duplikasi pada QR Code, hanya QR Code yang terverifikasi pertama (pada saat penukaran tiket) yang dianggap valid. QR Code yang sudah diterima pembeli menjadi tanggung jawab pembeli yang bersangkutan sepenuhnya, pihak GMCO Live tidak bertanggung jawab atas segala kerugian yang ditimbulkan akibat kelalaian pembeli.
</p>
<br>
<p>
    Diharapkan, penonton datang tepat waktu pada saat jadwal penukaran tiket (16:00) untuk melakukan penukaran tiket. Apabila penonton datang setelah acara dimulai (18:30), penonton baru bisa memasuki venue auditorium pada setiap pergantian lagu agar tidak mengganggu jalannya konser.
</p>
<br>
<p>Enjoy the Show!</p>

@foreach($data['seats'] as $seat)
    <li>{{ $seat }}</li>
    <img src="{{$message->embed("/home/u1545269/public_html/api.gmco-event.com/storage/app/qr/{$seat}.png")}}">
@endforeach

