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
@if (auth()->user()->jabatan != 'Kasir' && auth()->user()->jabatan != 'Ketua_Koperasi')
<a href="{{url('toko/master/barang/create')}}" class="btn btn-sm btn-success mt-4 ms-4 pe-4"><i
        class="fas fa-plus"></i><b>Tambah</b></a>
@endif
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
                        <th>Harga Jual</th>
                        <th>Harga Grosir</th>
                        <th>Minimal Grosir</th>
                        <!-- <th>Nomor Rak</th>
                        <th>Tingkat Rak</th>
                        <th>Posisi Rak</th> -->
                        <th>Kode Supplier</th>
                        <th>Nama Supplier</th>
                        <th class="w-20">Opsi</th>
                        <th>Barcode</th>
                    </tr>
                </thead>
                @if (count($barang) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($barang as $data)
                    <tr id="row-<?php echo $data->id ?>">
                        <th class="align-middle text-center">
                            <div>{{$i++}}</div>
                        </th>
                        <td class="align-middle text-center">
                            <div id="kode-<?php echo $data->id ?>">{{$data->kode}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_nama', $data->nama, ['class' => 'd-none form-control form-control-sm',
                            'id' =>
                            'edit-nama-'.$data->id]) !!}
                            <div id="nama-<?php echo $data->id ?>">{{$data->nama}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_harga_jual', $data->harga_jual, ['class' => 'd-none form-control
                            form-control-sm', 'id' =>
                            'edit-harga-jual-'.$data->id]) !!}
                            <div id="harga-jual-<?php echo $data->id ?>">{{number_format($data->harga_jual, 2, ',', '.')}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_harga_grosir', $data->harga_grosir, ['class' => 'd-none form-control
                            form-control-sm', 'id' =>
                            'edit-harga-grosir-'.$data->id]) !!}
                            <div id="harga-grosir-<?php echo $data->id ?>">{{number_format($data->harga_grosir, 2, ',', '.')}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::number('edit_minimal_grosir', $data->minimal_grosir, ['class' => 'd-none
                            form-control form-control-sm', 'id' =>
                            'edit-minimal-grosir-'.$data->id]) !!}
                            <div id="minimal-grosir-<?php echo $data->id ?>">{{$data->minimal_grosir}}</div>
                        </td>
                        <!-- <td class="align-middle">
                            {!! Form::text('edit_nomor_rak', $data->nomor_rak, ['class' => 'd-none form-control
                            form-control-sm', 'id' =>
                            'edit-nomor-rak-'.$data->id]) !!}
                            <div id="nomor-rak-<?php //echo $data->id ?>">{{$data->nomor_rak}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_tingkat_rak', $data->tingkat_rak, ['class' => 'd-none form-control
                            form-control-sm', 'id' =>
                            'edit-tingkat-rak-'.$data->id]) !!}
                            <div id="tingkat-rak-<?php //echo $data->id ?>">{{$data->tingkat_rak}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_posisi_rak', $data->posisi_rak, ['class' => 'd-none form-control
                            form-control-sm', 'id' =>
                            'edit-posisi-rak-'.$data->id]) !!}
                            <div id="posisi-rak-<?php //echo $data->id ?>">{{$data->posisi_rak}}</div>
                        </td> -->
                        <td class="align-middle text-center">
                            <div id="kode-supplier-<?php echo $data->id ?>">{{$data->kode_supplier}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="nama-supplier-<?php echo $data->id ?>">{{$data->nama_supplier}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <a id=<?php echo "edit-" . $data->id ?> class="w-48 btn btn-sm btn-warning"
                                onclick="edit(<?php echo $data->id ?>)"><i class="fas fa-edit p-1"></i> Edit</a>
                            <a id=<?php echo "terapkan-" . $data->id ?> class="w-48 btn btn-sm btn-warning d-none"
                                onclick="terapkan(<?php echo $data->id ?>)">Terapkan</a>
                            <a id=<?php echo "hapus-" . $data->id ?> class="w-50 btn btn-sm btn-danger"
                                onclick="show_popup_hapus(<?php echo $data->id ?>)"><i class="fas fa-trash-alt p-1"></i>
                                Hapus</a>
                            <a id=<?php echo "batal-" . $data->id ?> class="w-50 btn btn-sm btn-danger d-none"
                                onclick="batal(<?php echo $data->id ?>)">Batal</a>
                        </td>
                        <td class="align-middle text-center"><a
                                href="<?php echo url('/toko/master/barang/barcode/' . $data->kode); ?>"
                                target="_blank"><i class="text-success fas fa-print" style="cursor: pointer;"
                                    title="Print"></i></a></td>
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
    $("#nama-" + id).addClass("d-none");
    $("#edit-nama-" + id).removeClass("d-none");
    $("#harga-jual-" + id).addClass("d-none");
    $("#edit-harga-jual-" + id).removeClass("d-none");
    $("#harga-grosir-" + id).addClass("d-none");
    $("#edit-harga-grosir-" + id).removeClass("d-none");
    $("#minimal-grosir-" + id).addClass("d-none");
    $("#edit-minimal-grosir-" + id).removeClass("d-none");
    // $("#nomor-rak-" + id).addClass("d-none");
    // $("#edit-nomor-rak-" + id).removeClass("d-none");
    // $("#tingkat-rak-" + id).addClass("d-none");
    // $("#edit-tingkat-rak-" + id).removeClass("d-none");
    // $("#posisi-rak-" + id).addClass("d-none");
    // $("#edit-posisi-rak-" + id).removeClass("d-none");
    $("#edit-" + id).addClass("d-none");
    $("#hapus-" + id).addClass("d-none");
    $("#terapkan-" + id).removeClass("d-none");
    $("#batal-" + id).removeClass("d-none");
}

function batal(id) {
    $("#nama-" + id).removeClass("d-none");
    $("#edit-nama-" + id).val($("#nama-" + id).text());
    $("#edit-nama-" + id).addClass("d-none");
    $("#harga-jual-" + id).removeClass("d-none");
    $("#edit-harga-jual-" + id).val($("#harga-jual-" + id).text());
    $("#edit-harga-jual-" + id).addClass("d-none");
    $("#harga-grosir-" + id).removeClass("d-none");
    $("#edit-harga-grosir-" + id).val($("#harga-grosir-" + id).text());
    $("#edit-harga-grosir-" + id).addClass("d-none");
    $("#minimal-grosir-" + id).removeClass("d-none");
    $("#edit-minimal-grosir-" + id).val($("#minimal-grosir-" + id).text());
    $("#edit-minimal-grosir-" + id).addClass("d-none");
    // $("#nomor-rak-" + id).removeClass("d-none");
    // $("#edit-nomor-rak-" + id).val($("#nomor-rak-" + id).text());
    // $("#edit-nomor-rak-" + id).addClass("d-none");
    // $("#tingkat-rak-" + id).removeClass("d-none");
    // $("#edit-tingkat-rak-" + id).val($("#tingkat-rak-" + id).text());
    // $("#edit-tingkat-rak-" + id).addClass("d-none");
    // $("#posisi-rak-" + id).removeClass("d-none");
    // $("#edit-posisi-rak-" + id).val($("#posisi-rak-" + id).text());
    // $("#edit-posisi-rak-" + id).addClass("d-none");
    $("#edit-" + id).removeClass("d-none");
    $("#hapus-" + id).removeClass("d-none");
    $("#terapkan-" + id).addClass("d-none");
    $("#batal-" + id).addClass("d-none");
}

function terapkan(id) {
    var allFilled = false;
    var index = 0;

    $('#row-' + id).find('input').each(function() {
        if (!$(this).val()) {
            $(this).popover({
                content: "Tidak boleh kosong",
                placement: "top",
                trigger: "focus"
            }).popover('show');
            allFilled = false;
        } else {
            if (index == 0) {
                allFilled = true;
            } else {
                if (allFilled == true) {
                    allFilled = true;
                } else {
                    return false;
                }
            }
        }

        index++;
    });

    if (allFilled) {
        $.ajax({
            url: '/toko/master/barang/update',
            type: 'POST',
            data: {
                id: id,
                nama: $('#edit-nama-' + id).val(),
                harga_jual: $('#edit-harga-jual-' + id).val(),
                harga_grosir: $('#edit-harga-grosir-' + id).val(),
                minimal_grosir: $('#edit-minimal-grosir-' + id).val()
                // nomor_rak: $('#edit-nomor-rak-' + id).val(),
                // tingkat_rak: $('#edit-tingkat-rak-' + id).val(),
                // posisi_rak: $('#edit-posisi-rak-' + id).val()
            },
            success: function(response) {
                if (response.code == 200) {
                    $("#nama-" + id).removeClass("d-none");
                    $("#edit-nama-" + id).addClass("d-none");
                    $("#harga-jual-" + id).removeClass("d-none");
                    $("#edit-harga-jual-" + id).addClass("d-none");
                    $("#harga-grosir-" + id).removeClass("d-none");
                    $("#edit-harga-grosir-" + id).addClass("d-none");
                    $("#minimal-grosir-" + id).removeClass("d-none");
                    $("#edit-minimal-grosir-" + id).addClass("d-none");
                    // $("#nomor-rak-" + id).removeClass("d-none");
                    // $("#edit-nomor-rak-" + id).addClass("d-none");
                    // $("#tingkat-rak-" + id).removeClass("d-none");
                    // $("#edit-tingkat-rak-" + id).addClass("d-none");
                    // $("#posisi-rak-" + id).removeClass("d-none");
                    // $("#edit-posisi-rak-" + id).addClass("d-none");
                    $("#edit-" + id).removeClass("d-none");
                    $("#hapus-" + id).removeClass("d-none");
                    $("#terapkan-" + id).addClass("d-none");
                    $("#batal-" + id).addClass("d-none");

                    $("#nama-" + id).html(response.barang.nama);
                    $("#harga-jual-" + id).html(response.barang.harga_jual.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00');
                    $("#harga-grosir-" + id).html(response.barang.harga_grosir.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00');
                    $("#minimal-grosir-" + id).html(response.barang.minimal_grosir);
                    // $("#nomor-rak-" + id).html(response.barang.nomor_rak);
                    // $("#tingkat-rak-" + id).html(response.barang.tingkat_rak);
                    // $("#posisi-rak-" + id).html(response.barang.posisi_rak);
                }
            }
        });
    }
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