<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Skripsi</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">

    @yield('style')

</head>

<body class="antialiased">
    @yield('popup')
    <div class="page-wrapper chiller-theme toggled">
        <main class="page-content">
            @include('toko.navbar')
            @include('toko.sidebar')
            <div class="container-fluid">
                @yield('main')
            </div>
        </main>
    </div>
</body>

</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>

@yield('script')

<script type="text/javascript">
jQuery(function($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".sidebar-dropdown > a").click(function() {
        $(".sidebar-submenu").slideUp(200);
        $(".sidebar-submenu2").slideUp(200);
        $(".sidebar-submenu3").slideUp(200);
        $(".sidebar-submenu4").slideUp(200);
        $(".sidebar-submenu5").slideUp(200);
        $(".sidebar-submenu6").slideUp(200);
        if ($(this).parent().hasClass("active")) {
            $(".sidebar-dropdown").removeClass("active");
            $(".sidebar-dropdown2").removeClass("active");
            $(".sidebar-dropdown3").removeClass("active");
            $(".sidebar-dropdown4").removeClass("active");
            $(".sidebar-dropdown5").removeClass("active");
            $(".sidebar-dropdown6").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".sidebar-dropdown").removeClass("active");
            $(this).next(".sidebar-submenu").slideDown(200);
            $(this).parent().addClass("active");
        }
    });

    $(".sidebar-dropdown2 > a").click(function() {
        $(".sidebar-submenu2").slideUp(200);
        $(".sidebar-submenu3").slideUp(200);
        $(".sidebar-submenu4").slideUp(200);
        $(".sidebar-submenu5").slideUp(200);
        $(".sidebar-submenu6").slideUp(200);
        if ($(this).parent().hasClass("active")) {
            $(".sidebar-dropdown2").removeClass("active");
            $(".sidebar-dropdown3").removeClass("active");
            $(".sidebar-dropdown4").removeClass("active");
            $(".sidebar-dropdown5").removeClass("active");
            $(".sidebar-dropdown6").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".sidebar-dropdown2").removeClass("active");
            $(this).next(".sidebar-submenu2").slideDown(200);
            $(this).parent().addClass("active");
        }
    });

    $(".sidebar-dropdown3 > a").click(function() {
        $(".sidebar-submenu3").slideUp(200);
        $(".sidebar-submenu4").slideUp(200);
        $(".sidebar-submenu5").slideUp(200);
        $(".sidebar-submenu6").slideUp(200);
        if ($(this).parent().hasClass("active")) {
            $(".sidebar-dropdown3").removeClass("active");
            $(".sidebar-dropdown4").removeClass("active");
            $(".sidebar-dropdown5").removeClass("active");
            $(".sidebar-dropdown6").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".sidebar-dropdown3").removeClass("active");
            $(this).next(".sidebar-submenu3").slideDown(200);
            $(this).parent().addClass("active");
        }
    });

    $(".sidebar-dropdown4 > a").click(function() {
        $(".sidebar-submenu4").slideUp(200);
        $(".sidebar-submenu5").slideUp(200);
        $(".sidebar-submenu6").slideUp(200);
        if ($(this).parent().hasClass("active")) {
            $(".sidebar-dropdown4").removeClass("active");
            $(".sidebar-dropdown5").removeClass("active");
            $(".sidebar-dropdown6").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".sidebar-dropdown4").removeClass("active");
            $(this).next(".sidebar-submenu4").slideDown(200);
            $(this).parent().addClass("active");
        }
    });

    $(".sidebar-dropdown5 > a").click(function() {
        $(".sidebar-submenu5").slideUp(200);
        $(".sidebar-submenu6").slideUp(200);
        if ($(this).parent().hasClass("active")) {
            $(".sidebar-dropdown5").removeClass("active");
            $(".sidebar-dropdown6").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".sidebar-dropdown5").removeClass("active");
            $(this).next(".sidebar-submenu5").slideDown(200);
            $(this).parent().addClass("active");
        }
    });

    $(".sidebar-dropdown6 > a").click(function() {
        $(".sidebar-submenu6").slideUp(200);
        if ($(this).parent().hasClass("active")) {
            $(".sidebar-dropdown6").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".sidebar-dropdown6").removeClass("active");
            $(this).next(".sidebar-submenu6").slideDown(200);
            $(this).parent().addClass("active");
        }
    });

    $(".sidebar-submenu").slideUp(200);
    $(".sidebar-submenu2").slideUp(200);
    $(".sidebar-submenu3").slideUp(200);
    $(".sidebar-submenu4").slideUp(200);
    $(".sidebar-submenu5").slideUp(200);
    $(".sidebar-submenu6").slideUp(200);

    $("#close-sidebar").click(function() {
        $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function() {
        $(".page-wrapper").addClass("toggled");
    });

    $(window).resize(function() {
        if (window.innerWidth < 1280 && $('.page-wrapper').hasClass('toggled')) {
            $(".page-wrapper").removeClass("toggled");
        }

        if (window.innerWidth >= 1280 && !$('.page-wrapper').hasClass('toggled')) {
            $(".page-wrapper").addClass("toggled");
        }
    });

    if (window.innerWidth < 1280 && $('.page-wrapper').hasClass('toggled')) {
        $(".page-wrapper").removeClass("toggled");
    }

});
</script>