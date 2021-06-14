<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simpan Pinjam | Laporan Jurnal Umum</title>

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
                <a href="{{ route('shu.index') }}" class="btn btn-default" style="margin: 10px;"><i></i> Kembali</a>
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
                <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo" style="width: 80px; height: 80px;" class="brand-image img-circle elevation-2"
                    style="opacity: .8">
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
                <h3><b>Laporan Sisa Hasil Usaha</b></h3><br>
                <h3 style="margin-top: -30px; margin-bottom: 20px;"><b>Primer Koperasi Polrestabes Semarang</b></h3>
            </div>
            <div>
                <address>
                    Tanggal : {{ $startDate }} / {{ $endDate }}
                </address>
            </div>
            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Kode Akun</th>
                                <th class="text-center">Nama Akun</th>
                                <th class="text-center">Debet (Rp)</th>
                                <th class="text-center">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $shu)
                            <tr>
                                <td class="text-center">{{ $shu->kode_akun }}</td>
                                <td>{{ $shu->nama_akun }}</td>
                                <td class="text-right"> 
                                    @if($shu->debet == null)
                                        0
                                    @else
                                        {{ number_format($shu->debet, 2, ',', '.') }}
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($shu->kredit == null)
                                        0
                                    @else
                                        {{ number_format($shu->kredit, 2, ',', '.') }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-center"><b>Saldo</b></td>
                                <td class="text-right"><b>{{ number_format($total_debet, 2, ',', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($total_kredit, 2, ',', '.') }}</b></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><b>Laba</b></td>
                                <td></td>
                                <td class="text-right"><b>{{ number_format($laba, 2, ',', '.') }}</b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.col -->
            </div>
        </div>

    </div>
    <script>
        // window.addEventListener("load", window.print());

    </script>
</body>

</html>
