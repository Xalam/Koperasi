@extends('toko.layout')

@section('main')
<div class="card m-6">
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/data-master', 'method' => 'GET']) !!}
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Laporan Data Master', ['class' => 'col-4 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Laporan bagian', ['class' => 'col-5']) !!}
            {!! Form::select('bagian', ['Admin' => 'Admin', 'Barang' => 'Barang', 'Pelanggan' => 'Pelanggan', 'Supplier'
            =>
            'Supplier'], (isset($bagian) ? $bagian : null), ['class' => 'col-6']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-small']) !!}
        </div>
        {!! Form::close() !!}
        <hr>
        @if (isset($laporan_master) && count($laporan_master) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="text-center text-nowrap">
                    <tr>
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
                        @else
                        <th>No</th>
                        <th>Kode Supplier</th>
                        <th class="col-2">Nama Supplier</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-wrap">
                    @php
                    $i = 1 + 1 * ($laporan_master->currentPage() - 1);
                    @endphp
                    @foreach ($laporan_master as $data)
                    <tr>
                        @if ($bagian == 'Admin')
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{$data->level}}</td>                        </td>
                        @elseif ($bagian == 'Barang')
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{$data->harga_beli}}</td>     
                        <td class="align-middle text-center">{{$data->harga_jual}}</td>     
                        <td class="align-middle text-center">{{$data->satuan}}</td>     
                        <td class="align-middle text-center">{{$data->stok}}</td>     
                        @else
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle">{{$data->alamat}}</td>     
                        <td class="align-middle text-center">{{$data->telepon}}</td>     
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if(empty($search))
            {{ $laporan_master->appends(['data-master' => $bagian])->links() }}
            @else
            {{ $laporan_master->appends(['data-master' => $bagian, 'search' => $search])->links() }}
            @endif
        </div>
        @endif
    </div>
</div>
@endsection