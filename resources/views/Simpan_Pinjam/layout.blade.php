<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Simpan Pinjam | @yield('title', 'Admin')</title>
    <link rel="shortcut icon" href="{{ asset('assets/dist/img/logo-koperasi.png') }}" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">

    @stack('style')
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <style>
        #pulsate {
            animation: pulse 1s ease infinite;
        }

        #pulsate-simpanan {
            animation: pulse 1s ease infinite;
        }

        .pulsate-child {
            margin: auto;
            height: 10px;
            width: 10px;
            background-color: #dc3545;
            border-radius: 50%;
            display: inline-block;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            100% {
                transform: scale(1.1);
                opacity: 1;
            }
        }

        .dropdown-menu-xl {
            max-height: 500px;
            max-width: 400px;
            overflow-x: hidden;
            overflow-y: auto;
        }

    </style>
    @stack('custom-style')
</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-mini-xs layout-footer-fixed">
    <div class="wrapper">

        @include('Simpan_Pinjam.navbar')

        @include('Simpan_Pinjam.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('content_header') <small
                                    style="font-size:16px;">@yield('content_header_small')</small></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Main row -->
                    @yield('content_main')
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2021 <a href="#">Kompak Polines</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    @stack('before-script')
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('assets/dist/js/demo.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {{-- <script src="{{ asset('assets/dist/js/pages/dashboard.js') }}"></script> --}}
    <!-- Pusher -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <script>
        function delete_notifikasi() {
            $.ajax({
                method: 'GET',
                url: '{{ route('delete-notif') }}',
                success: function(data) {
                    $('#notifikasi-text').text(data.count);
                    $('#notifikasi-header').text(data.count + ' Notifikasi');
                }
            })
        }

        function clear_notification(id_notif) {
            $.ajax({
                method: 'POST',
                url: '{{ route('clear-notif') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': id_notif
                },
                success: function(data) {
                    $('#notifikasi-text').text(data.count);
                    $('#notifikasi-header').text(data.count + ' Notifikasi');
                }
            })
        }

        function data_notifikasi() {
            let notifWrapper = $('.dropdown-notif');
            let notifChild = notifWrapper.find('.dropdown-menu');
            let notifData = notifChild.find('.dropdown-menu-notif');


            $.ajax({
                method: 'GET',
                url: '{{ route('all-notif') }}',
                beforeSend: function() {
                    notifData.html(`<div class="text-center"><i class="fa fa-spinner fa-spin"></i></div>`);
                },
                success: function(data) {
                    let len = 0;
                    console.log(data);
                    notifData.html('');
                    if (data != null) {
                        len = data.length;
                    }

                    if (len > 0) {
                        for (let i = 0; i < len; i++) {
                            let timestamp = new Date(Date.parse(data[i].created_at));
                            let date = moment(timestamp).toNow(true);
                            let notifHtml = `
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-item">
                                <i class="fas fa-dot-circle mr-2 text-warning"></i> <b>` + data[i].title + `</b>
                                <button type="button" class="close float-right text-danger"
                                        aria-label="Close"><span aria-hidden="true"
                                        onclick="clear_notification(` + data[i].id + `)"><i
                                        class="fas fa-times"></i></span></button>
                                <br>
                                <span class="text-secondary text-sm text-wrap">` + data[i].content +
                                `</span>&nbsp;<i class="text-xs">(` + date + ` ago)</i>
                            </div>`
                            notifData.append(notifHtml);
                        }
                    }
                }
            });
        }

        var pusher = new Pusher('45750234e475d705a290', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('my-channel');

        channel.bind('my-event', function(data) {
            if (data.type == 1) {
                $('#pulsate-simpanan').removeAttr('hidden');
                $('#pulsate-child-simpanan').removeAttr('hidden');
            } else if (data.type == 2) {
                $('#pulsate-simpanan').removeAttr('hidden');
                $('#pulsate-child-penarikan').removeAttr('hidden');
            } else if (data.type == 3) {
                $('#pulsate').removeAttr('hidden');
                $('#pulsate-child-pengajuan').removeAttr('hidden');
            } else if (data.type == 4) {
                $('#pulsate').removeAttr('hidden');
                $('#pulsate-child-pelunasan').removeAttr('hidden');
            }

            data_notifikasi();

            let countString = $('#notifikasi-text').text()
            let count = parseInt(countString) + 1;
            $('#notifikasi-text').text(count);
            $('#notifikasi-header').text(count + ' Notifikasi');
        });
    </script>
    @yield('script')
</body>

</html>
