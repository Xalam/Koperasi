@extends('Simpan_Pinjam.layout')

@section('title', 'Simpanan')

@section('content_header', 'Penarikan Simpanan')

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
    <li class="breadcrumb-item"><a href="#">Simpanan</a></li>
    <li class="breadcrumb-item active">Penarikan Simpanan</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Permintaan Penarikan</h3>
                    <a href="{{ route('tarik-saldo.create') }}" class="btn btn-sm btn-primary float-right">Tambah Penarikan</a>
                </div>
                <div class="card-body">
                    <table id="table-permintaan" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th>Tanggal</th>
                                <th class="text-center">Nama Anggota</th>
                                <th class="text-center">Nominal (Rp)</th>
                                <th width="20%">Aksi</th>
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
                    <h3 class="card-title">Permintaan Penarikan Dalam Proses</h3>
                </div>
                <div class="card-body">
                    <table id="table-permintaan-proses" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th>Tanggal</th>
                                <th class="text-center">Nama Anggota</th>
                                <th class="text-center">Nominal (Rp)</th>
                                <th width="20%">Aksi</th>
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
            $('#table-permintaan').DataTable({
                "stateSave": true,
                "deferRender": true,
                "columnDefs": [
                {
                  "targets": 0,
                  "className": "text-center",
                },
                {
                  "targets": 1,
                  "className": "text-center",
                },
                {
                  "targets": 3,
                  "className": "text-right",
                },
                {
                  "targets": 4,
                  "className": "text-center",
                }],
                "ajax": {
                    url: "{{ route('tarik-saldo.index') }}?type=permintaan_masuk"
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
                        data: 'action',
                        orderable: false
                    }
                ]
            });

            $('#table-permintaan-proses').DataTable({
                "stateSave": true,
                "deferRender": true,
                "columnDefs": [
                {
                  "targets": 0,
                  "className": "text-center",
                },
                {
                  "targets": 1,
                  "className": "text-center",
                },
                {
                  "targets": 3,
                  "className": "text-right",
                },
                {
                  "targets": 4,
                  "className": "text-center",
                }],
                "ajax": {
                    url: "{{ route('tarik-saldo.index') }}?type=permintaan_proses"
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

@endsection