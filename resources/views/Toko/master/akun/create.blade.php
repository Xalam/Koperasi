@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Tambah Akun</p>
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/akun/store']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Kode Akun', ['class' => 'col-lg-2']) !!}
            {!! Form::text('kode', null, ['class' => 'col-lg-4 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Akun', ['class' => 'col-lg-2']) !!}
            {!! Form::text('nama', null, ['class' =>'col-lg-9 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Saldo Awal', ['class' => 'col-lg-2']) !!}
            {!! Form::number('saldo', null, ['class' => 'col-lg-2 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')", 'onchange' => 'currencyDigit(this)']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Simpan', ['class' => 'btn btn-sm btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('script')
@if(Session::get('success'))
<script>
$(document).ready(function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'middle',
        showConfirmButton: false,
        timer: 1000,
        timerProgressBar: true
    });

    Toast.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Data Berhasil Disimpan'
    });
    setTimeout(function() {
        window.location = "/toko/master/akun";
    }, 1000);
});
</script>
@elseif(Session::get('failed'))
<script>
$(document).ready(function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'middle',
        showConfirmButton: false,
        timer: 1000,
        timerProgressBar: true
    });

    Toast.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Data Gagal Disimpan'
    });
    setTimeout(function() {
        window.location = "/toko/master/akun/create";
    }, 1000);
});
</script>
@endif
@endsection