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
    <p class="card-header bg-light">Tambah Admin</p>
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/admin/store']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Kode Admin', ['class' => 'col-lg-2']) !!}
            {!! Form::text('kode', null, ['class' => 'col-lg-4 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Admin', ['class' => 'col-lg-2']) !!}
            {!! Form::text('nama', null, ['class' => 'col-lg-9 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Password', ['class' => 'col-lg-2']) !!}
            {!! Form::text('password', null, ['class' => 'col-lg-4 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Level', ['class' => 'col-lg-2']) !!}
            {!! Form::select('level', ['Admin' => 'Admin'], null, ['class' => 'col-lg-2 form-select form-select-sm',
            'required']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Simpan', ['class' => 'btn btn-sm btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="card m-6">
    <p class="card-header bg-light">Daftar Admin</p>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Admin</th>
                        <th class="col-2">Nama Admin</th>
                        <th>Password Admin</th>
                        <th>Level Admin</th>
                        <th class="w-20">Opsi</th>
                    </tr>
                </thead>
                @if (count($admin) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($admin as $data)
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
                        <td class="align-middle">
                            {!! Form::text('edit_password', $data->password, ['class' => 'd-none', 'id' =>
                            'edit-password-'.$data->id]) !!}
                            <p id="password-<?php echo $data->id ?>">{{$data->password}}</p>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::select('edit_level', ['Admin' => 'Admin'], null, ['class' => 'd-none', 'id' =>
                            'edit-level-'.$data->id]) !!}
                            <p id="level-<?php echo $data->id ?>">{{$data->level}}</p>
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
function edit(id) {
    $("#kode-" + id).addClass("d-none");
    $("#edit-kode-" + id).removeClass("d-none");
    $("#nama-" + id).addClass("d-none");
    $("#edit-nama-" + id).removeClass("d-none");
    $("#password-" + id).addClass("d-none");
    $("#edit-password-" + id).removeClass("d-none");
    $("#level-" + id).addClass("d-none");
    $("#edit-level-" + id).removeClass("d-none");
    $("#edit-" + id).addClass("d-none");
    $("#terapkan-" + id).removeClass("d-none");
}

function terapkan(id) {
    $.ajax({
        url: '/toko/master/admin/update/',
        type: 'POST',
        data: {
            id: id,
            kode: $('#edit-kode-' + id).val(),
            nama: $('#edit-nama-' + id).val(),
            password: $('#edit-password-' + id).val(),
            level: $('#edit-level-' + id).val()
        },
        success: function(response) {
            if (response.code == 200) {
                $("#kode-" + id).removeClass("d-none");
                $("#edit-kode-" + id).addClass("d-none");
                $("#nama-" + id).removeClass("d-none");
                $("#edit-nama-" + id).addClass("d-none");
                $("#password-" + id).removeClass("d-none");
                $("#edit-password-" + id).addClass("d-none");
                $("#level-" + id).removeClass("d-none");
                $("#edit-level-" + id).addClass("d-none");
                $("#edit-" + id).removeClass("d-none");
                $("#terapkan-" + id).addClass("d-none");

                $("#kode-" + id).html(response.admin.kode);
                $("#nama-" + id).html(response.admin.nama);
                $("#password-" + id).html(response.admin.password);
                $("#level-" + id).html(response.admin.alamat);
            }
        }
    });
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
    $('#popup-body').append('<div class="row-lg align-item-center mb-1">' +
        '<label for="">Apakah anda yakin ingin menghapus data ini?</label>' +
        '</div><div class="row-lg align-item-center mb-1">' +
        '<a class="btn btn-block btn-sm btn-success mt-1" onclick="hapus(' + id + ')">Hapus</a>' +
        '<a class="btn btn-block btn-sm btn-danger offset-1 mt-1" onclick="close_popup_hapus()">Batal</a>' +
        '</div>')
}

function close_popup_hapus() {
    $("#popup-delete").addClass("d-none");
}
</script>
@endsection