@extends('toko.layout')

@section('main')
<div class="card m-6">
    <div class="d-flex flex-row">
        @if ((isset($laporan_pendapatan) && count($laporan_pendapatan) > 0) || (isset($laporan_pengeluaran) &&
        count($laporan_pengeluaran) > 0))
        <p class="card-header col-lg">Laporan Laba Rugi {{$tanggal_awal}} - {{$tanggal_akhir}}</p>
        <a href=<?php echo 'pendapatan/export/'.$tanggal_awal.'/'.$tanggal_akhir ?> target="_blank"><i
                class="card-header text-success fas fa-file-export" style="cursor: pointer;"
                title="Export to Excel"></i></a>
        <a href=<?php echo 'pendapatan/print/'.$tanggal_awal.'/'.$tanggal_akhir ?> target="_blank"><i
                class="card-header text-success fas fa-print" style="cursor: pointer;" title="Print"></i></a>
        @else
        <p class="card-header col-lg">Laporan Laba Rugi</p>
        @endif
    </div>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/pendapatan', 'method' => 'GET']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal Awal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_awal', (isset($tanggal_awal) ? $tanggal_awal : $cur_date), ['class' => 'col-lg-2
            form-control form-control-sm', 'required']) !!}
            {!! Form::label(null, '-', ['class' => 'col-lg-1 text-center']) !!}
            {!! Form::label(null, 'Tanggal Akhir', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_akhir', (isset($tanggal_akhir) ? $tanggal_akhir : $cur_date), ['class' => 'col-lg-2
            form-control form-control-sm', 'required']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-sm']) !!}
        </div>
        {!! Form::close() !!}
        <div class="table-responsive mt-4">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center"> 
                    <tr>
                        <th>No</th>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                    </tr>
                </thead>
                @if (isset($laporan_pendapatan) && count($laporan_pendapatan) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($laporan_pendapatan AS $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode_akun}}</td>
                        <td class="align-middle text-center">{{$data->nama_akun}}</td>
                        <td class="align-middle">{{number_format($data->debit, 2, ",", ".")}}</td>
                        <td class="align-middle text-center">{{number_format($data->kredit, 2, ",", ".")}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tr>
                    @if ($pemasukan->jumlah-$pengeluaran->jumlah < 0)
                    <th colspan="4" class="align-middle">Rugi</th>
                    @else
                    <th colspan="4" class="align-middle">Laba</th>
                    @endif
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="align-middle text-center">{{number_format(($pemasukan->jumlah-$pengeluaran->jumlah), 2, ",", ".")}}</th>
                </tr>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection