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
        {!! Form::open(['url' => '/toko/master/akun/store']) !!}
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Tambah Akun', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Kode Akun', ['class' => 'col-5']) !!}
            {!! Form::text('kode', null, ['class' => 'col-8', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Nama Akun', ['class' => 'col-5']) !!}
            {!! Form::text('nama', null, ['class' =>'col-14', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Saldo Awal', ['class' => 'col-5']) !!}
            {!! Form::text('saldo', null, ['class' => 'col-8', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::submit('Simpan', ['class' => 'btn btn-primary btn-small']) !!}
        </div>
        {!! Form::close() !!}
        <hr>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Daftar Akun', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="text-center text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Kode Akun</th>
                        <th class="col-2">Nama Akun</th>
                        <th>Saldo Awal</th>
                        <th colspan="2">Opsi</th>
                    </tr>
                </thead>
                @if (count($akun) > 0)
                <tbody class="text-wrap">
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($akun as $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{$data->saldo}}</td>
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