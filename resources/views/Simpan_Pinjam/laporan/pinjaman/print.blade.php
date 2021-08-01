@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Laporan Pinjaman')

    @push('style')

    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item active">Cetak Laporan Pinjaman</li>
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
                        <a href="{{ route('lap-pinjaman.print-show', $anggota->id) }}" rel="noopener" target="_blank"
                            class="btn btn-info float-right"><i class="fas fa-print"></i> Print</a>
                        <a href="{{ route('lap-pinjaman.index') }}" class="btn btn-default float-right"
                            style="margin-right: 5px;"><i></i> Kembali</a>
                    </div>
                </div><br>
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
                    <h3><b>Laporan Pinjaman Anggota</b></h3><br>
                    <h3 style="margin-top: -30px; margin-bottom: 20px;"><b>Primer Koperasi Polrestabes Semarang</b></h3>
                </div>
                <div>
                    <address>
                        <div class="col-12">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="15%">Kode Anggota</td>
                                    <td>: {{ $anggota->kd_anggota }}</td>
                                </tr>
                                <tr>
                                    <td width="15%">Nama Anggota</td>
                                    <td>: {{ $anggota->nama_anggota }}</td>
                                </tr>
                            </table>
                        </div>
                    </address>
                </div>
                <!-- Table row -->
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode Pinjaman</th>
                                    <th class="text-center">Tanggal Pinjaman</th>
                                    <th class="text-center">Anggota</th>
                                    <th class="text-center">Pokok Pinjaman (Rp)</th>
                                    <th class="text-center">Jangka Waktu (x)</th>
                                    <th class="text-center">Sisa Angsuran (x)</th>
                                    <th class="text-center">Angsuran Pokok (Rp)</th>
                                    <th class="text-center">Angsuran Bunga (Rp)</th>
                                    <th class="text-center">Jumlah Angsuran (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($pinjaman as $pin)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td class="text-center">{{ $pin->kode_pinjaman }}</td>
                                        <td class="text-center">{{ date('d-m-Y', strtotime($pin->tanggal)) }}</td>
                                        <td class="text-center">
                                            {{ $pin->anggota->kd_anggota . ' / ' . $pin->anggota->nama_anggota }}</td>
                                        <td class="text-right">{{ number_format($pin->nominal_pinjaman, 2, ',', '.') }}
                                        </td>
                                        <td class="text-center">{{ $pin->tenor }}</td>
                                        <td class="text-center">{{ $pin->tenor - $pin->angsuran_ke }}</td>
                                        <td class="text-right">
                                            {{ number_format($pin->nominal_pinjaman / $pin->tenor, 2, ',', '.') }}</td>
                                        <td class="text-right">
                                            {{ number_format(($pin->total_pinjaman - $pin->nominal_pinjaman) / $pin->tenor, 2, ',', '.') }}
                                        </td>
                                        <td class="text-right">{{ number_format($pin->nominal_angsuran, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-center"><b>Jumlah</b></td>
                                    <td class="text-right"><b>{{ number_format($sumPinjaman, 2, ',', '.') }}</b></td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </div>
    </div>
@endsection
