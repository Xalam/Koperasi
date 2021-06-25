@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Jurnal Umum')

    @push('style')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
        <!-- daterange picker -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
        <link rel="stylesheet"
            href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item active">Jurnal Umum</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <b>Tampilkan Periode :</b>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-sm" name='range_tanggal'
                                    id="reservation" placeholder="Dari tanggal / Sampai tanggal">
                            </div>
                        </div>
                        <div class="col-6 text-right" style="margin-top: 12px;">
                            <form action="{{ route('jurnal.print-show') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="text" class="form-control form-control-sm" name="start_date" id="start-date" hidden>
                                <input type="text" class="form-control form-control-sm" name="end_date" id="end-date" hidden>
                                <button type="submit" class="btn btn-sm btn-info"><i class="fas fa-print"></i>&nbsp;Cetak</button>
                            </form>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jurnal Umum</h3>
                    <a href="{{ route('jurnal.create') }}" class="btn btn-sm btn-primary float-right">Tambah Jurnal</a>
                </div>
                <div class="card-body">
                    <table id="table-jurnal" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode Jurnal</th>
                                <th class="text-center">Keterangan</th>
                                <th>Kode Akun</th>
                                <th class="text-center">Nama Akun</th>
                                <th class="text-center">Debet (Rp)</th>
                                <th class="text-center">Kredit (Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <th colspan="6"><b>Total</b></th>
                            <th class="text-right"><b>{{ number_format($debet, 2, ',', '.') }}</b></th>
                            <th class="text-right"><b>{{ number_format($kredit, 2, ',', '.') }}</th>
                            <th></th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <!-- date-range-picker -->
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
@endpush

@section('script')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script>

        var start_date;
        var end_date;

        var DateFilterFunction = (function(oSettings, aData, iDataIndex) {
            var dateStart = parseDateValue(start_date);
            var dateEnd = parseDateValue(end_date);

            var evalDate = parseDateValue(aData[1]);
            if ((isNaN(dateStart) && isNaN(dateEnd)) ||
                (isNaN(dateStart) && evalDate <= dateEnd) ||
                (dateStart <= evalDate && isNaN(dateEnd)) ||
                (dateStart <= evalDate && evalDate <= dateEnd)) {
                return true;
            }
            return false;
        });

        function parseDateValue(rawDate) {
            var dateArray= rawDate.split("-");
            var parsedDate= new Date(dateArray[2], parseInt(dateArray[1])-1, dateArray[0]);  // -1 because months are from 0 to 11   
            return parsedDate;
        }

        $(function() {
            var table = $('#table-jurnal').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "scrollX": true,
                "deferRender": true,
                "ajax": {
                    url: "{{ route('jurnal.index') }}"
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
                        "targets": 2,
                        "className": "text-center"
                    },
                    {
                        "targets": 4,
                        "className": "text-center"
                    },
                    {
                        "targets": 6,
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
                        data: 'tanggal'
                    },
                    {
                        data: 'kode'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'kode_akun'
                    },
                    {
                        data: 'nama_akun'
                    },
                    {
                        data: 'debet'
                    },
                    {
                        data: 'kredit'
                    },
                    {
                        data: 'action'
                    }
                ]
            });

            $('#reservation').daterangepicker({
                autoUpdateInput: false
            });

            $('#reservation').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + '/' + picker.endDate.format('DD-MM-YYYY'));
                start_date=picker.startDate.format('DD-MM-YYYY');
                end_date=picker.endDate.format('DD-MM-YYYY');
                $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
                table.draw();
                $('#start-date').attr('value', start_date);
                $('#end-date').attr('value', end_date);
            });

            $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                start_date='';
                end_date='';
                $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
                table.draw();
                $('#start-date').attr('value', start_date);
                $('#end-date').attr('value', end_date);
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

    <script>
        jQuery(document).ready(function($) {
            $('#mymodalJurnal').on('show.bs.modal', function(e) {
                var button = $(e.relatedTarget);
                var modal = $(this);

                modal.find('.modal-content').load(button.data("remote"));
            });
        });

    </script>

    <div class="modal" id="mymodalJurnal" tabindex="-1" role="dialog">
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
