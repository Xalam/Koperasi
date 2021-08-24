@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Penjualan</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/penjualan', 'method' => 'GET']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal Awal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_awal', (isset($tanggal_awal) ? $tanggal_awal : $cur_date), ['class' => 'col-lg-2
            form-control form-control-sm', 'required']) !!}
            {!! Form::label(null, '-', ['class' => 'offset-lg-1 col-lg-1']) !!}
            {!! Form::label(null, 'Tanggal Akhir', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_akhir', (isset($tanggal_akhir) ? $tanggal_akhir : $cur_date), ['class' => 'col-lg-2
            form-control form-control-sm']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Type Pembayaran', ['class' => 'col-lg-2']) !!}
            {!! Form::select('type_pembayaran', $pembayaran, (isset($type_pembayaran) ? $type_pembayaran : null),
            ['class' => 'col-lg-4 form-select form-select-sm']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Type Penjualan', ['class' => 'col-lg-2']) !!}
            {!! Form::select('type_penjualan', ['0' => 'Semua', '1' => 'Offline', '2' => 'Online'], (isset($type_penjualan) ? $type_penjualan : null),
            ['class' => 'col-lg-4 form-select form-select-sm']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="card m-6">
    <div class="d-flex flex-row">
        @if (isset($laporan_penjualan) && count($laporan_penjualan) > 0)
        <p class="card-header col-lg">Daftar {{$pembayaran[$type_pembayaran]}} Penjualan</p>
        <a href=<?php echo 'penjualan/export/'.$type_pembayaran.'/'.$tanggal_awal.'/'.$tanggal_akhir ?>
            target="_blank"><i class="card-header text-success fas fa-file-export" style="cursor: pointer;"
                title="Export to Excel"></i></a>
        <a href=<?php echo 'penjualan/print/'.$type_pembayaran.'/'.$tanggal_awal.'/'.$tanggal_akhir ?>
            target="_blank"><i class="card-header text-success fas fa-print" style="cursor: pointer;"
                title="Print"></i></a>
        @else
        <p class="card-header col-lg">Daftar </p>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No.</th>
                        <th>Nomor Transaksi</th>
                        <th>Tanggal Transaksi</th>
                        <th>Kode Anggota</th>
                        <th>Nama Anggota</th>
                        <th>Status Anggota</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga Jual</th>
                        <th>Jumlah Jual</th>
                        <th>Total Harga</th>
                        <th>Tipe Penjualan</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                @if (isset($laporan_penjualan) && count($laporan_penjualan) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($laporan_penjualan AS $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->nomor}}</td>
                        <td class="align-middle text-center">{{$data->tanggal}}</td>
                        @if (isset($data->kode_anggota))
                        <td class="align-middle">{{$data->kode_anggota}}</td>
                        <td class="align-middle">{{$data->nama_anggota}}</td>
                        <td class="align-middle text-center">{{$data->status}}</td>
                        @else
                        <td class="align-middle">Masyarakat Umum</td>
                        <td class="align-middle">Masyarakat Umum</td>
                        <td class="align-middle text-center">-</td>
                        @endif
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                <td class="align-middle text-center">{{($data->jumlah >= $data->minimal_grosir) ? number_format($data->harga_grosir, 2, ",", ".") : number_format($data->harga_jual, 2, ",", ".")}}</td>
                        <td class="align-middle text-center">{{$data->jumlah}}</td>
                        <td class="align-middle text-center">{{number_format($data->total_harga, 2, ",", ".")}}</td>
                        <td class="align-middle text-center">{{($data->type_penjualan == 1) ? 'Offline' : 'Online'}}</td>
                        <td class="align-middle text-center"><a
                                href="<?php echo url('toko/laporan/penjualan/nota/' . $data->nomor); ?>"
                                target="_blank"><i class="text-success fas fa-print" style="cursor: pointer;"
                                    title="Print"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection