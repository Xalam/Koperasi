@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Tambah Anggota</p>
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/anggota/store', 'method' => 'post', 'enctype' => 'multipart/form-data'])
        !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Anggota', ['class' => 'col-lg-2']) !!}
            {!! Form::text('nama_anggota', null, ['class' =>'col-lg-9 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jenis Kelamin', ['class' => 'col-lg-2']) !!}
            {!! Form::select('jenis_kelamin', ['Pria' => 'Pria', 'Wanita' => 'Wanita'], null, ['class' => 'col-lg-2
            form-select form-select-sm',
            'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Agama', ['class' => 'col-lg-2']) !!}
            {!! Form::select('agama', ['Lainnya' => 'Lainnya', 'Buddha' => 'Buddha', 'Hindu' => 'Hindu', 'Islam' =>
            'Islam', 'Katholik' => 'Katholik', 'Kristen' =>
            'Kristen', 'Khonghucu' => 'Khonghucu'], null, ['class' => 'col-lg-2
            form-select form-select-sm',
            'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tempat Lahir', ['class' => 'col-lg-2']) !!}
            {!! Form::text('tempat_lahir', null, ['class' =>'col-lg-4 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal Lahir', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_lahir', null, ['class' =>'col-lg-2 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Alamat', ['class' => 'col-lg-2']) !!}
            {!! Form::text('alamat', null, ['class' =>'col-lg-9 form-control form-control-sm', 'required', 'oninvalid'
            => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Telepon', ['class' => 'col-lg-2']) !!}
            {!! Form::number('no_hp', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required', 'oninvalid' =>
            "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'WA', ['class' => 'col-lg-2']) !!}
            {!! Form::number('no_wa', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required', 'oninvalid' =>
            "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Status', ['class' => 'col-lg-2']) !!}
            {!! Form::select('status', ['Belum Menikah' => 'Belum Menikah', 'Sudah Menikah' => 'Sudah Menikah'], null,
            ['class' => 'col-lg-2
            form-select form-select-sm',
            'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jabatan', ['class' => 'col-lg-2']) !!}
            {!! Form::text('jabatan', null, ['class' => 'col-lg-2 form-select form-select-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Email', ['class' => 'col-lg-2']) !!}
            {!! Form::email('email', null, ['class' =>'col-lg-4 form-control form-control-sm', 'required', 'oninvalid'
            => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Username (NRP)', ['class' => 'col-lg-2']) !!}
            {!! Form::number('username', null, ['class' =>'col-lg-4 form-control form-control-sm', 'required', 'oninvalid'
            => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Password', ['class' => 'col-lg-2']) !!}
            {!! Form::password('password', ['class' =>'col-lg-4 form-control form-control-sm', 'required', 'oninvalid'
            => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Role', ['class' => 'col-lg-2']) !!}
            {!! Form::select('role', ['Anggota' => 'Anggota', 'Umum' => 'Umum', 'Pengurus' => 'Pengurus'], null,
            ['class' => 'col-lg-2
            form-select form-select-sm',
            'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Gaji', ['class' => 'col-lg-2']) !!}
            {!! Form::number('gaji', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required', 'oninvalid' =>
            "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Limit Gaji', ['class' => 'col-lg-2']) !!}
            {!! Form::number('limit_gaji', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'readonly',
            'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Foto', ['class' => 'col-lg-2']) !!}
            {!! Form::file('foto', ['class' => 'col-lg-4 form-control form-control-sm',
            'required']) !!}
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
        window.location = "/toko/master/anggota";
    }, 1000);
});
</script>
@elseif (Session::get('failed'))
<script>
$(document).ready(function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'middle',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });

    Toast.fire({
        icon: 'error',
        title: 'Gagal',
        text: `{{Session::get('failed')}}`
    });
    setTimeout(function() {
        window.location = "/toko/master/anggota/create";
    }, 2000);
});
</script>
@endif
<script>
$('[name="gaji"]').change(function() {
    $('[name="limit_gaji"]').val(($('[name="gaji"]').val() * 2 / 3).toFixed(0));
});
</script>
@endsection