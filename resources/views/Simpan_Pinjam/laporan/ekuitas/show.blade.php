@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Perubahan Ekuitas')

@push('style')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endpush

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Laporan</a></li>
<li class="breadcrumb-item"><a href="{{ route('ekuitas.index') }}">Perubahan Ekuitas</a></li>
<li class="breadcrumb-item active">Tampil Perubahan Ekuitas</li>
@endsection

@section('content_main')
<div class="row" style="margin-bottom: 15px;">
<div class="col-6">
    <a href="{{ route('ekuitas.index') }}" class="btn btn-default">Kembali</a>
</div>
<div class="col-6 text-right">
    <form action="{{ route('ekuitas.print-show') }}" method="post" enctype="multipart/form-data">
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
                <h3 class="card-title">Perubahan Ekuitas</h3>
            </div>
            <div class="col-12 text-right">
                @if ($startDate != '')
                    <h3 class="card-title" style="float: right;">Tanggal: {{ $startDate }} / {{ $endDate }}
                    </h3>
                @else
                    <h3 class="card-title" style="float: right;">Tanggal: {{ $endDate }}
                    </h3>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table id="table-ekuitas" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Keterangan</th>
                        @foreach ($akun as $a)
                            <th class="text-center">{{ $a->nama_akun }}</th>
                        @endforeach
                        <th class="text-center">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Saldo Awal</td>
                        @foreach ($akun as $a)
                            <td class="text-right">{{ number_format($a->saldo * -1, 2, ',', '.') }}</td>
                        @endforeach
                        <td class="text-right">{{ number_format($totalSaldo * -1, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Penambahan</td>
                        @foreach ($penambahan as $plus)
                            <td class="text-right">{{ number_format($plus, 2, ',', '.') }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Pengurangan</td>
                        @foreach ($pengurangan as $min)
                            <td class="text-right">{{ number_format($min, 2, ',', '.') }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Saldo Akhir</td>
                        @foreach ($saldoAkhir as $sal)
                            <td class="text-right"><b>{{ number_format($sal, 2, ',', '.') }}</b></td>
                        @endforeach
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
        $('#table-ekuitas').DataTable({
            "paging": false,
            "ordering": false,
            "scrollX": true,
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
