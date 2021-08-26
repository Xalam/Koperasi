<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simpan Pinjam | Laporan Bendahara Pusat</title>

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
                <a href="{{ route('bendahara.index') }}" class="btn btn-default" style="margin: 10px;"><i></i>
                    Kembali</a>
            </div>
        </div>
        <div class="invoice p-3 mb-3">
            <!-- title row -->
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
                        Jl. Kaligarang No.1A, Barusari<br>
                        Semarang<br>
                        Phone: 0895-2458-3818<br>
                        Email: -
                    </address>
                </div>
            </div>
            <div class="text-center">
                <h3><b>Laporan Piutang Anggota</b></h3><br>
                <h3 style="margin-top: -30px; margin-bottom: 20px;"><b>Primer Koperasi Polrestabes Semarang</b></h3>
            </div>
            <div class="row" style="margin-bottom: 10px;">
                Periode : {{ isset($tanggal) ? date('m-Y', strtotime($tanggal)) : date('m-Y') }}
            </div>
            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">NRP</th>
                                <th class="text-center">Nama Anggota</th>
                                <th class="text-center">Tanggal Registrasi</th>
                                <th class="text-center">Piutang Toko (Rp)</th>
                                <th class="text-center">Angsuran Pinjaman (Rp)</th>
                                <th class="text-center">Simpanan Wajib (Rp)</th>
                                <th class="text-center">Total Piutang (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($anggota as $key => $value)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ $value->username }}</td>
                                    <td>{{ $value->nama_anggota }}</td>
                                    <td class="text-center">{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                    <td class="text-right">
                                        {{ $value->sisa_piutang != null ? number_format($value->sisa_piutang, 0, '', '.') : '-' }}
                                    </td>
                                    <td class="text-right">
                                        {{ $value->nominal_angsuran != null ? number_format($value->nominal_angsuran, 0, '', '.') : '-' }}
                                    </td>
                                    <td class="text-right">
                                        {{ date('Y-m', strtotime($value->created_at)) < $tanggal ? number_format($simpananWajib, 0, '', '.') : '-' }}
                                    </td>
                                    <td class="text-right">{{ number_format($totalPiutang[$key], 0, '', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-center"><b>TOTAL</b></td>
                                <td class="text-right"><b>{{ number_format($sumHutang, 0, '', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($sumAngsuran, 0, '', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($sumSimpanan, 0, '', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($sumTotalPiutang, 0, '', '.') }}</b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <div class="row float-right mr-3 mt-5">
                <table border="0">
                    <tr>
                        <td class="text-center col-6">
                            Bendahara Koperasi
                            <br>
                            <br>
                            <br>
                            .............................
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
