@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Tambah Anggota</p>
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/anggota/store']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Kode Anggota', ['class' => 'col-lg-2']) !!}
            {!! Form::text('kode', null, ['class' => 'col-lg-4 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Anggota', ['class' => 'col-lg-2']) !!}
            {!! Form::text('nama', null, ['class' =>'col-lg-9 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Status', ['class' => 'col-lg-2']) !!}
            {!! Form::select('status', ['Aktif' => 'Aktif', 'Tidak Aktif' => 'Tidak Aktif'], null, ['class' => 'col-lg-2
            form-select form-select-sm',
            'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jabatan', ['class' => 'col-lg-2']) !!}
            {!! Form::select('jabatan', ['Anggota' => 'Anggota', 'Umum' => 'Umum', 'Pengurus' => 'Pengurus'], null,
            ['class' => 'col-lg-2
            form-select form-select-sm',
            'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Alamat', ['class' => 'col-lg-2']) !!}
            {!! Form::text('alamat', null, ['class' =>'col-lg-9 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Gaji', ['class' => 'col-lg-2']) !!}
            {!! Form::number('gaji', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Limit Belanja', ['class' => 'col-lg-2']) !!}
            {!! Form::number('limit_belanja', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'readonly', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Telepon', ['class' => 'col-lg-2']) !!}
            {!! Form::number('telepon', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'WA', ['class' => 'col-lg-2']) !!}
            {!! Form::number('wa', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Simpan', ['class' => 'btn btn-sm btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('script')
<script>
$('[name="gaji"]').change(function() {
    $('[name="limit_belanja"]').val(($('[name="gaji"]').val() * 2 / 3).toFixed(0));
});
</script>
@endsection