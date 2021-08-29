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
<!-- <a href="{{url('toko/master/anggota/create')}}" class="btn btn-sm btn-success mt-4 ms-4 pe-4"><i
        class="fas fa-plus"></i><b>Tambah</b></a> -->
<div class="card m-6">
    <p class="card-header bg-light">Daftar Anggota</p>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Anggota</th>
                        <th>Nama Anggota</th>
                        <th>Status</th>
                        <th>Alamat</th>
                        <th>Jabatan</th>
                        <th>Gaji</th>
                        <th>Limit Gaji</th>
                        <th>Nomor Telepon</th>
                        <th>Nomor WA</th>
                        <!-- <th>Opsi</th> -->
                    </tr>
                </thead>
                @if (count($anggota) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($anggota as $data)
                    <tr id="row-<?php echo $data->id ?>">
                        <th class="align-middle text-center">
                            <div>{{$i++}}</div>
                        </th>
                        <td class="align-middle text-center">
                            <div id="kd-anggota-<?php echo $data->id ?>">{{$data->kd_anggota}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_nama_anggota', $data->nama_anggota, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-nama-anggota-'.$data->id]) !!}
                            <div id="nama-anggota-<?php echo $data->id ?>">{{$data->nama_anggota}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_status', $data->status, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-status-'.$data->id]) !!}
                            <div id="status-<?php echo $data->id ?>">{{$data->status}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_alamat', $data->alamat, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-alamat-'.$data->id]) !!}
                            <div id="alamat-<?php echo $data->id ?>">{{$data->alamat}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="jabatan-<?php echo $data->id ?>">{{$data->jabatan}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_gaji', $data->gaji, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-gaji-'.$data->id]) !!}
                            <div id="gaji-<?php echo $data->id ?>">{{number_format($data->gaji, 2, ',', '.')}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="limit-gaji-<?php echo $data->id ?>">{{number_format($data->limit_gaji, 2, ',', '.')}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_no_hp', $data->no_hp, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-no-hp-'.$data->id]) !!}
                            <div id="no-hp-<?php echo $data->id ?>">{{$data->no_hp}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_no_wa', $data->no_wa, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-no-wa-'.$data->id]) !!}
                            <div id="no-wa-<?php echo $data->id ?>">{{$data->no_wa}}</div>
                        </td>
                        <!-- <td class="align-middle text-center">
                            <a id=<?php //echo "edit-" . $data->id ?> class="w-48 btn btn-sm btn-warning"
                                onclick="edit(<?php //echo $data->id ?>)"><i class="fas fa-edit p-1"></i> Edit</a>
                            <a id=<?php //echo "terapkan-" . $data->id ?> class="w-48 btn btn-sm btn-warning d-none"
                                onclick="terapkan(<?php //echo $data->id ?>)">Terapkan</a>
                            <a id=<?php //echo "hapus-" . $data->id ?> class="w-50 btn btn-sm btn-danger"
                                onclick="show_popup_hapus(<?php //echo $data->id ?>)"><i class="fas fa-trash-alt p-1"></i> Hapus</a>
                            <a id=<?php //echo "batal-" . $data->id ?> class="w-50 btn btn-sm btn-danger d-none"
                                onclick="batal(<?php //echo $data->id ?>)">Batal</a>
                        </td> -->
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
    $("#nama-anggota" + id).addClass("d-none");
    $("#edit-nama-anggota-" + id).removeClass("d-none");
    $("#status-" + id).addClass("d-none");
    $("#edit-status-" + id).removeClass("d-none");
    $("#alamat-" + id).addClass("d-none");
    $("#edit-alamat-" + id).removeClass("d-none");
    $("#gaji-" + id).addClass("d-none");
    $("#edit-gaji-" + id).removeClass("d-none");
    $("#no-hp-" + id).addClass("d-none");
    $("#edit-no-hp-" + id).removeClass("d-none");
    $("#no-wa-" + id).addClass("d-none");
    $("#edit-no-wa-" + id).removeClass("d-none");
    $("#edit-" + id).addClass("d-none");
    $("#hapus-" + id).addClass("d-none");
    $("#terapkan-" + id).removeClass("d-none");
    $("#batal-" + id).removeClass("d-none");
}

function batal(id) {
    $("#nama-anggota" + id).removeClass("d-none");
    $("#edit-nama-anggota-" + id).val($("#nama-anggota-" + id).text());
    $("#edit-nama-anggota-" + id).addClass("d-none");
    $("#status-" + id).removeClass("d-none");
    $("#edit-status-" + id).val($("#status-" + id).text());
    $("#edit-status-" + id).addClass("d-none");
    $("#alamat-" + id).removeClass("d-none");
    $("#edit-alamat-" + id).val($("#alamat-" + id).text());
    $("#edit-alamat-" + id).addClass("d-none");
    $("#gaji-" + id).removeClass("d-none");
    $("#edit-gaji-" + id).val($("#gaji-" + id).text());
    $("#edit-gaji-" + id).addClass("d-none");
    $("#no-hp-" + id).removeClass("d-none");
    $("#edit-no-hp-" + id).val($("#no-hp-" + id).text());
    $("#edit-no-hp-" + id).addClass("d-none");
    $("#no-wa-" + id).removeClass("d-none");
    $("#edit-no-wa-" + id).val($("#no-wa-" + id).text());
    $("#edit-no-wa-" + id).addClass("d-none");
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
            url: '/toko/master/anggota/update',
            type: 'POST',
            data: {
                id: id,
                nama_anggota: $('#edit-nama-anggota-' + id).val(),
                status: $('#edit-status-' + id).val(),
                alamat: $('#edit-alamat-' + id).val(),
                gaji: $('#edit-gaji-' + id).val(),
                limit_gaji: parseInt($('#edit-gaji-' + id).val())*2/3,
                no_hp: $('#edit-no-hp-' + id).val(),
                no_wa: $('#edit-no-wa-' + id).val()
            },
            success: function(response) {
                if (response.code == 200) {
                    $("#nama-anggota" + id).removeClass("d-none");
                    $("#edit-nama-anggota-" + id).addClass("d-none");
                    $("#status-" + id).removeClass("d-none");
                    $("#edit-status-" + id).addClass("d-none");
                    $("#alamat-" + id).removeClass("d-none");
                    $("#edit-alamat-" + id).addClass("d-none");
                    $("#gaji-" + id).removeClass("d-none");
                    $("#edit-gaji-" + id).addClass("d-none");
                    $("#no-hp-" + id).removeClass("d-none");
                    $("#edit-no-hp-" + id).addClass("d-none");
                    $("#no-wa-" + id).removeClass("d-none");
                    $("#edit-no-wa-" + id).addClass("d-none");
                    $("#edit-" + id).removeClass("d-none");
                    $("#hapus-" + id).removeClass("d-none");
                    $("#terapkan-" + id).addClass("d-none");
                    $("#batal-" + id).addClass("d-none");

                    $("#nama-anggota-" + id).html(response.supplier.nama_anggota);
                    $("#status-" + id).html(response.supplier.status);
                    $("#alamat-" + id).html(response.supplier.alamat);
                    $("#gaji-" + id).html(response.supplier.gaji.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00');
                    $("#limit-gaji-" + id).html(response.supplier.limit_gaji.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00');
                    $("#no-hp-" + id).html(response.supplier.no_hp);
                    $("#no-wa-" + id).html(response.supplier.no_wa);
                }
            }
        });
    }
}

function hapus(id) {
    $.ajax({
        url: '/toko/master/anggota/delete',
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