@extends('simpan_pinjam.layout')

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
                Halaman ini akan dicetak.
            </div>

            <!-- Main content -->
            <div class="invoice p-3 mb-3">
                <!-- title row -->
                <div class="row no-print">
                    <div class="col-12">
                        <a href="{{ route('lap-simpanan.print-show', $anggota->id) }}" rel="noopener" target="_blank" class="btn btn-info float-right"><i
                                class="fas fa-print"></i> Print</a>
                        <a href="{{ route('lap-simpanan.index') }}" class="btn btn-default float-right" style="margin-right: 5px;"><i></i> Kembali</a>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-12">
                        <h4>
                            <small class="float-right">Tanggal Cetak: {{ date('d-m-Y') }}</small>
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
                                Jl. Kaligarang No.1A, Petompon<br>
                                Semarang<br>
                                Phone: 0895-2458-3818<br>
                                Email: -
                            </address>
                        </div>
                </div>
                <div class="text-center">
                    <h3><b>Laporan Simpanan Anggota</b></h3><br>
                    <h3 style="margin-top: -30px; margin-bottom: 20px;"><b>Primer Koperasi Polrestabes Semarang</b></h3>
                </div>
                <div>
                    <address>
                        Kode Anggota: {{ $anggota->kd_anggota }} <br>
                        Nama Anggota: {{ $anggota->nama_anggota }}
                    </address>
                </div>
                <!-- Table row -->
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Simpanan</th>
                                    <th>Tanggal Simpan</th>
                                    <th>Jenis Simpanan</th>
                                    <th>Jumlah Simpan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($simpanan as $sim)
                                    <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $sim->kode_simpanan }}</td>
                                    <td>{{ date('d-m-Y', strtotime($sim->tanggal)) }}</td>
                                    <td>
                                        @if ($sim->jenis_simpanan == 1)
                                            Simpanan Pokok
                                        @elseif ($sim->jenis_simpanan == 2)
                                            Simpanan Wajib
                                        @else
                                            Simpanan Sukarela
                                        @endif
                                    </td>
                                    <td>Rp. {{ number_format($sim->nominal, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.col -->
                </div>                
            </div>
        </div>
    </div>
@endsection
