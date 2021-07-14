@extends('Simpan_Pinjam.layout')

@section('title', 'Simpanan')

@section('content_header', 'Edit Simpanan')

    @push('style')
        <link rel="stylesheet"
            href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Simpanan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('data.index') }}">Data Simpanan</a></li>
    <li class="breadcrumb-item active">Edit Simpanan</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Edit Simpanan</h3>
                </div>
                <div class="card-body mx-auto col-md-6">
                    <form id="form-data" action="{{ route('data.edit-all', $simpanan->id) }}" role="form" method="post"
                        autocomplete="off">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="kode-simpanan">Kode Simpanan</label>
                            <input type="text" class="form-control" id="kode-simpanan"
                                value="{{ $simpanan->kode_simpanan }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="id-anggota">Nama Anggota</label>
                            <input type="text" class="form-control" id="id-anggota"
                                value="{{ $simpanan->anggota->kd_anggota . '-' . $simpanan->anggota->nama_anggota }}"
                                disabled>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <div class="input-group date" id="tanggal" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#tanggal"
                                    name="tanggal" value="{{ $simpanan->tanggal }}" placeholder="Tanggal" disabled />
                                <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label>Nominal</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Masukkan jumlah nominal" id="nominal"
                                    value="{{ $simpanan->nominal }}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jenis Simpanan</label>
                            <select class="form-control select2" style="width: 100%;" name="jenis_simpanan">
                                <option {{ $simpanan->jenis_simpanan == 1 ? 'selected="selected"' : '' }} value="1">Simpanan Pokok</option>
                                <option {{ $simpanan->jenis_simpanan == 2 ? 'selected="selected"' : ''}} value="2">Simpanan Wajib</option>
                                <option {{ $simpanan->jenis_simpanan == 3 ? 'selected="selected"' : ''}} value="3">Simpanan Sukarela</option>
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label>Pembayaran</label>
                            <select class="form-control select2" style="width: 100%;" name="status">
                                <option {{ $simpanan->status == 0 ? 'selected="selected"' : '' }} value="0">Belum Bayar
                                </option>
                                <option {{ $simpanan->status == 1 ? 'selected="selected"' : '' }} value="1">Sudah Bayar
                                </option>
                            </select>
                        </div>
                        {{-- <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan keterangan" value="{{ $simpanan->keterangan }}">
                        </div> --}}
                        <a href="{{ route('data.index') }}" class="btn btn-light">Kembali</a>&nbsp;
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <script src="{{ asset('assets/plugins/jquery-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
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
            $('#nominal').mask('#.##0', {
                reverse: true
            });

            // $('#tanggal').datetimepicker({
            //     format: 'YYYY-MM-DD'
            // });

            $('.select2').select2();

            $.validator.setDefaults({
                submitHandler: function() {
                    form.submit();
                }
            });

            $('#form-data').validate({
                rules: {
                    tanggal: {
                        required: true
                    },
                    nominal: {
                        required: true
                    },
                },
                messages: {
                    tanggal: "Tanggal wajib diisi",
                    nominal: "Nominal wajib diisi",
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        })
    </script>
@endsection
