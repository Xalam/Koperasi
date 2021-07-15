@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Tambah Admin</p>
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/admin/store']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Kode Admin', ['class' => 'col-lg-2']) !!}
            {!! Form::text('kode', null, ['class' => 'col-lg-4 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Admin', ['class' => 'col-lg-2']) !!}
            {!! Form::text('nama', null, ['class' => 'col-lg-9 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Password', ['class' => 'col-lg-2']) !!}
            {!! Form::text('password', null, ['class' => 'col-lg-4 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jabatan', ['class' => 'col-lg-2']) !!}
            {!! Form::select('jabatan', ['Super_Admin' => 'Super Admin', 'Kanit' => 'Kanit', 'Gudang' => 'Gudang',
                'Kasir' => 'Kasir'], null, ['class' => 'col-lg-2 form-select form-select-sm',
            'required', 'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
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
$('[name="nama"]').change(function() {
    $(this).val($(this).val().replace(/\s/g, ''));
});
</script>
@endsection