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
        {!! Form::open(['url' => '/toko/master/pelanggan/store']) !!}
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Tambah Pelanggan', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Kode Pelanggan', ['class' => 'col-5']) !!}
            {!! Form::text('kode', null, ['class' => 'col-8', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Nama Pelanggan', ['class' => 'col-5']) !!}
            {!! Form::text('nama', null, ['class' =>'col-14', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Alamat', ['class' => 'col-5']) !!}
            {!! Form::text('alamat', null, ['class' => 'col-8', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Telepon', ['class' => 'col-5']) !!}
            {!! Form::number('telepon', 0, ['class' => 'col-8', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::submit('Simpan', ['class' => 'btn btn-primary btn-small']) !!}
        </div>
        {!! Form::close() !!}
        <hr>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Daftar Pelanggan', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="text-center text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Kode Pelanggan</th>
                        <th class="col-2">Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th colspan="2">Opsi</th>
                    </tr>
                </thead>
                @if (count($pelanggan) > 0)
                <tbody class="text-wrap">
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($pelanggan as $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle">{{$data->alamat}}</td>
                        <td class="align-middle text-center">{{$data->telepon}}</td>
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