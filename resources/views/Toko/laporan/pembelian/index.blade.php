@extends('toko.layout')

@section('main')
<div class="card m-6">
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/pembelian', 'method' => 'GET']) !!}
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Laporan Pembelian', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Tanggal Awal', ['class' => 'col-3']) !!}
            {!! Form::date('tanggal_awal', (isset($tanggal_awal) ? $tanggal_awal : null), ['class' => 'col-4']) !!}
            {!! Form::label(null, '-', ['class' => 'offset-5 col-1']) !!}
            {!! Form::label(null, 'Tanggal Akhir', ['class' => 'col-3']) !!}
            {!! Form::date('tanggal_akhir', (isset($tanggal_akhir) ? $tanggal_akhir : null), ['class' => 'col-4']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Type Pembayaran', ['class' => 'col-5']) !!}
            {!! Form::select('type_pembayaran', $pembayaran, (isset($type_pembayaran) ? $type_pembayaran : null), ['class' => 'col-8']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-small']) !!}
        </div>
        {!! Form::close() !!}
        <hr>
        @if (isset($laporan_pembelian) && count($laporan_pembelian) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="text-center text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th class="col-2">Nama Barang</th>
                        <th>Harga Jual</th>
                        <th>Jumlah Jual</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody class="text-wrap">
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
            @if(empty($search))
            {{ $laporan_pembelian->appends(['dari' => $tanggal_awal, 'sampai' => $tanggal_akhir])->links() }}
            @else
            {{ $laporan_pembelian->appends(['dari' => $tanggal_awal, 'sampai' => $tanggal_akhir, 'search' => $search])->links() }}
            @endif
        </div>
        @endif
    </div>
</div>
@endsection