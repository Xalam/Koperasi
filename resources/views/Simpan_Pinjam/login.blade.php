<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Primkop | Log in</title>

    <link rel="shortcut icon" href="{{ asset('assets/dist/img/logo-koperasi.png') }}" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <style>
        :root {
            --input-padding-x: 1.5rem;
            --input-padding-y: .75rem;
        }

        body {
            background: #007bff;
            background: linear-gradient(to right, #0062E6, #33AEFF);
        }

        .card-signin {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-signin .card-title {
            margin-bottom: 2rem;
            font-weight: 300;
            font-size: 1.5rem;
        }

        .card-signin .card-img-left {
            width: 45%;
            /* Link to your background image using in the property below! */
            background: scroll center url('https://source.unsplash.com/WEQbe2jBg40/414x512');
            background-size: cover;
        }

        .card-signin .card-body {
            padding: 2rem;
        }

        .form-signin {
            width: 100%;
        }

        .form-signin .btn {
            font-size: 80%;
            border-radius: 5rem;
            letter-spacing: .1rem;
            font-weight: bold;
            padding: 1rem;
            transition: all 0.2s;
        }

        .form-label-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-label-group input {
            height: auto;
            border-radius: 2rem;
        }

        .form-label-group>input,
        .form-label-group>label {
            padding: var(--input-padding-y) var(--input-padding-x);
        }

        .form-label-group>label {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            margin-bottom: 0;
            /* Override default `<label>` margin */
            line-height: 1.5;
            color: #495057;
            border: 1px solid transparent;
            border-radius: .25rem;
            transition: all .1s ease-in-out;
        }

        .form-label-group input::-webkit-input-placeholder {
            color: transparent;
        }

        .form-label-group input:-ms-input-placeholder {
            color: transparent;
        }

        .form-label-group input::-ms-input-placeholder {
            color: transparent;
        }

        .form-label-group input::-moz-placeholder {
            color: transparent;
        }

        .form-label-group input::placeholder {
            color: transparent;
        }

        .form-label-group input:not(:placeholder-shown) {
            padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
            padding-bottom: calc(var(--input-padding-y) / 3);
        }

        .form-label-group input:not(:placeholder-shown)~label {
            padding-top: calc(var(--input-padding-y) / 3);
            padding-bottom: calc(var(--input-padding-y) / 3);
            font-size: 12px;
            color: #777;
        }

        .line {
            height: 1px;
            width: 30%;
            background-color: #E0E0E0;
            margin-top: 15px
        }

        .or {
            width: 40%;
        }

        .suffix {
            margin-top: -35px;
            float: right;
            margin-right: 15px;
        }

    </style>
</head>

<body class="hold-transition login-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-9 mx-auto">
                <div class="card card-signin flex-row my-5">
                    <div class="card-img-left d-none d-md-flex">
                        <!-- Background image for card set in CSS! -->
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo"
                                class="brand-image img-circle elevation-3"
                                style="opacity: .9; width: 60px; height: 60px;">
                            <h5 style="margin-left: 25px; margin-top: 5px;" class="text-center">Primer Koperasi
                                Polrestabes <br> Semarang</h5>
                        </div>
                        <div class="row" style="margin-bottom: 20px; margin-top: 20px;">
                            <div class="line"></div>
                            <h5 class="text-center or"><b>Silahkan Login</b></h5>
                            <div class="line"></div>
                        </div>
                        <form action="{{ route('post-login') }}" class="form-signin" method="POST" role="form">
                            @csrf
                            <div class="form-label-group">
                                <input type="text" id="input-username" name="username" class="form-control"
                                    placeholder="Username" required>
                                <label for="input-username">Username</label>
                            </div>

                            <div class="form-label-group" id="password">
                                <input type="password" id="input-password" name="password" class="form-control"
                                    placeholder="Password" required>
                                <div class="suffix">
                                    <a href=""><i class="fas fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                                <label for="input-password">Password</label>
                            </div>

                            <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <a href="{{ route('login') }}" class="btn btn-danger-outline btn-lg text-white"
            style="background-color: transparent;">
            <i class="fas fa-arrow-left fa-sm text-white"></i>&nbsp;Kembali
        </a>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

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
        })
    </script>

</body>

</html>
