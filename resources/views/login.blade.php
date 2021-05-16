<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Skripsi</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">

    @yield('style')

</head>

<body class="background-login">
    <div class='container-login align-item-center'>
        <div class="card-login">
            <h4 class="text-center">Salepropos</h4>
            <hr class="color-grey">
            <div class="card-body">
                {!! Form::open(['url' => 'login']) !!}
                {!! Form::label(null, 'Username', ['class' => 'font-3 font-weight-bold']) !!}
                {!! Form::text(null, null, ['class' => 'font-3 mb-4 p-1', 'placeholder' => 'Input Username', 'required']) !!}
                {!! Form::label(null, 'Password', ['class' => 'font-3 font-weight-bold']) !!}
                {!! Form::password(null, ['class' => 'font-3 p-1', 'placeholder' => 'Input Password', 'required']) !!}
                {!! Form::submit('Login', ['class' => 'btn btn-small btn-primary mt-5']) !!}
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