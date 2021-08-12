@extends('Simpan_Pinjam.layout')

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
@if (auth()->user()->role != 'bendahara')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><b>Pinjaman</b> <b class="text-warning">(Proses)</b></h3>
                <a href="{{ route('pengajuan.create') }}" class="btn btn-sm btn-primary float-right">Tambah
                    Pengajuan</a>
                <br><span class="text-secondary">Silahkan klik <button class="btn btn-info btn-xs"><i
                            class="far fa-plus-square"></i>&nbsp;Proses</button> untuk menyetujui atau <button
                        class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button> untuk menolak
                    pengajuan pinjaman berikut.</span>
            </div>
            <div class="card-body">
                <table id="table-pinjaman" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pinjaman</th>
                            <th>Kode Anggota</th>
                            <th>Tanggal</th>
                            <th class="text-center">Nama Anggota</th>
                            <th class="text-center">Nominal Pinjaman</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><b>Pinjaman</b> <b class="text-success">(Disetujui)</b></h3>
            <br><span class="text-secondary">Silahkan klik <button class="btn btn-success btn-xs"><i
                        class="far fa-check-square"></i>&nbsp;Cair</button> pada pengajuan pinjaman berikut apabila
                uang telah dicairkan.</span>
        </div>
        <div class="card-body">
            <table id="table-pinjaman-acc" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pinjaman</th>
                        <th>Kode Anggota</th>
                        <th>Tanggal</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">Nominal Pinjaman</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
</div>
@if (auth()->user()->role != 'bendahara')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><b>Pinjaman</b> <b class="text-primary">(Sudah Dicairkan)</b></h3>
            </div>
            <div class="card-body">
                <table id="table-pinjaman-cair" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pinjaman</th>
                            <th>Kode Anggota</th>
                            <th>Tanggal</th>
                            <th class="text-center">Nama Anggota</th>
                            <th class="text-center">Nominal Pinjaman</th>
                            <th>Status</th>
                            <th>Kode Jurnal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
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
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "deferRender": true,
            "columnDefs": [{
                    "targets": 0,
                    "className": "text-center",
                },
                {
                    "targets": 1,
                    "className": "text-center",
                },
                {
                    "targets": 2,
                    "className": "text-center",
                },
                {
                    "targets": 3,
                    "className": "text-center",
                },
                {
                    "targets": 5,
                    "className": "text-right",
                },
                {
                    "targets": 6,
                    "className": "text-center",
                },
                {
                    "targets": 7,
                    "className": "text-center",
                }
            ],
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
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "deferRender": true,
            "columnDefs": [{
                    "targets": 0,
                    "className": "text-center",
                },
                {
                    "targets": 1,
                    "className": "text-center",
                },
                {
                    "targets": 2,
                    "className": "text-center",
                },
                {
                    "targets": 3,
                    "className": "text-center",
                },
                {
                    "targets": 5,
                    "className": "text-right",
                },
                {
                    "targets": 6,
                    "className": "text-center",
                },
                {
                    "targets": 7,
                    "className": "text-center",
                }
            ],
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

        $('#table-pinjaman-cair').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "deferRender": true,
            "columnDefs": [{
                    "targets": 0,
                    "className": "text-center",
                },
                {
                    "targets": 1,
                    "className": "text-center",
                },
                {
                    "targets": 2,
                    "className": "text-center",
                },
                {
                    "targets": 3,
                    "className": "text-center",
                },
                {
                    "targets": 5,
                    "className": "text-right",
                },
                {
                    "targets": 6,
                    "className": "text-center",
                },
                {
                    "targets": 7,
                    "className": "text-center",
                },
                {
                    "targets": 8,
                    "className": "text-center",
                }
            ],
            "ajax": {
                url: "{{ route('pengajuan.index') }}?type=cair"
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
                    data: 'jurnal'
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
