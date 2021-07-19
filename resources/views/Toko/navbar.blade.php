<nav class="navbar navbar-expand-lg overflow-visible">
    <div class="container-fluid">
        <a href="/" class="navbar-brand text-wrap">Primer Koperasi Kepolisian Resor Kota Besar Semarang</a>
        <div class="d-flex">
            <div class="me-3">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        @php
                        $total_notif = 0;
                        @endphp
                        @foreach ($data_notified as $data)
                            @php
                            $expired = explode('-', $data->expired);
                            $now = explode('-', date('Y-m-d'));

                            $diffYears = $expired[0] - $now[0];
                            $diffMonths = $expired[1] - $now[1];
                            $diffDays = $expired[2] - $now[2];
                            
                            if ($data->alert_status > 0) {
                                $total_notif++;
                            }

                            if ($diffDays <= 3) {
                                $total_notif++;
                            }
                            @endphp
                        @endforeach
                        <i id="notification" class="fas fa-bell fa-lg position-relative" style="cursor: pointer;"
                            data-toggle="dropdown">
                            <span id="notification-count" class="notification-count">{{$total_notif}}</span>
                        </i>
                        <ul class="dropdown-menu dropdown-menu-right alert-primary mt-2 ps-2 pe-2 overflow-auto"
                            style="max-height: 512px; right: -12px;">
                            @foreach ($data_notified as $data)
                                @php
                                $expired = explode('-', $data->expired);
                                $now = explode('-', date('Y-m-d'));

                                $diffYears = $expired[0] - $now[0];
                                $diffMonths = $expired[1] - $now[1];
                                $diffDays = $expired[2] - $now[2];
                                @endphp
                                @if (isset($data_notified) && count($data_notified) > 0)
                                @if ($data->stok <= $data->stok_minimal)
                                <li class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i id="<?php echo $data->id; ?>" class="fas fa-times" aria-hidden="true"
                                            onclick="close_notification(<?php echo $data->id; ?>)"></i>
                                    </div>
                                    <p id="alert-title" class="alert-message"><b>Pemberitahuan Persediaan Barang</b>
                                        <br> Persediaan <b class="text-danger">{{$data->nama}}</b> kurang
                                        dari <b class="text-danger">stok minimal</b>
                                    </p>
                                </li>
                                @endif
                                @endif
                                @if ($diffYears == 0 && $diffMonths == 0 && $diffDays < 0)
                                <div class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i class="fas fa-times" aria-hidden="true"></i>
                                    </div>
                                    <p class="alert-message"><b>Pemberitahuan Persediaan Barang</b> <br> Persediaan
                                        <b class="text-danger">{{$data->nama}}</b> telah <b class="text-danger">expired</b> pada tanggal <b class="text-danger">{{$data->expired}}</b>.</p>
                                    <a href="/toko/master/barang" class="btn btn-sm btn-success mb-2">Ubah Tanggal</a>
                                </div>
                                @elseif ($diffYears == 0 && $diffMonths == 0 && $diffDays == 0)
                                <div class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i class="fas fa-times" aria-hidden="true"></i>
                                    </div>
                                    <p class="alert-message"><b>Pemberitahuan Persediaan Barang</b> <br> Persediaan
                                        <b class="text-danger">{{$data->nama}}</b> akan <b class="text-danger">expired</b> hari ini.</p>
                                    <a href="/toko/master/barang" class="btn btn-sm btn-success">Ubah Tanggal</a>
                                </div>
                                @elseif ($diffYears == 0 && $diffMonths == 0 && $diffDays <= 3)
                                <div class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i class="fas fa-times" aria-hidden="true"></i>
                                    </div>
                                    <p class="alert-message"><b>Pemberitahuan Persediaan Barang</b> <br> Persediaan
                                        <b class="text-danger">{{$data->nama}}</b> akan <b class="text-danger">expired</b> dalam waktu {{$diffDays}} hari.</p>
                                    <a href="/toko/master/barang" class="btn btn-sm btn-success">Ubah Tanggal</a>
                                </div>
                                @endif
                                @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
            @if (Auth::check())
            <a href="/toko/logout" class="text-login">Logout</a>
            @else
            <a href="/toko/login" class="text-login">Login</a>
            @endif
        </div>
    </div>
</nav>