@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Tambah Supplier</p>
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/supplier/store']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Kode Supplier', ['class' => 'col-lg-2']) !!}
            {!! Form::text('kode', 'S', ['class' => 'col-lg-4 form-control form-control-sm',
            'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')",
            'readonly']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Supplier', ['class' => 'col-lg-2']) !!}
            {!! Form::text('nama', null, ['class' =>'col-lg-9 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'onkeyup' => 'setKode()', 'oninput' => "this.setCustomValidity('')"])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Alamat', ['class' => 'col-lg-2']) !!}
            {!! Form::text('alamat', null, ['class' => 'col-lg-9 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nomor Telepon', ['class' => 'col-lg-2']) !!}
            {!! Form::number('telepon', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nomor WA', ['class' => 'col-lg-2']) !!}
            {!! Form::number('wa', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jarak', ['class' => 'col-lg-2']) !!}
            {!! Form::text('jarak', null, ['class' => 'col-lg-1 form-control form-control-sm', 'step' => 'any',
            'required', 'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' =>
            "this.setCustomValidity('')", 'onkeyup' => 'formatNumber(event)', 'onkeypress' => 'formatNumber(event)', 'maxlength' => '3']) !!}
            {!! Form::label(null, 'km', ['class' => 'col-lg-1']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Lama Pengiriman', ['class' => 'col-lg-2']) !!}
            {!! Form::text('lama_kirim', null, ['class' => 'col-lg-1 form-control form-control-sm', 'step' => 'any',
            'required', 'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' =>
            "this.setCustomValidity('')", 'onkeyup' => 'formatNumber(event)', 'onkeypress' => 'formatNumber(event)', 'maxlength' => '2']) !!}
            {!! Form::label(null, 'hari', ['class' => 'col-lg-1']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Email', ['class' => 'col-lg-2']) !!}
            {!! Form::email('email', null, ['class' => 'col-lg-9 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Keterangan', ['class' => 'col-lg-2']) !!}
            {!! Form::text('keterangan', null, ['class' => 'col-lg-9 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tempo', ['class' => 'col-lg-2']) !!}
            {!! Form::number('tempo', 0, ['class' => 'col-lg-1 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
            {!! Form::label(null, 'hari', ['class' => 'col-lg-1']) !!}
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
        timer: 2000,
        timerProgressBar: true
    });

    Toast.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Data Berhasil Disimpan'
    });
    setTimeout(function() {
        window.location = "/toko/master/supplier";
    }, 2000);
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
        window.location = "/toko/master/supplier/create";
    }, 2000);
});
</script>
@endif
<script>
function setKode() {
    var split = $("input[name='nama']").val().split(' ');

    var initial = '';

    for (var i = 0; i < split.length; i++) {
        if (i < 3 && split[i][0] != null) {
            initial += split[i][0];
        }
    }

    $("input[name='kode']").val("S" + initial.toUpperCase() + $("[name='jarak']").val() + $(
        "[name='lama_kirim']").val());
}



function formatString(e) {
    var code = event.keyCode;
    var allowedKeys = [8];
    if (allowedKeys.indexOf(code) !== -1) {
        return;
    }
    event.target.value = event.target.value.replace(
        /^([1-9]\/|[2-9])$/g, '0$1'
    ).replace(
        /^(0[1-9]{1}|1[0-2]{1})$/g, '$1'
    ).replace(
        /^([0]{1,})\/|[0]{1,}$/g, '0'
    ).replace(
        /[^\d\/]|^[\/]{0,}$/g, ''
    );
    
}

function formatNumber(evt) {
    event.target.value = event.target.value.replace(
        /[^\d\/]{0,}$/g, ''
    );

    setKode();
}

$('[name="jarak"]').focusout(function() {
    if ($(this).val().length < 1) {
        $(this).val("000" + $(this).val());
    } else if ($(this).val().length < 2) {
        $(this).val("00" + $(this).val());
    } else if ($(this).val().length < 3) {
        $(this).val("0" + $(this).val());
    }
});

$('[name="lama_kirim"]').focusout(function() {
    if ($(this).val().length < 1) {
        $(this).val("00" + $(this).val());
    } else if ($(this).val().length < 2) {
        $(this).val("0" + $(this).val());
    }
});
</script>
@endsection