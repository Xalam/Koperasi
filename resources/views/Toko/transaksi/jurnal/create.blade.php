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
    <p class="card-header bg-light">Tambah Jurnal Umum</p>
    <div id="form" class="card-body">
        {!! Form::open(['url' => '/toko/transaksi/jurnal-umum/save']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal', $cur_date, ['class' => 'col-lg-2 form-control form-control-sm', 'required'])
            !!}
            {!! Form::label(null, 'Nomor', ['class' => 'offset-lg-2 col-lg-2']) !!}
            {!! Form::text('nomor', null, ['class' => 'col-lg-3 form-control form-control-sm',
            'required', 'readonly'])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Akun', ['class' => 'col-lg-2 fw-bold']) !!}
            <div class="w-100"></div>
            {!! Form::label(null, 'Kode Akun', ['class' => 'col-lg-2']) !!}
            {!! Form::select('kode_akun', $kode_akun, null, ['class' => 'col-lg-4 form-select form-select-sm',
            'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Akun', ['class' => 'col-lg-2']) !!}
            {!! Form::select('nama_akun', $nama_akun, null, ['class' => 'col-lg-9 form-select form-select-sm',
            'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Debit', ['class' => 'col-lg-2']) !!}
            {!! Form::number('debit', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Kredit', ['class' => 'col-lg-2']) !!}
            {!! Form::number('kredit', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Keterangan', ['class' => 'col-lg-2']) !!}
            {!! Form::text('keterangan', null, ['class' => 'col-lg-9 form-control form-control-sm', 'required']) !!}
        </div>
        <br>
        <div class="d-grid gap-2 mb-2">
            <a id="tambah" class="btn btn-sm btn-primary">Tambah</a>
        </div>
    </div>
</div>

<div class="card m-6">
    <p class="card-header bg-light">Daftar Jurnal Umum</p>
    <div class="card-body">
        <div class="table-responsive mb-2">
            <table id="table-jurnal-umum" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody id="table-data-jurnal-umum">
                </tbody>
            </table>
        </div>
        <div class="d-grid gap-2 mb-2">
            {!! Form::submit('Simpan', ['class' => 'btn btn-sm btn-success']) !!}
        </div>
        <div class="d-grid gap-2">
            <a id="batal" class="btn btn-sm btn-danger">Batal</a>
        </div>
        {!! Form::close() !!}
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
        window.location = "/toko/transaksi/jurnal";
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
        title: 'Proses Penyimpanan',
        text: `{{Session::get('failed')}}`
    });
    setTimeout(function() {
        window.location = "/toko/transaksi/jurnal-umum/create";
    }, 2000);
});
</script>
@endif

<script src="{{ asset('js/base-url.js') }}"></script>
<script src="{{ asset('js/data-akun.js') }}"></script>
<script src="{{ asset('js/nomor-jurnal-umum.js') }}"></script>
<script>
var nomor;

function tambah_daftar() {
    $.ajax({
        url: '/toko/transaksi/jurnal-umum/store',
        type: 'POST',
        data: {
            nomor: $('[name="nomor"]').val(),
            id_akun: $('[name="nama_akun"]').val(),
            debit: $('[name="debit"]').val(),
            kredit: $('[name="kredit"]').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.code == 200) {
                tampil_daftar();
            }
        }
    });
}

function hapus_daftar($id) {
    $.ajax({
        url: '/toko/transaksi/jurnal-umum/delete/' + $id,
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
    nomor = $('[name="nomor"]').val();
    jumlah_harga = 0;

    $.ajax({
        url: '/toko/transaksi/jurnal-umum/' + nomor,
        type: 'GET',
        success: function(response) {
            if (response.code == 200) {
                $('#table-data-jurnal-umum').empty();
                $.each(response.jurnal_umum, function(index, value) {
                    $('#table-data-jurnal-umum').append('<tr>' +
                        '<th class="align-middle text-center">' + i++ + '</th>' +
                        '<td class="align-middle text-center">' + value.kode_akun + '</td>' +
                        '<td class="align-middle">' + value.nama_akun + '</td>' +
                        '<td class="align-middle text-center">' + value.debit.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00</td>' +
                        '<td class="align-middle text-center">' + value.kredit.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") +
                        ',00</td>' +
                        '<td class="align-middle text-center"><a id="hapus-' + value
                        .id + '" class="btn btn-sm btn-danger" onclick="show_popup_hapus(' +
                        value
                        .id + ')"><i class="fas fa-trash-alt p-1"></i> Hapus</a>' +
                        '</td>' +
                        '</tr>')
                })

                if (response.jurnal_umum.length > 0) {
                    $('[name="tanggal"]').attr('readonly', true);
                } else {
                    $('[name="tanggal"]').removeAttr('readonly');
                }

                $('#table-jurnal-umum').DataTable();
                return false;
            }
        }
    });
}

function batal_transaksi() {
    $.ajax({
        url: '/toko/transaksi/jurnal-umum/cancel/',
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
    $('#table-jurnal-umum').DataTable();

    $('[name="debit"]').change(function() {
        if (parseInt($(this).val()) < 0) {
            $(this).val(0);
        }
    });

    $('[name="kredit"]').change(function() {
        if (parseInt($(this).val()) < 0) {
            $(this).val(0);
        }
    });

    $('#tambah').click(function() {
        var allFilled = false;
        var skip = false;

        document.getElementById('form').querySelectorAll('[required]').forEach(function(
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
            tambah_daftar();
        }
    });

    $('#batal').click(function() {
        batal_transaksi();
    });
});
</script>
@endsection