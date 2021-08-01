@extends('toko.layout')

@section('popup')
<div id="popup-delete" class="popup-background d-none">
    <div class="popup center-object">
        <div id="popup-body" class="popup-body p-4">
        </div>
    </div>
</div>
@endsection

@section('main')
<div class="card m-6">
    <div class="d-flex bg-light">
        <p class="card-header col-lg">Daftar Pesanan Online</p>
        <i class="card-header fas fa-sync text-success" style="cursor: pointer;" title="Refresh Page"
            onclick="location.reload()"></i>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nomor Jurnal</th>
                        <th>Kode Anggota</th>
                        <th>Nama Anggota</th>
                        <th>Daftar Barang</th>
                        <th>Jumlah Harga</th>
                        <th>Opsi</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                @if (count($pesanan_online) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($pesanan_online as $data)
                    @php
                    $barang = explode(', ', $data->daftar_barang);
                    @endphp
                    <tr>
                        <th class="align-middle text-center">
                            <p>{{$i++}}</p>
                        </th>
                        <td class="align-middle text-center">{{$data->nomor}}</td>
                        <td class="align-middle text-center">{{$data->kode_anggota}}</td>
                        <td class="align-middle">{{$data->nama_anggota}}</td>
                        <td class="align-middle text-center">{{$data->daftar_barang}}</td>
                        <td class="align-middle text-center">{{$data->jumlah_harga}}</td>

                        <td class="align-middle text-center">
                            @if (auth()->user()->jabatan != 'Kanit')
                            <a id="<?php echo "check-" . $data->id; ?>" class="<?php 
                                    if ($data->proses == 0) {
                                        echo 'btn btn-sm btn-outline-success';
                                    } else {
                                        echo 'btn btn-sm btn-success';
                                    }
                                ?>" onclick="checklist(<?php echo $data->id; ?>)"><?php 
                                    if ($data->proses == 0) {
                                        echo 'Belum Diproses';
                                    } else {
                                        echo 'Sudah Diproses';
                                    }
                                ?></a>
                            <a id="<?php echo "check-" . $data->id; ?>" class="btn btn-sm btn-danger" onclick="hapus(<?php echo $data->id; ?>)">Hapus</a>
                            @else
                            <a class="<?php 
                                    if ($data->proses == 0) {
                                        echo 'btn btn-sm btn-outline-success';
                                    } else {
                                        echo 'btn btn-sm btn-success';
                                    }
                                ?>"><?php 
                                    if ($data->proses == 0) {
                                        echo 'Belum Diproses';
                                    } else {
                                        echo 'Sudah Diproses';
                                    }
                                ?></a>
                            @endif
                        </td>
                        <td class="align-middle text-center"><a href="<?php echo url('/toko/transaksi/pesanan-online/nota/' . $data->nomor); ?>" target="_blank"><i class="text-success fas fa-print"
                                style="cursor: pointer;" title="Print"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function checklist(id) {
    if ($('#check-' + id).text() == "Belum Diproses") {
        proses(1, id);
    } else {
        proses(0, id);
    }
}

function proses($proses, $id) {
    $.ajax({
        url: '/toko/transaksi/pesanan-online/proses/' + $id + '/' + $proses,
        type: 'POST',
        success: function(response) {
            if (response.code == 200) {
                if ($('#check-' + $id).text() == "Belum Diproses") {
                    $('#check-' + $id).text('Sudah Diproses');
                    $('#check-' + $id).addClass('btn-success');
                    $('#check-' + $id).removeClass('btn-outline-success');
                } else {
                    $('#check-' + $id).text('Belum Diproses');
                    $('#check-' + $id).removeClass('btn-success');
                    $('#check-' + $id).addClass('btn-outline-success');
                }
            }
        }
    });
}

function hapus(id) {
    Swal.fire({
        title: 'Hapus Pesanan',
        text: 'Anda yakin ingin menghapus data ini?',
        position: 'center',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/toko/transaksi/pesanan-online/delete',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.code == 200) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'middle',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Hapus Pesanan',
                            text: 'Pesanan Berhasil Dihapus'
                        });
                        setTimeout(function() {
                            window.location = "{{url('toko/transaksi/pesanan-online')}}";
                        }, 2000);
                    }
                },
                error: function(error) {
                    alert(error.responseText);
                }
            });
        }
    });
}
</script>
@endsection