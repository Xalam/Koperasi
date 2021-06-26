@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Persediaan</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/persediaan/', 'method' => 'GET']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jumlah barang kurang dari', ['class' => 'col-lg-3']) !!}
            {!! Form::number('jumlah_barang', (isset($jumlah_barang) ? $jumlah_barang : 0), ['class' => 'col-lg-1
            form-control form-control-sm']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="card m-6">
    @if (isset($laporan_persediaan) && count($laporan_persediaan) > 0)
    <p class="card-header bg-light">Daftar Barang Kurang Dari {{$jumlah_barang}}</p>
    @else
    <p class="card-header bg-light">Daftar Barang </p>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th class="col-2">Nama Barang</th>
                        <th>Stok</th>
                        <th>HPP</th>
                        <th>Jumlah Harga</th>
                    </tr>
                </thead>
                @if (isset($laporan_persediaan) && count($laporan_persediaan) > 0)
                <tbody>
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
        </div>
        @endif
    </div>
</div>
@endsection