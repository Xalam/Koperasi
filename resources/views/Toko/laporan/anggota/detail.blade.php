@extends('toko.layout')

@section('main')
<div class="card m-6">
    <div class="d-flex flex-row">
        <p class="card-header col-lg">Daftar Belanja</p>
        @if (isset($laporan_detail_anggota) && count($laporan_detail_anggota) > 0)
        <a href=<?php echo 'anggota/detail/export/'.$id.'/'.$tanggal_awal.'/'.$tanggal_akhir ?> target="_blank"><i
                class="card-header text-success fas fa-file-export" style="cursor: pointer;"
                title="Export to Excel"></i></a>
        <a href=<?php echo 'anggota/detail/print/'.$id.'/'.$tanggal_awal.'/'.$tanggal_akhir ?> target="_blank"><i
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
                        <th>Tanggal</th>
                        <th>Transaksi</th>
                        <th>Nominal</th>
                        <th>Total Belanja Toko</th>
                    </tr>
                </thead>
                @if (isset($laporan_detail_anggota) && count($laporan_detail_anggota) > 0)
                <tbody>
                    @php
                    $i = 1;
                    $totalBelanja = 0;
                    @endphp
                    @foreach ($laporan_detail_anggota AS $data)
                    @php
                    $totalBelanja += $data->jumlah_harga;
                    $transaksi = 'Penjualan';
                    $typePenjualan = ($data->type_penjualan == 1) ? 'Offline' : 'Online';
                    $typePembayaran = ($data->pembayaran == 1) ? 'Kredit' : 'Tunai';
                    @endphp
                    <tr>
                        <th class="align-middle text-center">{{$i++}}</th>
                        <td class="align-middle text-center">{{$data->tanggal}}</td>
                        <td class="align-middle text-center">{{$transaksi . ' ' . $typePenjualan . ' ' . $typePembayaran}}</td>
                        <td class="align-middle text-center">{{number_format($data->jumlah_harga, 2, ',', '.')}}</td>
                        <td class="align-middle text-center">{{number_format($totalBelanja, 2, ',', '.')}}</td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection