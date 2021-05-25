@extends('simpan_pinjam.layout')

@section('title', 'Simpanan')

@section('content_header', 'Riwayat Penarikan Simpanan')

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Simpanan</a></li>
    <li class="breadcrumb-item active">Riwayat Penarikan Simpanan</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Penarikan</h3>
                </div>
                <div class="card-body">
                    <table id="table-history" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th>Tanggal</th>
                                <th>Nama Anggota</th>
                                <th>Nominal</th>
                                <th>Aksi</th>
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

    <script>
        $(function() {
            $('#table-history').DataTable({
                "stateSave": true,
                "deferRender": true,
                "ajax": {
                    url: "{{ route('tarik-saldo.history') }}"
                },
                "columns": [{
                        data: 'no'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'nominal'
                    },
                    {
                        data: 'action'
                    }
                ]
            });
            
        });

    </script>
@endsection
