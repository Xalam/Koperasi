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
@if (auth()->user()->jabatan != 'Ketua_Koperasi')
<a href="{{url('toko/master/supplier/create')}}" class="btn btn-sm btn-success mt-4 ms-4 pe-4"><i
        class="fas fa-plus"></i><b>Tambah</b></a>
@endif
<div class="card m-6">
    <p class="card-header bg-light">Daftar Supplier</p>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Supplier</th>
                        <th>Nama Supplier</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>WA</th>
                        <th>Jarak (km)</th>
                        <th>Lama Pengiriman (hari)</th>
                        <th>Email</th>
                        <th>Keterangan</th>
                        <th>Tempo</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                @if (count($supplier) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($supplier as $data)
                    <tr id="row-<?php echo $data->id ?>">
                        <th class="align-middle text-center">
                            <div>{{$i++}}</div>
                        </th>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_kode', $data->kode, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-kode-'.$data->id, 'required']) !!}
                            <div id="kode-<?php echo $data->id ?>">{{$data->kode}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_nama', $data->nama, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-nama-'.$data->id, 'required']) !!}
                            <div id="nama-<?php echo $data->id ?>">{{$data->nama}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_alamat', $data->alamat, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-alamat-'.$data->id, 'required']) !!}
                            <div id="alamat-<?php echo $data->id ?>">{{$data->alamat}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_telepon', $data->telepon, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-telepon-'.$data->id, 'required']) !!}
                            <div id="telepon-<?php echo $data->id ?>">{{$data->telepon}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_wa', $data->wa, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-wa-'.$data->id, 'required']) !!}
                            <div id="wa-<?php echo $data->id ?>">{{$data->wa}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_jarak', $data->jarak, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-jarak-'.$data->id, 'required']) !!}
                            <div id="jarak-<?php echo $data->id ?>">{{$data->jarak}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_lama_kirim', $data->lama_kirim, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-lama-kirim-'.$data->id, 'required']) !!}
                            <div id="lama-kirim-<?php echo $data->id ?>">{{$data->lama_kirim}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_email', $data->email, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-email-'.$data->id, 'required']) !!}
                            <div id="email-<?php echo $data->id ?>">{{$data->email}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_keterangan', $data->keterangan, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-keterangan-'.$data->id, 'required']) !!}
                            <div id="keterangan-<?php echo $data->id ?>">{{$data->keterangan}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_tempo', $data->tempo, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-tempo-'.$data->id, 'required']) !!}
                            <div id="tempo-<?php echo $data->id ?>">{{$data->tempo}}</div>
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
    $("#alamat-" + id).addClass("d-none");
    $("#edit-alamat-" + id).removeClass("d-none");
    $("#telepon-" + id).addClass("d-none");
    $("#edit-telepon-" + id).removeClass("d-none");
    $("#wa-" + id).addClass("d-none");
    $("#edit-wa-" + id).removeClass("d-none");
    $("#jarak-" + id).addClass("d-none");
    $("#edit-jarak-" + id).removeClass("d-none");
    $("#lama-kirim-" + id).addClass("d-none");
    $("#edit-lama-kirim-" + id).removeClass("d-none");
    $("#email-" + id).addClass("d-none");
    $("#edit-email-" + id).removeClass("d-none");
    $("#keterangan-" + id).addClass("d-none");
    $("#edit-keterangan-" + id).removeClass("d-none");
    $("#tempo-" + id).addClass("d-none");
    $("#edit-tempo-" + id).removeClass("d-none");
    $("#edit-" + id).addClass("d-none");
    $("#hapus-" + id).addClass("d-none");
    $("#terapkan-" + id).removeClass("d-none");
    $("#batal-" + id).removeClass("d-none");
}

function batal(id) {
    $("#kode-" + id).removeClass("d-none");
    $("#edit-kode-" + id).val($("#kode-" + id).text());
    $("#edit-kode-" + id).addClass("d-none");
    $("#nama-" + id).removeClass("d-none");
    $("#edit-nama-" + id).val($("#nama-" + id).text());
    $("#edit-nama-" + id).addClass("d-none");
    $("#alamat-" + id).removeClass("d-none");
    $("#edit-alamat-" + id).val($("#alamat-" + id).text());
    $("#edit-alamat-" + id).addClass("d-none");
    $("#telepon-" + id).removeClass("d-none");
    $("#edit-telepon-" + id).val($("#telepon-" + id).text());
    $("#edit-telepon-" + id).addClass("d-none");
    $("#wa-" + id).removeClass("d-none");
    $("#edit-wa-" + id).val($("#wa-" + id).text());
    $("#edit-wa-" + id).addClass("d-none");
    $("#jarak-" + id).removeClass("d-none");
    $("#edit-jarak-" + id).val($("#jarak-" + id).text());
    $("#edit-jarak-" + id).addClass("d-none");
    $("#lama-kirim-" + id).removeClass("d-none");
    $("#edit-lama-kirim-" + id).val($("#lama-kirim-" + id).text());
    $("#edit-lama-kirim-" + id).addClass("d-none");
    $("#email-" + id).removeClass("d-none");
    $("#edit-email-" + id).val($("#email-" + id).text());
    $("#edit-email-" + id).addClass("d-none");
    $("#keterangan-" + id).removeClass("d-none");
    $("#edit-keterangan-" + id).val($("#keterangan-" + id).text());
    $("#edit-keterangan-" + id).addClass("d-none");
    $("#tempo-" + id).removeClass("d-none");
    $("#edit-tempo-" + id).val($("#tempo-" + id).text());
    $("#edit-tempo-" + id).addClass("d-none");
    $("#edit-" + id).removeClass("d-none");
    $("#hapus-" + id).removeClass("d-none");
    $("#terapkan-" + id).addClass("d-none");
    $("#batal-" + id).addClass("d-none");
}

function terapkan(id) {
    var allFilled = false;
    var index = 0;

    $('#row-' + id).find('input').each(function() {
        if(!$(this).val()){
            $(this).popover({content: "Tidak boleh kosong", placement: "top", trigger: "focus"}).popover('show');
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
            url: '/toko/master/supplier/update',
            type: 'POST',
            data: {
                id: id,
                kode: $('#edit-kode-' + id).val(),
                nama: $('#edit-nama-' + id).val(),
                alamat: $('#edit-alamat-' + id).val(),
                telepon: $('#edit-telepon-' + id).val(),
                wa: $('#edit-wa-' + id).val(),
                jarak: $('#edit-jarak-' + id).val(),
                lama_kirim: $('#edit-lama-kirim-' + id).val(),
                email: $('#edit-email-' + id).val(),
                keterangan: $('#edit-keterangan-' + id).val(),
                tempo: $('#edit-tempo-' + id).val()
            },
            success: function(response) {
                if (response.code == 200) {
                    $("#kode-" + id).removeClass("d-none");
                    $("#edit-kode-" + id).addClass("d-none");
                    $("#nama-" + id).removeClass("d-none");
                    $("#edit-nama-" + id).addClass("d-none");
                    $("#alamat-" + id).removeClass("d-none");
                    $("#edit-alamat-" + id).addClass("d-none");
                    $("#telepon-" + id).removeClass("d-none");
                    $("#edit-telepon-" + id).addClass("d-none");
                    $("#wa-" + id).removeClass("d-none");
                    $("#edit-wa-" + id).addClass("d-none");
                    $("#jarak-" + id).removeClass("d-none");
                    $("#edit-jarak-" + id).addClass("d-none");
                    $("#lama-kirim-" + id).removeClass("d-none");
                    $("#edit-lama-kirim-" + id).addClass("d-none");
                    $("#email-" + id).removeClass("d-none");
                    $("#edit-email-" + id).addClass("d-none");
                    $("#keterangan-" + id).removeClass("d-none");
                    $("#edit-keterangan-" + id).addClass("d-none");
                    $("#tempo-" + id).removeClass("d-none");
                    $("#edit-tempo-" + id).addClass("d-none");
                    $("#edit-" + id).removeClass("d-none");
                    $("#hapus-" + id).removeClass("d-none");
                    $("#terapkan-" + id).addClass("d-none");
                    $("#batal-" + id).addClass("d-none");

                    $("#kode-" + id).html(response.supplier.kode);
                    $("#nama-" + id).html(response.supplier.nama);
                    $("#alamat-" + id).html(response.supplier.alamat);
                    $("#telepon-" + id).html(response.supplier.telepon);
                    $("#wa-" + id).html(response.supplier.wa);
                    $("#jarak-" + id).html(response.supplier.jarak);
                    $("#lama-kirim-" + id).html(response.supplier.lama_kirim);
                    $("#email-" + id).html(response.supplier.email);
                    $("#keterangan-" + id).html(response.supplier.keterangan);
                    $("#tempo-" + id).html(response.supplier.tempo);
                }
            }
        });
    }
}

function hapus(id) {
    $.ajax({
        url: '/toko/master/supplier/delete',
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