@extends('toko.layout')

@section('main')
<div class="card m-6">
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/persediaan/', 'method' => 'GET']) !!}
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Laporan Persediaan', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Jumlah barang kurang dari', ['class' => 'col-5']) !!}
            {!! Form::number('jumlah_barang', (isset($jumlah_barang) ? $jumlah_barang : null), ['class' => 'col-2 text-center']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-small']) !!}
        </div>
        {!! Form::close() !!}
        <hr>
        @if (isset($laporan_persediaan) && count($laporan_persediaan) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="text-center text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th class="col-2">Nama Barang</th>
                        <th>Stok</th>
                        <th>HPP</th>
                        <th>Jumlah Harga</th>
                    </tr>
                </thead>
                <tbody class="text-wrap">
                    @php
                    $i = 1 + 1 * ($laporan_persediaan->currentPage() - 1);
                    @endphp
                    @foreach ($laporan_persediaan AS $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{$data->stok}}</td>
                        <td class="align-middle text-center">{{$data->harga_jual}}</td>
                        <td class="align-middle text-center">{{$data->stok * $data->harga_jual}}</td>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if(empty($search))
            {{ $laporan_persediaan->appends(['minimal-barang' => $jumlah_barang])->links() }}
            @else
            {{ $laporan_persediaan->appends(['minimal-barang' => $jumlah_barang, 'search' => $search])->links() }}
            @endif
        </div>
        @endif
    </div>
</div>
@endsection