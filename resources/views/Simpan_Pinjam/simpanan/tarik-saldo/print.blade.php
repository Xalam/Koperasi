@extends('Simpan_Pinjam.layout')

@section('title', 'Simpanan')

@section('content_header', 'Cetak Riwayat Penarikan')

    @push('style')

    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Simpanan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tarik-saldo.history') }}">Riwayat Penarikan</a></li>
    <li class="breadcrumb-item active">Cetak Riwayat Penarikan</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Note:</h5>
                Halaman ini akan dicetak. Periksa kembali detail penarikan di bawah sebelum dicetak.
            </div>

            <!-- Main content -->
            <div class="invoice p-3 mb-3">
                <!-- title row -->
                <div class="row">
                    <div class="col-12">
                        <h4>
                            <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo" class="brand-image img-circle elevation-1" style="max-height: 30px;">
                                Primkop Polrestabes Semarang
                            <small class="float-right">Tanggal: {{ date('d-m-Y', strtotime($tarikSaldo->tanggal)) }}</small>
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
                <!-- /.row -->

                <!-- this row will not appear when printing -->
                <div class="row no-print">
                    <div class="col-12">
                        <a href="{{ route('tarik-saldo.print-show', $tarikSaldo->id) }}" rel="noopener" target="_blank" class="btn btn-info float-right"><i
                                class="fas fa-print"></i> Print</a>
                        <a href="{{ route('tarik-saldo.history') }}" class="btn btn-default float-right" style="margin-right: 5px;"><i></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
