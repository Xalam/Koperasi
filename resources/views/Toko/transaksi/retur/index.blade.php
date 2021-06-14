@extends('toko.layout')

@section('main')
<div class="card m-6">
    <p class="card-header bg-light">Tambah Retur</p>
    <div class="card-body">
        {!! Form::open(['url' => '/toko/transaksi/retur-pembelian/retur']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal', null, ['class' => 'col-lg-2 form-control form-control-sm']) !!}
            {!! Form::label(null, 'No. Retur', ['class' => 'offset-lg-2 col-lg-2']) !!}
            {!! Form::text('nomor', null, ['class' => 'col-lg-2 form-control form-control-sm']) !!}
            <a id="cek-nomor" class="btn btn-sm btn-primary col-lg-1">Cek</a>
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Retur', ['class' => 'col-lg-2 fw-bold']) !!}
            <div class="w-100"></div>
            {!! Form::label(null, 'No. Beli', ['class' => 'col-lg-2']) !!}
            {!! Form::select('id_beli', $nomor_beli, null, ['class' => 'col-lg-4 form-select form-select-sm']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Supplier', ['class' => 'col-lg-2']) !!}
            {!! Form::select('nama_supplier', [], null, ['class' => 'col-lg-9 form-select form-select-sm', 'readonly' => 'readonly']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Kode Barang', ['class' => 'col-lg-2']) !!}
            {!! Form::select('kode_barang', [], null, ['class' => 'col-lg-4 form-select form-select-sm']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Barang', ['class' => 'col-lg-2']) !!}
            {!! Form::select('nama_barang', [], null, ['class' => 'col-lg-9 form-select form-select-sm']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Harga Beli', ['class' => 'col-lg-2']) !!}
            {!! Form::number('harga_beli', null, ['class' => 'col-lg-2 form-control form-control-sm', 'readonly' => 'readonly']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jumlah Retur', ['class' => 'col-lg-2']) !!}
            {!! Form::number('jumlah', null, ['class' => 'col-lg-1 form-control form-control-sm']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jumlah Harga', ['class' => 'col-lg-2 d-none']) !!}
            {!! Form::text('jumlah_harga', null, ['class' => 'col-lg-2 form-control form-control-sm d-none']) !!}
        </div>
        <div class="d-grid gap-2">
            <a id="tambah" class="btn btn-small btn-primary">Tambah</a>
        </div>
    </div>
</div>

<div class="card m-6">
    <p class="card-header bg-light">Daftar Retur</p>
    <div class="card-body">
        <div class="table-responsive mb-2">
            <table id="table-retur" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th class="col-2">Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Jumlah Retur</th>
                        <th>Harga Total</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody id="table-data-retur">
                </tbody>
            </table>
        </div>
        <div class="d-grid gap-2 mb-2">
            {!! Form::submit('Retur', ['class' => 'btn btn-success btn-small']) !!}
        </div>
        <div class="d-grid gap-2">
            <a id="batal" class="btn btn-small btn-danger">Batal</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/base-url.js') }}"></script>
<script src="{{ asset('js/data-retur-barang.js') }}"></script>
<script src="{{ asset('js/data-retur-supplier.js') }}"></script>
<script>
var nomor;
var jumlah_harga;

$('#table-retur').DataTable();

function tambah_daftar() {
    $.ajax({
        url: '/toko/transaksi/retur-pembelian/store',
        type: 'POST',
        data: {
            nomor: $('[name="nomor"]').val(),
            id_barang: $('[name="kode_barang"]').val(),
            harga_beli: $('[name="harga_beli"]').val(),
            jumlah: $('[name="jumlah"]').val(),
            total_harga: $('[name="harga_beli"]').val() * $('[name="jumlah"]').val()
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
        url: '/toko/transaksi/retur-pembelian/' + nomor,
        type: 'GET',
        success: function(response) {
            if (response.code == 200) {
                $('#table-data-retur').empty();
                $.each(response.barang_retur, function(index, value) {
                    $('#table-data-retur').append('<tr>' +
                        '<th class="align-middle text-center">' + i++ + '</th>' +
                        '<td class="align-middle text-center">' + value.kode_barang + '</td>' +
                        '<td class="align-middle">' + value.nama_barang + '</td>' +
                        '<td class="align-middle text-center">' + value.harga_beli + '</td>' +
                        '<td class="align-middle text-center">' + value.jumlah_retur +
                        '</td>' +
                        '<td class="align-middle text-center">' + value.total_harga + '</td>' +
                        '<td class="align-middle text-center"><a id="hapus-' + value
                        .id + '" class="btn btn-small btn-danger">Hapus</a>' +
                        '</td>' +
                        '</tr>')

                    jumlah_harga += value.total_harga;
                })

                $('[name="jumlah_harga"]').val(jumlah_harga);

                $('[name="nomor"]').attr('readonly', true);

                if (response.barang_retur.length > 0) {
                    $('[name="tanggal"]').attr('readonly', true);
                    $('[name="id_beli"]').attr('readonly', true);
                } else {
                    $('[name="id_beli"]').removeAttr('readonly');
                    $('[name="tanggal"]').removeAttr('readonly');
                }

                if (response.supplier_retur) {
                    $('[name="tanggal"]').val(response.supplier_retur.tanggal);
                    $('[name="id_beli"]').val(response.supplier_retur.id_beli);
                    $('[name="nama_supplier"]').append('<option value=' + response.supplier_retur
                        .id_supplier + '>' + response.supplier_retur.nama_supplier + '</option>');

                    data_retur_barang();

                    $('[name="harga_beli"]').val('');
                    $('[name="jumlah"]').val('');
                }
                $('#table-retur').DataTable();
                return false;
            }
        }
    });
}

function batal_transaksi() {
    $.ajax({
        url: '/toko/transaksi/retur-pembelian/cancel/',
        type: 'POST',
        data: {
            nomor: $('[name="nomor"]').val()
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

    $('#tambah').click(function() {
        tambah_daftar();
    });

    $('#batal').click(function() {
        batal_transaksi();
    });
});
</script>
@endsection