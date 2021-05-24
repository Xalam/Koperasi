@extends('toko.layout')

@section('main')
<div class="card m-6">
    <div class="card-body">
        {!! Form::open(['url' => '/toko/transaksi/penjualan/jual']) !!}
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Tanggal', ['class' => 'col-4']) !!}
            {!! Form::date('tanggal', null, ['class' => 'col-4']) !!}
            {!! Form::label(null, 'No. Jual', ['class' => 'offset-6 col-3']) !!}
            {!! Form::text('nomor', null, ['class' => 'col-6']) !!}
            <a id="cek-nomor" class="btn btn-small btn-primary offset-1">Cek</a>
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Pelanggan', ['class' => 'col-5 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Kode Pelanggan', ['class' => 'col-5']) !!}
            {!! Form::select('kode_pelanggan', $kode_pelanggan, null, ['class' => 'col-8']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Nama Pelanggan', ['class' => 'col-5']) !!}
            {!! Form::select('nama_pelanggan', $pelanggan, null, ['class' => 'col-14']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Alamat', ['class' => 'col-5']) !!}
            {!! Form::text('alamat', null, ['class' => 'col-14']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Telepon', ['class' => 'col-5']) !!}
            {!! Form::text('telepon', null, ['class' => 'col-8']) !!}
        </div>
        <br>
        <div class="row align-item-center mb-1">
            <div class="col-8">
                {!! Form::label(null, 'Barang', ['class' => 'font-weight-bold']) !!}
                {!! Form::label(null, 'Kode', ['class' => 'col-5']) !!}
                {!! Form::select('kode_barang', $kode_barang, null, ['class' => 'col-8']) !!}
            </div>
            <div class="offset-10">
                {!! Form::label(null, 'Pembayaran', ['class' => 'font-weight-bold']) !!}
                <div class="row text-center">
                    {!! Form::text('jumlah_harga', null, ['class' => 'hide']) !!}
                    <h2 id="jumlah-harga" class="color-danger">Rp. 0,-</h2>
                    {!! Form::text('jumlah_kembalian', null, ['class' => 'hide']) !!}
                    <h2 id="jumlah-kembalian" class="color-success">Rp. 0,-</h2>
                </div>
            </div>
        </div>
        <div class="row align-item-center mb-1">
            <div class="offset-50 col-4">
                {!! Form::label(null, 'Jenis Pembayaran', ['class' => 'col-4']) !!}
                {!! Form::select('pembayaran', $pembayaran, [], ['class' =>
                'col-4']) !!}
            </div>
            <div class="col-5 offset-5">
                {!! Form::label('dibayar', 'Dibayar', ['class' => 'col-4 hide']) !!}
                {!! Form::text('jumlah_bayar', null, ['class' => 'col-6 hide']) !!}
            </div>
        </div>
        <hr class="mt-2 mb-2">
        <div class="row align-item-center mb-2">
            <div class="col-5">
                {!! Form::label(null, 'Nama', null) !!}
                {!! Form::select('nama_barang', $barang, null) !!}
            </div>
            <div class="col-3 offset-3">
                {!! Form::label(null, 'Sisa Stok', null) !!}
                {!! Form::number('stok', 0) !!}
            </div>
            <div class="col-3 offset-4">
                {!! Form::label(null, 'Harga Satuan', null) !!}
                {!! Form::number('harga_satuan', 0) !!}
            </div>
            <div class="col-3 offset-4">
                {!! Form::label(null, 'Jumlah', null) !!}
                {!! Form::number('jumlah', 0) !!}
            </div>
            <div class="col-3 offset-4">
                {!! Form::label(null, 'Total Harga', null) !!}
                {!! Form::number('total_harga', 0) !!}
            </div>
        </div>
        <div class="row align-item-center mb-1">
            <a id="tambah" class="btn btn-small btn-primary">Tambah</a>
        </div>
        <hr>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Daftar Barang', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="text-center text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th class="col-2">Nama Barang</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Harga Total</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody id="table-data" class="text-wrap">
                </tbody>
            </table>
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::submit('Jual', ['class' => 'btn btn-success btn-small']) !!}
        </div>
        <div class="row align-item-center">
            <a id="batal" class="btn btn-small btn-danger">Batal</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/base-url.js') }}"></script>
<script src="{{ asset('js/data-barang.js') }}"></script>
<script src="{{ asset('js/data-pelanggan.js') }}"></script>
<script>
var nomor;
var jumlah_harga;

function tambah_daftar() {
    $.ajax({
        url: '/toko/transaksi/penjualan/store',
        type: 'POST',
        data: {
            nomor: $('[name="nomor"]').val(),
            id_barang: $('[name="kode_barang"]').val(),
            jumlah: $('[name="jumlah"]').val(),
            total_harga: $('[name="total_harga"]').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.code == 200) {
                tampil_daftar();
            }
        }
    });
}

function tampil_daftar() {
    var i = 1;
    nomor = $('[name="nomor"]').val();
    jumlah_harga = 0;

    $.ajax({
        url: '/toko/transaksi/penjualan/' + nomor,
        type: 'GET',
        success: function(response) {
            if (response.code == 200) {
                $('#table-data').empty();
                $.each(response.barang_penjualan, function(index, value) {
                    $('#table-data').append('<tr>' +
                        '<th class="align-middle text-center">' + i++ + '</th>' +
                        '<td class="align-middle text-center">' + value.kode_barang + '</td>' +
                        '<td class="align-middle">' + value.nama_barang + '</td>' +
                        '<td class="align-middle text-center">' + value.harga_beli + '</td>' +
                        '<td class="align-middle text-center">' + value.jumlah_barang +
                        '</td>' +
                        '<td class="align-middle text-center">' + value.total_harga + '</td>' +
                        '<td class="align-middle text-center"><a id="hapus"' + value
                        .id_barang + ' class="btn btn-small btn-danger">Hapus</a>' +
                        '</td>' +
                        '</tr>')

                    jumlah_harga += value.total_harga;
                })

                $('[name="nomor"]').attr('readonly', true);

                if (response.barang_penjualan.length > 0) {
                    $('[name="tanggal"]').attr('readonly', true);
                    $('[name="kode_pelanggan"]').attr('readonly', true);
                    $('[name="nama_pelanggan"]').attr('readonly', true);
                    $('[name="alamat"]').attr('readonly', true);
                    $('[name="telepon"]').attr('readonly', true);
                } else {
                    $('[name="kode_pelanggan"]').removeAttr('readonly');
                    $('[name="nama_pelanggan"]').removeAttr('readonly');
                    $('[name="alamat"]').removeAttr('readonly');
                    $('[name="telepon"]').removeAttr('readonly');
                }

                $('#jumlah-harga').html("Rp. " + jumlah_harga + ",-");
                $('[name="jumlah_harga"]').val(jumlah_harga);
                $('[name="jumlah_bayar"]').val(response.pelanggan_penjualan.jumlah_bayar);
                $('#jumlah-kembalian').html("Rp. " + response.pelanggan_penjualan.jumlah_kembalian + ",-");
                $('[name="jumlah_kembalian"]').val(response.pelanggan_penjualan.jumlah_kembalian);

                $('[name="tanggal"]').val(response.pelanggan_penjualan.tanggal);
                $('[name="kode_pelanggan"]').val(response.pelanggan_penjualan.id_pelanggan);
                $('[name="nama_pelanggan"]').val(response.pelanggan_penjualan.id_pelanggan);
                $('[name="alamat"]').val(response.pelanggan_penjualan.alamat);
                $('[name="telepon"]').val(response.pelanggan_penjualan.telepon);

                $('[name="pembayaran"]').val(response.pelanggan_penjualan.pembayaran);

                if ($('[name="pembayaran"]').val() == 1) {
                    $('[for="dibayar"]').addClass('hide');
                    $('[name="jumlah_bayar"]').addClass('hide');
                } else {
                    $('[for="dibayar"]').removeClass('hide');
                    $('[name="jumlah_bayar"]').removeClass('hide');
                }
                return false;
            }
        }
    });
}

function batal_transaksi() {
    $.ajax({
        url: '/toko/transaksi/penjualan/cancel/',
        type: 'POST',
        data: {
            nomor: $('[name="nomor"]').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.code == 200) {
                location.reload();
            }
        }
    });
}

$(function() {
    $('#cek-nomor').click(function() {
        tampil_daftar();
    });

    $('[name="pembayaran"]').change(function() {
        if ($(this).val() == 1) {
            $('[for="dibayar"]').addClass('hide');
            $('[name="jumlah_bayar"]').addClass('hide');
        } else {
            $('[for="dibayar"]').removeClass('hide');
            $('[name="jumlah_bayar"]').removeClass('hide');
        }
    });

    $('[name="jumlah"]').change(function() {
        $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $(this).val());
    });

    $('[name="kode_barang"]').change(function() {
        $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $('[name="jumlah"]').val());
    });

    $('[name="jumlah_bayar"]').change(function() {
        $('#jumlah-kembalian').html("Rp. " + ($('[name="jumlah_bayar"]').val() - jumlah_harga) + ",-");
        $('[name="jumlah_kembalian"]').val($('[name="jumlah_bayar"]').val() - jumlah_harga);
    });

    $('#tambah').click(function() {
        tambah_daftar();
    });

    $('#batal').click(function() {
        batal_transaksi();
    });
});
</script>
@endsection