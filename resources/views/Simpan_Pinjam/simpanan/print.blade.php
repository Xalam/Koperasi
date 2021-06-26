@extends('Simpan_Pinjam.layout')

@section('title', 'Simpanan')

@section('content_header', 'Cetak Simpanan')

    @push('style')

    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Simpanan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('data.index') }}">Data Simpanan</a></li>
    <li class="breadcrumb-item active">Cetak Simpanan</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Note:</h5>
                Halaman ini akan dicetak. Periksa kembali detail simpanan di bawah sebelum dicetak.
            </div>

            <!-- Main content -->
            <div class="invoice p-3 mb-3">
                <!-- title row -->
                <div class="row">
                    <div class="col-12">
                        <h4>
                            <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo" class="brand-image img-circle elevation-1" style="max-height: 30px;">
                                Primkop Polrestabes Semarang
                            <small class="float-right">Tanggal: {{ date('d-m-Y', strtotime($simpanan->tanggal)) }}</small>
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
                            Jl. Kaligarang No.1A, Petompon<br>
                            Semarang<br>
                            Phone: 0895-2458-3818<br>
                            Email: -
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                        To
                        <address>
                            <strong>{{ $simpanan->anggota->nama_anggota }}</strong><br>
                            {{ $simpanan->anggota->alamat }}<br>
                            Whatsapp: {{ $simpanan->anggota->no_wa }}<br>
                            Email: {{ $simpanan->anggota->email }}
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                        <b>Simpanan No# {{ $simpanan->kode_simpanan }}</b><br>
                        <b>Kode Anggota: {{ $simpanan->anggota->kd_anggota }}</b>
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
                                    <th>Jumlah Simpanan</th>
                                    <th>Jenis Simpanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{ number_format($simpanan->nominal, 2, ',', '.') }}</td>
                                    <td>
                                        @if ($simpanan->jenis_simpanan == 1)
                                            Simpanan Pokok
                                        @elseif ($simpanan->jenis_simpanan == 2)
                                            Simpanan Wajib
                                        @else
                                            Simpanan Sukarela
                                        @endif
                                    </td>
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
                        <p class="lead">Keterangan:</p>

                        <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                            {{ $simpanan->keterangan }}
                        </p>
                    </div>
                    <!-- /.col -->
                    <div class="col-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:50%">Total:</th>
                                    <td>Rp. {{ number_format($simpanan->nominal, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th style="width:50%">Pembayaran:</th>
                                    <td>{{ ($simpanan->status == 0) ? 'BELUM LUNAS' : 'LUNAS' }}</td>
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
                        <a href="{{ route('data.print-show', $simpanan->id) }}" rel="noopener" target="_blank" class="btn btn-info float-right"><i
                                class="fas fa-print"></i> Print</a>
                        <a href="{{ route('data.index') }}" class="btn btn-light float-right" style="margin-right: 5px;"><i></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
