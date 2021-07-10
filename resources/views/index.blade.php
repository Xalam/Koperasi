<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Primer Koperasi Polrestabes Semarang</title>

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

    <style>
        /* body {
            background: #007bff;
            background: linear-gradient(to right, #0062E6, #33AEFF);
        } */

        .center-screen {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-right: -50%;
            transform: translate(-50%, -50%);
        }

        .text-title {
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .zoom:hover {
            -ms-transform: scale(1.1);
            -webkit-transform: scale(1.1);
            transform: scale(1.1);
        }

        .position {
            margin-top: 90px;
        }

    </style>
</head>

<body>
    <div class="container position">
        <div class="text-center">
            <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo"
                class="brand-image img-circle elevation-3" style="opacity: .9; width: 80px; height: 80px;">
            <h2 class="text-title"><b>Primer Koperasi Polrestabes Semarang</b></h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 zoom">
                <a href="{{ route('t-login') }}">
                    <div class="card bg-info">
                        <div class="card-body">
                            <div class="text-center">
                                <i class="fas fa-arrow-circle-left fa-2x"></i>
                                <h3><b>TOKO</b></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-4 zoom">
                <a href="{{ route('s-login') }}">
                    <div class="card bg-maroon">
                        <div class="card-body">
                            <div class="text-center">
                                <i class="fas fa-arrow-circle-right fa-2x"></i>
                                <h3><b>SIMPAN PINJAM</b></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="fixed-bottom">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#0099ff" fill-opacity="1"
                d="M0,256L34.3,266.7C68.6,277,137,299,206,277.3C274.3,256,343,192,411,165.3C480,139,549,149,617,170.7C685.7,192,754,224,823,208C891.4,192,960,128,1029,133.3C1097.1,139,1166,213,1234,224C1302.9,235,1371,181,1406,154.7L1440,128L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
            </path>
        </svg>
    </div>
    <div class="fixed-bottom">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#0099ff" fill-opacity="0.4"
                d="M0,128L34.3,133.3C68.6,139,137,149,206,149.3C274.3,149,343,139,411,122.7C480,107,549,85,617,112C685.7,139,754,213,823,245.3C891.4,277,960,267,1029,245.3C1097.1,224,1166,192,1234,181.3C1302.9,171,1371,181,1406,186.7L1440,192L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
            </path>
        </svg>
    </div>
    <div class="fixed-bottom">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#0099ff" fill-opacity="0.1"
                d="M0,96L34.3,122.7C68.6,149,137,203,206,208C274.3,213,343,171,411,160C480,149,549,171,617,197.3C685.7,224,754,256,823,240C891.4,224,960,160,1029,117.3C1097.1,75,1166,53,1234,90.7C1302.9,128,1371,224,1406,272L1440,320L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
            </path>
        </svg>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

</body>

</html>
