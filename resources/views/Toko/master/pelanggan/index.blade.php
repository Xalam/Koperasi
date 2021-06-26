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
    <p class="card-header bg-light">Tambah Pelanggan</p>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Pelanggan</th>
                        <th class="col-2">Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>Nomor Telepon</th>
                        <th>Nomor WA</th>
                    </tr>
                </thead>
                @if (count($pelanggan) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($pelanggan as $data)
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
                            {!! Form::text('edit_alamat', $data->alamat, ['class' => 'd-none', 'id' =>
                            'edit-alamat-'.$data->id]) !!}
                            <p id="alamat-<?php echo $data->id ?>">{{$data->alamat}}</p>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_telepon', $data->telepon, ['class' => 'd-none', 'id' =>
                            'edit-telepon-'.$data->id]) !!}
                            <p id="telepon-<?php echo $data->id ?>">{{$data->telepon}}</p>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_wa', $data->wa, ['class' => 'd-none', 'id' =>
                            'edit-wa-'.$data->id]) !!}
                            <p id="wa-<?php echo $data->id ?>">{{$data->wa}}</p>
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