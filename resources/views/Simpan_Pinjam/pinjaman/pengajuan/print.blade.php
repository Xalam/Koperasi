@extends('Simpan_Pinjam.layout')

@section('title', 'Pinjaman')

@section('content_header', 'Cetak Pengajuan Pinjaman')

    @push('style')

    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pengajuan.index') }}">Pengajuan Pinjaman</a></li>
    <li class="breadcrumb-item active">Cetak Pengajuan Pinjaman</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Note:</h5>
                Halaman ini akan dicetak. Periksa kembali detail pinjaman di bawah sebelum dicetak.
            </div>

            <!-- Main content -->
            <div class="invoice p-3 mb-3">
                <!-- title row -->
                <div class="row">
                    <div class="col-12">
                        <h4>
                            <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo"
                                class="brand-image img-circle elevation-1" style="max-height: 30px;">
                            Primkop Polrestabes Semarang
                            <small class="float-right">Tanggal: {{ date('d-m-Y', strtotime($pinjaman->tanggal)) }}</small>
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
                                    <td>: Rp. {{ number_format($pinjaman->nominal_pinjaman, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th style="width:50%">Biaya Provisi</th>
                                    <td>: Rp. {{ number_format($pinjaman->biaya_provisi, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th style="width:50%">Biaya Asuransi</th>
                                    <td>: Rp. {{ number_format($pinjaman->biaya_asuransi, 2, ',', '.') }}</td>
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
                <div class="row no-print">
                    <div class="col-12">
                        <a href="{{ route('pengajuan.print-show', $pinjaman->id) }}" rel="noopener" target="_blank"
                            class="btn btn-info float-right"><i class="fas fa-print"></i> Print</a>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-default float-right"
                            style="margin-right: 5px;"><i></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
