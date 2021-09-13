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
<a href="{{url('toko/master/admin/create')}}" class="btn btn-sm btn-success mt-4 ms-4 pe-4"><i
        class="fas fa-plus"></i><b>Tambah</b></a>
<div class="card m-6">
    <p class="card-header bg-light">Daftar Admin</p>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Admin</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Jabatan</th>
                        <th class="w-20">Opsi</th>
                    </tr>
                </thead>
                @if (count($admin) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($admin as $data)
                    <tr id="row-<?php echo $data->id ?>">
                        <th class="align-middle text-center">
                            <div>{{$i++}}</div>
                        </th>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_kode', $data->kode, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-kode-'.$data->id]) !!}
                            <div id="kode-<?php echo $data->id ?>">{{$data->kode}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_nama', $data->nama, ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-nama-'.$data->id]) !!}
                            <div id="nama-<?php echo $data->id ?>">{{$data->nama}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::password('edit_password', ['class' => 'd-none form-control form-control-sm', 'id' =>
                            'edit-password-'.$data->id, 'value' => $data->password]) !!}
                            <div id="password-<?php echo $data->id ?>" style="-webkit-text-security: circle;">{{$data->password}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::select('edit_jabatan', ['Super_Admin' => 'Super Admin', 'Kanit' => 'Kanit', 'Ketua_Koperasi' => 'Ketua Koperasi',
                            'Gudang' => 'Gudang', 'Kasir' => 'Kasir'], $data->jabatan, ['class' => 'd-none form-select form-select-sm', 'id' =>
                            'edit-jabatan-'.$data->id]) !!}
                            <div id="jabatan-<?php echo $data->id ?>">{{(($data->jabatan == 'Super_Admin') ? 'Super Admin' : (($data->jabatan == 'Ketua_Koperasi') ? 'Ketua Koperasi' : $data->jabatan))}}</div>
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
    $("#password-" + id).addClass("d-none");
    $("#edit-password-" + id).removeClass("d-none");
    $("#jabatan-" + id).addClass("d-none");
    $("#edit-jabatan-" + id).removeClass("d-none");
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
    $("#password-" + id).removeClass("d-none");
    $("#edit-password-" + id).val($("#password-" + id).text());
    $("#edit-password-" + id).addClass("d-none");
    $("#jabatan-" + id).removeClass("d-none");
    $("#edit-jabatan-" + id).val($("#jabatan-" + id).text());
    $("#edit-jabatan-" + id).addClass("d-none");
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
            url: '/toko/master/admin/update',
            type: 'POST',
            data: {
                id: id,
                kode: $('#edit-kode-' + id).val(),
                nama: $('#edit-nama-' + id).val(),
                password: $('#edit-password-' + id).val(),
                jabatan: $('#edit-jabatan-' + id).val()
            },
            success: function(response) {
                if (response.code == 200) {
                    $("#kode-" + id).removeClass("d-none");
                    $("#edit-kode-" + id).addClass("d-none");
                    $("#nama-" + id).removeClass("d-none");
                    $("#edit-nama-" + id).addClass("d-none");
                    $("#password-" + id).removeClass("d-none");
                    $("#edit-password-" + id).addClass("d-none");
                    $("#jabatan-" + id).removeClass("d-none");
                    $("#edit-jabatan-" + id).addClass("d-none");
                    $("#edit-" + id).removeClass("d-none");
                    $("#hapus-" + id).removeClass("d-none");
                    $("#terapkan-" + id).addClass("d-none");
                    $("#batal-" + id).addClass("d-none");

                    $("#kode-" + id).html(response.admin.kode);
                    $("#nama-" + id).html(response.admin.nama);
                    $("#password-" + id).html(response.admin.password);
                    $("#jabatan-" + id).html(response.admin.alamat);

                    window.location.reload();
                }
            }
        });
    }
}

function hapus(id) {
    $.ajax({
        url: '/toko/master/admin/delete',
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