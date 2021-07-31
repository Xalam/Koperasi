@extends('toko.layout')

@section('main')
<div class="card m-6">
    <div class="d-flex flex-row">
        <p class="card-header col-lg">Daftar Minimal Persediaan</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Stok Minimal</th>
                        <th>Stok</th>
                        <th>Tempat</th>
                    </tr>
                </thead>
                @if (isset($laporan_minimal_persediaan) && count($laporan_minimal_persediaan) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($laporan_minimal_persediaan AS $data)
                    @if ($data->stok_etalase <= $data->stok_minimal || $data->stok_gudang <= $data->stok_minimal)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode}}</td>
                        <td class="align-middle">{{$data->nama}}</td>
                        <td class="align-middle text-center">{{$data->stok_minimal}}</td>
                        @if ($data->stok_etalase <= $data->stok_minimal && $data->stok_gudang > $data->stok_minimal)
                        <td class="align-middle text-center">{{$data->stok_etalase}}</td>
                        <td class="align-middle text-center">Etalase</td>
                        @elseif ($data->stok_gudang <= $data->stok_minimal && $data->stok_etalase > $data->stok_minimal)
                        <td class="align-middle text-center">{{$data->stok_gudang}}</td>
                        <td class="align-middle text-center">Gudang</td>
                        @elseif ($data->stok_gudang <= $data->stok_minimal && $data->stok_etalase <= $data->stok_minimal)
                        <td class="align-middle text-center">{{$data->stok_etalase}} & {{$data->stok_gudang}}</td>
                        <td class="align-middle text-center">Etalase & Gudang</td>
                        @endif
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection