@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Laporan Anggota</p>
    <div class="card-body">
        {!! Form::open( ['url' => '/toko/laporan/anggota', 'method' => 'GET']) !!}
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
        <p class="card-header col-lg">Daftar Anggota</p>
        @if (isset($laporan_anggota) && count($laporan_anggota) > 0)
        <a href=<?php echo 'anggota/export/'.$tanggal_awal.'/'.$tanggal_akhir ?> target="_blank"><i
                class="card-header text-success fas fa-file-export" style="cursor: pointer;"
                title="Export to Excel"></i></a>
        <a href=<?php echo 'anggota/print/'.$tanggal_awal.'/'.$tanggal_akhir ?> target="_blank"><i
                class="card-header text-success fas fa-print" style="cursor: pointer;" title="Print"></i></a>
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
                        <th>Total Belanja Toko</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                @if (isset($laporan_anggota) && count($laporan_anggota) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($laporan_anggota AS $data)
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->kode_anggota}}</td>
                        <td class="align-middle text-center">{{$data->nama_anggota}}</td>
                        <td class="align-middle text-center">{{number_format($data->total_belanja, 2, ',', '.')}}</td>
                        <td class="align-middle text-center"><a
                                href="<?php echo url('toko/laporan/anggota/detail/' . $data->id . '/' . $tanggal_awal . '/' . $tanggal_akhir); ?>"
                                class="btn btn-sm btn-success">Detail</a>
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