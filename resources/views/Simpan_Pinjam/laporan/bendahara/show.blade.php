@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Laporan Bendahara Pusat')

@push('style')

@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item active">Cetak Laporan Bendahara Pusat</li>
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
                    <div class="col-6">
                        <a href="{{ route('bendahara.index') }}" class="btn btn-default">Kembali</a>
                    </div>
                    <div class="col-6 text-right">
                        <form action="{{ route('bendahara.print-show') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" class="form-control form-control-sm" name="tanggal"
                                value="{{ isset($tanggal) ? $tanggal : '' }}" hidden>
                            <button type="submit" id="btn-cetak" class="btn btn-info"><i
                                    class="fas fa-print"></i>&nbsp;Cetak</button>
                        </form>
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
                    <h3><b>Laporan Piutang Anggota</b></h3><br>
                    <h3 style="margin-top: -30px; margin-bottom: 20px;"><b>Primer Koperasi Polrestabes Semarang</b></h3>
                </div>
                <div class="row" style="margin-bottom: 10px;">
                    Periode : {{ isset($tanggal) ? date('m-Y', strtotime($tanggal)) : date('m-Y') }}
                </div>
                <!-- Table row -->
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">NRP</th>
                                    <th class="text-center">Nama Anggota</th>
                                    <th class="text-center">Tanggal Registrasi</th>
                                    <th class="text-center">Piutang Toko (Rp)</th>
                                    <th class="text-center">Angsuran Pinjaman (Rp)</th>
                                    <th class="text-center">Simpanan Wajib (Rp)</th>
                                    <th class="text-center">Total Piutang (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($anggota as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td class="text-center">{{ $value->username }}</td>
                                        <td>{{ $value->nama_anggota }}</td>
                                        <td class="text-center">{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                        <td class="text-right">
                                            {{ $value->sisa_piutang != null ? number_format($value->sisa_piutang, 0, '', '.') : '-' }}
                                        </td>
                                        <td class="text-right">
                                            {{ $value->nominal_angsuran != null ? number_format($value->nominal_angsuran, 0, '', '.') : '-' }}
                                        </td>
                                        <td class="text-right">
                                            {{ date('Y-m', strtotime($value->created_at)) < $tanggal ? number_format($simpananWajib, 0, '', '.') : '-' }}
                                        </td>
                                        <td class="text-right">{{ number_format($totalPiutang[$key], 0, '', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-center"><b>TOTAL</b></td>
                                    <td class="text-right"><b>{{ number_format($sumHutang, 0, '', '.') }}</b></td>
                                    <td class="text-right"><b>{{ number_format($sumAngsuran, 0, '', '.') }}</b></td>
                                    <td class="text-right"><b>{{ number_format($sumSimpanan, 0, '', '.') }}</b></td>
                                    <td class="text-right"><b>{{ number_format($sumTotalPiutang, 0, '', '.') }}</b></td>
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
