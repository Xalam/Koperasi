@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Pembelian</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/pembelian', 'method' => 'GET']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal Awal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_awal', (isset($tanggal_awal) ? $tanggal_awal : null), ['class' => 'col-lg-2
            form-control form-control-sm', 'required']) !!}
            {!! Form::label(null, '-', ['class' => 'offset-lg-1 col-lg-1']) !!}
            {!! Form::label(null, 'Tanggal Akhir', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_akhir', (isset($tanggal_akhir) ? $tanggal_akhir : null), ['class' => 'col-lg-2
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
    @if (isset($laporan_pembelian) && count($laporan_pembelian) > 0)
    <p class="card-header bg-light">Daftar {{$pembayaran[$type_pembayaran]}}</p>
    @else
    <p class="card-header bg-light">Daftar </p>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th class="col-2">Nama Barang</th>
                        <th>Harga Jual</th>
                        <th>Jumlah Jual</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                @if (isset($laporan_pembelian) && count($laporan_pembelian) > 0)
                <tbody>
                    @php
                    $i = 1 + 1 * ($laporan_pembelian->currentPage() - 1);
                    @endphp
                    @foreach ($laporan_pembelian AS $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{$data->harga_jual}}</td>
                        <td class="align-middle text-center">{{$data->jumlah}}</td>
                        <td class="align-middle text-center">{{$data->total_harga}}</td>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection