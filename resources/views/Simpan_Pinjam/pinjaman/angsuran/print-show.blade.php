<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simpan Pinjam | Angsuran Print</title>

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
        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <a href="{{ route('angsuran.index') }}" class="btn btn-default" style="margin: 10px;"><i></i>
                    Kembali</a>
            </div>
        </div>
        <!-- Main content -->
        <section class="invoice">
            <div class="row">
                <div class="col-12">
                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <small class="float-right" style="font-size: 10pt;">Tanggal Cetak:
                                        {{ date('d-m-Y H:i:s') }}</small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo"
                                        class="brand-image img-circle elevation-1" style="max-height: 30px;">
                                    Primkop Polrestabes Semarang
                                    <small class="float-right">Tanggal:
                                        {{ date('d-m-Y', strtotime($angsuran->tanggal)) }}</small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                From
                                <address>
                                    <strong>Primkop Polrestabes Semarang</strong><br>
                                    Jl. Kaligarang No.1A, Barusari<br>
                                    Semarang<br>
                                    Phone: 0895-2458-3818<br>
                                    Email: -
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                To
                                <address>
                                    <strong>{{ $angsuran->pinjaman->anggota->nama_anggota }}</strong><br>
                                    {{ $angsuran->pinjaman->anggota->alamat }}<br>
                                    Whatsapp: {{ $angsuran->pinjaman->anggota->no_wa }}<br>
                                    Email: {{ $angsuran->pinjaman->anggota->email }}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b>Kode Anggota: {{ $angsuran->pinjaman->anggota->kd_anggota }}</b>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        <div class="row" style="margin-bottom: 10px;">
                            Lembar : <b>Bukti Pembayaran Angsuran</b>
                        </div>
                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode Pinjaman</th>
                                            <th>Nominal Pinjaman</th>
                                            <th>Jangka Waktu</th>
                                            <th>Bunga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $angsuran->pinjaman->kode_pinjaman }}</td>
                                            <td>{{ number_format($angsuran->pinjaman->nominal_pinjaman, 2, ',', '.') }}
                                            </td>
                                            <td>{{ $angsuran->pinjaman->tenor }} x</td>
                                            <td>{{ $angsuran->pinjaman->bunga }} %</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-6">

                            </div>
                            <!-- /.col -->
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Kode Angsuran</th>
                                            <td>{{ $angsuran->kode_angsuran }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Angsuran Ke</th>
                                            <td>{{ $angsuran->pinjaman->tenor - $angsuran->sisa_bayar }}
                                                @if ($angsuran->lunas == 1)
                                                    (<b>LUNAS</b>)
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Jumlah#</th>
                                            <td>Rp. {{ number_format($angsuran->nominal_angsuran, 2, ',', '.') }}
                                                @if ($angsuran->status == 0)
                                                    (<b>BELUM BAYAR</b>)
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
