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
        <button id="btn-piutang" class="btn btn-sm btn-primary col-lg-6" onclick="panel_piutang()">Piutang</button>
        <button id="btn-terima-piutang" class="btn btn-sm col-lg-6" onclick="panel_terima_piutang()">Terima
            Piutang</button>
    </div>
    @endif
    <div id="panel-terima-piutang" class="d-none">
        <p class="card-header bg-light">Tambah Angsuran</p>
        <div class="card-body">
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Tanggal', ['class' => 'col-lg-2']) !!}
                {!! Form::date('tanggal', $cur_date, ['class' => 'col-lg-2 form-control form-control-sm', 'required'])
                !!}
                {!! Form::label(null, 'No. Jual', ['class' => 'offset-lg-2 col-lg-2']) !!}
                {!! Form::select('nomor_jual', $nomor, null, ['class' => 'col-lg-3 form-control form-control-sm',
                'readonly'])
                !!}
                {!! Form::text('nomor', null, ['class' => 'd-none', 'readonly'])!!}
                {!! Form::text('nomor_jurnal', null, ['class' => 'd-none', 'readonly'])!!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Anggota', ['class' => 'col-lg-2 fw-bold']) !!}
                <div class="w-100"></div>
                {!! Form::label(null, 'Kode Anggota', ['class' => 'col-lg-2']) !!}
                {!! Form::select('kode_anggota', [], null, ['class' => 'col-lg-4 form-control form-control-sm',
                'readonly']) !!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Nama Anggota', ['class' => 'col-lg-2']) !!}
                {!! Form::select('nama_anggota', [], null, ['class' => 'col-lg-9 form-control form-control-sm',
                'readonly']) !!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Sisa Piutang', ['class' => 'col-lg-2']) !!}
                {!! Form::number('sisa_piutang', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'readonly'])
                !!}
            </div>
            <div class="row-lg align-item-center mb-2">
                {!! Form::label(null, 'Pelunasan', ['class' => 'col-lg-2 fw-bold']) !!}
                <div class="w-100"></div>
                {!! Form::label(null, 'Terima Piutang', ['class' => 'col-lg-2']) !!}
                {!! Form::number('terima_piutang', null, ['class' => 'col-lg-2 form-control form-control-sm',
                'required']) !!}
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

    <div id="panel-piutang">
        <div class="d-flex bg-light">
            <p class="card-header col-lg">Daftar Piutang</p>
            <i class="card-header fas fa-sync text-success" style="cursor: pointer;" title="Refresh Page" onclick="location.reload()"></i>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Nomor Jual</th>
                            <th>Kode Anggota</th>
                            <th>Nama Anggota</th>
                            <th>Jumlah Piutang</th>
                            <th>Terima Piutang</th>
                            <th>Sisa Piutang</th>
                            @if (auth()->user()->jabatan != 'Kanit')
                            <th>Opsi</th>
                            @endif
                        </tr>
                    </thead>
                    @if (count($piutang) > 0)
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach ($piutang as $data)
                        @if ($data->status == 0)
                        <tr>
                            <th class="align-middle text-center">
                                <p>{{$i++}}</p>
                            </th>
                            <td class="align-middle text-center">{{$data->nomor_jual}}</td>
                            <td class="align-middle text-center">{{$data->kode_anggota}}</td>
                            <td class="align-middle">{{$data->nama_anggota}}</td>
                            <td class="align-middle text-center">{{$data->jumlah_piutang}}</td>
                            <td class="align-middle text-center">{{$data->jumlah_terima_piutang}}</td>
                            <td class="align-middle text-center">{{$data->sisa_piutang}}</td>
                            @if (auth()->user()->jabatan != 'Kanit')
                            <td class="align-middle text-center">
                                <a id=<?php echo "bayar-" . $data->id ?> class="btn btn-sm btn-success"
                                    onclick="bayar(<?php echo $data->id ?>)">Terima</a>
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

<div id="panel-daftar-terima-piutang" class="d-none">
    <div class="card m-6">
        <p class="card-header bg-light">Daftar Terima Piutang</p>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-terima-piutang" class="table table-striped table-bordered table-hover nowrap">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Nomor</th>
                            <th>Nomor Jurnal</th>
                            <th>Tanggal</th>
                            <th>Terima Piutang</th>
                            <th>Sisa Piutang</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody id="table-data-terima-piutang">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/base-url.js') }}"></script>
<script src="{{ asset('js/nomor-terima-piutang.js') }}"></script>
<script>
var nomor_jual;

function panel_piutang() {
    $('#panel-piutang').removeClass('d-none');
    $('#btn-piutang').addClass('btn-primary');
    $('#btn-terima-piutang').removeClass('btn-primary');
    $('#panel-terima-piutang').addClass('d-none');
    $('#panel-daftar-terima-piutang').addClass('d-none');
}

function panel_terima_piutang() {
    $('#panel-piutang').addClass('d-none');
    $('#btn-piutang').removeClass('btn-primary');
    $('#btn-terima-piutang').addClass('btn-primary');
    $('#panel-terima-piutang').removeClass('d-none');
    $('#panel-daftar-terima-piutang').removeClass('d-none');
}

function bayar($id) {
    $('[name="nomor_jual"]').val($id);
    tampil_daftar();
    panel_terima_piutang();
}

function terima_piutang() {
    $.ajax({
        url: '/toko/transaksi/piutang/store',
        type: 'POST',
        data: {
            nomor: $('[name="nomor"]').val(),
            nomor_jurnal: $('[name="nomor_jurnal"]').val(),
            tanggal: $('[name="tanggal"]').val(),
            id_piutang: $('[name="nomor_jual"]').val(),
            sisa_piutang: $('[name="sisa_piutang"]').val() - $('[name="terima_piutang"]').val(),
            terima_piutang: $('[name="terima_piutang"]').val(),
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
        url: '/toko/transaksi/piutang/delete/' + $id,
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
    nomor_jual = $('[name="nomor_jual"]').val();

    $.ajax({
        url: '/toko/transaksi/piutang/' + nomor_jual,
        type: 'GET',
        success: function(response) {
            if (response.code == 200) {
                $('#table-data-terima-piutang').empty();

                $sisa_piutang = response.anggota_piutang.jumlah_piutang;

                $.each(response.terima_piutang, function(index, value) {
                    $sisa_piutang -= value.terima_piutang;

                    $('#table-data-terima-piutang').append('<tr>' +
                        '<th class="align-middle text-center">' + i++ + '</th>' +
                        '<td class="align-middle text-center">' + value.nomor + '</td>' +
                        '<td class="align-middle">' + value.nomor_jurnal + '</td>' +
                        '<td class="align-middle text-center">' + value.tanggal + '</td>' +
                        '<td class="align-middle text-center">' + value.terima_piutang +
                        '</td>' +
                        '<td class="align-middle text-center">' + $sisa_piutang +
                        '</td>' +
                        '<td class="align-middle text-center"><a id="hapus-' + value
                        .id + '" class="btn btn-sm btn-danger" onclick="show_popup_hapus(' +
                        value
                        .id + ')"><i class="fas fa-trash-alt p-1"></i> Hapus</a>' +
                        '</td>' +
                        '</tr>')
                })

                if (response.anggota_piutang) {
                    $('[name="kode_anggota"]').empty();
                    $('[name="nama_anggota"]').empty();
                    $('[name="sisa_piutang"]').empty();
                    $('[name="kode_anggota"]').append('<option value=' + response.anggota_piutang
                        .id_anggota + '>' + response.anggota_piutang.kode_anggota + '</option>');
                    $('[name="nama_anggota"]').append('<option value=' + response.anggota_piutang
                        .id_anggota + '>' + response.anggota_piutang.nama_anggota + '</option>');
                    $('[name="sisa_piutang"]').val(response.anggota_piutang.sisa_piutang);
                    $('[name="terima_piutang"]').val("");
                }
                $('#table-terima-piutang').DataTable();
                return false;
            }
        }
    });
}

function batal_transaksi() {
    $.ajax({
        url: '/toko/transaksi/piutang/cancel/',
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
    $('#table-penjualan').DataTable();

    $('[name="terima_piutang"]').change(function() {
        if (parseInt($(this).val()) < 0) {
            $(this).val(0);
        }
        
        if (parseInt($(this).val()) > parseInt($('[name="sisa_piutang"]').val())) {
            $(this).val($('[name="sisa_piutang"]').val());
        }
    });

    $('#bayar').click(function() {
        var allFilled = false;
        var skip = false;

        document.getElementById('panel-terima-piutang').querySelectorAll('[required]').forEach(function(
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
            terima_piutang();
        }
    });

    $('#batal').click(function() {
        batal_transaksi();
    });
});
</script>
@endsection