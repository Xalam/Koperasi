@extends('simpan_pinjam.layout')

@section('title', 'Anggota')

@section('content_header', 'Tambah Anggota')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Master</a></li>
    <li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
    <li class="breadcrumb-item active">Tambah Anggota</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Anggota</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('anggota.store') }}" role="form" method="post" enctype="multipart/form-data"
                        autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" id="kode-anggota" name="kd_anggota"
                                placeholder="Masukkan nama anggota" hidden>
                        </div>
                        <div class="form-group">
                            <label for="nama-anggota">Nama Anggota</label>
                            <input type="text" class="form-control" id="nama-anggota" name="nama_anggota"
                                placeholder="Masukkan nama anggota">
                            @if ($errors->has('nama_anggota'))
                                <span class="text-danger">{{ $errors->first('nama_anggota') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="pria">Jenis Kelamin</label>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="radio-pria" name="jenis_kelamin" 
                                value="Pria" checked>
                                <label for="radio-pria" class="custom-control-label">Pria</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="radio-wanita" name="jenis_kelamin"
                                    value="Wanita">
                                <label for="radio-wanita" class="custom-control-label">Wanita</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Agama</label>
                            <select class="form-control select2" style="width: 100%;" name="agama">
                                <option selected="selected" value="Lainnya">Lainnya</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tempat-lahir">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat-lahir" name="tempat_lahir"
                                placeholder="Masukkan tempat lahir">
                                @if ($errors->has('tempat_lahir'))
                                <span class="text-danger">{{ $errors->first('tempat_lahir') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <div class="input-group date col-md-6" id="tanggal-lahir" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#tanggal-lahir" name="tanggal_lahir"/>
                                <div class="input-group-append" data-target="#tanggal-lahir" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat">
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
                                <input type="text" class="form-control" data-inputmask-placeholder="" data-inputmask='"mask": "99999999999[999]"' data-mask name="no_hp">
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
                                <input type="text" class="form-control" data-inputmask-placeholder="" data-inputmask='"mask": "99999999999[999]"' data-mask name="no_wa">
                            </div>
                            @if ($errors->has('no_wa'))
                                <span class="text-danger">{{ $errors->first('no_wa') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Status Pernikahan</label>
                            <select class="form-control select2" style="width: 100%;" name="status">
                                <option selected="selected" value="belum_kawin">Belum Menikah</option>
                                <option value="kawin">Menikah</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Masukkan jabatan">
                            @if ($errors->has('jabatan'))
                                <span class="text-danger">{{ $errors->first('jabatan') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="username">Username (NRP)</label>
                            <input type="text" class="form-control" data-inputmask='"mask": "99999999"' data-mask placeholder="Masukkan Nomor Registrasi Pokok" name="username">
                            @if ($errors->has('username'))
                                <span class="text-danger">{{ $errors->first('username') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group" id="password">
                                <input type="password" class="form-control" name="password" placeholder="Masukkan password">
                                <div class="input-group-append">
                                    <a href="" class="input-group-text"><i class="fas fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control select2" style="width: 100%;" name="role">
                                <option selected="selected" value="anggota">Anggota</option>
                                <option value="bendahara">Bendahara</option>
                                <option value="bendahara_pusat">Bendahara Pusat</option>
                                <option value="ketua_koperasi">Ketua Koperasi</option>
                                <option value="simpan_pinjam">Unit Simpan Pinjam</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-foto">Upload Foto</label>
                            <div class="input-group col-md-6">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="input-foto" name="foto">
                                    <label class="custom-file-label" for="input-foto">Pilih file</label>
                                </div>
                            </div>
                            <img id="preview-foto" src="https://www.riobeauty.co.uk/images/product_image_not_found.gif" alt="Preview Image" style="max-height: 150px; margin-top: 10px">
                            @if ($errors->has('foto'))
                                <span class="text-danger">{{ $errors->first('foto') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('anggota.index') }}" class="btn btn-light">Kembali</a>&nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
@endpush

@section('script')
    <script>
        $(function() {
            $('#tanggal-lahir').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('[data-mask]').inputmask();

            $('.select2').select2();

            $("#password a").on('click', function(event) {
                event.preventDefault();
                if ($('#password input').attr("type") == "text") {
                    $('#password input').attr('type', 'password');
                    $('#password i').addClass( "fa-eye-slash" );
                    $('#password i').removeClass( "fa-eye" );
                } else if ($('#password input').attr("type") == "password") {
                    $('#password input').attr('type', 'text');
                    $('#password i').removeClass( "fa-eye-slash" );
                    $('#password i').addClass( "fa-eye" );
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
        })

    </script>
@endsection
