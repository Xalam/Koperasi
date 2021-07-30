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
                            <form action="{{ route('shu.print-show') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="text" class="form-control form-control-sm" name="start_date" id="start-date"
                                    hidden>
                                <input type="text" class="form-control form-control-sm" name="end_date" id="end-date"
                                    hidden>
                                <button type="submit" class="btn btn-sm btn-info"><i
                                        class="fas fa-print"></i>&nbsp;Cetak</button>
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
                    <h3 class="card-title">Sisa Hasil Usaha</h3>
                </div>
                <div class="card-body">
                    <table id="table-shu" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Kode Akun</th>
                                <th class="text-center">Nama Akun</th>
                                <th class="text-center">Debet (Rp)</th>
                                <th class="text-center">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><b>Saldo</b></td>
                                <td><b>0</b></td>
                                <td><b>0</b></td>
                            </tr>
                            <tr>
                                <td colspan="3"><b>Laba</b></td>
                                <td><b>0</b></td>
                            </tr>
                        </tfoot>
                    </table>
                    {{ csrf_field() }}
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
            var dateArray = rawDate.split("-");
            var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[
            0]); // -1 because months are from 0 to 11   
            return parsedDate;
        }

        $(function() {
            let _token = $('input[name="_token"]').val();

            filter_table();

            function filter_table(start_date = '', end_date = '') {
                $.ajax({
                    url: "{{ route('shu.show-data') }}",
                    method: "POST",
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        _token: _token
                    },
                    dataType: "json",
                    success: function(data) {
                        let output = '';
                        let foot = '';

                        for (let count = 0; count < data.data.length; count++) {
                            let deb = 0;
                            let kre = 0;

                            if (data.data[count].debet != null) {
                                deb = data.data[count].debet;
                            }

                            if (data.data[count].kredit != null) {
                                kre = data.data[count].kredit;
                            }

                            output += '<tr>';
                            output += '<td  class="text-center">' + data.data[count].kode_akun +
                            '</td>';
                            output += '<td>' + data.data[count].nama_akun + '</td>';
                            output += '<td class="text-right">' + formatMoney(deb) + '</td>'
                            output += '<td class="text-right">' + formatMoney(kre) + '</td></tr>';

                        }

                        foot += '<tr>';
                        foot += '<td colspan="2"><b>Saldo</b></td>';
                        foot += '<td class="text-right"><b>' + formatMoney(data.total_debet) +
                            '</b></td>';
                        foot += '<td class="text-right"><b>' + formatMoney(data.total_kredit) +
                            '</b></td></tr>';
                        foot += '<tr>';
                        foot += '<td colspan="3"><b>Laba</b></td>';
                        foot += '<td class="text-right"><b>' + formatMoney(data.laba) +
                        '</b></td></tr>';

                        $('tbody').html(output);
                        $('tfoot').html(foot);
                    }
                });

                function formatMoney(n) {
                    return new Intl.NumberFormat("id-ID").format(n);
                }
            }

            $('#reservation').daterangepicker({
                autoUpdateInput: false
            });

            $('#reservation').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + '/' + picker.endDate.format(
                    'DD-MM-YYYY'));
                start_date = picker.startDate.format('DD-MM-YYYY');
                end_date = picker.endDate.format('DD-MM-YYYY');
                $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
                filter_table(start_date, end_date);
                // table.draw();
                $('#start-date').attr('value', start_date);
                $('#end-date').attr('value', end_date);
            });

            $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                start_date = '';
                end_date = '';
                $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
                filter_table(start_date, end_date);
                // table.draw();
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
@endsection
