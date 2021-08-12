@extends('Simpan_Pinjam.layout')

@section('title', 'Simpanan')

@section('content_header', 'Data Simpanan Anggota')

@push('style')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Simpanan</a></li>
<li class="breadcrumb-item active">Data Simpanan</li>
@endsection

@section('content_main')
@if (auth()->user()->role != 'bendahara')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><b>Data Simpanan Anggota </b><b class="text-warning">(Proses)</b></h3>
                <a href="{{ route('data.create') }}" class="btn btn-sm btn-primary float-right">Tambah
                    Simpanan</a>
                <br><span class="text-secondary">Silahkan klik <button class="btn btn-info btn-xs"><i
                            class="far fa-plus-square"></i>&nbsp;Proses</button> apabila telah dibayar atau <button
                        class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button> untuk menolak
                    pengajuan simpanan berikut.</span>
            </div>
            <div class="card-body">
                <table id="table-simpanan-waiting" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Simpanan</th>
                            <th>Tanggal Simpan</th>
                            <th>Jenis Simpanan</th>
                            <th class="text-center">Nama Anggota</th>
                            <th class="text-center">Nominal Setoran (Rp)</th>
                            <th>Status Bayar</th>
                            <th>Bukti Transfer</th>
                            <th class="text-center">Keterangan</th>
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
            <h3 class="card-title"><b>Data Simpanan Anggota </b><b class="text-success">(Disetujui)</b></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <span>Tampilkan:&nbsp;</span>
                <select id="filter-select" class="form-control-sm col-md-2" style="margin-bottom: 15px;">
                    <option value="all" selected="selected">Semua</option>
                    <option value="pokok">Pokok</option>
                    <option value="wajib">Wajib</option>
                    <option value="sukarela">Sukarela</option>
                </select>
            </div>
            <table id="table-simpanan" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Simpanan</th>
                        <th>Tanggal Simpan</th>
                        <th>Jenis Simpanan</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">Nominal Setoran (Rp)</th>
                        <th>Status Bayar</th>
                        <th>Bukti Transfer</th>
                        <th class="text-center">Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
</div>

{{-- <div class="row">
        <div class="col-12">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Edit Pembayaran Anggota</h3>
                </div>
                <div class="card-body">
                    <form id="form-edit" action="{{ route('data.edit-all') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nama Anggota</label>
                                    <select class="form-control select2" style="width: 100%;" name="id_anggota">
                                        <option selected="selected" value="">Pilih anggota</option>
                                        @foreach ($anggota as $a)
                                            <option value="{{ $a->id }}">
                                                {{ $a->kd_anggota . ' - ' . $a->nama_anggota }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <div class="input-group date" id="tanggal" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tanggal"
                                            name="tanggal" placeholder="Tanggal" />
                                        <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Jenis Simpanan</label>
                                    <select class="form-control select2" style="width: 100%;" name="jenis_simpanan">
                                        <option selected="selected" value="1">Simpanan Pokok</option>
                                        <option value="2">Simpanan Wajib</option>
                                        <option value="3">Simpanan Sukarela</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Pembayaran</label>
                                    <select class="form-control select2" style="width: 100%;" name="status">
                                        <option selected="selected" value="0">Belum Bayar</option>
                                        <option value="1">Sudah Bayar</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float: right;">
                            <div class="" style="margin-right: 30px;">
                                <input type="text" class="form-control form-control-sm" name="start_date" id="start-date"
                                    hidden>
                                <input type="text" class="form-control form-control-sm" name="end_date" id="end-date"
                                    hidden>
                                <button type="submit" class="btn btn-warning"><i
                                        class="fas fa-edit"></i>&nbsp;Perbarui</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('script')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('.select2').select2();

        $('#tanggal').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $('#table-simpanan-waiting').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
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
                    "targets": 9,
                    "className": "text-center",
                }
            ],
            "ajax": {
                url: "{{ route('data.index') }}?type=waiting"
            },
            "columns": [{
                    data: 'no'
                },
                {
                    data: 'kode_simpanan'
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'jenis_simpanan'
                },
                {
                    data: 'nama_anggota'
                },
                {
                    data: 'nominal'
                },
                {
                    data: 'status'
                },
                {
                    data: 'image'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'action',
                    orderable: false
                }
            ]
        });

        var table = $('#table-simpanan').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
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
                    "targets": 9,
                    "className": "text-center",
                }
            ],
            "ajax": {
                url: "{{ route('data.index') }}"
            },
            "columns": [{
                    data: 'no'
                },
                {
                    data: 'kode_simpanan'
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'jenis_simpanan'
                },
                {
                    data: 'nama_anggota'
                },
                {
                    data: 'nominal'
                },
                {
                    data: 'status'
                },
                {
                    data: 'image'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'action',
                    orderable: false
                }
            ]
        });

        $('#filter-select').on('change', function() {
            table.ajax.url("{{ route('data.index') }}?filter=" + $(this).val()).load();
        });

        $.validator.setDefaults({
            submitHandler: function() {
                form.submit();
            }
        });

        $('#form-edit').validate({
            rules: {
                id_anggota: {
                    required: true
                },
                tanggal: {
                    required: true
                },
            },
            messages: {
                id_anggota: "Nama Anggota wajib diisi",
                range_tanggal: "Tanggal wajib diisi",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>

@if (session()->has('success'))
<script>
    toastr.success("{{ session()->get('success') }}");
</script>
@endif

@if (session()->has('success_edit'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session()->get('success_edit') }}',
        icon: 'success',
        confirmButtonText: 'OK'
    })
</script>
@endif

@if (session()->has('error'))
<script>
    Swal.fire({
        title: 'Peringatan!',
        text: '{{ session()->get('error') }}',
        icon: 'warning',
        confirmButtonText: 'OK'
    })
</script>
@endif

<script>
    jQuery(document).ready(function($) {
        $('#modalTransfer').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget);
            var modal = $(this);

            modal.find('.modal-content').html('<i>&nbsp;</i>')
            modal.find('.modal-content').load(button.data("remote"));
        });

        $('#modalKonfirmasi').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget);
            var modal = $(this);

            modal.find('.modal-content').html('<i>&nbsp;</i>')
            modal.find('.modal-content').load(button.data("remote"));
        });
    });
</script>

<div class="modal" id="modalTransfer" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Bukti Transfer</h5>
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

@endsection
