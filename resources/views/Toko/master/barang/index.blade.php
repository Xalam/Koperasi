@extends('toko.layout')

@section('popup')
<div id="popup-delete" class="popup-background d-none">
    <div class="popup center-object">
        <div id="popup-body" class="popup-body">
        </div>
    </div>
</div>
@endsection

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
            {!! Form::number('stok', null, ['class' => 'col-lg-1 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg">
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
        <div class="d-grid gap-2">
            {!! Form::submit('Simpan', ['class' => 'btn btn-sm btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="card m-6">
    <p class="card-header bg-light">Daftar Barang</p>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th class="col-2">Nama Barang</th>
                        <th>HPP</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th class="w-20">Opsi</th>
                    </tr>
                </thead>
                @if (count($barang) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($barang as $data)
                    <tr>
                        <th class="align-middle text-center">
                            <p>{{$i++}}</p>
                        </th>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_kode', $data->kode, ['class' => 'd-none', 'id' =>
                            'edit-kode-'.$data->id]) !!}
                            <p id="kode-<?php echo $data->id ?>">{{$data->kode}}</p>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_nama', $data->nama, ['class' => 'd-none', 'id' =>
                            'edit-nama-'.$data->id]) !!}
                            <p id="nama-<?php echo $data->id ?>">{{$data->nama}}</p>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_hpp', $data->hpp, ['class' => 'd-none', 'id' =>
                            'edit-hpp-'.$data->id]) !!}
                            <p id="hpp-<?php echo $data->id ?>">{{$data->hpp}}</p>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_harga_jual', $data->harga_jual, ['class' => 'd-none', 'id' =>
                            'edit-harga-jual-'.$data->id]) !!}
                            <p id="harga-jual-<?php echo $data->id ?>">{{$data->harga_jual}}</p>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_stok', $data->stok, ['class' => 'd-none', 'id' =>
                            'edit-stok-'.$data->id]) !!}
                            <p id="stok-<?php echo $data->id ?>">{{$data->stok}}</p>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_satuan', $data->satuan, ['class' => 'd-none', 'id' =>
                            'edit-satuan-'.$data->id]) !!}
                            <p id="satuan-<?php echo $data->id ?>">{{$data->satuan}}</p>
                        </td>
                        <td class="align-middle text-center">
                            <a id=<?php echo "edit-" . $data->id ?> class="w-48 btn btn-sm btn-warning"
                                onclick="edit(<?php echo $data->id ?>)">Edit</a>
                            <a id=<?php echo "terapkan-" . $data->id ?> class="w-48 btn btn-sm btn-warning d-none"
                                onclick="terapkan(<?php echo $data->id ?>)">Terapkan</a>
                            <a id=<?php echo "hapus-" . $data->id ?> class="w-48 btn btn-sm btn-danger"
                                onclick="show_popup_hapus(<?php echo $data->id ?>)">Hapus</a>
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

@section('script')
<script>
$(function() {
    $("input[name='margin']").change(function() {
        $("input[name='hasil_margin']").val(parseInt($("input[name='hpp']").val()) * (100 + parseInt($(
            "input[name='margin']").val())) / 100);
    });
});

function edit(id) {
    $("#kode-" + id).addClass("d-none");
    $("#edit-kode-" + id).removeClass("d-none");
    $("#nama-" + id).addClass("d-none");
    $("#edit-nama-" + id).removeClass("d-none");
    $("#hpp-" + id).addClass("d-none");
    $("#edit-hpp-" + id).removeClass("d-none");
    $("#harga-jual-" + id).addClass("d-none");
    $("#edit-harga-jual-" + id).removeClass("d-none");
    $("#stok-" + id).addClass("d-none");
    $("#edit-stok-" + id).removeClass("d-none");
    $("#satuan-" + id).addClass("d-none");
    $("#edit-satuan-" + id).removeClass("d-none");
    $("#edit-" + id).addClass("d-none");
    $("#terapkan-" + id).removeClass("d-none");
}

function terapkan(id) {
    $.ajax({
        url: '/toko/master/barang/update/',
        type: 'POST',
        data: {
            id: id,
            kode: $('#edit-kode-' + id).val(),
            nama: $('#edit-nama-' + id).val(),
            hpp: $('#edit-hpp-' + id).val(),
            harga_jual: $('#edit-harga-jual-' + id).val(),
            stok: $('#edit-stok-' + id).val(),
            satuan: $('#edit-satuan-' + id).val()
        },
        success: function(response) {
            if (response.code == 200) {
                $("#kode-" + id).removeClass("d-none");
                $("#edit-kode-" + id).addClass("d-none");
                $("#nama-" + id).removeClass("d-none");
                $("#edit-nama-" + id).addClass("d-none");
                $("#hpp-" + id).removeClass("d-none");
                $("#edit-hpp-" + id).addClass("d-none");
                $("#harga-jual-" + id).removeClass("d-none");
                $("#edit-harga-jual-" + id).addClass("d-none");
                $("#stok-" + id).removeClass("d-none");
                $("#edit-stok-" + id).addClass("d-none");
                $("#satuan-" + id).removeClass("d-none");
                $("#edit-satuan-" + id).addClass("d-none");
                $("#edit-" + id).removeClass("d-none");
                $("#terapkan-" + id).addClass("d-none");

                $("#kode-" + id).html(response.barang.kode);
                $("#nama-" + id).html(response.barang.nama);
                $("#hpp-" + id).html(response.barang.hpp);
                $("#harga-jual-" + id).html(response.barang.harga_jual);
                $("#stok-" + id).html(response.barang.stok);
                $("#satuan-" + id).html(response.barang.satuan);
            }
        }
    });
}

function hapus(id) {
    $.ajax({
        url: '/toko/master/barang/delete',
        type: 'POST',
        data: {
            id: id
        },
        success: function(response) {
            if (response.code == 200) {
                location.reload();
            }
        }
    });
}

function show_popup_hapus(id) {
    $("#popup-delete").removeClass("d-none");

    $('#popup-body').empty();
    $('#popup-body').append('<div class="row-lg align-item-center mb-2">' +
        '<label for="">Apakah anda yakin ingin menghapus data ini?</label>' +
        '</div><div class="row-lg align-item-center mb-2">' +
        '<a class="btn btn-block btn-sm btn-success mt-1" onclick="hapus(' + id + ')">Hapus</a>' +
        '<a class="btn btn-block btn-sm btn-danger offset-1 mt-1" onclick="close_popup_hapus()">Batal</a>' +
        '</div>')
}

function close_popup_hapus() {
    $("#popup-delete").addClass("d-none");
}
</script>
@endsection