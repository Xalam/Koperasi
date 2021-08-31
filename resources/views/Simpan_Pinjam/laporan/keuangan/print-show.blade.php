<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simpan Pinjam | Laporan Posisi Keuangan</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">

    <style>
        tr {
            height: 10px;
            border-bottom: 1px solid #ccc;
        }

    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <div class="row no-print">
            <div class="col-12">
                <a href="{{ route('keuangan.index') }}" class="btn btn-default" style="margin: 10px;"><i></i>
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
                <h3><b>Laporan Posisi Keuangan</b></h3><br>
                <h3 style="margin-top: -30px; margin-bottom: 20px;"><b>Primer Koperasi Polrestabes Semarang</b></h3>
            </div>
            <div>
                <address>
                    @if (isset($startDate) && isset($endDate))
                        Periode : {{ $startDate }} / {{ $endDate }}
                    @else
                        Periode : {{ date('m-Y') }}
                    @endif
                </address>
            </div>
            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-center">Nama Akun</th>
                                <th class="text-center">Saldo (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="height: 15px;">
                                <td><b>ASET</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>Aset Lancar</b></td>
                                <td></td>
                            </tr>
                            @foreach ($asetLancar as $key => $value)
                                <tr>
                                    <td>{{ $value->nama_akun }}</td>
                                    <td class="text-right">{{ number_format($saldoLancar[$key], 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td><b>Total Aset Lancar</b></td>
                                <td class="text-right"><b>{{ number_format($sumLancar, 2, ',', '.') }}</b></td>
                            </tr>
                            <tr>
                                <td><b>Penyertaan</b></td>
                                <td></td>
                            </tr>
                            @foreach ($penyertaan as $key => $value)
                                <tr>
                                    <td>{{ $value->nama_akun }}</td>
                                    <td class="text-right">{{ number_format($saldoPenyertaan[$key], 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td><b>Total Penyertaan</b></td>
                                <td class="text-right"><b>{{ number_format($sumPenyertaan, 2, ',', '.') }}</b></td>
                            </tr>
                            <tr>
                                <td><b>Aset Tidak Lancar</b></td>
                                <td></td>
                            </tr>
                            @foreach ($asetTidakLancar as $key => $value)
                                <tr>
                                    <td>{{ $value->nama_akun }}</td>
                                    <td class="text-right">{{ number_format($saldoTidakLancar[$key], 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td><b>Total Aset Tidak Lancar</b></td>
                                <td class="text-right"><b>{{ number_format($sumTidakLancar, 2, ',', '.') }}</b></td>
                            </tr>
                            <tr>
                                <td class="table-secondary"><b>TOTAL ASET</b></td>
                                <td class="text-right table-secondary">
                                    <b>{{ number_format($sumLancar + $sumTidakLancar + $sumPenyertaan, 2, ',', '.') }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td><b>KEWAJIBAN & EKUITAS</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>Kewajiban Janga Pendek</b></td>
                                <td></td>
                            </tr>
                            @foreach ($kewajibanPendek as $key => $value)
                                <tr>
                                    <td>{{ $value->nama_akun }}</td>
                                    @if ($saldoPendek[$key] < 0)
                                        <td class="text-right">
                                            {{ number_format($saldoPendek[$key] * -1, 2, ',', '.') }}
                                        </td>
                                    @else
                                        <td class="text-right">{{ number_format($saldoPendek[$key], 2, ',', '.') }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td><b>Total Kewajiban Jangka Pendek</b></td>
                                @if ($sumPendek < 0)
                                    <td class="text-right"><b>{{ number_format($sumPendek * -1, 2, ',', '.') }}</b>
                                    </td>
                                @else
                                    <td class="text-right"><b>{{ number_format($sumPendek, 2, ',', '.') }}</b></td>
                                @endif
                            </tr>
                            <tr>
                                <td><b>Kewajiban Jangka Panjang</b></td>
                                <td></td>
                            </tr>
                            @foreach ($kewajibanPanjang as $key => $value)
                                <tr>
                                    <td>{{ $value->nama_akun }}</td>
                                    @if ($saldoPanjang[$key] < 0)
                                        <td class="text-right">
                                            {{ number_format($saldoPanjang[$key] * -1, 2, ',', '.') }}
                                        </td>
                                    @else
                                        <td class="text-right">{{ number_format($saldoPanjang[$key], 2, ',', '.') }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td><b>Total Kewajiban Jangka Panjang</b></td>
                                @if ($sumPanjang < 0)
                                    <td class="text-right"><b>{{ number_format($sumPanjang * -1, 2, ',', '.') }}</b>
                                    </td>
                                @else
                                    <td class="text-right"><b>{{ number_format($sumPanjang, 2, ',', '.') }}</b></td>
                                @endif
                            </tr>
                            <tr>
                                <td><b>TOTAL KEWAJIBAN</b></td>
                                @if ($sumPendek + $sumPanjang < 0)
                                    <td class="text-right">
                                        <b>{{ number_format(($sumPendek + $sumPanjang) * -1, 2, ',', '.') }}</b>
                                    </td>
                                @else
                                    <td class="text-right">
                                        <b>{{ number_format($sumPendek + $sumPanjang, 2, ',', '.') }}</b>
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td><b>Ekuitas</b></td>
                                <td></td>
                            </tr>
                            @foreach ($ekuitas as $key => $value)
                                <tr>
                                    <td>{{ $value->nama_akun }}</td>
                                    @if ($saldoEkuitas[$key] < 0)
                                        <td class="text-right">
                                            {{ number_format($saldoEkuitas[$key] * -1, 2, ',', '.') }}
                                        </td>
                                    @else
                                        <td class="text-right">{{ number_format($saldoEkuitas[$key], 2, ',', '.') }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td><b>TOTAL EKUITAS</b></td>
                                @if ($sumEkuitas < 0)
                                    <td class="text-right"><b>{{ number_format($sumEkuitas * -1, 2, ',', '.') }}</b>
                                    </td>
                                @else
                                    <td class="text-right"><b>{{ number_format($sumEkuitas, 2, ',', '.') }}</b></td>
                                @endif
                            </tr>
                            <tr>
                                <td><b>SHU {{ isset($reqEnd) ? date('Y', strtotime($reqEnd)) : date('Y') }}</b></td>
                                <td class="text-right"><b>{{ number_format($sumSHUAkun, 2, ',', '.') }}</b></td>
                            </tr>
                            <tr>
                                <td class="table-secondary"><b>TOTAL KEWAJIBAN & EKUITAS</b></td>
                                @if ($sumPendek + $sumPanjang + $sumEkuitas < 0)
                                    <td class="text-right table-secondary">
                                        <b>{{ number_format(($sumPendek + $sumPanjang + $sumEkuitas) * -1 + $sumSHUAkun, 2, ',', '.') }}</b>
                                    </td>
                                @else
                                    <td class="text-right table-secondary">
                                        <b>{{ number_format($sumPendek + $sumPanjang + $sumEkuitas + $sumSHUAkun, 2, ',', '.') }}</b>
                                    </td>
                                @endif
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
