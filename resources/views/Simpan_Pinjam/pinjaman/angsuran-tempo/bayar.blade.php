@extends('Simpan_Pinjam.layout')

@section('title', 'Pinjaman')

@section('content_header', 'Pembayaran Angsuran')

@push('style')
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tempo.index') }}">Pelunasan Sebelum Jatuh Tempo</a></li>
    <li class="breadcrumb-item active">Pembayaran</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
                <!-- Table row -->
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Pinjaman</th>
                                    <th>Kode Anggota</th>
                                    <th>Nama Anggota</th>
                                    <th>Pokok Pinjaman</th>
                                    <th>Bunga</th>
                                    <th>Jangka Waktu</th>
                                    <th>Sisa Angsuran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $data->kode_pinjaman }}</td>
                                    <td>{{ $data->anggota->kd_anggota }}</td>
                                    <td>{{ $data->anggota->nama_anggota }}</td>
                                    <td>Rp. {{ number_format($data->nominal_pinjaman, 2, ',', '.') }}</td>
                                    <td>{{ $data->bunga }} %</td>
                                    <td>{{ $data->tenor }} x</td>
                                    <td>{{ $data->tenor - $data->angsuran_ke }} x</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <form id="form-angsuran" action="{{ route('tempo.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-6">

                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                            <div class="___class_+?12___">
                                <table class="table">
                                    <tr>
                                        <th style="width:50%">Tanggal Pelunasan</th>
                                        <td>
                                            <input type="text" class="form-control" id="tanggal"
                                                value="{{ date('Y-m-d') }}" disabled>
                                            <input type="text" class="form-control" name="tanggal"
                                                value="{{ date('Y-m-d') }}" hidden>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">Potongan</th>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Potongan"
                                                    id="potongan" disabled>
                                                <input type="text" class="form-control" name="potongan" id="hide-potongan"
                                                    hidden>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">Jumlah Bayar</th>
                                        <td>
                                            <input type="text" class="form-control" id="total-bayar" disabled>
                                            <input type="text" class="form-control" name="total_bayar" id="hide-bayar"
                                                hidden>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">Keterangan</th>
                                        <td>
                                            <input type="text" class="form-control" name="keterangan"
                                                placeholder="Keterangan">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-12">
                            <input type="hidden" name="id_pinjaman" value="{{ $data->id }}">
                            <button class="btn btn-info float-right"><i class="fas fa-credit-card"></i>&nbsp; Bayar</button>
                            <a href="{{ route('tempo.index') }}" class="btn btn-default float-right"
                                style="margin-right: 5px;"><i></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <script src="{{ asset('assets/plugins/jquery-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
@endpush

@section('script')
    <script>
        $(function() {
            let bayarAngsuran = @php echo($data->nominal_pinjaman / $data->tenor); @endphp;
            let tenorAngsuran = @php echo($data->tenor - $data->angsuran_ke); @endphp;
            let bunga = @php echo($data->nominal_pinjaman * ($data->bunga / 100)); @endphp;
            let totalBayar = (bayarAngsuran * tenorAngsuran) + bunga;
            let potongan = bunga * tenorAngsuran;

            // Ratusan
            let intNumber = totalBayar.toFixed(0);
            let ratusanSub = intNumber.toString().substring(intNumber.length - 3, intNumber.length);
            let ratusan = parseInt(ratusanSub);
            let bulat = intNumber - ratusan;
            let newRatusan = 0;

            if (ratusan > 0 && ratusan <= 100) {
                newRatusan = 100;
            } else if (ratusan > 100 && ratusan <= 200) {
                $newRatusan = 200;
            } else if (ratusan > 200 && ratusan <= 300) {
                newRatusan = 300;
            } else if (ratusan > 300 && ratusan <= 400) {
                newRatusan = 400;
            } else if (ratusan > 400 && ratusan <= 500) {
                newRatusan = 500;
            } else if (ratusan > 500 && ratusan <= 600) {
                newRatusan = 600;
            } else if (ratusan > 600 && ratusan <= 700) {
                newRatusan = 700;
            } else if (ratusan > 700 && ratusan <= 800) {
                newRatusan = 800;
            } else if (ratusan > 800 && ratusan <= 900) {
                newRatusan = 900;
            } else if (ratusan > 900 && ratusan <= 999) {
                newRatusan = 1000;
            } else {
                newRatusan = ratusan;
            }

            let result = bulat + newRatusan;

            $('#hide-bayar').attr('value', result);
            $('#total-bayar').attr('value', formatMoney(result));
            $('#hide-potongan').attr('value', potongan);
            $('#potongan').attr('value', formatMoney(potongan))

            $('#tanggal').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('#tanggal-input').keydown(function(event) {
                event.preventDefault();
            });

            $('#form-angsuran').validate({
                rules: {
                    tanggal: {
                        required: true
                    }
                },
                messages: {
                    tanggal: "Tanggal wajib diisi",
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });

        function formatMoney(n) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(n);
        }
    </script>
@endsection
