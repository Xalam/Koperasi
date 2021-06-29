@extends('toko.layout')

@section('popup')
<div id="popup-delete" class="popup-background d-none">
    <div class="popup center-object">
        <div id="popup-body" class="popup-body p-4">
        </div>
    </div>
</div>
@endsection

@section('main')
<a href="{{url('toko/master/barang/create')}}" class="btn btn-sm btn-success mt-4 ms-4 pe-4"><i class="fas fa-plus"></i><b>Tambah</b></a>
<div class="card m-6">
    <p class="card-header bg-light">Daftar Barang</p>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
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
                            <div>{{$i++}}</div>
                        </th>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_kode', $data->kode, ['class' => 'd-none', 'id' =>
                            'edit-kode-'.$data->id]) !!}
                            <div id="kode-<?php echo $data->id ?>">{{$data->kode}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_nama', $data->nama, ['class' => 'd-none', 'id' =>
                            'edit-nama-'.$data->id]) !!}
                            <div id="nama-<?php echo $data->id ?>">{{$data->nama}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_hpp', $data->hpp, ['class' => 'd-none', 'id' =>
                            'edit-hpp-'.$data->id]) !!}
                            <div id="hpp-<?php echo $data->id ?>">{{$data->hpp}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_harga_jual', $data->harga_jual, ['class' => 'd-none', 'id' =>
                            'edit-harga-jual-'.$data->id]) !!}
                            <div id="harga-jual-<?php echo $data->id ?>">{{$data->harga_jual}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_stok', $data->stok, ['class' => 'd-none', 'id' =>
                            'edit-stok-'.$data->id]) !!}
                            <div id="stok-<?php echo $data->id ?>">{{$data->stok}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_satuan', $data->satuan, ['class' => 'd-none', 'id' =>
                            'edit-satuan-'.$data->id]) !!}
                            <div id="satuan-<?php echo $data->id ?>">{{$data->satuan}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <a id=<?php echo "edit-" . $data->id ?> class="w-48 btn btn-sm btn-warning"
                                onclick="edit(<?php echo $data->id ?>)"><i class="fas fa-edit p-1"></i> Edit</a>
                            <a id=<?php echo "terapkan-" . $data->id ?> class="w-48 btn btn-sm btn-warning d-none"
                                onclick="terapkan(<?php echo $data->id ?>)">Terapkan</a>
                            <a id=<?php echo "hapus-" . $data->id ?> class="w-52 btn btn-sm btn-danger"
                                onclick="show_popup_hapus(<?php echo $data->id ?>)"><i class="fas fa-trash-alt p-1"></i> Hapus</a>
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
    $('#popup-body').append('<div class="row-lg align-item-center">' +
        '<label for="">Apakah anda yakin ingin menghapus data ini?</label>' +
        '</div><div class="row-lg align-item-center">' +
        '<a class="btn btn-block btn-sm btn-success mt-1" onclick="hapus(' + id + ')">Hapus</a>' +
        '<a class="btn btn-block btn-sm btn-danger mt-1" onclick="close_popup_hapus()">Batal</a>' +
        '</div>')
}

function close_popup_hapus() {
    $("#popup-delete").addClass("d-none");
}
</script>
@endsection