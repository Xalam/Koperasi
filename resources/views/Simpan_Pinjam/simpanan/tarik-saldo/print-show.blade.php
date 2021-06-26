<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simpan Pinjam | Penarikan Print</title>

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
                        <div class="col-12">
                            <small class="float-right">Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</small>
                        </div>
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12" style="margin-bottom: 15px;">
                                <h4>
                                    <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo" class="brand-image img-circle elevation-1" style="max-height: 30px;">
                                        Primkop Polrestabes Semarang
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
                                    <strong>{{ $tarikSaldo->saldo->anggota->nama_anggota }}</strong><br>
                                    {{ $tarikSaldo->saldo->anggota->alamat }}<br>
                                    Whatsapp: {{ $tarikSaldo->saldo->anggota->no_wa }}<br>
                                    Email: {{ $tarikSaldo->saldo->anggota->email }}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b>Kode Anggota: {{ $tarikSaldo->saldo->anggota->kd_anggota }}</b>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        <div class="row" style="margin-bottom: 10px;">
                            Lembar : <b>Bukti Penarikan Simpanan</b>
                        </div>
                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Penarikan</th>
                                            <th>Jumlah Penarikan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>{{ date('d-m-Y', strtotime($tarikSaldo->tanggal)) }}</td>
                                            <td>{{ number_format($tarikSaldo->nominal, 2, ',', '.') }}</td>
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
                                            <th style="width:50%">Total:</th>
                                            <td>Rp. {{ number_format($tarikSaldo->nominal, 2, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
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