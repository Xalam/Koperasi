<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simpan Pinjam | Laporan Data Anggota</title>

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
        <div class="row">
            <div class="col-12">
                <h4>
                    <small class="float-right">Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</small>
                </h4>
            </div>
            <!-- /.col -->
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
                            <th class="text-center">No</th>
                            <th class="text-center">Kode Anggota</th>
                            <th class="text-center">Nama Anggota</th>
                            <th class="text-center">Gender</th>
                            <th class="text-center">Agama</th>
                            <th class="text-center">TTL</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">No HP</th>
                            <th class="text-center">No WA</th>
                            <th class="text-center">Jabatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($anggota as $ang)
                            <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="text-center">{{ $ang->kd_anggota }}</td>
                            <td>{{ $ang->nama_anggota }}</td>
                            <td class="text-center">{{ $ang->jenis_kelamin }}</td>
                            <td class="text-center">{{ $ang->agama }}</td>
                            <td class="text-center">{{ $ang->tempat_lahir .', '. date('d-m-Y', strtotime($ang->tanggal_lahir))  }}</td>
                            <td>{{ $ang->alamat }}</td>
                            <td class="text-center">{{ $ang->no_hp }}</td>
                            <td class="text-center">{{ $ang->no_wa }}</td>
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
