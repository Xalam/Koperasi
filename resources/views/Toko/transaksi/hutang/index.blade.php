@extends('toko.layout')

@section('main')
<div class="card m-6">
    <div class="row-lg align-item-center">
        <button id="btn-daftar" class="btn btn-sm btn-primary col-lg-6" onclick="panel_daftar()">Daftar Utang</button>
        <button id="btn-bayar" class="btn btn-sm col-lg-6" onclick="panel_bayar()">Bayar Utang</button>
    </div>
    <div id="panel-bayar-utang" class="d-none">
        <p class="card-header bg-light">Tambah Angsuran</p>
        <div class="card-body">
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Tanggal', ['class' => 'col-lg-2']) !!}
                {!! Form::date('tanggal', null, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
                {!! Form::label(null, 'No. Beli', ['class' => 'offset-lg-2 col-lg-2']) !!}
                {!! Form::select('nomor_beli', $pembayaran, null, ['class' => 'col-lg-3 form-select form-select-sm',
                'readonly'])
                !!}
                {!! Form::text('nomor', null, ['class' => 'd-none', 'readonly'])!!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Supplier', ['class' => 'col-lg-2 fw-bold']) !!}
                <div class="w-100"></div>
                {!! Form::label(null, 'Kode Supplier', ['class' => 'col-lg-2']) !!}
                {!! Form::select('kode_supplier', [], null, ['class' => 'col-lg-4 form-select form-select-sm']) !!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Nama Supplier', ['class' => 'col-lg-2']) !!}
                {!! Form::select('nama_supplier', [], null, ['class' => 'col-lg-9 form-control form-control-sm']) !!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Sisa Utang', ['class' => 'col-lg-2']) !!}
                {!! Form::number('sisa_utang', 0, ['class' => 'col-lg-2 form-control form-control-sm']) !!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Pelunasan', ['class' => 'col-lg-2 fw-bold']) !!}
                <div class="w-100"></div>
                {!! Form::label(null, 'Angsuran', ['class' => 'col-lg-2']) !!}
                {!! Form::number('angsuran', null, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
            </div>
            <br>
            <div class="d-grid gap-2 mb-2">
                <a id="bayar" class="btn btn-sm btn-primary">Bayar</a>
            </div>
            <div class="d-grid gap-2">
                <a id="batal" class="btn btn-sm btn-danger">Batal</a>
            </div>
        </div>
    </div>

    <div id="panel-daftar-utang">
        <p class="card-header bg-light">Daftar Hutang</p>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>No Beli</th>
                            <th>Kode Supplier</th>
                            <th>Nama Supplier</th>
                            <th>Tanggal Beli</th>
                            <th>Jatuh Tempo</th>
                            <th>Nilai Utang</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    @if (count($hutang) > 0)
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach ($hutang as $data)
                        <tr>
                            <th class="align-middle text-center">
                                <p>{{$i++}}</p>
                            </th>
                            <td class="align-middle text-center">
                                <p id="kode-<?php echo $data->id ?>">{{$data->nomor_beli}}</p>
                            </td>
                            <td class="align-middle">
                                <p id="nama-<?php echo $data->id ?>">{{$data->kode_supplier}}</p>
                            </td>
                            <td class="align-middle text-center">
                                <p id="hpp-<?php echo $data->id ?>">{{$data->nama_supplier}}</p>
                            </td>
                            <td class="align-middle text-center">
                                <p id="harga-jual-<?php echo $data->id ?>">{{$data->tanggal_beli}}</p>
                            </td>
                            <td class="align-middle text-center">
                                <p id="stok-<?php echo $data->id ?>">{{$data->jatuh_tempo}}</p>
                            </td>
                            <td class="align-middle text-center">
                                <p id="satuan-<?php echo $data->id ?>">{{$data->jumlah_hutang}}</p>
                            </td>
                            <td class="align-middle text-center">
                                <a id=<?php echo "bayar-" . $data->id ?> class="btn btn-sm btn-success"
                                    onclick="bayar(<?php echo $data->id ?>)">Bayar</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<div id="panel-daftar-bayar-utang" class="d-none">
    <div class="card m-6">
        <p class="card-header bg-light">Daftar Angsuran</p>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-angsuran" class="table table-striped table-bordered table-hover nowrap">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th class="col-2">Nomor Hutang</th>
                            <th>Angsuran</th>
                            <th>Sisa Hutang</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody id="table-data-angsuran">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/base-url.js') }}"></script>
<script src="{{ asset('js/nomor-angsuran.js') }}"></script>
<script>
var nomor_beli;

$('#table-angsuran').DataTable();

function panel_daftar() {
    $('#panel-daftar-utang').removeClass('d-none');
    $('#btn-daftar').addClass('btn-primary');
    $('#btn-bayar').removeClass('btn-primary');
    $('#panel-bayar-utang').addClass('d-none');
    $('#panel-daftar-bayar-utang').addClass('d-none');
}

function panel_bayar() {
    $('#panel-daftar-utang').addClass('d-none');
    $('#btn-daftar').removeClass('btn-primary');
    $('#btn-bayar').addClass('btn-primary');
    $('#panel-bayar-utang').removeClass('d-none');
    $('#panel-daftar-bayar-utang').removeClass('d-none');
}

function bayar($id) {
    $('[name="nomor_beli"]').val($id);
    tampil_daftar();
    panel_bayar();
}

function bayar_hutang() {
    $.ajax({
        url: '/toko/transaksi/hutang/store',
        type: 'POST',
        data: {
            tanggal: $('[name="tanggal"]').val(),
            id_hutang: $('[name="nomor_beli"]').val(),
            angsuran: $('[name="angsuran"]').val(),
            sisa_hutang: $('[name="sisa_utang"]').val() - $('[name="angsuran"]').val(),
            nomor: $('[name="nomor"]').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.code == 200) {
                tampil_daftar();
                nomorTransaksi();
            }
        }
    });
}

function tampil_daftar() {
    var i = 1;
    nomor_beli = $('[name="nomor_beli"]').val();

    $.ajax({
        url: '/toko/transaksi/hutang/' + nomor_beli,
        type: 'GET',
        success: function(response) {
            if (response.code == 200) {
                $('#table-data-angsuran').empty();
                $.each(response.detail_hutang, function(index, value) {
                    $('#table-data-angsuran').append('<tr>' +
                        '<th class="align-middle text-center">' + i++ + '</th>' +
                        '<td class="align-middle text-center">' + value.nomor + '</td>' +
                        '<td class="align-middle text-center">' + value.tanggal + '</td>' +
                        '<td class="align-middle">' + value.nomor_hutang + '</td>' +
                        '<td class="align-middle text-center">' + value.angsuran + '</td>' +
                        '<td class="align-middle text-center">' + value.sisa_hutang +
                        '</td>' +
                        '<td class="align-middle text-center"><a id="hapus-' + value
                        .id + '" class="btn btn-sm btn-danger">Hapus</a>' +
                        '</td>' +
                        '</tr>')
                })

                if (response.supplier_hutang) {
                    $('[name="kode_supplier"]').empty();
                    $('[name="nama_supplier"]').empty();
                    $('[name="sisa_utang"]').empty();
                    $('[name="kode_supplier"]').append('<option value=' + response.supplier_hutang
                        .id_supplier + '>' + response.supplier_hutang.kode_supplier + '</option>');
                    $('[name="nama_supplier"]').append('<option value=' + response.supplier_hutang
                        .id_supplier + '>' + response.supplier_hutang.nama_supplier + '</option>');
                    $('[name="sisa_utang"]').val(response.supplier_hutang.sisa_hutang);
                    $('[name="angsuran"]').val("");
                }
                $('#table-angsuran').DataTable();
                return false;
            }
        }
    });
}

function batal_transaksi() {
    $.ajax({
        url: '/toko/transaksi/hutang/cancel/',
        type: 'POST',
        data: {
            nomor_beli: $('[name="nomor_beli"]').val(),
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
    $('#bayar').click(function() {
        var allFilled = false;
        var skip = false;

        document.getElementById('panel-bayar-utang').querySelectorAll('[required]').forEach(function(i) {
            if (!skip) {
                if (!i.value) {
                    i.focus();
                    allFilled = false;
                    skip = true;
                } else {
                    allFilled = true;
                }
            } else {
                return;
            }
        });

        if (allFilled) {
            bayar_hutang();
        }
    });

    $('#batal').click(function() {
        batal_transaksi();
    });
});
</script>
@endsection