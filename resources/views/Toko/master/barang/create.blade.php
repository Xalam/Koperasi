@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Tambah Barang</p>
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/barang/store', 'enctype' => 'multipart/form-data', 'method' => 'post'])
        !!}
        <div class="row-lg mb-2">
            <div class="col-lg-6">
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Kode Barang', ['class' => 'col-lg-4 col-form-label']) !!}
                    {!! Form::text('kode', null, ['class' => 'col-lg-7 form-control form-control-sm', 'required',
                    'readonly',
                    'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' =>
                    "this.setCustomValidity('')"]) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Nama Barang', ['class' => 'col-lg-4 col-form-label']) !!}
                    {!! Form::text('nama', null, ['class' =>'col-lg-7 form-control form-control-sm', 'required',
                    'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' =>
                    "this.setCustomValidity('')"]) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Stok Minimal', ['class' => 'col-lg-4 col-form-label']) !!}
                    {!! Form::number('stok_minimal', 0, ['class' => 'col-lg-2 form-control form-control-sm',
                    'required']) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Harga Jual', ['class' => 'col-lg-4 col-form-label']) !!}
                    {!! Form::number('harga_jual', 0, ['class' => 'col-lg-4 form-control form-control-sm', 'required',
                    'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' =>
                    "this.setCustomValidity('')"])
                    !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Foto', ['class' => 'col-lg-4 col-form-label']) !!}
                    {!! Form::file('foto', ['class' => 'col-lg-7 form-control form-control-sm', 'required', 'accept' =>
                    'application/pdf, image/png, image/jpg, image/jpeg', 'oninvalid' => "this.setCustomValidity('Tidak
                    boleh kosong')", 'oninput' => "this.setCustomValidity('')"]) !!}
                </div>
                <div class="row-lg align-item-center">
                    {!! Form::label(null, 'Type : pdf, png, jfif, pjpeg, jpg, pjp, jpeg', ['class' => 'offset-lg-4
                    col-lg']) !!}
                    <div class="vw-100"></div>
                    {!! Form::label(null, 'Size : < 2 MB', ['class'=> 'offset-lg-4 col-lg']) !!}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Jenis', ['class' => 'col-lg-2 col-form-label']) !!}
                    {!! Form::select('jenis', ['01' => 'Makanan Ringan', '02' => 'Minuman', '03' => 'Sabun', '04' =>
                    'Pewangi', '05' => 'Antiseptic', '06' => 'Perawatan Muka', '07' => 'Elektronik', '08' => 'Botol',
                    '09' => 'Bahan Pokok Makanan'], null, ['class' =>'col-lg-3 form-select
                    form-select-sm', 'required']) !!}
                    {!! Form::text('text_jenis', null, ['class' => 'd-none col-lg-7 form-control form-control-sm']) !!}
                    {!! Form::label(null, 'Satuan', ['class' => 'offset-lg-1 col-lg-2 col-form-label']) !!}
                    {!! Form::select('satuan', ['01' => 'Pack', '02' => 'Pcs', '03' => 'Kg', '04' => 'Liter'], null,
                    ['class' => 'col-lg-3 form-select form-select-sm', 'required']) !!}
                    {!! Form::text('text_satuan', null, ['class' => 'd-none col-lg-7 form-control form-control-sm']) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Kemasan', ['class' => 'col-lg-2 col-form-label']) !!}
                    {!! Form::select('kemasan', ['01' => 'Karung', '02' => 'Plastik', '03' => 'Botol', '04' => 'Kardus',
                    '05' => 'Kaleng'], null, ['class' => 'col-lg-3 form-select form-select-sm',
                    'required']) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Supplier', ['class' => 'col-lg-2 col-form-label']) !!}
                    {!! Form::select('supplier', $supplier, null, ['class' => 'col-lg-9 form-select form-select-sm',
                    'required']) !!}
                    {!! Form::text('text_supplier', null, ['class' => 'd-none col-lg-7 form-control form-control-sm'])
                    !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Exired', ['class' => 'col-lg-2 col-form-label']) !!}
                    {!! Form::select('bulan', ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                    '04' => 'April', '05' => 'Mei',
                    '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' =>
                    'Oktober',
                    '11' => 'November', '12' => 'Desember'], null, ['class' => 'col-lg-3 form-select
                    form-select-sm',
                    'required']) !!}
                    {!! Form::select('tahun', $tahun, null, ['class' => 'offset-lg-1 col-lg-2 form-select
                    form-select-sm',
                    'required']) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Rak', ['class' => 'col-lg-2 col-form-label']) !!}
                    {!! Form::label(null, 'Nomor', ['class' => 'col-lg-2 col-form-label']) !!}
                    {!! Form::select('nomor_rak', $rak, null, ['class' => 'col-lg-2 form-select form-select-sm',
                    'required']) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Tingkat', ['class' => 'offset-lg-2 col-lg-2 col-form-label']) !!}
                    {!! Form::select('tingkat_rak', $rak, null, ['class' => 'col-lg-2 form-select form-select-sm',
                    'required']) !!}
                </div>
                <div class="row-lg align-item-center">
                    {!! Form::label(null, 'Posisi', ['class' => 'offset-lg-2 col-lg-2 col-form-label']) !!}
                    {!! Form::select('posisi_rak', $rak, null, ['class' => 'col-lg-2 form-select form-select-sm',
                    'required']) !!}
                </div>
            </div>
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Grosir', ['class' => 'col-lg fw-bold col-form-label']) !!}
            <div class="w-100"></div>
            {!! Form::label(null, 'Minimal Pembelian', ['class' => 'col-lg-2 col-form-label']) !!}
            {!! Form::number('minimal_grosir', 0, ['class' => 'col-lg-1 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Harga Jual', ['class' => 'col-lg-2 col-form-label']) !!}
            {!! Form::number('harga_grosir', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required',
            'oninvalid' => "this.setCustomValidity('Tidak boleh kosong')", 'oninput' => "this.setCustomValidity('')"])
            !!}
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
        text: `{{Session::get('success')}}`
    });
    setTimeout(function() {
        window.location = "/toko/master/barang";
    }, 2000);
});
</script>
@endif
<script>
$(function() {
    setKode();

    $('input[type="file"]').change(function() {
        var size = $(this)[0].files[0].size;
        var extension = $(this).val().replace(/^.*\./, '');
        var pattern = ['pdf', 'png', 'jfif', 'pjpeg', 'jpg', 'pjp', 'jpeg'];

        if (size > 2048000 || $.inArray(extension, pattern) == -1) {
            $(this).val(null);
        }
    });

    $('input').keyup(function() {
        setKode();
    });

    $('select').change(function() {
        setKode();
    });

    function setKode() {
        var split = $("input[name='nama']").val().split(' ');

        var initial = "";

        for (var i = 0; i < split.length; i++) {
            if (split[i].length > 0) {
                initial += split[i][0];
            }
        }

        $("input[name='kode']").val(initial + $("[name='kemasan'] option:selected").val() + $(
                "[name='jenis'] option:selected").val() + $("[name='satuan'] option:selected").val() + $(
                "[name='supplier'] option:selected").val() + $("[name='bulan']").val() + $("[name='tahun']")
            .val() + $("[name='nomor_rak'] option:selected").val() + $("[name='tingkat_rak'] option:selected").val() + $("[name='posisi_rak'] option:selected").val());

        $('[name="text_satuan"]').val($("[name='satuan'] option:selected").text());
        $('[name="text_supplier"]').val($("[name='supplier'] option:selected").text());
    }
});
</script>
@endsection