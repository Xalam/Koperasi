@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Data Master</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/data-master', 'method' => 'GET']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Laporan bagian', ['class' => 'col-lg-2']) !!}
            {!! Form::select('bagian', ['Admin' => 'Admin', 'Barang' => 'Barang', 'Pelanggan' => 'Pelanggan', 'Supplier'
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
    @if (isset($laporan_master) && count($laporan_master) > 0)
    <p class="card-header bg-light">Daftar {{$bagian}}</p>
    @else
    <p class="card-header bg-light">Daftar</p>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        @if (isset($laporan_master) && count($laporan_master) > 0)
                        @if ($bagian == 'Admin')
                        <th>No</th>
                        <th>Kode Admin</th>
                        <th class="col-2">Nama Admin</th>
                        <th>Level Admin</th>
                        @elseif ($bagian == 'Barang')
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th class="col-2">Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        @elseif ($bagian == 'Pelanggan')
                        <th>No</th>
                        <th>Kode Pelanggan</th>
                        <th class="col-2">Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>WA</th>
                        @else
                        <th>No</th>
                        <th>Kode Supplier</th>
                        <th class="col-2">Nama Supplier</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>WA</th>
                        @endif
                        @else
                        <th>No</th>
                        <th>Kode</th>
                        <th class="col-2">Nama</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        @endif
                    </tr>
                </thead>
                @if (isset($laporan_master) && count($laporan_master) > 0)
                <tbody>
                    @php
                    $i = 1 + 1 * ($laporan_master->currentPage() - 1);
                    @endphp
                    @foreach ($laporan_master as $data)
                    <tr>
                        @if ($bagian == 'Admin')
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{$data->level}}</td>
                        </td>
                        @elseif ($bagian == 'Barang')
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{$data->hpp}}</td>
                        <td class="align-middle text-center">{{$data->harga_jual}}</td>
                        <td class="align-middle text-center">{{$data->satuan}}</td>
                        <td class="align-middle text-center">{{$data->stok}}</td>
                        @else
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle">{{$data->alamat}}</td>
                        <td class="align-middle text-center">{{$data->telepon}}</td>
                        <td class="align-middle text-center">{{$data->wa}}</td>
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