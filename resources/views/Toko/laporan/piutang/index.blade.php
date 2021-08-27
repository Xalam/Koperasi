@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Piutang</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/piutang', 'method' => 'GET']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal Awal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_awal', (isset($tanggal_awal) ? $tanggal_awal : $cur_date), ['class' => 'col-lg-2
            form-control form-control-sm', 'required']) !!}
            {!! Form::label(null, '-', ['class' => 'offset-lg-1 col-lg-1']) !!}
            {!! Form::label(null, 'Tanggal Akhir', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal_akhir', (isset($tanggal_akhir) ? $tanggal_akhir : $cur_date), ['class' => 'col-lg-2
            form-control form-control-sm']) !!}
        </div>
        <div class="d-grid gap-2">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="card m-6">
    <div class="d-flex flex-row">
        <p class="card-header col-lg">Daftar Piutang</p>
        @if (isset($laporan_piutang) && count($laporan_piutang) > 0)
        <a href=<?php echo 'piutang/export/'.$tanggal_awal.'/'.$tanggal_akhir ?>
            target="_blank"><i class="card-header text-success fas fa-file-export" style="cursor: pointer;"
                title="Export to Excel"></i></a>
        <a href=<?php echo 'piutang/print/'.$tanggal_awal.'/'.$tanggal_akhir ?>
            target="_blank"><i class="card-header text-success fas fa-print" style="cursor: pointer;"
                title="Print"></i></a>
        @else
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No.</th>
                        <th>Kode Anggota</th>
                        <th>Nama Anggota</th>
                        <th>Sisa Piutang</th>
                    </tr>
                </thead>
                @if (isset($laporan_piutang) && count($laporan_piutang) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($laporan_piutang AS $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode_anggota}}</td>
                        <td class="align-middle text-center">{{$data->nama_anggota}}</td>
                        <td class="align-middle text-center">{{number_format($data->sisa_piutang, 2, ",", ".")}}</td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection