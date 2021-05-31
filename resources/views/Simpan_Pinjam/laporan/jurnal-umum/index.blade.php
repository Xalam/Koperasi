@extends('simpan_pinjam.layout')

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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jurnal Umum</h3>
                    <a href="{{ route('jurnal.create') }}" class="btn btn-sm btn-primary float-right">Tambah Jurnal</a>
                </div>
                <div class="card-body">
                    <div class="row" style="margin-bottom: 15px;">
                        <b>Tampilkan Periode :</b>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control form-control-sm col-md-4" name='range_tanggal'
                                id="reservation" placeholder="Dari tanggal / Sampai tanggal">
                        </div>
                    </div>
                    <table id="table-jurnal" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode Jurnal</th>
                                <th>Keterangan</th>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th>Debet (Rp)</th>
                                <th>Kredit (Rp)</th>
                            </tr>
                        </thead>
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
                "deferRender": true,
                "ajax": {
                    url: "{{ route('jurnal.index') }}"
                },
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
                    }
                ]
            });

            $('#reservation').daterangepicker({
                autoUpdateInput: false
            });

            $('#reservation').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' / ' + picker.endDate.format('DD-MM-YYYY'));
                start_date=picker.startDate.format('DD-MM-YYYY');
                end_date=picker.endDate.format('DD-MM-YYYY');
                $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
                table.draw();
            });

            $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                start_date='';
                end_date='';
                $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
                table.draw();
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
