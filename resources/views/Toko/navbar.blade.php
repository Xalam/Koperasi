<nav class="navbar navbar-expand-lg overflow-visible">
    <div class="container-fluid">
        <a href="/toko/dashboard" class="navbar-brand text-wrap">Primer Koperasi Kepolisian Resor Kota Besar Semarang</a>
        <div class="d-flex flex-row">
            <div class="me-3">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        @php
                        $total_notif = 0;
                        @endphp
                        @foreach ($data_notified as $data)
                            @php
                            $now = explode('-', date('y-m-d'));

                            $diffDays = date('t') + 1 - $now[2];
                            
                            if ($data->alert_status > 0) { $total_notif++; }

                            if ($diffDays <= 3) { $total_notif++; }
                            @endphp
                        @endforeach
                        @if(isset($data_notif_hutang))
                        @foreach ($data_notif_hutang as $data)
                            @php
                            $now = date('y-m-d');
                            $jatuhTempo = $data->jatuh_tempo;
                            $dateNow = date('Y-m-d', strtotime($now. ' + 3 days'));

                            $diff = abs(strtotime($dateNow) - strtotime($jatuhTempo));

                            $years = floor($diff / (365*60*60*24));
                            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                            if (strtotime($dateNow) - strtotime($jatuhTempo) < 0) {
                                $days *= -1;
                            }

                            if ($days >= 0) { $total_notif++; }
                            @endphp
                        @endforeach
                        @endif
                        <i id="notification" class="fas fa-bell fa-lg position-relative" style="cursor: pointer;"
                            data-toggle="dropdown">
                            <span id="notification-count" class="notification-count">{{$total_notif}}</span>
                        </i>
                        <ul class="dropdown-menu dropdown-menu-right alert-primary mt-2 ps-2 pe-2 overflow-auto"
                            style="max-height: 400px; width: 300px; right: -12px;">
                            @foreach ($data_notified as $data)
                                @php
                                $now = explode('-', date('y-m-d'));

                                $diffYears = $data->expired_tahun - $now[0];
                                $diffMonths = $data->expired_bulan - $now[1];
                                $diffDays = date('t') + 1 - $now[2];
                                @endphp
                                @if (isset($data_notified) && count($data_notified) > 0)
                                @if ($data->stok_etalase <= $data->stok_minimal && $data->stok_gudang > $data->stok_minimal)
                                <li class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i id="<?php echo $data->id; ?>" class="fas fa-times" aria-hidden="true"
                                            onclick="close_notification(<?php echo $data->id; ?>)"></i>
                                    </div>
                                    <p id="alert-title" class="alert-message text-wrap"><b>Pemberitahuan Persediaan Barang Etalase</b>
                                        <br> Persediaan <b class="text-danger">{{$data->nama}}</b> di etalase kurang
                                        dari <b class="text-danger">stok minimal</b>
                                    </p>
                                </li>
                                @elseif ($data->stok_etalase > $data->stok_minimal && $data->stok_gudang <= $data->stok_minimal)
                                <li class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i id="<?php echo $data->id; ?>" class="fas fa-times" aria-hidden="true"
                                            onclick="close_notification(<?php echo $data->id; ?>)"></i>
                                    </div>
                                    <p id="alert-title" class="alert-message text-wrap"><b>Pemberitahuan Persediaan Barang Gudang</b>
                                        <br> Persediaan <b class="text-danger">{{$data->nama}}</b> di gudang kurang
                                        dari <b class="text-danger">stok minimal</b>
                                    </p>
                                </li>
                                @elseif ($data->stok_etalase <= $data->stok_minimal && $data->stok_gudang <= $data->stok_minimal)
                                <li class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i id="<?php echo $data->id; ?>" class="fas fa-times" aria-hidden="true"
                                            onclick="close_notification(<?php echo $data->id; ?>)"></i>
                                    </div>
                                    <p id="alert-title" class="alert-message text-wrap"><b>Pemberitahuan Persediaan Barang</b>
                                        <br> Persediaan <b class="text-danger">{{$data->nama}}</b> di etalase & gudang kurang
                                        dari <b class="text-danger">stok minimal</b>
                                    </p>
                                </li>
                                @endif
                                @endif
                                @if ($diffYears == 0 && $diffMonths == 0 && $diffDays < 0)
                                <div class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i id="<?php echo $data->id; ?>" class="fas fa-times" aria-hidden="true"
                                            onclick="close_notification(<?php echo $data->id; ?>)"></i>
                                    </div>
                                    <p class="alert-message text-wrap"><b>Pemberitahuan Persediaan Barang</b> <br> Persediaan
                                        <b class="text-danger">{{$data->nama}}</b> telah <b class="text-danger">expired</b> pada tanggal <b class="text-danger">{{$data->expired}}</b>.</p>
                                    <a href="/toko/master/barang" class="btn btn-sm btn-success mb-2">Ubah Tanggal</a>
                                </div>
                                @elseif ($diffYears == 0 && $diffMonths == 0 && $diffDays == 0)
                                <div class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i id="<?php echo $data->id; ?>" class="fas fa-times" aria-hidden="true"
                                            onclick="close_notification(<?php echo $data->id; ?>)"></i>
                                    </div>
                                    <p class="alert-message text-wrap"><b>Pemberitahuan Persediaan Barang</b> <br> Persediaan
                                        <b class="text-danger">{{$data->nama}}</b> akan <b class="text-danger">expired</b> hari ini.</p>
                                    <a href="/toko/master/barang" class="btn btn-sm btn-success">Ubah Tanggal</a>
                                </div>
                                @elseif ($diffYears == 0 && $diffMonths == 0 && $diffDays <= 3)
                                <div class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i id="<?php echo $data->id; ?>" class="fas fa-times" aria-hidden="true"
                                            onclick="close_notification(<?php echo $data->id; ?>)"></i>
                                    </div>
                                    <p class="alert-message text-wrap"><b>Pemberitahuan Persediaan Barang</b> <br> Persediaan
                                        <b class="text-danger">{{$data->nama}}</b> akan <b class="text-danger">expired</b> dalam waktu {{$diffDays}} hari.</p>
                                    <a href="/toko/master/barang" class="btn btn-sm btn-success">Ubah Tanggal</a>
                                </div>
                                @endif
                                @endforeach
                                @if (isset($data_notif_hutang))
                                @foreach ($data_notif_hutang as $data)
                                    @php
                                    $now = date('y-m-d');
                                    $jatuhTempo = $data->jatuh_tempo;
                                    $dateNow = date('Y-m-d', strtotime($now. ' + 3 days'));

                                    $diff = abs(strtotime($dateNow) - strtotime($jatuhTempo));

                                    $years = floor($diff / (365*60*60*24));
                                    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                                    if (strtotime($dateNow) - strtotime($jatuhTempo) < 0) {
                                        $days *= -1;
                                    }
                                    @endphp

                                    @if ($data->alert_status > 0)
                                    <div class="alert dropdown-notification-item">
                                        <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                            <i id="<?php echo $data->id; ?>" class="fas fa-times" aria-hidden="true"
                                                onclick="close_notification_utang(<?php echo $data->id; ?>)"></i>
                                        </div>
                                        <p class="alert-message text-wrap"><b>Pemberitahuan Jatuh Tempo Utang</b> <br> Utang pada
                                            <b class="text-danger">{{$data->nama_supplier}}</b> dengan nomor beli 
                                            <b class="text-danger">{{$data->nomor_beli}}</b> jatuh tempo pada tanggal 
                                            <b class="text-danger">{{$data->jatuh_tempo}}</b>.</p>
                                    </div>
                                    @endif
                                @endforeach
                                @endif
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

@section('script')
<script>
$('.close').click(function() {
    $total_notif = $total_notif - 1;
    $('#notification-count').text($total_notif);
});
</script>
@endsection