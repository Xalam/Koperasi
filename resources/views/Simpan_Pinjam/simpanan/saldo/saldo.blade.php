@extends('Simpan_Pinjam.layout')

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
                    <h3 class="card-title"><b>Saldo</b> <b class="text-primary">(Simpanan Sukarela)</b></h3>
                </div>
                <div class="card-body">
                    <table id="table-sukarela" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Anggota</th>
                                <th class="text-center">Nama Anggota</th>
                                <th class="text-center">Saldo (Rp)</th>
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
                    <h3 class="card-title"><b>Saldo</b> <b class="text-success">(Simpanan Wajib)</b></h3>
                </div>
                <div class="card-body">
                    <table id="table-wajib" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Anggota</th>
                                <th class="text-center">Nama Anggota</th>
                                <th class="text-center">Saldo (Rp)</th>
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
                    <h3 class="card-title"><b>Saldo</b> <b class="text-danger">(Simpanan Pokok)</b></h3>
                </div>
                <div class="card-body">
                    <table id="table-pokok" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Anggota</th>
                                <th class="text-center">Nama Anggota</th>
                                <th class="text-center">Saldo (Rp)</th>
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
            $('#table-sukarela').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
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
                        "targets": 3,
                        "className": "text-right",
                    }
                ],
                "ajax": "{{ route('saldo.index') }}?type=sukarela",
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

            $('#table-wajib').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
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
                        "targets": 3,
                        "className": "text-right",
                    }
                ],
                "ajax": "{{ route('saldo.index') }}?type=wajib",
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

            $('#table-pokok').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
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
                        "targets": 3,
                        "className": "text-right",
                    }
                ],
                "ajax": "{{ route('saldo.index') }}?type=pokok",
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
