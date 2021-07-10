@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Pendapatan</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/pendapatan', 'method' => 'GET']) !!}
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
        @if ((isset($laporan_pendapatan) && count($laporan_pendapatan) > 0) || (isset($laporan_pengeluaran) &&
        count($laporan_pengeluaran) > 0))
        <p class="card-header col-lg">Daftar Pendapatan Per {{$tanggal}}</p>
        <a href=<?php echo 'pendapatan/export/'.$tanggal ?> target="_blank"><i
                class="card-header text-success fas fa-file-export" style="cursor: pointer;"
                title="Export to Excel"></i></a>
        <a href=<?php echo 'pendapatan/print/'.$tanggal ?> target="_blank"><i
                class="card-header text-success fas fa-print" style="cursor: pointer;" title="Print"></i></a>
        @else
        <p class="card-header col-lg">Daftar Pendapatan</p>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
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
                        <td class="align-middle">{{$data->debit}}</td>
                        <td class="align-middle text-center">{{$data->kredit}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tr>
                    <th colspan="4" class="align-middle">Laba</th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="align-middle text-center">{{$pemasukan->jumlah-$pengeluaran->jumlah}}</th>
                </tr>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection