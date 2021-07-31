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
    @if (auth()->user()->jabatan != 'Kanit')
    <div class="row-lg align-item-center">
        <button id="btn-daftar" class="btn btn-sm btn-primary col-lg-6" onclick="panel_daftar()">Daftar Utang</button>
        <button id="btn-bayar" class="btn btn-sm col-lg-6" onclick="panel_bayar()">Bayar Utang</button>
    </div>
    @endif
    <div id="panel-bayar-utang" class="d-none">
        <p class="card-header bg-light">Tambah Angsuran</p>
        <div class="card-body">
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Tanggal', ['class' => 'col-lg-2']) !!}
                {!! Form::date('tanggal', $cur_date, ['class' => 'col-lg-2 form-control form-control-sm', 'required'])
                !!}
                {!! Form::label(null, 'No. Beli', ['class' => 'offset-lg-2 col-lg-2']) !!}
                {!! Form::select('nomor_beli', $pembayaran, null, ['class' => 'col-lg-3 form-control form-control-sm',
                'readonly'])
                !!}
                {!! Form::text('nomor', null, ['class' => 'd-none', 'readonly'])!!}
                {!! Form::text('nomor_jurnal', null, ['class' => 'd-none', 'readonly'])!!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Supplier', ['class' => 'col-lg-2 fw-bold']) !!}
                <div class="w-100"></div>
                {!! Form::label(null, 'Kode Supplier', ['class' => 'col-lg-2']) !!}
                {!! Form::select('kode_supplier', [], null, ['class' => 'col-lg-4 form-control form-control-sm',
                'readonly']) !!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Nama Supplier', ['class' => 'col-lg-2']) !!}
                {!! Form::select('nama_supplier', [], null, ['class' => 'col-lg-9 form-control form-control-sm',
                'readonly']) !!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Sisa Utang', ['class' => 'col-lg-2']) !!}
                {!! Form::number('sisa_utang', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'readonly']) !!}
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
        <div class="d-flex bg-light">
            <p class="card-header col-lg">Daftar Utang</p>
            <i class="card-header fas fa-sync text-success" style="cursor: pointer;" title="Refresh Page" onclick="location.reload()"></i>
        </div>
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
                            <th>Angsuran Utang</th>
                            <th>Sisa Utang</th>
                            @if (auth()->user()->jabatan != 'Kanit')
                            <th>Opsi</th>
                            @endif
                        </tr>
                    </thead>
                    @if (count($hutang) > 0)
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach ($hutang as $data)
                        @if ($data->status == 0)
                        <tr>
                            <th class="align-middle text-center">
                                <p>{{$i++}}</p>
                            </th>
                            <td class="align-middle text-center">{{$data->nomor_beli}}</td>
                            <td class="align-middle">{{$data->kode_supplier}}</td>
                            <td class="align-middle text-center">{{$data->nama_supplier}}</td>
                            <td class="align-middle text-center">{{$data->tanggal_beli}}</td>
                            <td class="align-middle text-center">{{$data->jatuh_tempo}}</td>
                            <td class="align-middle text-center">{{$data->jumlah_hutang}}</td>
                            <td class="align-middle text-center">{{$data->jumlah_angsuran}}</td>
                            <td class="align-middle text-center">{{$data->sisa_hutang}}</td>
                            @if (auth()->user()->jabatan != 'Kanit')
                            <td class="align-middle text-center">
                                <a id=<?php echo "bayar-" . $data->id ?> class="btn btn-sm btn-success"
                                    onclick="bayar(<?php echo $data->id ?>)">Bayar</a>
                            </td>
                            @endif
                        </tr>
                        @endif
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
                            <th>Nomor Jurnal</th>
                            <th>Tanggal</th>
                            <th>Angsuran</th>
                            <th>Sisa Utang</th>
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
@if(Session::get('success'))
<script>
$(document).ready(function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'middle',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });

    Toast.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Data Berhasil Disimpan'
    });
    setTimeout(function() {
        window.location = "/toko/transaksi/hutang";
    }, 1000);
});
</script>
@elseif (Session::get('failed'))
<script>
$(document).ready(function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'middle',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });

    Toast.fire({
        icon: 'error',
        title: 'Gagal',
        text: `{{Session::get('failed')}}`
    });
    setTimeout(function() {
        window.location = "/toko/transaksi/hutang";
    }, 2000);
});
</script>
@endif

<script src="{{ asset('js/base-url.js') }}"></script>
<script src="{{ asset('js/nomor-angsuran.js') }}"></script>
<script>
var nomor_beli;

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
            nomor: $('[name="nomor"]').val(),
            nomor_jurnal: $('[name="nomor_jurnal"]').val(),
            tanggal: $('[name="tanggal"]').val(),
            id_hutang: $('[name="nomor_beli"]').val(),
            angsuran: $('[name="angsuran"]').val(),
            sisa_hutang: $('[name="sisa_utang"]').val() - $('[name="angsuran"]').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
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
                    title: 'Proses Transaksi',
                    text: response.message
                });

                tampil_daftar();
                nomorTransaksi();
            }
        }
    });
}

function hapus_daftar($id) {
    $.ajax({
        url: '/toko/transaksi/hutang/delete/' + $id,
        type: 'POST',
        success: function(response) {
            if (response.code == 200) {
                close_popup_hapus();
                tampil_daftar();
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

                $sisa_hutang = response.supplier_hutang.jumlah_hutang;

                $.each(response.detail_hutang, function(index, value) {
                    $sisa_hutang -= value.angsuran;

                    $('#table-data-angsuran').append('<tr>' +
                        '<th class="align-middle text-center">' + i++ + '</th>' +
                        '<td class="align-middle text-center">' + value.nomor + '</td>' +
                        '<td class="align-middle text-center">' + value.nomor_jurnal + '</td>' +
                        '<td class="align-middle text-center">' + value.tanggal + '</td>' +
                        '<td class="align-middle text-center">' + value.angsuran + '</td>' +
                        '<td class="align-middle text-center">' + $sisa_hutang +
                        '</td>' +
                        '<td class="align-middle text-center"><a id="hapus-' + value
                        .id + '" class="btn btn-sm btn-danger" onclick="show_popup_hapus(' +
                        value
                        .id + ')"><i class="fas fa-trash-alt p-1"></i> Hapus</a>' +
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

function show_popup_hapus(id) {
    $("#popup-delete").removeClass("d-none");

    $('#popup-body').empty();
    $('#popup-body').append('<div class="row-lg align-item-center">' +
        '<label for="">Apakah anda yakin ingin menghapus data ini?</label>' +
        '</div><div class="row-lg align-item-center">' +
        '<a class="btn btn-block btn-sm btn-success mt-1" onclick="hapus_daftar(' + id + ')">Hapus</a>' +
        '<a class="btn btn-block btn-sm btn-danger mt-1" onclick="close_popup_hapus()">Batal</a>' +
        '</div>')
}

function close_popup_hapus() {
    $("#popup-delete").addClass("d-none");
}

$(document).ready(function() {
    $('#table-angsuran').DataTable();

    $('[name="angsuran"]').change(function() {
        if (parseInt($(this).val()) < 0) {
            $(this).val(0);
        }
        
        if (parseInt($(this).val()) > parseInt($('[name="sisa_utang"]').val())) {
            $(this).val($('[name="sisa_utang"]').val());
        }
    });

    $('#bayar').click(function() {
        var allFilled = false;
        var skip = false;

        document.getElementById('panel-bayar-utang').querySelectorAll('[required]').forEach(function(
            i) {
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