@extends('Simpan_Pinjam.layout')

@section('title', 'Administrator')

@section('content_header', 'Tambah Administrator')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Master</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Daftar Administrator</a></li>
    <li class="breadcrumb-item active">Tambah Administrator</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Tambah Administrator</h3>
                </div>
                <div class="card-body col-md-6 mx-auto">
                    <form id="form-admin" action="{{ route('admin.store') }}" role="form" method="post"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama">
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group" id="password">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                                <div class="input-group-append">
                                    <a href="" class="input-group-text"><i class="fas fa-eye-slash"
                                            aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control select2" style="width: 100%;" name="role">
                                <option value="bendahara" selected="selected">Bendahara</option>
                                <option value="bendahara_pusat">Bendahara Pusat</option>
                                <option value="ketua_koperasi">Ketua Koperasi</option>
                                <option value="simpan_pinjam">Unit Simpan Pinjam</option>
                            </select>
                        </div>

                        <div style="margin-top: 30px;">
                            <a href="{{ route('admin.index') }}" class="btn btn-default">Kembali</a>&nbsp;
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@section('script')

    @if (session()->has('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ session()->get('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            })
        </script>
    @endif

    <script>
        $(function() {
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

            $.validator.setDefaults({
                submitHandler: function() {
                    form.submit();
                }
            });

            $('#form-admin').validate({
                rules: {
                    name: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                },
                messages: {
                    name: "Nama wajib diisi",
                    username: "Username wajib diisi",
                    password: "Password minimal 8 karakter",
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

            $('#username').on('keypress', function(e) {
                if (e.which == 32) {
                    return false;
                }
            });
        })
    </script>
@endsection
