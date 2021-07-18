@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Buku Besar')

    @push('style')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('buku-besar.index') }}">Buku Besar</a></li>
        <li class="breadcrumb-item active">Tampil Buku Besar</li>
@endsection

@section('content_main')
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-6">
            <a href="{{ route('buku-besar.index') }}" class="btn btn-default">Kembali</a>
        </div>
        <div class="col-6 text-right">
            <form action="{{ route('buku-besar.print-show') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="text" class="form-control form-control-sm" name="id_akun" id="id-akun" value="{{ $akun->id }}" hidden>
                <input type="text" class="form-control form-control-sm" name="start_date" id="start-date" value="{{ (isset($reqStart)) ? $reqStart : '' }}" hidden>
                <input type="text" class="form-control form-control-sm" name="end_date" id="end-date" value="{{ (isset($reqEnd)) ? $reqEnd : '' }}" hidden>
                <button type="submit" id="btn-cetak" class="btn btn-info"><i class="fas fa-print"></i>&nbsp;Cetak</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-12">
                        <h3 class="card-title">Buku Besar ({{ $akun->nama_akun }})</h3>
                    </div>
                    <div class="col-12 text-right">
                        @if (isset($reqStart) && isset($reqEnd))
                            <h3 class="card-title" style="float: right;">Tanggal: {{ $reqStart }} / {{ $reqEnd }}</h3>
                        @else
                            
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table id="table-buku" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Tanggal Jurnal</th>
                                <th class="text-center">Kode Jurnal</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Debet (Rp)</th>
                                <th class="text-center">Kredit (Rp)</th>
                                <th class="text-center">Saldo (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">{{ date('d-m-Y', strtotime($akun->updated_at)) }}</td>
                                <td></td>
                                <td>Saldo Awal ({{ $akun->nama_akun }})</td>
                                <td class="text-right">0,00</td>
                                <td class="text-right">0,00</td>
                                <td class="text-right">{{ number_format($akun->saldo, 2, ',', '.') }}</td>
                            </tr>
                            @foreach($jurnal as $jur)
                                <tr>
                                    <td class="text-center">{{ date('d-m-Y', strtotime($jur->tanggal)) }}</td>
                                    <td class="text-center">{{ $jur->kode_jurnal }}</td>
                                    <td>{{ $jur->keterangan }}</td>
                                    <td class="text-right">{{ number_format($jur->debet, 2, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($jur->kredit, 2, ',', '.') }}</td>
                                    <td class="text-right">
                                        @if ($jur->kredit != 0)
                                            {{ number_format($akun->saldo -= $jur->kredit, 2, ',', '.') }}
                                        @else
                                            {{ number_format($akun->saldo += $jur->debet, 2, ',', '.') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
            $('#table-buku').DataTable({
                "ordering": false
            });
        });

    </script>

    @if (session()->has('error'))
        <script>
            
        </script>
    @endif
@endsection
