@extends('simpan_pinjam.layout')

@section('title', 'Pinjaman')

@section('content_header', 'Pengajuan Pinjaman Anggota')

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
    <li class="breadcrumb-item active">Pengajuan Pinjaman</li>
@endsection

@section('content_main')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengajuan Pinjaman (Proses)</h3>
                    <a href="{{ route('pengajuan.create') }}" class="btn btn-sm btn-primary float-right">Tambah Pengajuan</a>
                </div>
                <div class="card-body">
                    <table id="table-pinjaman" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pinjaman</th>
                                <th>Kode Anggota</th>
                                <th>Tanggal</th>
                                <th>Nama Anggota</th>
                                <th>Nominal Pinjaman</th>
                                <th>Status Acc</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengajuan Pinjaman (Disetujui)</h3>
                </div>
                <div class="card-body">
                    <table id="table-pinjaman-acc" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pinjaman</th>
                                <th>Kode Anggota</th>
                                <th>Tanggal</th>
                                <th>Nama Anggota</th>
                                <th>Nominal Pinjaman</th>
                                <th width="10%">Status</th>
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
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>

    <script>
        $(function() {
            $('#table-pinjaman').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "deferRender": true,
                "ajax": {
                    url: "{{ route('pengajuan.index') }}?type=waiting"
                },
                "columns": [{
                        data: 'no'
                    },
                    {
                        data: 'kode'
                    },
                    {
                        data: 'kode_anggota'
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
                        data: 'status'
                    },
                    {
                        data: 'action'
                    }
                ]
            });

            $('#table-pinjaman-acc').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "deferRender": true,
                "ajax": {
                    url: "{{ route('pengajuan.index') }}?type=accept"
                },
                "columns": [{
                        data: 'no'
                    },
                    {
                        data: 'kode'
                    },
                    {
                        data: 'kode_anggota'
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
                        data: 'status'
                    },
                    {
                        data: 'action',
                        orderable: false
                    }
                ]
            });
        });

    </script>

    @if (session()->has('success'))
        <script>
            toastr.success("{{ session()->get('success') }}");

        </script>
    @endif

    <script>
        jQuery(document).ready(function($) {
            $('#modalKonfirmasi').on('show.bs.modal', function(e) {
                var button = $(e.relatedTarget);
                var modal = $(this);

                modal.find('.modal-content').load(button.data("remote"));
            });

            $('#modalShow').on('show.bs.modal', function(e) {
                var button = $(e.relatedTarget);
                var modal = $(this);

                modal.find('.modal-content').load(button.data("remote"));
            });
        });

    </script>

    <div class="modal" id="modalKonfirmasi" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalShow" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
