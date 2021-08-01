<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simpan Pinjam | Laporan Pinjaman</title>

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
                <a href="{{ route('lap-pinjaman.index') }}" class="btn btn-default" style="margin: 10px;"><i></i>
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
            <h3><b>Laporan Pinjaman Anggota</b></h3><br>
            <h3 style="margin-top: -30px; margin-bottom: 20px;"><b>Primer Koperasi Polrestabes Semarang</b></h3>
        </div>
        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode Pinjaman</th>
                            <th class="text-center">Tanggal Pinjaman</th>
                            <th class="text-center">Anggota</th>
                            <th class="text-center">Pokok Pinjaman (Rp)</th>
                            <th class="text-center">Jangka Waktu (x)</th>
                            <th class="text-center">Sisa Angsuran (x)</th>
                            <th class="text-center">Angsuran Pokok (Rp)</th>
                            <th class="text-center">Angsuran Bunga (Rp)</th>
                            <th class="text-center">Jumlah Angsuran (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($pinjaman as $pin)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td class="text-center">{{ $pin->kode_pinjaman }}</td>
                                <td class="text-center">{{ date('d-m-Y', strtotime($pin->tanggal)) }}</td>
                                <td class="text-center">
                                    {{ $pin->anggota->kd_anggota . ' / ' . $pin->anggota->nama_anggota }}</td>
                                <td class="text-right">{{ number_format($pin->nominal_pinjaman, 2, ',', '.') }}</td>
                                <td class="text-center">{{ $pin->tenor }}</td>
                                <td class="text-center">{{ $pin->tenor - $pin->angsuran_ke }}</td>
                                <td class="text-right">
                                    {{ number_format($pin->nominal_pinjaman / $pin->tenor, 2, ',', '.') }}</td>
                                <td class="text-right">
                                    {{ number_format(($pin->total_pinjaman - $pin->nominal_pinjaman) / $pin->tenor, 2, ',', '.') }}
                                </td>
                                <td class="text-right">{{ number_format($pin->nominal_angsuran, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-center"><b>Jumlah</b></td>
                            <td class="text-right"><b>{{ number_format($sumPinjaman, 2, ',', '.') }}</b></td>
                            <td colspan="5"></td>
                        </tr>
                    </tfoot>
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
