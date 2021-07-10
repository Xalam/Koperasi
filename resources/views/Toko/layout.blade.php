<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Skripsi</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.4.1/dist/chart.min.js"></script>

    <link rel="stylesheet" href="{{ asset('bootstrap 5/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">

    @yield('style')

</head>

<body class="antialiased">
    @yield('popup')
    <div class="page-wrapper chiller-theme toggled">
        @include('toko.navbar')
        <main class="page-content">
            @include('toko.sidebar')
            <div class="container-fluid">
                @yield('main')
                @include('toko.footer')
            </div>
        </main>
    </div>
    <div id="alert-popover">
        @if (isset($data_notified) && count($data_notified) > 0)
        <div class="alert-wrapper">
            @foreach ($data_notified as $data)
            @if ($data->stok <= $data->stok_minimal && $data->alert_status == 0)
                <div class="alert alert-primary">
                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </div>
                    <p class="alert-message"><b>Pemberitahuan Persediaan Barang</b> <br> Persediaan {{$data->nama}}
                        kurang
                        dari stok minimal</p>
                </div>
                @endif
                @endforeach
        </div>
        @endif
    </div>
</body>

</html>

@yield('script')

<script type="text/javascript">
$(document).ready(function() {
    $('#table-data').DataTable();
    setTimeout(showNotificationPenjualan(), 5000);
});

function close_notification($id) {
    $.ajax({
        url: '/toko/master/barang/remove-notification/' + $id,
        type: 'POST',
        success: function(response) {
            if (response.code == 200) {
                $('#notification-count').text(response.data_barang.length);
            }
        }
    });
}

function showNotificationPenjualan() {
    $.ajax({
        url: '/api/show-notification-penjualan',
        type: 'GET',
        success: function(response) {
            if (response.code == 200) {
                if (response.data.length > 0) {
                    $.each(response.data, function(i, v) {
                        $pembayaran = '';
                        if (v.pembayaran == 1) {
                            $pembayaran = 'kredit';
                        } else {
                            $pembayaran = 'tunai';
                        }

                        $('#alert-popover').append(`<div class="alert-wrapper">` +
                            `<div class="alert alert-success align-self-center">` +
                            `<div class="alert-close close" data-dismiss="alert" aria-label="close">` +
                            `<i class="fas fa-times" aria-hidden="true"></i>` +
                            `</div>` +
                            `<p class="alert-message"><b>` + v.nama_anggota +
                            `</b> <br> Melakukan pembelian barang secara ` +
                            $pembayaran + ` sebesar ` + v.jumlah_harga + `</p>` +
                            `</div>` +
                            `</div>`
                        );
                    });
                }
            }
        },
        complete: function(data) {
            setTimeout(showNotificationPenjualan(), 5000);
        }
    });
}

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