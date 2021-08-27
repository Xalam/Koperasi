@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Pembelian</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/pembelian', 'method' => 'GET']) !!}
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
        <div class="d-grid gap-2">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="card m-6">
    <div class="d-flex flex-row">
        @if (isset($laporan_pembelian) && count($laporan_pembelian) > 0)
        <p class="card-header col-lg">Daftar {{$pembayaran[$type_pembayaran]}} Pembelian</p>
        <a href=<?php echo 'pembelian/export/'.$type_pembayaran.'/'.$tanggal_awal.'/'.$tanggal_akhir ?> target="_blank"><i class="card-header text-success fas fa-file-export" style="cursor: pointer;" title="Export to Excel"></i></a>
        <a href=<?php echo 'pembelian/print/'.$type_pembayaran.'/'.$tanggal_awal.'/'.$tanggal_akhir ?> target="_blank"><i class="card-header text-success fas fa-print" style="cursor: pointer;" title="Print"></i></a>
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
                        <th>Nama Supplier</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Jumlah Beli</th>
                        <th>Total Harga</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                @if (isset($laporan_pembelian) && count($laporan_pembelian) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($laporan_pembelian AS $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->nomor}}</td>
                        <td class="align-middle text-center">{{$data->tanggal}}</td>
                        <td class="align-middle">{{$data->supplier}}</td>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{number_format($data->harga_satuan, 2, ",", ".")}}</td>
                        <td class="align-middle text-center">{{$data->jumlah}}</td>
                        <td class="align-middle text-center">{{number_format($data->total_harga, 2, ",", ".")}}</td>
                        <td class="align-middle text-center"><a href="<?php echo url('toko/laporan/pembelian/nota/' . $data->nomor); ?>" target="_blank"><i class="text-success fas fa-print"
                                style="cursor: pointer;" title="Print"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection