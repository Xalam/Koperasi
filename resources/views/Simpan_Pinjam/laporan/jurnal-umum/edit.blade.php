@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Edit Jurnal Umum')

@push('style')
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('jurnal.index') }}">Jurnal Umum</a></li>
    <li class="breadcrumb-item active">Edit Jurnal Umum</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Edit Jurnal Umum</h3>
                </div>
                <div class="card-body col-md-8 mx-auto">
                    <form id="form-jurnal" action="{{ route('jurnal.update', $jurnal->id) }}" role="form" method="post">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="kode-jurnal">Kode Jurnal</label>
                            <input type="text" class="form-control" id="kode-jurnal" name="kode_jurnal" placeholder="Masukkan kode" value="{{ $jurnal->kode_jurnal }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <div class="input-group date" id="tanggal" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#tanggal"
                                    name="tanggal" placeholder="Tanggal" value="{{ $jurnal->tanggal }}"/>
                                <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="{{ $jurnal->keterangan }}">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Nama Akun</label>
                                <select class="form-control select2" style="width: 100%;" name="id_akun">
                                    <option value="">Pilih akun</option>
                                    @foreach ($akun as $a)
                                        <option {{ $a->id == $jurnal->id_akun ? 'selected="selected"' : ''}} value="{{ $a->id }}">{{ $a->kode_akun . ' - ' . $a->nama_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Debet</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Debet" id="debet"
                                        name="debet" value="{{ number_format($jurnal->debet, 2, ',', '.') }}">
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Kredit</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Kredit" id="kredit"
                                        name="kredit" value="{{ number_format($jurnal->kredit, 2, ',', '.') }}">
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('jurnal.index') }}" class="btn btn-light">Kembali</a>&nbsp;
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
    <script src="{{ asset('assets/plugins/jquery-maskmoney/jquery.maskMoney.js') }}"></script>
@endpush

@section('script')
    <script>
        $(function() {
            $('#debet').maskMoney({ 
                allowNegative: true,
                thousands:'.',
                decimal: ','     
            });

            $('#kredit').maskMoney({ 
                allowNegative: true,
                thousands:'.',
                decimal: ','     
            });

            $('#tanggal').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('.select2').select2();

            $.validator.setDefaults({
                submitHandler: function () {
                    form.submit();
                }
            });

            $('#form-jurnal').validate({
                rules: {
                    tanggal: {
                        required: true
                    },
                    keterangan: {
                        required: true
                    },
                    id_akun: {
                        required: true
                    },
                },
                messages: {
                    tanggal: "Tanggal wajib diisi",
                    keterangan: "Keterangan wajib diisi",
                    id_akun: "Nama Akun wajib diisi",
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