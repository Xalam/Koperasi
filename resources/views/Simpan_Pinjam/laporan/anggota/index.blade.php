@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Data Anggota')

    @push('style')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item active">Data Anggota</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Anggota</h3>
                    <a href="{{ route('data-anggota.print-show') }}" class="btn btn-info float-right btn-sm"><i class="fas fa-print"></i>&nbsp;Cetak</a>
                </div>
                <div class="card-body">
                    <table id="table-anggota" class="table table-outline table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Anggota</th>
                                <th class="text-center">Nama Anggota</th>
                                <th>Gender</th>
                                <th>Agama</th>
                                <th>TTL</th>
                                <th class="text-center">Alamat</th>
                                <th>No HP</th>
                                <th>No WA</th>
                                <th class="text-center">Jabatan</th>
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
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>

    <script>

        $(function() {
            $('#table-anggota').DataTable({
                "lengthChange": true,
                "searching": false,
                "deferRender": true,
                "ajax": {
                    url: "{{ route('data-anggota.index') }}"
                },
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-center"
                    },
                    {
                        "targets": 1,
                        "className": "text-center"
                    },
                    {
                        "targets": 3,
                        "className": "text-center"
                    },
                    {
                        "targets": 4,
                        "className": "text-center"
                    },
                    {
                        "targets": 5,
                        "className": "text-right"
                    },
                    {
                        "targets": 7,
                        "className": "text-right"
                    },
                    {
                        "targets": 8,
                        "className": "text-center"
                    },
                ],
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
                        data: 'gender'
                    },
                    {
                        data: 'agama'
                    },
                    {
                        data: 'ttl'
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: 'no_hp'
                    },
                    {
                        data: 'no_wa'
                    },
                    {
                        data: 'jabatan'
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
            $(document).Toasts('create', {
                class: 'bg-warning',
                title: 'Peringatan!',
                subtitle: '',
                body: "{{ session()->get('error') }}"
            })

        </script>
    @endif
@endsection
