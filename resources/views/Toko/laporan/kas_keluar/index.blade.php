@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Kas Kaluar</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/kas-keluar', 'method' => 'GET']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal Awal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_awal', (isset($tanggal_awal) ? $tanggal_awal : $cur_date), ['class' => 'col-lg-2
            form-control form-control-sm', 'required']) !!}
            {!! Form::label(null, '-', ['class' => 'offset-lg-1 col-lg-1']) !!}
            {!! Form::label(null, 'Tanggal Akhir', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_akhir', (isset($tanggal_akhir) ? $tanggal_akhir : $cur_date), ['class' => 'col-lg-2
            form-control form-control-sm']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jenis Pengeluaran', ['class' => 'col-lg-2']) !!}
            {!! Form::select('jenis_pengeluaran', $pengeluaran, (isset($jenis_pengeluaran) ? $jenis_pengeluaran : null),
            ['class' => 'col-lg-4 form-select form-select-sm']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="card m-6">
    <div class="d-flex flex-row">
        @if (isset($laporan_kas_keluar) && count($laporan_kas_keluar) > 0)
        <p class="card-header col-lg">Daftar Kas Keluar {{$pengeluaran[$jenis_pengeluaran]}}</p>
        <a href=<?php echo 'kas-keluar/export/'.$jenis_pengeluaran.'/'.$tanggal_awal.'/'.$tanggal_akhir ?>
            target="_blank"><i class="card-header text-success fas fa-file-export" style="cursor: pointer;"
                title="Export to Excel"></i></a>
        <a href=<?php echo 'kas-keluar/print/'.$jenis_pengeluaran.'/'.$tanggal_awal.'/'.$tanggal_akhir ?>
            target="_blank"><i class="card-header text-success fas fa-print" style="cursor: pointer;"
                title="Print"></i></a>
        @else
        <p class="card-header col-lg">Daftar </p>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No.</th>
                        <th>Nomor Transaksi</th>
                        <th>Tanggal Transaksi</th>
                        <th>Kode Supplier</th>
                        <th>Nama Supplier</th>
                        <th>Keterangan</th>
                        <th>Jumlah Transaksi</th>
                    </tr>
                </thead>
                @if (isset($laporan_kas_keluar) && count($laporan_kas_keluar) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($laporan_kas_keluar AS $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->nomor}}</td>
                        <td class="align-middle text-center">{{$data->tanggal}}</td>
                        <td class="align-middle">{{$data->kode_supplier}}</td>
                        <td class="align-middle">{{$data->nama_supplier}}</td>
                        <td class="align-middle text-center">{{$data->keterangan}}</td>
                        <td class="align-middle text-center">{{number_format($data->jumlah_transaksi, 2, ',', '.')}}</td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection