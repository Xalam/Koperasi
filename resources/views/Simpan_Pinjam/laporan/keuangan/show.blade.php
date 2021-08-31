@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Posisi Keuangan')

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endpush

@push('custom-style')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('keuangan.index') }}">Posisi Keuangan</a></li>
    <li class="breadcrumb-item active">Tampil Posisi Keuangan</li>
@endsection

@section('content_main')
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-6">
            <a href="{{ route('keuangan.index') }}" class="btn btn-default">Kembali</a>
        </div>
        <div class="col-6 text-right">
            <form action="{{ route('keuangan.print-show') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="text" class="form-control form-control-sm" name="start_date" id="start-date"
                    value="{{ isset($reqStart) ? $reqStart : '' }}" hidden>
                <input type="text" class="form-control form-control-sm" name="end_date" id="end-date"
                    value="{{ isset($reqEnd) ? $reqEnd : '' }}" hidden>
                <button type="submit" id="btn-cetak" class="btn btn-info"><i class="fas fa-print"></i>&nbsp;Cetak</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-12">
                        <h3 class="card-title">Posisi Keuangan</h3>
                    </div>
                    <div class="col-12 text-right">
                        @if (isset($startDate) && isset($endDate))
                            <h3 class="card-title" style="float: right;">Periode : {{ $startDate }} /
                                {{ $endDate }}
                            </h3>
                        @else
                            Periode : {{ date('m-Y') }}
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table id="table-keuangan" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Nama Akun</th>
                                <th class="text-center">Saldo (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
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
                                    <td class="text-right">{{ number_format($saldoPenyertaan[$key], 2, ',', '.') }}</td>
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
                                    <td class="text-right">{{ number_format($saldoTidakLancar[$key], 2, ',', '.') }}</td>
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
                                        <td class="text-right">{{ number_format($saldoPendek[$key] * -1, 2, ',', '.') }}
                                        </td>
                                    @else
                                        <td class="text-right">{{ number_format($saldoPendek[$key], 2, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td><b>Total Kewajiban Jangka Pendek</b></td>
                                @if ($sumPendek < 0)
                                    <td class="text-right"><b>{{ number_format($sumPendek * -1, 2, ',', '.') }}</b></td>
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
                                        <td class="text-right">{{ number_format($saldoPanjang[$key] * -1, 2, ',', '.') }}
                                        </td>
                                    @else
                                        <td class="text-right">{{ number_format($saldoPanjang[$key], 2, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td><b>Total Kewajiban Jangka Panjang</b></td>
                                @if ($sumPanjang < 0)
                                    <td class="text-right"><b>{{ number_format($sumPanjang * -1, 2, ',', '.') }}</b></td>
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
                                        <td class="text-right">{{ number_format($saldoEkuitas[$key] * -1, 2, ',', '.') }}
                                        </td>
                                    @else
                                        <td class="text-right">{{ number_format($saldoEkuitas[$key], 2, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td><b>TOTAL EKUITAS</b></td>
                                @if ($sumEkuitas < 0)
                                    <td class="text-right"><b>{{ number_format($sumEkuitas * -1, 2, ',', '.') }}</b></td>
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
            </div>
        </div>
    </div>
@endsection

@push('before-script')

@endpush

@section('script')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(function() {
            $('#table-keuangan').DataTable({
                "paging": false,
                "ordering": false,
                "searching": false,
                "lengthChange": false
            });
        });
    </script>

    @if (session()->has('error'))
        <script>

        </script>
    @endif
@endsection
