@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Buku Besar')

    @push('style')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
        <!-- daterange picker -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
        <link rel="stylesheet"
            href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
         <!-- SweetAlert2 -->
         <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item active">Buku Besar</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="row">
                        <div class="col-4">
                            <b>Nama Akun :</b>
                            <select class="form-control select2" name="id_akun">
                                <option selected="selected" style="width: 100%;" value="">Pilih akun</option>
                                @foreach ($akun as $a)
                                    <option value="{{ $a->id }}">{{ $a->kode_akun . ' - ' . $a->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <b>Tampilkan Periode :</b>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" name='range_tanggal'
                                    id="reservation" placeholder="Dari tanggal / Sampai tanggal">
                            </div>
                        </div>
                        <div class="col-4 text-right" style="margin-top: 15px;"> 
                            <form action="{{ route('buku-besar.show-data') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="text" class="form-control form-control-sm" name="id_akun" id="id-akun" hidden>
                                <input type="text" class="form-control form-control-sm" name="start_date" id="start-date" hidden>
                                <input type="text" class="form-control form-control-sm" name="end_date" id="end-date" hidden>
                                <button type="submit" id="btn-tampil" class="btn btn-sm btn-info"><i class="fas fa-search"></i>&nbsp;Tampil</button>
                            </form>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Buku Besar</h3>
                </div>
                <div class="card-body">
                    <table id="table-buku" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Tanggal Jurnal</th>
                                <th class="text-center">Kode Jurnal</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Debet (Rp)</th>
                                <th class="text-center">Kredit (Rp)</th>
                                <th class="text-center">Saldo (Rp)</th>
                            </tr>
                        </thead>
                    </table>
                    {{ csrf_field() }}
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@push('before-script')
    <!-- date-range-picker -->
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

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

        let start_date;
        let end_date;
        let idAkun;

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
            $('.select2').select2();

            let _token = $('input[name="_token"]').val();

            document.getElementById("btn-tampil").disabled = true;

            $('.select2').on('change', function() {
                if (this.value == '') {
                    document.getElementById("btn-tampil").disabled = true;
                } else {
                    idAkun = this.value;
                    $('#id-akun').attr('value', idAkun);
                    document.getElementById("btn-tampil").disabled = false;
                }
            });

            // let table = $('#table-buku').DataTable({
            //     'processing':true,
            //     'serverSide':true,
            //     'ajax':{
            //         url: "{{route('buku-besar.show-data')}}",
            //         type: "POST",
            //         data: {idAkun:idAkun, start_date:start_date, end_date:end_date, _token:_token}
            //     },
            //     "columns": [
            //         {
            //             data: 'tanggal'
            //         },
            //         {
            //             data: 'kode'
            //         },
            //         {
            //             data: 'keterangan'
            //         },
            //         {
            //             data: 'debet'
            //         },
            //         {
            //             data: 'kredit'
            //         },
            //         {
            //             data: 'saldo'
            //         }
            //     ]
            // });

            function formatMoney(n) {
               return new Intl.NumberFormat("id-ID").format(n);
            }

            $('#reservation').daterangepicker({
                autoUpdateInput: false
            });

            $('#reservation').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + '/' + picker.endDate.format('DD-MM-YYYY'));
                start_date=picker.startDate.format('DD-MM-YYYY');
                end_date=picker.endDate.format('DD-MM-YYYY');
                $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
                // table.draw();
                $('#start-date').attr('value', start_date);
                $('#end-date').attr('value', end_date);
            });

            $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                start_date='';
                end_date='';
                $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
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
            Swal.fire({
                title: 'Peringatan!',
                text: '{{ session()->get('error') }}',
                icon: 'warning',
                confirmButtonText: 'OK'
            })

        </script>
    @endif
@endsection
