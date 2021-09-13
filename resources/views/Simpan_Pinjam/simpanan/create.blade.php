@extends('Simpan_Pinjam.layout')

@section('title', 'Simpanan')

@section('content_header', 'Tambah Simpanan')

    @push('style')
        <link rel="stylesheet"
            href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Simpanan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('data.index') }}">Data Simpanan</a></li>
    <li class="breadcrumb-item active">Tambah Simpanan</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary collapsed-card card-outline">
                <div class="card-header">
                    <h3 class="card-title">Tambah Simpanan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-6 mx-auto">
                    <form id="form-data" action="{{ route('data.store') }}" role="form" method="post" autocomplete="off">
                        @csrf
                        <input type="text" class="form-control" name="kode_simpanan" id="kode_simpanan"
                            placeholder="Masukkan kode" hidden>
                        <div class="form-group">
                            <label>Nama Anggota</label>
                            <select class="form-control select2" style="width: 100%;" name="id_anggota">
                                <option selected="selected" value="">Pilih anggota</option>
                                @foreach ($anggota as $a)
                                    <option value="{{ $a->id }}">{{ $a->kd_anggota . ' - ' . $a->nama_anggota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" class="form-control" name="tanggal" id="tanggal-input"
                                value="{{ date('Y-m-d') }}" readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label>Nominal</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Masukkan jumlah nominal" id="nominal"
                                    name="nominal">
                            </div>
                            @if ($errors->has('nominal'))
                                <span class="text-danger">{{ $errors->first('nominal') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Jenis Simpanan</label>
                            <select class="form-control select2" style="width: 100%;" name="jenis_simpanan">
                                <option selected="selected" value="1">Simpanan Pokok</option>
                                <option value="2">Simpanan Wajib</option>
                                <option value="3">Simpanan Sukarela</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pembayaran</label>
                            <select class="form-control select2" style="width: 100%;" name="status">
                                <option selected="selected" value="0">Belum Bayar</option>
                                <option value="1">Sudah Bayar</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" id="keterangan"
                                placeholder="Masukkan keterangan">
                        </div>
                        <a href="{{ route('data.index') }}" class="btn btn-light">Kembali</a>&nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-success collapsed-card card-outline">
                <div class="card-header">
                    <h3 class="card-title">Tambah Simpanan Wajib (Semua Anggota)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-6 mx-auto">
                    <form id="form-data-setor" action="{{ route('data.store-all') }}" role="form" method="post"
                        autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Tanggal Setor</label>
                            <input type="text" class="form-control" id="tanggal-setor"
                                value="{{ date('Y-m-d') }}" disabled>
                            <input type="text" class="form-control" name="tanggal"
                                value="{{ date('Y-m-d') }}" hidden>
                        </div>
                        <div class="form-group">
                            <label>Nominal</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Masukkan jumlah nominal"
                                    id="nominal-setor" name="nominal">
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label>Pembayaran</label>
                            <select class="form-control select2" style="width: 100%;" name="status">
                                <option selected="selected" value="0">Belum Bayar</option>
                                <option value="1">Sudah Bayar</option>
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" id="keterangan-setor"
                                placeholder="Masukkan keterangan">
                        </div>
                        <a href="{{ route('data.index') }}" class="btn btn-light">Kembali</a>&nbsp;
                        <button type="submit" class="btn btn-success">Simpan</button>
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

            $('#nominal-setor').mask('#.##0', {
                reverse: true
            });

            $('#tanggal').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('#tanggal-input').keydown(function(event) {
                event.preventDefault();
            });

            $('#tanggal-setor').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('#tanggal-input-setor').keydown(function(event) {
                event.preventDefault();
            });

            $('.select2').select2();

            $.validator.setDefaults({
                submitHandler: function() {
                    form.submit();
                }
            });

            $('#form-data').validate({
                rules: {
                    id_anggota: {
                        required: true
                    },
                    tanggal: {
                        required: true
                    },
                    nominal: {
                        required: true
                    },
                    jenis_simpanan: {
                        required: true
                    },
                },
                messages: {
                    id_anggota: "Nama Anggota wajib diisi",
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

            $('#form-data-setor').validate({
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
