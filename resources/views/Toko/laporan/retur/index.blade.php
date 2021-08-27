@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Retur</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/retur-pembelian', 'method' => 'GET']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal', (isset($tanggal) ? $tanggal : $cur_date), ['class' => 'col-lg-2
            form-control form-control-sm', 'required']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="card m-6">
    <div class="d-flex flex-row">
        @if (isset($laporan_retur_pembelian) && count($laporan_retur_pembelian) > 0)
        <p class="card-header col-lg">Daftar Retur</p>
        <a href=<?php echo 'retur-pembelian/export/'.$tanggal ?> target="_blank"><i class="card-header text-success fas fa-file-export" style="cursor: pointer;" title="Export to Excel"></i></a>
        <a href=<?php echo 'retur-pembelian/print/'.$tanggal ?> target="_blank"><i class="card-header text-success fas fa-print" style="cursor: pointer;" title="Print"></i></a>
        @else
        <p class="card-header col-lg">Daftar Retur</p>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nomor Retur</th>
                        <th>Tanggal Retur</th>
                        <th>Nomor Beli</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Jumlah Retur</th>
                        <th>Total Harga</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                @if (isset($laporan_retur_pembelian) && count($laporan_retur_pembelian) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($laporan_retur_pembelian AS $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->nomor}}</td>
                        <td class="align-middle">{{$data->tanggal}}</td>
                        <td class="align-middle text-center">{{$data->nomor_beli}}</td>
                        <td class="align-middle text-center">{{$data->kode_barang}}</td>
                        <td class="align-middle text-center">{{$data->nama_barang}}</td>
                        <td class="align-middle text-center">{{number_format($data->harga_beli, 2, ",", ".")}}</td>
                        <td class="align-middle text-center">{{$data->jumlah}}</td>
                        <td class="align-middle text-center">{{number_format($data->total_harga, 2, ",", ".")}}</td>
                        <td class="align-middle text-center"><a href="<?php echo url('toko/laporan/retur-pembelian/nota/' . $data->nomor); ?>" target="_blank"><i class="text-success fas fa-print"
                                style="cursor: pointer;" title="Print"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection