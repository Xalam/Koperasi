@extends('Simpan_Pinjam.layout')

@section('title', 'Simpanan')

@section('content_header', 'Data Simpanan Anggota')

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Simpanan</a></li>
    <li class="breadcrumb-item active">Data Simpanan</li>
@endsection

@section('content_main')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Simpanan Anggota</h3>
                    <a href="{{ route('data.create') }}" class="btn btn-sm btn-primary float-right">Tambah Simpanan</a>
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
                                <th class="text-center">Keterangan</th>
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
            var table = $('#table-simpanan').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
                  "targets": 8,
                  "className": "text-center",
                }],
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
                        data: 'keterangan'
                    },
                    {
                        data: 'action',
                        orderable: false
                    }
                ]
            });

            $('#filter-select').on('change', function() {
                table.ajax.url("{{ route('data.index') }}?filter="+$(this).val()).load();
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
            $('#mymodal').on('show.bs.modal', function(e) {
                var button = $(e.relatedTarget);
                var modal = $(this);

                modal.find('.modal-content').load(button.data("remote"));
            });

            $('#modalKonfirmasi').on('show.bs.modal', function(e) {
                var button = $(e.relatedTarget);
                var modal = $(this);

                modal.find('.modal-content').load(button.data("remote"));
            });
        });

    </script>

    <div class="modal" id="mymodal" tabindex="-1" role="dialog">
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
