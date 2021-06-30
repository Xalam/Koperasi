@extends('Simpan_Pinjam.layout')

@section('title', 'Simpanan')

@section('content_header', 'Tambah Penarikan')

    @push('style')
        <link rel="stylesheet"
            href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Simpanan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tarik-saldo.index') }}">Permintaan Penarikan</a></li>
    <li class="breadcrumb-item active">Tambah Penarikan</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Tambah Penarikan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-6 mx-auto">
                    <form id="form-tarik" action="{{ route('tarik-saldo.store') }}" role="form" method="post"
                        autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Nama Anggota</label>
                            <select class="form-control select2" style="width: 100%;" name="id_anggota" id="id-anggota">
                                <option selected="selected" value="">Pilih anggota</option>
                                @foreach ($anggota as $a)
                                    <option value="{{ $a->id }}">{{ $a->kd_anggota . ' - ' . $a->nama_anggota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jenis Simpanan</label>
                            <select class="form-control select2" style="width: 100%;" name="jenis_simpanan"
                                id="jenis-simpanan">
                                <option selected="selected" value="3">Simpanan Sukarela</option>
                                <option value="2">Simpanan Wajib</option>
                                <option value="1">Simpanan Pokok</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Saldo Anggota</label>
                            <input type="text" class="form-control" id="saldo" readonly>
                        </div>
                        {{-- <div class="form-group">
                            <label>Tanggal</label>
                            <div class="input-group date" id="tanggal" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#tanggal"
                                    name="tanggal" placeholder="Tanggal"/>
                                <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label>Nominal Penarikan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Masukkan jumlah nominal" id="nominal"
                                    name="nominal">
                            </div>
                        </div>
                        <a href="{{ route('tarik-saldo.index') }}" class="btn btn-light">Kembali</a>&nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
            function getSaldo(id, idSimpan) {
                $.ajax({
                    method: 'POST',
                    url: '{{ route('tarik-saldo.saldo') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id,
                        'id_simpan': idSimpan
                    },
                    success: function(data) {
                        // console.log(data.saldo);
                        $('#saldo').attr('value', formatMoney(data.saldo));
                        if (data.saldo < 10000) {
                            document.getElementById('nominal').value = 0;
                            document.getElementById('nominal').readOnly = true;
                        } else {
                            document.getElementById('nominal').readOnly = false;
                        }
                    }
                })
            }

            let id_simpanan = 3;
            let id = 0;

            $('#nominal').mask('#.##0', {
                reverse: true
            });

            $('#tanggal').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('.select2').select2();

            $('#jenis-simpanan').on('change', function() {
                id_simpanan = $(this).val();
                getSaldo(id, id_simpanan);
            });

            $('#id-anggota').on('change', function() {
                id = $(this).val();
                console.log(id);
                console.log(id_simpanan);
                getSaldo(id, id_simpanan);
            });

            $.validator.setDefaults({
                submitHandler: function() {
                    form.submit();
                }
            });

            $('#form-tarik').validate({
                rules: {
                    id_anggota: {
                        required: true
                    },
                    nominal: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    id_anggota: "Nama Anggota wajib diisi",
                    nominal: {
                        required: "Nominal wajib diisi",
                        minlength: "Minimal penarikan 10.000"
                    }
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

            function formatMoney(n) {
                return new Intl.NumberFormat("id-ID").format(n);
            }
        })
    </script>
@endsection
