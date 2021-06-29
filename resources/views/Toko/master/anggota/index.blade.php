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
                        <th>Jabatan</th>
                        <th>Alamat</th>
                        <th>Gaji</th>
                        <th>Limit Belanja</th>
                        <th>Nomor Telepon</th>
                        <th>Nomor WA</th>
                        <th>Status</th>
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
                            <div id="kode-<?php echo $data->id ?>">{{$data->kode}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="nama-<?php echo $data->id ?>">{{$data->nama}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="jabatan-<?php echo $data->id ?>">{{$data->jabatan}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="alamat-<?php echo $data->id ?>">{{$data->alamat}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="gaji-<?php echo $data->id ?>">{{$data->gaji}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="limit_belanja-<?php echo $data->id ?>">{{$data->limit_belanja}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="telepon-<?php echo $data->id ?>">{{$data->telepon}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="wa-<?php echo $data->id ?>">{{$data->wa}}</div>
                        </td>
                        <td class="align-middle text-center">
                            @if ($data->status == 'Aktif')
                            <div id="status-<?php echo $data->id ?>" class="btn-success rounded p-1">{{$data->status}}
                            </div>
                            @else
                            <div id="status-<?php echo $data->id ?>" class="btn-danger rounded p-1 ps-2 pe-2">{{$data->status}}
                            </div>
                            @endif
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