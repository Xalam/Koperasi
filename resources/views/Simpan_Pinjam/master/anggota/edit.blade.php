@extends('Simpan_Pinjam.layout')

@section('title', 'Anggota')

@section('content_header', 'Edit Anggota')

@push('style')
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Master</a></li>
    <li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
    <li class="breadcrumb-item active">Edit Anggota</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Edit Anggota</h3>
                </div>
                <div class="card-body col-md-6 mx-auto">
                    <form id="form-anggota-edit" action="{{ route('anggota.update', $anggota->id) }}" role="form"
                        method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="kode-anggota">Kode Anggota</label>
                            <input type="text" class="form-control" id="kode-anggota" name="kd_anggota" disabled
                                value="{{ old('kd_anggota') ? old('kd_anggota') : $anggota->kd_anggota }}">
                        </div>
                        <div class="form-group">
                            <label for="nama-anggota">Nama Anggota</label>
                            <input type="text" class="form-control" id="nama-anggota" name="nama_anggota"
                                placeholder="Nama anggota"
                                value="{{ old('nama_anggota') ? old('nama_anggota') : $anggota->nama_anggota }}">
                            @if ($errors->has('nama_anggota'))
                                <span class="text-danger">{{ $errors->first('nama_anggota') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Instansi</label>
                            <select class="form-control select2" style="width: 100%;" name="id_instansi" id="instansi">
                                @foreach ($instansi as $ins)
                                    <option value="{{ $ins->id }}"
                                        {{ $ins->kode_instansi == $anggota->id_instansi ? 'selected="selected"' : '' }}>
                                        {{ $ins->kode_instansi . ' - ' . $ins->nama_instansi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pria">Jenis Kelamin</label>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="radio-pria" name="jenis_kelamin"
                                    value="Pria" {{ $anggota->jenis_kelamin == 'Pria' ? ' checked' : '' }}>
                                <label for="radio-pria" class="custom-control-label">Pria</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="radio-wanita" name="jenis_kelamin"
                                    value="Wanita" {{ $anggota->jenis_kelamin == 'Wanita' ? ' checked' : '' }}>
                                <label for="radio-wanita" class="custom-control-label">Wanita</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Agama</label>
                            <select class="form-control select2" style="width: 100%;" name="agama">
                                <option {{ $anggota->agama == 'Lainnya' ? 'selected="selected"' : '' }} value="Lainnya">
                                    Lainnya</option>
                                <option {{ $anggota->agama == 'Islam' ? 'selected="selected"' : '' }} value="Islam">Islam
                                </option>
                                <option {{ $anggota->agama == 'Kristen' ? 'selected="selected"' : '' }} value="Kristen">
                                    Kristen</option>
                                <option {{ $anggota->agama == 'Katolik' ? 'selected="selected"' : '' }} value="Katolik">
                                    Katolik</option>
                                <option {{ $anggota->agama == 'Hindu' ? 'selected="selected"' : '' }} value="Hindu">Hindu
                                </option>
                                <option {{ $anggota->agama == 'Buddha' ? 'selected="selected"' : '' }} value="Buddha">
                                    Buddha</option>
                                <option
                                    {{ $anggota->agama == 'Khonghucu' ? 'selected="selected"' : '' }}value="Khonghucu">
                                    Khonghucu</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tempat-lahir">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat-lahir" name="tempat_lahir"
                                placeholder="Tempat lahir"
                                value="{{ old('tempat_lahir') ? old('tempat_lahir') : $anggota->tempat_lahir }}">
                            @if ($errors->has('tempat_lahir'))
                                <span class="text-danger">{{ $errors->first('tempat_lahir') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <div class="input-group date" id="tanggal-lahir" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#tanggal-lahir"
                                    id="tanggal-input" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir') ? old('tanggal_lahir') : $anggota->tanggal_lahir }}"
                                    placeholder="Tanggal lahir" />
                                <div class="input-group-append" data-target="#tanggal-lahir" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat"
                                value="{{ old('alamat') ? old('alamat') : $anggota->alamat }}">
                            @if ($errors->has('alamat'))
                                <span class="text-danger">{{ $errors->first('alamat') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>No Handphone</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" class="form-control number" placeholder="No Handphone" name="no_hp"
                                    value="{{ old('no_hp') ? old('no_hp') : $anggota->no_hp }}">
                            </div>
                            @if ($errors->has('no_hp'))
                                <span class="text-danger">{{ $errors->first('no_hp') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>No Whatsapp</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                </div>
                                <input type="text" class="form-control number" placeholder="No Whatsapp" name="no_wa"
                                    value="{{ old('no_wa') ? old('no_wa') : $anggota->no_wa }}">
                            </div>
                            @if ($errors->has('no_wa'))
                                <span class="text-danger">{{ $errors->first('no_wa') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Status Pernikahan</label>
                            <select class="form-control select2" style="width: 100%;" name="status">
                                <option {{ $anggota->status == 'belum_kawin' ? 'selected="selected"' : '' }}
                                    value="belum_kawin">Belum Menikah</option>
                                <option {{ $anggota->status == 'kawin' ? 'selected="selected"' : '' }} value="kawin">
                                    Menikah</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan"
                                placeholder="Masukkan jabatan"
                                value="{{ old('jabatan') ? old('jabatan') : $anggota->jabatan }}">
                            @if ($errors->has('jabatan'))
                                <span class="text-danger">{{ $errors->first('jabatan') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                value="{{ old('email') ? old('email') : $anggota->email }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="username">Username (NRP)</label>
                            <input type="text" class="form-control number" placeholder="Nomor Registrasi Pokok"
                                name="username" value="{{ old('username') ? old('username') : $anggota->username }}"
                                disabled>
                        </div>
                        <div class="form-group">
                            <label for="password">Password Baru (boleh dikosongkan)</label>
                            <div class="input-group" id="password">
                                <input type="password" class="form-control" name="password" placeholder="Password baru">
                                <div class="input-group-append">
                                    <a href="" class="input-group-text"><i class="fas fa-eye-slash"
                                            aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control select2" style="width: 100%;" name="role">
                                <option {{ $anggota->role == 'anggota' ? 'selected="selected"' : '' }} value="anggota">
                                    Anggota</option>
                                <option {{ $anggota->role == 'bendahara' ? 'selected="selected"' : '' }}
                                    value="bendahara">Bendahara</option>
                                <option {{ $anggota->role == 'bendahara_pusat' ? 'selected="selected"' : '' }}
                                    value="bendahara_pusat">Bendahara Pusat</option>
                                <option {{ $anggota->role == 'ketua_koperasi' ? 'selected="selected"' : '' }}
                                    value="ketua_koperasi">Ketua Koperasi</option>
                                <option {{ $anggota->role == 'simpan_pinjam' ? 'selected="selected"' : '' }}
                                    value="simpan_pinjam">Unit Simpan Pinjam</option>
                            </select>
                        </div>
                        <div class="row cal-gaji">
                            <div class="form-group col-md-6">
                                <label>Gaji</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Gaji" id="gaji" name="gaji"
                                        value="{{ number_format($anggota->gaji, 2, ',', '.') }}">
                                </div>
                                @if ($errors->has('gaji'))
                                    <span class="text-danger">{{ $errors->first('gaji') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label>Limit</label>
                                <input type="text" class="form-control" placeholder="Limit" id="limit" disabled
                                    value="{{ old('limit_gaji') ? old('limit_gaji') : 'Rp ' . number_format($anggota->limit_gaji, 2, ',', '.') }}">
                                <input type="hidden" name="limit_gaji" id="limit-gaji"
                                    value="{{ old('limit_gaji') ? old('limit_gaji') : $anggota->limit_gaji }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-foto">Upload Foto Baru</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="input-foto" name="foto"
                                        value="{{ old('foto') }}">
                                    <label class="custom-file-label" for="input-foto">Pilih file</label>
                                </div>
                            </div>
                            <img id="preview-foto" src="https://www.riobeauty.co.uk/images/product_image_not_found.gif"
                                alt="Preview Image" style="max-height: 150px; margin-top: 10px">
                            @if ($errors->has('foto'))
                                <span class="text-danger">{{ $errors->first('foto') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('anggota.index') }}" class="btn btn-light">Kembali</a>&nbsp;
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
            $('#tanggal-lahir').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('#tanggal-input').keydown(function(event) {
                event.preventDefault();
            });

            $('#gaji').mask('#.##0,00', {
                reverse: true
            });

            $('.select2').select2();

            $("#password a").on('click', function(event) {
                event.preventDefault();
                if ($('#password input').attr("type") == "text") {
                    $('#password input').attr('type', 'password');
                    $('#password i').addClass("fa-eye-slash");
                    $('#password i').removeClass("fa-eye");
                } else if ($('#password input').attr("type") == "password") {
                    $('#password input').attr('type', 'text');
                    $('#password i').removeClass("fa-eye-slash");
                    $('#password i').addClass("fa-eye");
                }
            });

            $('.cal-gaji').keyup(function() {
                let gaji = $('#gaji').val();
                let newGaji = 0;

                if (gaji != null) {
                    let cleanDot = gaji.split('.').join("");
                    let cleanComa = cleanDot.split(',').join(".");
                    newGaji = cleanComa;
                }

                let simWajib = @php echo($wajib); @endphp;

                let result = 2 / 3 * newGaji - simWajib;
                $('#limit').attr('value', formatMoney(result));
                $('#limit-gaji').attr('value', (result.toFixed(2)));
            });

            function formatMoney(n) {
                return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR"
                }).format(n);
            }

            $('input.number').keyup(function(event) {
                if (event.which !== 8 && event.which !== 0 && event.which < 48 || event.which > 57) {
                    $(this).val(function(index, value) {
                        return value.replace(/\D/g, "");
                    });
                }
            });

            $('input[name="jenis_kelamin"]:checked').val();

            $('#input-foto').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-foto').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            });

            $.validator.addMethod('filesize', function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param)
            }, 'Ukuran file tidak boleh lebih dari 500 kb');

            $.validator.setDefaults({
                submitHandler: function() {
                    form.submit();
                }
            });

            $('#form-anggota-edit').validate({
                rules: {
                    nama_anggota: {
                        required: true
                    },
                    agama: {
                        required: true
                    },
                    tempat_lahir: {
                        required: true
                    },
                    tanggal_lahir: {
                        required: true
                    },
                    alamat: {
                        required: true
                    },
                    no_hp: {
                        required: true
                    },
                    no_wa: {
                        required: true
                    },
                    jabatan: {
                        required: true
                    },
                    password: {
                        minlength: 8
                    },
                    gaji: {
                        required: true
                    },
                    foto: {
                        extension: "jpg,jpeg,png",
                        filesize: 500000,
                    },
                },
                messages: {
                    nama_anggota: "Nama Anggota wajib diisi",
                    agama: "Agama wajib diisi",
                    tempat_lahir: "Tempat lahir wajib diisi",
                    tanggal_lahir: "Tanggal lahir wajib diisi",
                    alamat: "Alamat wajib diisi",
                    no_hp: "No Handphone wajib diisi",
                    no_wa: "No Whatsapp wajib diisi",
                    jabatan: "Jabatan wajib diisi",
                    password: "Password minimal 8 karakter",
                    gaji: "Gaji wajib diisi",
                    foto: {
                        extension: "Gunakan file berformat .jpg atau .jpeg atau png",
                        filesize: "Ukuran file maksimal 500kb"
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
        })
    </script>
@endsection
