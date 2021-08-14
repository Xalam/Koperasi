<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simpan Pinjam | Laporan Perubahan Ekuitas</title>

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
                <a href="{{ route('ekuitas.index') }}" class="btn btn-default" style="margin: 10px;"><i></i>
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
                <h3><b>Laporan Perubahan Ekuitas</b></h3><br>
                <h3 style="margin-top: -30px; margin-bottom: 20px;"><b>Primer Koperasi Polrestabes Semarang</b></h3>
            </div>
            <div>
                <address>
                    @if ($startDate != '')
                        Tanggal: {{ $startDate }} / {{ $endDate }}
                    @else
                        Tanggal: {{ $endDate }}
                    @endif
                </address>
            </div>
            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Keterangan</th>
                                @foreach ($akun as $a)
                                    <th class="text-center">{{ $a->nama_akun }}</th>
                                @endforeach
                                <th class="text-center">Total (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Saldo Awal</td>
                                @foreach ($akun as $a)
                                    <td class="text-right">{{ number_format($a->saldo * -1, 2, ',', '.') }}</td>
                                @endforeach
                                <td class="text-right">{{ number_format($totalSaldo * -1, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Penambahan</td>
                                @foreach ($penambahan as $plus)
                                    <td class="text-right">{{ number_format($plus, 2, ',', '.') }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Pengurangan</td>
                                @foreach ($pengurangan as $min)
                                    <td class="text-right">{{ number_format($min, 2, ',', '.') }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Saldo Akhir</td>
                                @foreach ($saldoAkhir as $sal)
                                    <td class="text-right"><b>{{ number_format($sal, 2, ',', '.') }}</b></td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
        </div>

    </div>
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
