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
                            <div id="kd_anggota-<?php echo $data->id ?>">{{$data->kd_anggota}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="nama_anggota-<?php echo $data->id ?>">{{$data->nama_anggota}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <div id="status-<?php echo $data->id ?>">{{$data->status}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="alamat-<?php echo $data->id ?>">{{$data->alamat}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="jabatan-<?php echo $data->id ?>">{{$data->jabatan}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="gaji-<?php echo $data->id ?>">{{$data->gaji}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="limit_gaji-<?php echo $data->id ?>">{{$data->limit_gaji}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="no_hp-<?php echo $data->id ?>">{{$data->no_hp}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="no_wa-<?php echo $data->id ?>">{{$data->no_wa}}</div>
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