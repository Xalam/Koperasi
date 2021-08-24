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

    <link rel="stylesheet" href="{{ asset('bootstrap 5/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">

    <style type="text/css" media="print">
    @page {
        size: portrait;
    }
    </style>
    <style>
    body {
        font-size: 10px;
    }
    </style>
</head>

<body>
    <div class="p-2" style="width: 216px;">
        <div class="text-center">
            <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="logo-koperasi" width="48px">
            <p class="mt-2"><b>Primkop Polrestabes Semarang</b></p>
            <p>Jl. Kaligarang No. 1A, Barusari, Kec. Semarang Selatan, Kota Semarang, Jawa Tengah 50244</p>
            024-xxxxxxxx
            =================================
        </div>
        <table class="table table-sm table-borderless">
            <thead>
                <tr class="text-center">
                    <td style="width: 100px;">Item</td>
                    <td class="text-center" style="width: 10px;">Qty</td>
                    <td class="text-center" style="width: 53px;">Price</td>
                    <td class="text-center" style="width: 53px;">Total</td>
                </tr>
            </thead>
            <tbody>
                @php
                $jumlahItem = 0;
                @endphp
                @foreach ($penjualan as $data)
                <tr>
                    @php
                    $jumlahItem += $data->jumlah;
                    @endphp
                    <td>{{$data->nama_barang}}</td>
                    <td class="text-center">{{$data->jumlah}}</td>
                    <td class="text-center">{{($data->jumlah >= $data->minimal_grosir) ? number_format($data->harga_grosir, 2, ",", ".") : number_format($data->harga_jual, 2, ",", ".")}}</td>
                    <td class="text-center">{{number_format($data->total_harga, 2, ",", ".")}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        -----------------------------------------------
        @foreach ($pembeli as $data)
        <table class="table table-sm table-borderless">
            <tbody>
                <tr>
                    <td style="width: 100px;">Pembelian</td>
                    <td class="text-center" style="width: 10px;">{{$jumlahItem}}</td>
                    <td class="text-right" style="width: 53px;">Rp. </td>
                    <td class="text-center" style="width: 53px;">{{number_format($data->jumlah_harga, 2, ",", ".")}}</td>
                </tr>
                <tr>
                    <td>Pembayaran</td>
                    <td></td>
                    <td class="text-right">Rp. </td>
                    <td class="text-center">{{number_format($data->jumlah_bayar, 2, ",", ".")}}</td>
                </tr>
                <tr>
                    <td>Kembalian</td>
                    <td></td>
                    <td class="text-right">Rp. </td>
                    <td class="text-center">{{number_format($data->jumlah_kembalian, 2, ",", ".")}}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center">
                        <b>{{($data->pembayaran == 1) ? 'Faktur Penjualan' : 'Nota Penjualan'}}</b>
                        <br>
                        ======= {{$data->created_at}} =======
                        <br>
                        <b>{{(isset($data->nama_anggota)) ? $data->nama_anggota : 'Masyarakat Umum'}}</b>
                    </td>
                </tr>
            </tbody>
        </table>
        @endforeach
        <div class="text-center">
            ----------------------------------------------- <br>
            Barang yang sudah dibeli tidak boleh dikembalikan <br>
            ----------------------------------------------- <br>
            <b>Belanja aman dan mudah dengan menggunakan aplikasi koperasi Primkop Polrestabes Semarang di perangkat android anda</b> <br>
            -----------------------------------------------
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
</body>

<script>
window.print();
</script>