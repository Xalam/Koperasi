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
                                        {{ date('d-m-Y', strtotime($pinjaman->tanggal)) }}</small>
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
                                    <strong>{{ $pinjaman->anggota->nama_anggota }}</strong><br>
                                    {{ $pinjaman->anggota->alamat }}<br>
                                    Whatsapp: {{ $pinjaman->anggota->no_wa }}<br>
                                    Email: {{ $pinjaman->anggota->email }}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b>Kode Anggota: {{ $pinjaman->anggota->kd_anggota }}</b>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        <div class="row" style="margin-bottom: 10px;">
                            Lembar : <b>Pengajuan Pinjaman</b>
                        </div>
                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nominal Pinjaman</th>
                                            <th>Jangka Waktu</th>
                                            <th>Bunga</th>
                                            <th>Angsuran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>{{ number_format($pinjaman->nominal_pinjaman, 2, ',', '.') }}</td>
                                            <td>{{ $pinjaman->tenor }} x</td>
                                            <td>{{ $pinjaman->bunga }} %</td>
                                            <td>{{ number_format($pinjaman->nominal_angsuran, 2, ',', '.') }}</td>
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
                                            <th style="width:50%">Nominal Pinjaman</th>
                                            <td>: Rp. {{ number_format($pinjaman->nominal_pinjaman, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Biaya Provisi</th>
                                            <td>: Rp. {{ number_format($pinjaman->biaya_provisi, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Biaya Asuransi</th>
                                            <td>: Rp. {{ number_format($pinjaman->biaya_asuransi, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Total Diterima</th>
                                            <td>: Rp.
                                                {{ number_format($pinjaman->nominal_pinjaman - $pinjaman->biaya_provisi - $pinjaman->biaya_asuransi, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
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
