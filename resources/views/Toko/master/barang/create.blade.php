@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Tambah Barang</p>
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/barang/store']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Kode Barang', ['class' => 'col-lg-2']) !!}
            {!! Form::text('kode', null, ['class' => 'col-lg-4 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Barang', ['class' => 'col-lg-2']) !!}
            {!! Form::text('nama', null, ['class' =>'col-lg-9 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Stok', ['class' => 'col-lg-2']) !!}
            {!! Form::number('stok', 0, ['class' => 'col-lg-1 form-control form-control-sm', 'required']) !!}
            {!! Form::label(null, 'Minimal', ['class' => 'offset-lg-1 col-lg-1']) !!}
            {!! Form::number('stok_minimal', 0, ['class' => 'col-lg-1 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg mb-2">
            <div class="col-sm-6">
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Satuan', ['class' => 'col-lg-4']) !!}
                    {!! Form::select('satuan', ['Batang' => 'Batang', 'Botol' => 'Botol'], null, ['class' => 'col-lg-4
                    form-select form-select-sm',
                    'required']) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'HPP', ['class' => 'col-lg-4']) !!}
                    {!! Form::number('hpp', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Harga Jual', ['class' => 'col-lg-4']) !!}
                    {!! Form::number('harga_jual', 0, ['class' => 'col-lg-4 form-control form-control-sm', 'required'])
                    !!}
                </div>
            </div>
            <div class="col-sm-5">
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Kalkulator Laba', ['class' => 'col-lg-12 text-center']) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::label(null, 'Margin', ['class' => 'col-lg-5 text-center']) !!}
                    {!! Form::label(null, 'Harga Jual Seharusnya', ['class' => 'offset-lg-1 col-lg-6 text-center']) !!}
                </div>
                <div class="row-lg align-item-center mb-2">
                    {!! Form::number('margin', 0, ['class' => 'col-lg-5 form-control form-control-sm', 'required']) !!}
                    {!! Form::label(null, '%', ['class' => 'col-lg-1 text-center']) !!}
                    {!! Form::number('hasil_margin', 0, ['class' => 'offset-lg-1 col-lg-5 form-control form-control-sm',
                    'required']) !!}
                </div>
            </div>
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Grosir', ['class' => 'col-lg fw-bold']) !!}
            <div class="w-100"></div>
            {!! Form::label(null, 'Harga Jual', ['class' => 'col-lg-2']) !!}
            {!! Form::number('harga_grosir', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Minimal Pembelian', ['class' => 'col-lg-2']) !!}
            {!! Form::number('minimal_grosir', 0, ['class' => 'col-lg-1 form-control form-control-sm', 'required'])
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
<script>
$(function() {
    $("input[name='margin']").change(function() {
        marginLaba();
    });
    
    $("input[name='hpp']").change(function() {
        marginLaba();
    });

    function marginLaba() {
        $("input[name='hasil_margin']").val(parseInt($("input[name='hpp']").val()) * (100 + parseInt($(
            "input[name='margin']").val())) / 100);
    }
});
</script>
@endsection