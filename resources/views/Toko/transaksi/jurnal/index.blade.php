@extends('toko.layout')

@section('main')
@if (auth()->user()->jabatan != 'Kanit')
<a href="{{url('toko/transaksi/jurnal-umum/create')}}" class="btn btn-sm btn-success mt-4 ms-4 pe-4"><i class="fas fa-plus"></i><b>Tambah</b></a>
@endif
<div class="card m-6">
    <p class="card-header bg-light">Daftar Jurnal</p>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nomor Jurnal Umum</th>
                        <th>Tanggal</th>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Keterangan</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                    </tr>
                </thead>
                @if (count($jurnal) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($jurnal as $data)
                    <tr>
                        <th class="align-middle text-center">
                            <p>{{$i++}}</p>
                        </th>
                        <td class="align-middle text-center">{{$data->nomor}}</td>
                        <td class="align-middle text-center">{{$data->tanggal}}</td>
                        <td class="align-middle text-center">{{$data->kode_akun}}</td>
                        <td class="align-middle">{{$data->nama_akun}}</td>
                        <td class="align-middle">{{$data->keterangan}}</td>
                        <td class="align-middle text-center">{{number_format($data->debit, 2, ',', '.')}}</td>
                        <td class="align-middle text-center">{{number_format($data->kredit, 2, ',', '.')}}</td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection