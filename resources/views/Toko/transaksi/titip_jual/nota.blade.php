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
        font-size: 11px;
    }
    </style>
</head>

<body>
    <div class="p-2" style="width: 1024px;">
        <div class="align-content-center d-flex flex-row">
            <div class="col-1">
                <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="logo-koperasi"
                    style="position: absolute; transform: translateY(-50%); top: 50%;" width="48px" height="48px">
            </div>
            <div class="col-5">
                <b style="font-size: 16px;">Faktur Titip Jual</b><br>
                <b style="font-size: 14px;">Primkop Polrestabes Semarang</b><br>
                Jl. Kaligarang No. 1A, Barusari, Kec. Semarang Selatan, Kota Semarang, Jawa Tengah 50244,
                024-xxxxxxxx <br>
            </div>
            @foreach ($supplier as $data)
            @php
            $pembayaran = $data->pembayaran;
            $tanggal = $data->tanggal;
            $jumlahBayar = $data->jumlah_bayar;
            $jumlahKembalian = $data->jumlah_kembalian
            @endphp
            <div class="col-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td style="width: 100px;"><b>Nomor Transaksi</b></td>
                        <td><b>:</b></td>
                        <td>{{$data->nomor}}</td>
                        <td><b>Kode Supplier</b></td>
                        <td><b>:</b></td>
                        <td>{{$data->kode_supplier}}</td>
                    </tr>
                    <tr>
                        <td><b>Tanggal</b></td>
                        <td><b>:</b></td>
                        <td>{{$data->created_at}}</td>
                        <td><b>Nama Supplier</b></td>
                        <td><b>:</b></td>
                        <td>{{$data->nama_supplier}}</td>
                    </tr>
                    <tr>
                        <td><b>Alamat Supplier</b></td>
                        <td><b>:</b></td>
                        <td colspan="4">{{$data->alamat_supplier}}</td>
                    </tr>
                </table>
            </div>
            @endforeach
        </div>
        <table class="table table-sm mt-2">
            <thead class="table-light">
                <tr class="text-center">
                    <th>No.</th>
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Potongan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                $i = 1;
                $jumlahItem = 0;
                $jumlahHarga = 0;
                @endphp
                @foreach ($titip_jual as $data)
                @php
                $jumlahItem += $data->jumlah;
                $jumlahHarga += $data->total_harga;
                @endphp
                <tr>
                    <td class="text-center">{{$i++}}</td>
                    <td class="text-center">{{$data->kode_barang}}</td>
                    <td class="text-left">{{$data->nama_barang}}</td>
                    <td class="text-right">{{$data->jumlah}}</td>
                    <td class="text-center">{{$data->satuan}}</td>
                    <td class="text-right">{{number_format($data->harga_satuan, 2, ',', '.')}}</td>
                    <td class="text-right">0</td>
                    <td class="text-right">{{number_format($data->total_harga, 2, ',', '.')}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table table-sm table-borderless">
            <tr>
                <td colspan="4"></td>
                <td class="text-right">Jumlah Item</td>
                <td>:</td>
                <td class="text-right">{{$jumlahItem}}</td>
                <td></td>
                <td class="text-right">Sub Total</td>
                <td>:</td>
                <td class="text-right" style="width: 100px;">{{number_format($jumlahHarga, 2, ',', '.')}}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-center">
                    <div class="d-flex flex-row">
                        <div class="col-6">Hormat Kami</div>
                        <div class="col-6">Penerima</div>
                    </div>
                </td>
                <td class="text-right">Potongan</td>
                <td>:</td>
                <td class="text-right">0.00%</td>
                <td class="text-right">0.00</td>
                <td class="text-right">Total Akhir</td>
                <td>:</td>
                <td class="text-right" style="width: 100px;">{{number_format($jumlahHarga, 2, ',', '.')}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="text-right">Pajak</td>
                <td>:</td>
                <td class="text-right">0.00%</td>
                <td class="text-right">0.00</td>
                <td class="text-right"></td>
                <td></td>
                <td class="text-right" style="width: 100px;"></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="text-right">Biaya Lain</td>
                <td>:</td>
                <td class="text-right">0</td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td></td>
                <td class="text-right" style="width: 100px;"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center">
                    <div class="d-flex flex-row">
                        <div class="col-6">( _____________________________ )</div>
                        <div class="col-6">( _____________________________ )</div>
                    </div>
                </td>
                <td class="text-right">Tanggal Jt</td>
                <td>:</td>
                <td class="text-right">{{$tanggal}}</td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td></td>
                <td class="text-right" style="width: 100px;">
                </td>
            </tr>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
</body>

<script>
// window.print();
</script>