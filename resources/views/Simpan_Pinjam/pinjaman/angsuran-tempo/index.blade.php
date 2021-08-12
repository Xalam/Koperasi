@extends('Simpan_Pinjam.layout')

@section('title', 'Pinjaman')

@section('content_header', 'Pelunasan Sebelum Jatuh Tempo')

@push('style')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
<li class="breadcrumb-item active">Pelunasan Sebelum Jatuh Tempo</li>
@endsection

@section('content_main')
@if (auth()->user()->role != 'bendahara')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form action="{{ route('tempo.bayar') }}" method="post" enctype="multipart/form-data">
                    <div class="row float-right">
                        <b>Masukkan Nomor Pinjaman&nbsp;</b>
                        <div class="input-group">
                            @csrf
                            <input type="search" class="form-control form-control-sm" name="kode_bayar"
                                id="kode-bayar" placeholder="Nomor Pinjaman">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-sm btn-default"><i
                                        class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><b>Pelunasan Sebelum Jatuh Tempo</b></h3>
                <br><span class="text-secondary">Silahkan klik <button class="btn btn-info btn-xs"><i
                            class="far fa-plus-square"></i>&nbsp;Proses</button> untuk menyetujui atau <button
                        class="btn btn-danger btn-xs"><i class="fas fa-trash"></i>&nbsp;Hapus</button> untuk menolak
                    pengajuan pelunasan berikut.</span>
            </div>
            <div class="card-body">
                <table id="table-angsuran" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pinjaman</th>
                            <th>Kode Anggota</th>
                            <th>Tanggal</th>
                            <th class="text-center">Nama Anggota</th>
                            <th class="text-center">Nominal Pelunasan</th>
                            <th class="text-center">Angsuran ke -</th>
                            <th width="10%">Status</th>
                            <th>Bukti Transfer</th>
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
            <h3 class="card-title"><b>Pelunasan Sebelum Jatuh Tempo </b><b class="text-success">(Disetujui)</b></h3>
        </div>
        <div class="card-body">
            <table id="table-angsuran-acc" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pinjaman</th>
                        <th>Kode Anggota</th>
                        <th>Tanggal</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">Nominal Pelunasan</th>
                        <th class="text-center">Angsuran ke -</th>
                        <th width="10%">Status</th>
                        <th>Kode Jurnal</th>
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
<!-- SweetAlert2 -->
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    $(function() {
        $('#table-angsuran').DataTable({
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
                    "className": "text-center"
                },
                {
                    "targets": 9,
                    "className": "text-center"
                }
            ],
            "ajax": {
                url: "{{ route('tempo.index') }}?type=waiting"
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
                    data: 'angsuran'
                },
                {
                    data: 'status'
                },
                {
                    data: 'image'
                },
                {
                    data: 'action'
                }
            ]
        });

        $('#table-angsuran-acc').DataTable({
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
                    "className": "text-center"
                },
                {
                    "targets": 9,
                    "className": "text-center"
                }
            ],
            "ajax": {
                url: "{{ route('tempo.index') }}?type=accept"
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
                    data: 'angsuran'
                },
                {
                    data: 'status'
                },
                {
                    data: 'jurnal'
                },
                {
                    data: 'action'
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

@if (session()->has('error'))
<script>
    Swal.fire({
        title: 'Error!',
        text: '{{ session()->get('error') }}',
        icon: 'error',
        confirmButtonText: 'OK'
    })
</script>
@endif

<script>
    jQuery(document).ready(function($) {
        $('#modalKonfirmasi').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget);
            var modal = $(this);

            modal.find('.modal-content').html('<i>&nbsp;</i>')
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

        </div>
    </div>
</div>
</div>
@endsection
