<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simpan Pinjam | Pinjaman Print</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>
<body>
    <div class="wrapper">
        <!-- Main content -->
        <div class="row no-print">
            <div class="col-12">
                <a href="{{ route('data-anggota.index') }}" class="btn btn-default" style="margin: 10px;"><i></i> Kembali</a>
            </div>
        </div>
        <div class="row" style="margin: 15px;">
            <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo" style="width: 80px; height: 80px;" class="brand-image img-circle elevation-2"
                style="opacity: .8">
                <div style="margin-left: 15px;">
                    <h3><b>Primkop Polrestabes Semarang</b></h3>
                    <address>
                        Jl. Kaligarang No.1A, Petompon<br>
                        Semarang<br>
                        Phone: 0895-2458-3818<br>
                        Email: -
                    </address>
                </div>
            
        </div>
        <div class="text-center">
            <h3><b>Laporan Data Anggota</b></h3><br>
            <h3 style="margin-top: -30px; margin-bottom: 20px;"><b>Primer Koperasi Polrestabes Semarang</b></h3>
        </div>
        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Anggota</th>
                            <th>Nama Anggota</th>
                            <th>Gender</th>
                            <th>Agama</th>
                            <th>TTL</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>No WA</th>
                            <th>Jabatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($anggota as $ang)
                            <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $ang->kd_anggota }}</td>
                            <td>{{ $ang->nama_anggota }}</td>
                            <td>{{ $ang->jenis_kelamin }}</td>
                            <td>{{ $ang->agama }}</td>
                            <td>{{ $ang->tempat_lahir .', '. date('d-m-Y', strtotime($ang->tanggal_lahir))  }}</td>
                            <td>{{ $ang->alamat }}</td>
                            <td>{{ $ang->no_hp }}</td>
                            <td>{{ $ang->no_wa }}</td>
                            <td>{{ $ang->jabatan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </div>
    <script>
        window.addEventListener("load", window.print());

    </script>
</body>

</html>
