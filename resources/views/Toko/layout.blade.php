<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Skripsi</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('bootstrap 5/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">

    <style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
    </style>

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
            @if ($data->alert_status == 0)
            @if ($data->stok_etalase <= $data->stok_minimal && $data->stok_gudang > $data->stok_minimal)
                <div class="alert alert-primary">
                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </div>
                    <p class="alert-message"><b>Pemberitahuan Persediaan Barang Etalase</b> <br> Persediaan
                        <b class="text-danger">{{$data->nama}}</b> di etalase kurang dari stok minimal.
                    </p>
                </div>
                @elseif ($data->stok_etalase > $data->stok_minimal && $data->stok_gudang <= $data->stok_minimal)
                    <div class="alert alert-primary">
                        <div class="alert-close close" data-dismiss="alert" aria-label="close">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </div>
                        <p class="alert-message"><b>Pemberitahuan Persediaan Barang Gudang</b> <br> Persediaan
                            <b class="text-danger">{{$data->nama}}</b> di gudang kurang dari stok minimal.
                        </p>
                    </div>
                @elseif ($data->stok_etalase <= $data->stok_minimal && $data->stok_gudang <= $data->stok_minimal)
                    <div class="alert alert-primary">
                        <div class="alert-close close" data-dismiss="alert" aria-label="close">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </div>
                        <p class="alert-message"><b>Pemberitahuan Persediaan Barang</b> <br> Persediaan
                            <b class="text-danger">{{$data->nama}}</b> di etalase & gudang kurang dari stok minimal.
                        </p>
                    </div>
                    @endif
                    @endif
                    @endforeach
        </div>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.4.1/dist/chart.min.js"></script>
    <script src="{{ asset('js/onScan.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.4/dist/JsBarcode.all.min.js"></script>
</body>

@yield('script')

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    $('#table-data').DataTable();
    setTimeout(showNotificationPenjualan(), 5000);
});

function close_notification($id) {
    $.ajax({
        url: '/toko/master/barang/remove-notification/' + $id,
        type: 'POST',
        success: function(response) {

        }
    });
}

function close_notification_utang($id) {
    $.ajax({
        url: '/toko/transaksi/hutang/remove-notification/' + $id,
        type: 'POST',
        success: function(response) {
            
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

    $('input[type="number"]').change(function() {
        if (parseInt($(this).val()) < 0 || $(this).val() == '') {
            $(this).val(0);
        };
    });

    $('input').attr('oninvalid', `this.setCustomValidity('Tidak boleh kosong')`);
    $('input').attr('oninput', `this.setCustomValidity('')`);

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

</html>