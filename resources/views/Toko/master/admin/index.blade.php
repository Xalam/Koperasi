@extends('toko.layout')

@section('popup')
<div class="popup-background hide">
    <div class="popup center-object">
        <div class="popup-body">
            <div class="row align-item-center mb-1">
                {!! Form::label('null', 'Apakah anda yakin ingin menghapus data ini?', null) !!}
            </div>
            <div class="row align-item-center mb-1">
                {!! Form::submit('Simpan', ['class' => 'btn btn-block btn-success btn-small me-1']) !!}
                <a class="btn btn-block btn-small btn-danger ms-1">Batal</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('main')
<div class="card m-6">
    <div class="card-body">
        {!! Form::open(['url' => '/toko/master/admin/store']) !!}
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Tambah Admin', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Kode Admin', ['class' => 'col-5']) !!}
            {!! Form::text('kode', null, ['class' => 'col-8', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Nama Admin', ['class' => 'col-5']) !!}
            {!! Form::text('nama', null, ['class' =>'col-14', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Password', ['class' => 'col-5']) !!}
            {!! Form::text('password', null, ['class' => 'col-8', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Level', ['class' => 'col-5']) !!}
            {!! Form::select('level', ['Admin' => 'Admin'], null, ['class' => 'col-4', 'required']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::submit('Simpan', ['class' => 'btn btn-primary btn-small']) !!}
        </div>
        {!! Form::close() !!}
        <hr>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Daftar Admin', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="text-center text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Kode Admin</th>
                        <th class="col-2">Nama Admin</th>
                        <th>Password Admin</th>
                        <th>Level Admin</th>
                        <th colspan="2">Opsi</th>
                    </tr>
                </thead>
                @if (count($admin) > 0)
                <tbody class="text-wrap">
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($admin as $data)
                    {!! Form::open(['url' => '/toko/master/admin/update']) !!}
                    <tr>
                        <th class="align-middle text-center">
                            <p>{{$i++}}</p>
                        </th>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_kode', $data->kode, ['class' => 'hide', 'id' =>
                            'edit-kode-'.$data->id]) !!}
                            <p id="kode-<?php echo $data->id ?>">{{$data->kode}}</p>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_nama', $data->nama, ['class' => 'hide', 'id' =>
                            'edit-nama-'.$data->id]) !!}
                            <p id="nama-<?php echo $data->id ?>">{{$data->nama}}</p>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_password', $data->password, ['class' => 'hide', 'id' =>
                            'edit-password-'.$data->id]) !!}
                            <p id="password-<?php echo $data->id ?>">{{$data->password}}</p>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::select('edit_level', ['Admin' => 'Admin'], null, ['class' => 'hide', 'id' =>
                            'edit-level-'.$data->id]) !!}
                            <p id="level-<?php echo $data->id ?>">{{$data->level}}</p>
                        </td>
                        <td class="align-middle text-center">
                            <a id=<?php echo "edit-" . $data->id ?> class="btn btn-small btn-warning"
                                onclick="edit(<?php echo $data->id ?>)">Edit</a>
                            {!! Form::submit('Terapkan', ['class' => 'btn btn-small btn-warning hide', 'id' =>
                            'terapkan-'.$data->id]) !!}
                        </td>
                        <td class="align-middle text-center">
                            <a id=<?php echo "hapus-" . $data->id ?> class="btn btn-small btn-danger">Hapus</a>
                        </td>
                    </tr>
                    {!! Form::close() !!}
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
    $("#kode-" + id).addClass("hide");
    $("#edit-kode-" + id).removeClass("hide");
    $("#nama-" + id).addClass("hide");
    $("#edit-nama-" + id).removeClass("hide");
    $("#password-" + id).addClass("hide");
    $("#edit-password-" + id).removeClass("hide");
    $("#level-" + id).addClass("hide");
    $("#edit-level-" + id).removeClass("hide");
    $("#edit-" + id).addClass("hide");
    $("#terapkan-" + id).removeClass("hide");
}
</script>
@endsection