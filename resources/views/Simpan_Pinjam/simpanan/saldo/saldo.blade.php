@extends('simpan_pinjam.layout')

@section('title', 'Simpanan')

@section('content_header', 'Saldo Simpanan Anggota')

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Simpanan</a></li>
    <li class="breadcrumb-item active">Saldo Simpanan</li>
@endsection

@section('content_main')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Saldo Simpanan Anggota</h3>
                </div>
                <div class="card-body">
                    <table id="table-saldo" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Anggota</th>
                                <th>Nama Anggota</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>

    <script>
        $(function() {
            $('#table-saldo').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "deferRender": true,
                "ajax": "{{ route('saldo.index') }}",
                "columns": [{
                        data: 'no'
                    },
                    {
                        data: 'kode'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'saldo'
                    }
                ]
            });
        });

    </script>
@endsection
