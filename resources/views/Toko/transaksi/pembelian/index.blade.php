@extends('toko.layout')

@section('main')
<div class="card m-6">
    <div class="card-body">
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Tanggal', ['class' => 'col-4']) !!}
            {!! Form::date(null, null, ['class' => 'col-4']) !!}
            {!! Form::label(null, 'No. Beli', ['class' => 'offset-6 col-3']) !!}
            {!! Form::text('nomor', null, ['class' => 'col-6']) !!}
            <a id="cek-nomor" class="btn btn-small btn-primary offset-1">Cek</a>
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Supplier', ['class' => 'col-5 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Kode Supplier', ['class' => 'col-5']) !!}
            {!! Form::select('kode_supplier', $kode_supplier, null, ['class' => 'col-8']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Nama Supplier', ['class' => 'col-5']) !!}
            {!! Form::select('nama_supplier', $supplier, null, ['class'
            =>
            'col-14']) !!}
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
            {!! Form::label(null, 'Barang', ['class' => 'font-weight-bold']) !!}
            {!! Form::label(null, 'Pembayaran', ['class' => 'ms-2 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            <div class="col-8">
                {!! Form::label(null, 'Kode', ['class' => 'col-5']) !!}
                {!! Form::select('kode_barang', $kode_barang, null, ['class' => 'col-8']) !!}
            </div>
            <div class="offset-8 text-center">
                <h2 class="color-danger">Rp. 1.000.000.000,-</h2>
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
                {!! Form::label(null, 'Jumlah Beli', null) !!}
                {!! Form::number('jumlah_beli', 0) !!}
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
            {!! Form::submit('Submit', ['class' => 'btn btn-success btn-small']) !!}
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/base-url.js') }}"></script>
<script src="{{ asset('js/data-barang.js') }}"></script>
<script src="{{ asset('js/data-supplier.js') }}"></script>
<script>
function tambah_daftar() {
    $.ajax({
        url: '/transaksi/pembelian/store',
        type: 'POST',
        data: {
            nomor: $('[name="nomor"]').val(),
            id_supplier: $('[name="kode_supplier"]').val(),
            id_barang: $('[name="kode_barang"]').val(),
            jumlah: $('[name="jumlah_beli"]').val(),
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
    var nomor = $('[name="nomor"]').val();

    $.ajax({
        url: '/transaksi/pembelian/' + nomor,
        type: 'GET',
        success: function(response) {
            if (response.code == 200) {
                $.each(response.pembelian, function(index, value) {
                    $('#table-data').append('<tr>' +
                        '<th class="align-middle text-center">' + i++ + '</th>' +
                        '<td class="align-middle text-center">' + value.nomor + '</td>' +
                        '<td class="align-middle">' + value.nama + '</td>' +
                        '<td class="align-middle text-center">' + value.harga_beli + '</td>' +
                        '<td class="align-middle text-center">' + value.jumlah + '</td>' +
                        '<td class="align-middle text-center">' + value.total_harga + '</td>' +
                        '<td class="align-middle text-center"><button id="hapus"' + value.id + ' class="btn btn-small btn-danger">Hapus</button>' +
                        '</td>' +
                        '</tr>')
                })
                return false;
            }
        }
    });
}

$(function() {
    $('#cek-nomor').click(function() {
        tampil_daftar();
    });

    $('[name="pembayaran"]').change(function() {
        if ($(this).val() == "Kredit") {
            $('[for="dibayar"]').addClass('hide');
            $('[name="jumlah_bayar"]').addClass('hide');
        } else {
            $('[for="dibayar"]').removeClass('hide');
            $('[name="jumlah_bayar"]').removeClass('hide');
        }
    });

    $('[name="jumlah_beli"]').change(function() {
        $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $(this).val());
    });

    $('#tambah').click(function() {
        tambah_daftar();
    });
});
</script>
@endsection