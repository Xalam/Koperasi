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
<a href="{{url('toko/master/anggota/create')}}" class="btn btn-sm btn-success mt-4 ms-4 pe-4"><i
        class="fas fa-plus"></i><b>Tambah</b></a>
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
                        <th>Opsi</th>
                    </tr>
                </thead>
                @if (count($anggota) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($anggota as $data)
                    <tr>
                        <th class="align-middle text-center">
                            <div>{{$i++}}</div>
                        </th>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_kd_anggota', $data->kd_anggota, ['class' => 'd-none', 'id' =>
                            'edit-kd-anggota-'.$data->id]) !!}
                            <div id="kd_anggota-<?php echo $data->id ?>">{{$data->kd_anggota}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_nama_anggota', $data->nama_anggota, ['class' => 'd-none', 'id' =>
                            'edit-nama-anggota-'.$data->id]) !!}
                            <div id="nama_anggota-<?php echo $data->id ?>">{{$data->nama_anggota}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_status', $data->status, ['class' => 'd-none', 'id' =>
                            'edit-status-'.$data->id]) !!}
                            <div id="status-<?php echo $data->id ?>">{{$data->status}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_alamat', $data->alamat, ['class' => 'd-none', 'id' =>
                            'edit-alamat-'.$data->id]) !!}
                            <div id="alamat-<?php echo $data->id ?>">{{$data->alamat}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_jabatan', $data->jabatan, ['class' => 'd-none', 'id' =>
                            'edit-jabatan-'.$data->id]) !!}
                            <div id="jabatan-<?php echo $data->id ?>">{{$data->jabatan}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_gaji', $data->gaji, ['class' => 'd-none', 'id' =>
                            'edit-gaji-'.$data->id]) !!}
                            <div id="gaji-<?php echo $data->id ?>">{{$data->gaji}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_limit_gaji', $data->limit_gaji, ['class' => 'd-none', 'id' =>
                            'edit-limit-gaji-'.$data->id]) !!}
                            <div id="limit_gaji-<?php echo $data->id ?>">{{$data->limit_gaji}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_no_hp', $data->no_hp, ['class' => 'd-none', 'id' =>
                            'edit-no-hp-'.$data->id]) !!}
                            <div id="no_hp-<?php echo $data->id ?>">{{$data->no_hp}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_no_wa', $data->no_wa, ['class' => 'd-none', 'id' =>
                            'edit-no-wa-'.$data->id]) !!}
                            <div id="no_wa-<?php echo $data->id ?>">{{$data->no_wa}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <a id=<?php echo "edit-" . $data->id ?> class="w-48 btn btn-sm btn-warning"
                                onclick="edit(<?php echo $data->id ?>)"><i class="fas fa-edit p-1"></i> Edit</a>
                            <a id=<?php echo "terapkan-" . $data->id ?> class="w-48 btn btn-sm btn-warning d-none"
                                onclick="terapkan(<?php echo $data->id ?>)">Terapkan</a>
                            <a id=<?php echo "hapus-" . $data->id ?> class="w-50 btn btn-sm btn-danger"
                                onclick="show_popup_hapus(<?php echo $data->id ?>)"><i class="fas fa-trash-alt p-1"></i> Hapus</a>
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