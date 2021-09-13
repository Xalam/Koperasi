<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simpan Pinjam | Laporan Simpanan</title>

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
                <a href="{{ route('lap-simpanan.index') }}" class="btn btn-default" style="margin: 10px;"><i></i>
                    Kembali</a>
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
            <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo"
                style="width: 80px; height: 80px;" class="brand-image img-circle elevation-2" style="opacity: .8">
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
            <h3><b>Laporan Simpanan Anggota</b></h3><br>
            <h3 style="margin-top: -30px; margin-bottom: 20px;"><b>Primer Koperasi Polrestabes Semarang</b></h3>
        </div>
        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode Simpanan</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Anggota</th>
                            <th class="text-center">Jenis Simpanan</th>
                            <th class="text-center">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                            $total = 0;
                        @endphp
                        @foreach ($simpananUnion as $sim)
                            @php
                                $total += $sim->nominal;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td class="text-center">
                                    {{ $sim->kode_simpanan == '0' ? 'Penarikan' : $sim->kode_simpanan }}</td>
                                <td class="text-center">{{ date('d-m-Y', strtotime($sim->tanggal)) }}</td>
                                <td class="text-center">
                                    {{ $sim->kd_anggota . ' / ' . $sim->nama_anggota }}</td>
                                <td class="text-center">
                                    @if ($sim->jenis_simpanan == 1)
                                        Simpanan Pokok
                                    @elseif ($sim->jenis_simpanan == 2)
                                        Simpanan Wajib
                                    @else
                                        Simpanan Sukarela
                                    @endif
                                </td>
                                <td class="text-right">{{ number_format($sim->nominal, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-center"><b>Jumlah Simpanan</b></td>
                            <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                        </tr>
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
