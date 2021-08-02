@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Sisa Hasil Usaha')

    @push('style')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('shu.index') }}">Sisa Hasil Usaha</a></li>
    <li class="breadcrumb-item active">Tampil Sisa Hasil Usaha</li>
@endsection

@section('content_main')
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-6">
            <a href="{{ route('shu.index') }}" class="btn btn-default">Kembali</a>
        </div>
        <div class="col-6 text-right">
            <form action="{{ route('shu.print-show') }}" method="post" enctype="multipart/form-data">
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
                        <h3 class="card-title"><b>Sisa Hasil Usaha</b></h3>
                    </div>
                    <div class="col-12 text-right">
                        @if (isset($startDate) && isset($endDate))
                            Periode: {{ $startDate }} / {{ $endDate }}
                        @else
                            Periode: {{ date('d-m-Y') }}
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table id="table-shu" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Kode Akun</th>
                                <th class="text-center">Nama Akun</th>
                                <th class="text-center">(Rp)</th>
                                <th class="text-center">Total (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($valueAkunFour as $four)
                                <tr>
                                    <td class="text-center">{{ $four->kode_akun }}</td>
                                    <td>{{ $four->nama_akun }}</td>
                                    <td class="text-right">{{ number_format($four->total * -1, 2, ',', '.') }}</td>
                                    <td class="text-right"></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td class="text-center"><b>Total Pendapatan</b></td>
                                <td></td>
                                <td class="text-right"><b>{{ number_format($sumAkunFour * -1, 2, ',', '.') }}</b></td>
                            </tr>

                            @foreach ($valueAkunFive as $five)
                                <tr>
                                    <td class="text-center">{{ $five->kode_akun }}</td>
                                    <td>{{ $five->nama_akun }}</td>
                                    <td class="text-right"></td>
                                    <td class="text-right">{{ number_format($five->total * -1, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td class="table-secondary"></td>
                                <td class="text-center table-secondary"><b>Laba Kotor</b></td>
                                <td class="table-secondary"></td>
                                <td class="text-right"><b>{{ number_format($labaKotor, 2, ',', '.') }}</b></td>
                            </tr>

                            @foreach ($valueAkunSix as $six)
                                <tr>
                                    <td class="text-center">{{ $six->kode_akun }}</td>
                                    <td>{{ $six->nama_akun }}</td>
                                    <td class="text-right">{{ number_format($six->total, 2, ',', '.') }}</td>
                                    <td class="text-right"></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td class="text-center"><b>Total Beban Administrasi dan Umum</b></td>
                                <td class="text-right"><b>{{ number_format($sumAkunSix, 2, ',', '.') }}</b></td>
                                <td></td>
                            </tr>

                            @foreach ($valueAkunSixThree as $sixThree)
                                <tr>
                                    <td class="text-center">{{ $sixThree->kode_akun }}</td>
                                    <td>{{ $sixThree->nama_akun }}</td>
                                    <td class="text-right">{{ number_format($sixThree->total, 2, ',', '.') }}</td>
                                    <td class="text-right"></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td class="text-center"><b>Total Beban Organisasi</b></td>
                                <td class="text-right"><b>{{ number_format($sumAkunSixThree, 2, ',', '.') }}</b></td>
                                <td></td>
                            </tr>

                            @foreach ($valueAkunSixFour as $sixFour)
                                <tr>
                                    <td class="text-center">{{ $sixFour->kode_akun }}</td>
                                    <td>{{ $sixFour->nama_akun }}</td>
                                    <td class="text-right">{{ number_format($sixFour->total, 2, ',', '.') }}</td>
                                    <td class="text-right"></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td class="text-center"><b>Total Beban</b></td>
                                <td></td>
                                <td class="text-right"><b>{{ number_format($totalBeban, 2, ',', '.') }}</b></td>
                            </tr>

                            <tr>
                                <td class="table-secondary"></td>
                                <td class="text-center table-secondary"><b>Pendapatan Operasional</b></td>
                                <td class="table-secondary"></td>
                                <td class="text-right"><b>{{ number_format($pendapatanOperasional, 2, ',', '.') }}</b>
                                </td>
                            </tr>

                            @foreach ($valueAkunFourTwo as $fourTwo)
                                <tr>
                                    <td class="text-center">{{ $fourTwo->kode_akun }}</td>
                                    <td>{{ $fourTwo->nama_akun }}</td>
                                    <td class="text-right">{{ number_format($fourTwo->total * -1, 2, ',', '.') }}</td>
                                    <td class="text-right"></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td class="text-center"><b>Total Pendapatan di Luar Usaha</b></td>
                                <td></td>
                                <td class="text-right"><b>{{ number_format($sumAkunFourTwo * -1, 2, ',', '.') }}</b></td>
                            </tr>

                            <tr>
                                <td colspan="3" class="table-secondary text-center"><b>SHU Sebelum Pajak</b></td>
                                <td class="text-right"><b>{{ number_format($sumSHU, 2, ',', '.') }}</b></td>
                            </tr>

                            <tr>
                                <td colspan="3" class="text-center"><b>Pajak</b></td>
                                <td class="text-right"><b>{{ number_format($pajakSHU, 2, ',', '.') }}</b></td>
                            </tr>

                            <tr>
                                <td colspan="3" class="text-center"><b>SHU Setelah Pajak</b></td>
                                <td class="text-right"><b>{{ number_format($sumSHUPajak, 2, ',', '.') }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <h5><b>Pembagian Sisa Hasil Usaha</b></h5>
                    <table border="0">
                        @foreach ($pembagian as $key => $pem)
                            <tr>
                                <td width="60%">{{ $pem->nama }}</td>
                                <td width="10%" class="text-center">{{ $pem->angka }} %</td>
                                <td width="30%" class="text-right">
                                    {{ number_format($calculatePembagian[$key], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><b>JUMLAH</b></td>
                            <td class="text-center"><b>100%</b></td>
                            <td class="text-right"><b>{{ number_format($sumSHUPajak, 2, ',', '.') }}</b></td>
                        </tr>
                    </table>
                    <br>
                    <h5><b>Pembagian Sisa Hasil Usaha Jasa Anggota</b></h5>
                    <table id="table-jasa" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">NRP</th>
                                <th class="text-center">Nama Anggota</th>
                                <th class="text-center">Simpanan Wajib (Rp)</th>
                                <th class="text-center">Simpanan Sukarela (Rp)</th>
                                <th class="text-center">Pinjaman (Rp)</th>
                                <th class="text-center">Belanja Toko (Rp)</th>
                                <th class="text-center">Keaktifan Anggota (Rp)</th>
                                <th class="text-center">Pembagian SHU (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($jasaAnggota as $key => $item)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ $item->username }}</td>
                                    <td>{{ $item->nama_anggota }}</td>
                                    <td class="text-right">{{ number_format($item->wajib, 0, '', '.') }}</td>
                                    <td class="text-right">{{ number_format($item->sukarela, 0, '', '.') }}</td>
                                    <td class="text-right">{{ number_format($item->total_pinjaman, 0, '', '.') }}</td>
                                    <td class="text-right">{{ number_format($item->total_penjualan, 0, '', '.') }}</td>
                                    <td class="text-right">{{ number_format($item->keaktifan_anggota, 0, '', '.') }}</td>
                                    <td class="text-right">{{ number_format($pembagianSHU[$key], 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-center"><b>JUMLAH</b></td>
                                <td class="text-right"><b>{{ number_format($jumWajib, 0, '', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($jumSukarela, 0, '', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($jumPinjaman, 0, '', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($jumPenjualan, 0, '', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($jumAktifAnggota, 0, '', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($jumPembagianSHU, 2, ',', '.') }}</b></td>
                            </tr>
                        </tfoot>
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
    <script>
        $(function() {
            $('#table-jasa').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "scrollX": true
            });
        });
    </script>
@endsection
