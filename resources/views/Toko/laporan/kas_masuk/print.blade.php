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
        size: landscape;
    }
    </style>
</head>

<body>
    <table class="table table-striped table-bordered table-hover nowrap">
        <thead class="text-center">
            <tr>
                <th>No.</th>
                <th>Nomor Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Kode Anggota</th>
                <th>Nama Anggota</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Jumlah Transaksi</th>
            </tr>
        </thead>
        @if (isset($laporan_kas_masuk) && count($laporan_kas_masuk) > 0)
        <tbody>
            @php
            $i = 1;
            @endphp
            @foreach ($laporan_kas_masuk AS $data)
            <tr>
                <th class="align-middle text-center">{{$i++}}</th>
                <td class="align-middle text-center">{{$data->nomor}}</td>
                <td class="align-middle text-center">{{$data->tanggal}}</td>
                <td class="align-middle">{{$data->kode_anggota}}</td>
                <td class="align-middle">{{$data->nama_anggota}}</td>
                <td class="align-middle text-center">{{$data->status}}</td>
                <td class="align-middle text-center">{{$data->keterangan}}</td>
                <td class="align-middle text-center">{{number_format($data->jumlah_transaksi, 2, ",", ".")}}</td>
            </tr>
            @endforeach
        </tbody>
        @endif
    </table>
</body>

<script>
window.print();
</script>