@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Tambah Supplier</p>
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/supplier/store']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Kode Supplier', ['class' => 'col-lg-2']) !!}
            {!! Form::text('kode', null, ['class' => 'col-lg-4 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Supplier', ['class' => 'col-lg-2']) !!}
            {!! Form::text('nama', null, ['class' =>'col-lg-9 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Alamat', ['class' => 'col-lg-2']) !!}
            {!! Form::text('alamat', null, ['class' => 'col-lg-9 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nomor Telepon', ['class' => 'col-lg-2']) !!}
            {!! Form::number('telepon', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nomor WA', ['class' => 'col-lg-2']) !!}
            {!! Form::number('wa', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jarak', ['class' => 'col-lg-2']) !!}
            {!! Form::number('jarak', 0, ['class' => 'col-lg-1 form-control form-control-sm', 'step' => 'any',
            'required']) !!}
            {!! Form::label(null, 'km', ['class' => 'col-lg-1']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Email', ['class' => 'col-lg-2']) !!}
            {!! Form::email('email', null, ['class' => 'col-lg-9 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Keterangan', ['class' => 'col-lg-2']) !!}
            {!! Form::text('keterangan', null, ['class' => 'col-lg-9 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tempo', ['class' => 'col-lg-2']) !!}
            {!! Form::text('tempo', null, ['class' => 'col-lg-1 form-control form-control-sm', 'required']) !!}
            {!! Form::label(null, 'hari', ['class' => 'col-lg-1']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Simpan', ['class' => 'btn btn-sm btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection