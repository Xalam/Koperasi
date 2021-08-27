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
    <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
        <thead class="text-center">
            <tr>
                @if (isset($laporan_master) && count($laporan_master) > 0)
                @if ($bagian == 'Admin')
                <th>No</th>
                <th>Kode Admin</th>
                <th>Nama Admin</th>
                <th>Jabatan Admin</th>
                @elseif ($bagian == 'Barang')
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>HPP</th>
                <th>Harga Jual</th>
                <th>Satuan</th>
                <th>Stok Etalase</th>
                <th>Stok Gudang</th>
                @elseif ($bagian == 'Anggota')
                <th>No</th>
                <th>Kode Anggota</th>
                <th>Nama Anggota</th>
                <th>Jabatan</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>WA</th>
                <th>Status</th>
                @else
                <th>No</th>
                <th>Kode Supplier</th>
                <th>Nama Supplier</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>WA</th>
                <th>Tempo</th>
                @endif
                @else
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Telepon</th>
                @endif
            </tr>
        </thead>
        @if (isset($laporan_master) && count($laporan_master) > 0)
        <tbody>
            @php
            $i = 1;
            @endphp
            @foreach ($laporan_master as $data)
            <tr>
                @if ($bagian == 'Admin')
                <th class="align-middle text-center">{{$i++}}</th>
                <td class="align-middle text-center">{{$data->kode}}</td>
                <td class="align-middle">{{$data->nama}}</td>
                <td class="align-middle text-center">{{$data->jabatan}}</td>
                @elseif ($bagian == 'Barang')
                <th class="align-middle text-center">{{$i++}}</th>
                <td class="align-middle text-center">{{$data->kode}}</td>
                <td class="align-middle">{{$data->nama}}</td>
                <td class="align-middle text-center">{{number_format($data->hpp, 2, ",", ".")}}</td>
                <td class="align-middle text-center">{{number_format($data->harga_jual, 2, ",", ".")}}</td>
                <td class="align-middle text-center">{{$data->satuan}}</td>
                <td class="align-middle text-center">{{$data->stok_etalase}}</td>
                <td class="align-middle text-center">{{$data->stok_gudang}}</td>
                @elseif ($bagian == 'Anggota')
                <th class="align-middle text-center">{{$i++}}</th>
                <td class="align-middle text-center">{{$data->kd_anggota}}</td>
                <td class="align-middle">{{$data->nama_anggota}}</td>
                <td class="align-middle text-center">{{$data->jabatan}}</td>
                <td class="align-middle">{{$data->alamat}}</td>
                <td class="align-middle text-center">{{$data->no_hp}}</td>
                <td class="align-middle text-center">{{$data->no_wa}}</td>
                <td class="align-middle text-center">{{$data->status}}</td>
                @else
                <th class="align-middle text-center">{{$i++}}</th>
                <td class="align-middle text-center">{{$data->kode}}</td>
                <td class="align-middle">{{$data->nama}}</td>
                <td class="align-middle">{{$data->alamat}}</td>
                <td class="align-middle text-center">{{$data->telepon}}</td>
                <td class="align-middle text-center">{{$data->wa}}</td>
                <td class="align-middle text-center">{{$data->tempo}}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
        @endif
    </table>
</body>

<script>
window.print();
</script>