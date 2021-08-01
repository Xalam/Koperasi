@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Sisa Hasil Usaha')

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
    <li class="breadcrumb-item active">Sisa Hasil Usaha</li>
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
                                <input type="text" class="form-control" name='range_tanggal' id="reservation"
                                    placeholder="Dari tanggal / Sampai tanggal">
                            </div>
                        </div>
                        <div class="col-6 text-right" style="margin-top: 15px;">
                            <form action="{{ route('shu.show') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="text" class="form-control form-control-sm" name="start_date" id="start-date"
                                    hidden>
                                <input type="text" class="form-control form-control-sm" name="end_date" id="end-date"
                                    hidden>
                                <button type="submit" class="btn btn-sm btn-info"><i
                                        class="fas fa-search"></i>&nbsp;Tampil</button>
                            </form>
                        </div>
                    </div>
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
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        var start_date;
        var end_date;

        $(function() {

            $('#reservation').daterangepicker({
                autoUpdateInput: false
            });

            $('#reservation').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + '/' + picker.endDate.format(
                    'DD-MM-YYYY'));
                start_date = picker.startDate.format('YYYY-MM-DD');
                end_date = picker.endDate.format('YYYY-MM-DD');
                $('#start-date').attr('value', start_date);
                $('#end-date').attr('value', end_date);
            });

            $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                start_date = '';
                end_date = '';
                $('#start-date').attr('value', start_date);
                $('#end-date').attr('value', end_date);
            });
        });
    </script>
@endsection
