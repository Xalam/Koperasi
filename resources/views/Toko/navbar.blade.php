<nav class="navbar navbar-expand-lg overflow-visible">
    <div class="container-fluid">
        <a href="/" class="navbar-brand text-wrap">Primer Koperasi Kepolisian Resor Kota Besar Semarang</a>
        <div class="d-flex">
            <div class="me-3">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <i id="notification" class="fas fa-bell fa-lg position-relative" style="cursor: pointer;"
                            data-toggle="dropdown">
                            @if (isset($data_notif))
                            <span id="notification-count" class="notification-count">{{count($data_notif)}}</span>
                            @endif
                        </i>
                        <ul class="dropdown-menu dropdown-menu-right alert-primary mt-2 ps-1 pe-1"
                            style="right: -12px;">
                            @if (isset($data_notified) && count($data_notified) > 0)
                            @foreach ($data_notified as $data)
                            @if ($data->stok <= $data->stok_minimal)
                                <li class="alert dropdown-notification-item">
                                    <div class="alert-close close" data-dismiss="alert" aria-label="close">
                                        <i id="<?php echo $data->id; ?>" class="fas fa-times" aria-hidden="true"
                                            onclick="close_notification(<?php echo $data->id; ?>)"></i>
                                    </div>
                                    <p id="alert-title" class="alert-message"><b>Pemberitahuan Persediaan Barang</b>
                                        <br> Persediaan {{$data->nama}} kurang
                                        dari stok minimal
                                    </p>
                                </li>
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