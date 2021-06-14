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
            <h4 class="text-center card-header p-3">Salepropos</h4>
            <div class="card-body">
                {!! Form::open(['url' => '']) !!}
                {!! Form::label(null, 'Username', ['class' => 'font-3 fw-bold']) !!}
                {!! Form::text(null, null, ['class' => 'font-3 mb-2 form-control form-control-sm', 'placeholder' =>
                'Input Username', 'required']) !!}
                {!! Form::label(null, 'Password', ['class' => 'font-3 fw-bold']) !!}
                {!! Form::password(null, ['class' => 'font-3 form-control form-control-sm', 'placeholder' => 'Input Password', 'required']) !!}
                <div class="d-grid gap-2 mt-2">
                    <a href="/" class="btn btn-small btn-primary">Login</a>
                    <!-- {!! Form::submit('Login', ['class' => 'btn btn-small btn-primary']) !!} -->
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</body>

</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>

@yield('script')