@extends('Simpan_Pinjam.layout')

@section('title', 'Pinjaman')

@section('content_header', 'Angsuran Pinjaman')

    @push('style')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
    <li class="breadcrumb-item active">Angsuran Pinjaman</li>
@endsection

@section('content_main')

    {{-- <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <form action="{{ route('angsuran.bayar') }}" method="post" enctype="multipart/form-data">
                        <div class="row float-right">
                            <b>Masukkan Nomor Pinjaman&nbsp;</b>
                            <div class="input-group">
                                @csrf
                                <input type="search" class="form-control form-control-sm" name="kode_bayar" id="kode-bayar"
                                    placeholder="Nomor Pinjaman">
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
    </div> --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>Angsuran Pinjaman</b></h3>
                    <a href="{{ route('angsuran.create') }}" class="btn btn-sm btn-primary float-right">Tambah
                        Angsuran</a>
                    <br><span class="text-secondary">Silahkan klik <button class="btn btn-success btn-xs"><i
                                class="fas fa-check"></i></button> untuk mengubah penyetujuan atau <button
                            class="btn btn-danger btn-xs">&nbsp;<i class="fas fa-times"></i>&nbsp;</button> untuk mengubah
                        pembatalan angsuran berikut.</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <span>Tampilkan:&nbsp;</span>
                        <div class="input-group date col-3" id="tanggal" data-target-input="nearest">
                            <input type="text" id="tanggal-input" class="form-control datetimepicker-input form-control-sm"
                                data-target="#tanggal" name="tanggal" placeholder="Bulan" />
                            <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                        <button class="btn btn-danger btn-sm" id="button-clear">Hapus</button>
                    </div><br>
                    <table id="table-angsuran" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pinjaman</th>
                                <th>Kode Anggota</th>
                                <th>Tanggal</th>
                                <th class="text-center">Nama Anggota</th>
                                <th class="text-center">Nominal Angsuran</th>
                                <th>Angsuran ke -</th>
                                <th width="10%">Status Bayar</th>
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
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        let dateSearch;

        let DateFilterFunction = (function(settings, data, dataIndex) {
            let dateS = dateSearch;
            let date = parseDateValue(data[3]);

            console.log(dateS);
            console.log(date);
            if (dateS === date) {
                return true;
            }

            if (dateS == '') {
                return true;
            }

            return false;
        });

        function parseDateValue(rawDate) {
            var dateArray = rawDate.split("-");
            var parsedDate = dateArray[2] + '-' + dateArray[1];
            return parsedDate;
        }

        $(function() {
            var tableAng = $('#table-angsuran').DataTable({
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
                    },
                    {
                        "targets": 9,
                        "className": "text-center",
                    }
                ],
                "ajax": {
                    url: "{{ route('angsuran.index') }}"
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

            $('#tanggal').datetimepicker({
                format: 'yyyy-MM',
            });

            $('#tanggal-input').keydown(function(event) {
                event.preventDefault();
            });

            $('#tanggal').on('change.datetimepicker', function() {
                dateSearch = $('#tanggal-input').val();
                $.fn.dataTable.ext.search.push(DateFilterFunction);
                tableAng.draw();
            });

            $('#button-clear').click(function() {
                $('#tanggal-input').val('');
                dateSearch = $('#tanggal-input').val();
                $.fn.dataTable.ext.search.push(DateFilterFunction);
                tableAng.draw();
            });
        });

        function edit_angsuran(id) {
            $('#modalKonfirmasi').modal('hide');
            let statusEdit = $('#status-edit').val();

            $.ajax({
                type: 'POST',
                url: '{{ route('angsuran.update-bayar') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    id: id,
                    status: statusEdit
                },
                success: function(data) {
                    if (data.tanggal) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Tidak dapat diubah. Periode pengubahan sudah terlewat!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }

                    if (data.lunas) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terdapat pelunasan angsuran sebelum jatuh tempo. Tidak dapat mengubah status bayar!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }

                    $('#table-angsuran').DataTable().ajax.reload();
                },
                error: function(e) {
                    alert('Terjadi kesalahan, Harap coba lagi nanti');
                }
            });
        }
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
                modal.find('.modal-content').html('<i class="">&nbsp;</i>');
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
