@extends('toko.layout')

@section('popup')
<div class="popup-background hide">
    <div class="popup center-object">
        <div class="popup-body">
            <div class="row align-item-center mb-1">
                {!! Form::label('null', 'Apakah anda yakin ingin menghapus data ini?', null) !!}
            </div>
            <div class="row align-item-center mb-1">
                {!! Form::submit('Simpan', ['class' => 'btn btn-block btn-success btn-small me-1']) !!}
                <a class="btn btn-block btn-small btn-danger ms-1">Batal</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('main')
<div class="card m-6">
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/barang/store']) !!}
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Tambah Barang', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Kode Barang', ['class' => 'col-5']) !!}
            {!! Form::text('kode', null, ['class' => 'col-8', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Nama Barang', ['class' => 'col-5']) !!}
            {!! Form::text('nama', null, ['class' =>'col-14', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Stok', ['class' => 'col-5']) !!}
            {!! Form::number('stok', null, ['class' => 'col-2', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Satuan', ['class' => 'col-5']) !!}
            {!! Form::select('satuan', ['Batang' => 'Batang', 'Botol' => 'Botol'], null, ['class' => 'col-4', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Harga Beli', ['class' => 'col-5']) !!}
            {!! Form::number('harga_beli', null, ['class' => 'col-3', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Harga Jual', ['class' => 'col-5']) !!}
            {!! Form::number('harga_jual', null, ['class' => 'col-3', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::submit('Simpan', ['class' => 'btn btn-primary btn-small']) !!}
        </div>
        {!! Form::close() !!}
        <hr>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Daftar Barang', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="text-center text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th class="col-2">Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th colspan="2">Opsi</th>
                    </tr>
                </thead>
                @if (count($barang) > 0)
                <tbody class="text-wrap">
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($barang as $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{$data->stok}}</td>
                        <td class="align-middle text-center">{{$data->harga_beli}}</td>
                        <td class="align-middle text-center">{{$data->harga_jual}}</td>
                        <td class="align-middle text-center"><a id=<?php echo "edit-" . $data->id ?> class="btn btn-small btn-warning">Edit</a>
                        <td class="align-middle text-center"><a id=<?php echo "hapus-" . $data->id ?> class="btn btn-small btn-danger">Hapus</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection