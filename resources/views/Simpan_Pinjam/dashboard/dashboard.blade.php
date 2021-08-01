@extends('Simpan_Pinjam.layout')

@section('title', 'Dashboard')

@section('content_header', 'Dashboard')

@section('breadcrumb')
    <span id="ct"></span>
@endsection

@push('style')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@section('content_main')
    <div class="row">
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $count_anggota }}</h3>

                    <p>Anggota</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('anggota.index') }}" class="small-box-footer">Info lebih <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp. {{ number_format($count_pinjaman, 0, '', '.') }}</h3>

                    <p>Total Pinjaman</p>
                </div>
                <div class="icon">
                    <i class="ion ion-cash"></i>
                </div>
                <a href="{{ route('pengajuan.index') }}" class="small-box-footer">Info lebih <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-maroon">
                <div class="inner">
                    <h3>Rp. {{ number_format($count_simpanan, 0, '', '.') }}</h3>

                    <p>Total Simpanan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-card"></i>
                </div>
                <a href="{{ route('data.index') }}" class="small-box-footer">Info lebih <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        Grafik Laba ({{ date('Y') }})
                    </h3>
                </div>
                <div class="card-body">
                    <canvas class="chart" id="line-chart"
                        style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <!-- ChartJS -->
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('assets/plugins/sparklines/sparkline.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@section('script')
    <script>
        function display_c() {
            let refresh = 1000; // Refresh rate in milli seconds
            mytime = setTimeout('display_ct()', refresh);
        }

        function display_ct() {
            let x = new Date()
            // date part ///
            let month = x.getMonth() + 1;
            let day = x.getDate();
            let year = x.getFullYear();
            if (month < 10) {
                month = '0' + month;
            }
            if (day < 10) {
                day = '0' + day;
            }
            let x3 = day + '-' + month + '-' + year;

            // time part //
            let hour = x.getHours();
            let minute = x.getMinutes();
            let second = x.getSeconds();
            if (hour < 10) {
                hour = '0' + hour;
            }
            if (minute < 10) {
                minute = '0' + minute;
            }
            if (second < 10) {
                second = '0' + second;
            }
            let cx3 = x3 + ' ' + hour + ':' + minute + ':' + second

            document.getElementById('ct').innerHTML = cx3;
            display_c();
        }

        $(function() {
            window.onload = display_ct();

            var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d');

            var salesGraphChartData = {
                labels: [
                    @for ($i = 0; $i < sizeof($monthly); $i++)
                        '{{ date('F Y', strtotime($monthly[$i]['month'])) }}',
                    @endfor
                ],
                datasets: [{
                    label: 'Laba',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: '#058DC7',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: '#007bff',
                    pointBackgroundColor: '#007bff',
                    data: [
                        @for ($i = 0; $i < sizeof($monthly); $i++)
                            '{{ $monthly[$i]['laba'] }}',
                        @endfor
                    ]
                }]
            }

            var salesGraphChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false,
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            fontColor: '#000000',
                        },
                        gridLines: {
                            display: false,
                            color: '#f0f0ff',
                            drawBorder: false,
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 100000,
                            fontColor: '#000000',
                        },
                        gridLines: {
                            display: true,
                            color: '#f0f0ff',
                            drawBorder: false,
                        }
                    }]
                }
            }

            // This will get the first returned node in the jQuery collection.
            var salesGraphChart = new Chart(salesGraphChartCanvas, {
                type: 'line',
                data: salesGraphChartData,
                options: salesGraphChartOptions
            })
        });
    </script>

    @if (session()->has('success'))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session()->get('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            })
        </script>
    @endif
@endsection
