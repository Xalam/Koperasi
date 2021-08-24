@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Data Master</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/data-master', 'method' => 'GET']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Laporan bagian', ['class' => 'col-lg-2']) !!}
            {!! Form::select('bagian', ['Admin' => 'Admin', 'Barang' => 'Barang', 'Anggota' => 'Anggota', 'Supplier'
            =>
            'Supplier'], (isset($bagian) ? $bagian : null), ['class' => 'col-lg-2 form-select form-select-sm']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="card m-6">
    <div class="d-flex flex-row">
        @if (isset($laporan_master) && count($laporan_master) > 0)
        <p class="card-header col-lg">Daftar {{$bagian}}</p>
        <a href=<?php echo 'data-master/export/'.$bagian ?> target="_blank"><i class="card-header text-success fas fa-file-export" style="cursor: pointer;" title="Export to Excel"></i></a>
        <a href=<?php echo 'data-master/print/'.$bagian ?> target="_blank"><i class="card-header text-success fas fa-print" style="cursor: pointer;" title="Print"></i></a>
        @else
        <p class="card-header col-lg">Daftar</p>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        @if (isset($laporan_master) && count($laporan_master) > 0)
                        @if ($bagian == 'Admin')
                        <th>No</th>
                        <th>Kode Admin</th>
                        <th>Nama Admin</th>
                        <th>Jabatan Admin</th>
                        @elseif ($bagian == 'Barang')
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>HPP</th>
                        <th>Harga Jual</th>
                        <th>Satuan</th>
                        <th>Stok Etalase</th>
                        <th>Stok Gudang</th>
                        @elseif ($bagian == 'Anggota')
                        <th>No</th>
                        <th>Kode Anggota</th>
                        <th>Nama Anggota</th>
                        <th>Jabatan</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>WA</th>
                        <th>Status</th>
                        @else
                        <th>No</th>
                        <th>Kode Supplier</th>
                        <th>Nama Supplier</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>WA</th>
                        <th>Tempo</th>
                        @endif
                        @else
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        @endif
                    </tr>
                </thead>
                @if (isset($laporan_master) && count($laporan_master) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($laporan_master as $data)
                    <tr>
                        @if ($bagian == 'Admin')
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{$data->jabatan}}</td>
                        @elseif ($bagian == 'Barang')
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{number_format($data->hpp, 2, ",", ".")}}</td>
                        <td class="align-middle text-center">{{number_format($data->harga_jual, 2, ",", ".")}}</td>
                        <td class="align-middle text-center">{{$data->satuan}}</td>
                        <td class="align-middle text-center">{{$data->stok_etalase}}</td>
                        <td class="align-middle text-center">{{$data->stok_gudang}}</td>
                        @elseif ($bagian == 'Anggota')
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kd_anggota}}</td>
                        <td class="align-middle">{{$data->nama_anggota}}</td>
                        <td class="align-middle text-center">{{$data->jabatan}}</td>
                        <td class="align-middle">{{$data->alamat}}</td>
                        <td class="align-middle text-center">{{$data->no_hp}}</td>
                        <td class="align-middle text-center">{{$data->no_wa}}</td>
                        <td class="align-middle text-center">{{$data->status}}</td>
                        @else
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle">{{$data->alamat}}</td>
                        <td class="align-middle text-center">{{$data->telepon}}</td>
                        <td class="align-middle text-center">{{$data->wa}}</td>
                        <td class="align-middle text-center">{{$data->tempo}}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection