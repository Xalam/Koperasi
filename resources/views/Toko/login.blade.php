<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Skripsi</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('bootstrap 5/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">

    @yield('style')

</head>

<body class="background-login">
    <div class='container-login align-item-center'>
        <div class="card-login">
            <div class="card-header text-center">
                <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .9; width: 60px; height: 60px;">
                <h6 class="fw-bolder mt-3">Unit Toko Primkop Polrestabes Semarang</h6>
            </div>
            <div class="card-body">
                {!! Form::open(['url' => '/toko/login/store']) !!}
                @if (Session::has('success'))
                <div class="alert alert-success p-3">
                    {{ Session::get('success') }}
                </div>
                @endif
                @if (Session::has('error'))
                <div class="alert alert-danger p-3">
                    {{ Session::get('error') }}
                </div>
                @endif
                {!! Form::label(null, 'Username', ['class' => 'font-3 fw-bold']) !!}
                {!! Form::text('nama', null, ['class' => 'font-3 mb-2 form-control form-control-sm', 'placeholder' =>
                'Username', 'required', 'oninvalid' => "this.setCustomValidity('Username tidak boleh kosong')",
                'oninput' => "this.setCustomValidity('')"]) !!}
                {!! Form::label(null, 'Password', ['class' => 'font-3 fw-bold']) !!}
                {!! Form::password('password', ['class' => 'font-3 mb-2 form-control form-control-sm', 'placeholder' =>
                'Password', 'required', 'oninvalid' => "this.setCustomValidity('Password tidak boleh kosong')",
                'oninput' => "this.setCustomValidity('')"]) !!}
                {!! Form::label(null, 'Jabatan', ['class' => 'font-3 fw-bold']) !!}
                {!! Form::select('jabatan', ['Super_Admin' => 'Super Admin', 'Kanit' => 'Kanit', 'Ketua_Koperasi' => 'Ketua Koperasi', 'Gudang' => 'Gudang',
                'Kasir' => 'Kasir'], null, ['class' => 'form-select form-select-sm mb-2']) !!}
                <div class="d-grid gap-2 mt-2">
                    {!! Form::submit('Login', ['class' => 'btn btn-small btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="btn btn-danger-outline btn-lg text-white"
                style="background-color: transparent;">
                <i class="fas fa-arrow-left fa-sm text-white"></i>&nbsp;Kembali
            </a>
        </div>
    </div>
</body>

</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>

@yield('script')