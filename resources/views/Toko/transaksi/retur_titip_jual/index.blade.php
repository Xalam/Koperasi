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
    <p class="card-header bg-light">Tambah Pengembalian Titip Jual</p>
    <div id="form" class="card-body">
        {!! Form::open(['url' => '/toko/transaksi/retur-titip-jual/retur']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal', $cur_date, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
            {!! Form::label(null, 'No. Retur', ['class' => 'offset-lg-2 col-lg-2']) !!}
            {!! Form::text('nomor', null, ['class' => 'col-lg-3 form-control form-control-sm', 'readonly']) !!}
            {!! Form::text('nomor_jurnal', null, ['class' => 'd-none', 'readonly']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Pengembalian Titip Jual', ['class' => 'col-lg-2 fw-bold']) !!}
            <div class="w-100"></div>
            {!! Form::label(null, 'No. Titip Jual', ['class' => 'col-lg-2']) !!}
            {!! Form::select('id_titip_jual', $nomor_titip_jual, null, ['class' => 'col-lg-4 form-select form-select-sm',
            'required']) !!}
            {!! Form::text('nomor_titip_jual', null, ['class' => 'd-none', 'readonly']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Supplier', ['class' => 'col-lg-2']) !!}
            {!! Form::select('nama_supplier', [], null, ['class' => 'col-lg-9 form-control form-control-sm', 'readonly'
            => 'readonly', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Kode Barang', ['class' => 'col-lg-2']) !!}
            {!! Form::select('kode_barang', [], null, ['class' => 'col-lg-4 form-select form-select-sm', 'required',
            'disabled'])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Barang', ['class' => 'col-lg-2']) !!}
            {!! Form::select('nama_barang', [], null, ['class' => 'col-lg-9 form-select form-select-sm', 'required',
            'disabled'])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Harga Beli', ['class' => 'col-lg-2']) !!}
            {!! Form::number('harga_beli', 0, ['class' => 'col-lg-2 form-control form-control-sm', 'readonly' =>
            'readonly', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jumlah Retur', ['class' => 'col-lg-2']) !!}
            {!! Form::number('jumlah', 0, ['class' => 'col-lg-1 form-control form-control-sm', 'required', 'readonly'])
            !!}
            {!! Form::label(null, 'Maksimal Retur : 0', ['class' => 'offset-lg-1 col-lg-auto', 'id' =>
            'text-maksimal-retur']) !!}
            {!! Form::number('maksimal_retur', null, ['class' => 'col-lg-1 form-control form-control-sm d-none']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Jumlah Harga', ['class' => 'col-lg-2 d-none']) !!}
            {!! Form::text('jumlah_harga', null, ['class' => 'col-lg-2 form-control form-control-sm d-none', 'required',
            'readonly']) !!}
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
            <table id="table-retur-titip-jual" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Jumlah Retur</th>
                        <th>Harga Total</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody id="table-data-retur-titip-jual">
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
@if(Session::get('success'))
<script>
$(document).ready(function() {
    Swal.fire({
        icon: 'success',
        title: '<b>Proses Transaksi</b>',
        text: `{{Session::get('success')}}`,
        position: 'middle',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Cetak Nota',
        cancelButtonText: 'Tutup',
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("{{url('toko/transaksi/retur-titip-jual/nota')}}");
            window.location = "{{url('toko/transaksi/retur-titip-jual')}}";
        } else {
            window.location = "{{url('toko/transaksi/retur-titip-jual')}}";
        }
    });
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
        title: 'Proses Transaksi',
        text: `{{Session::get('failed')}}`
    });
    setTimeout(function() {
        window.location = "/toko/transaksi/retur-titip-jual";
    }, 2000);
});
</script>
@endif

<script src="{{ asset('js/base-url.js') }}"></script>
<script src="{{ asset('js/data-retur-titip-jual-barang.js') }}"></script>
<script src="{{ asset('js/data-retur-titip-jual-supplier.js') }}"></script>
<script src="{{ asset('js/nomor-retur-titip-jual.js') }}"></script>
<script>
var nomor;
var jumlah_harga;

function tambah_daftar() {
    $.ajax({
        url: '/toko/transaksi/retur-titip-jual/store',
        type: 'POST',
        data: {
            nomor: $('[name="nomor"]').val(),
            nomor_titip_jual: $('[name="id_titip_jual"] option:selected').text(),
            id_barang: $('[name="kode_barang"]').val(),
            harga_beli: $('[name="harga_beli"]').val(),
            jumlah: $('[name="jumlah"]').val(),
            total_harga: $('[name="harga_beli"]').val() * $('[name="jumlah"]').val()
        },
        success: function(response) {
            if (response.code == 200) {
                tampil_daftar();

                const id_barang = $('[name="kode_barang"]').val();
                const nomor_titip_jual = $('[name="id_titip_jual"] option:selected').text();
                if (id_barang != '') {
                    $('[name="nama_barang"]').val(id_barang);
                    $.get(`${base_url}api/data-retur-titip-jual-detail-barang/${nomor_titip_jual}/${id_barang}`,
                        function(data, status) {
                            $('[name="harga_beli"]').val(data.harga);
                            $('#text-maksimal-retur').text('Maksimal Retur : ' + parseInt(data
                                .jumlah_beli - data.jumlah_retur));
                            $('[name="maksimal_retur"]').val(parseInt(data.jumlah_beli - data
                                .jumlah_retur));

                            if (parseInt(data.jumlah_beli - data.jumlah_retur) <= 0) {
                                $('[name="jumlah"]').attr('readonly', true);
                                $('[name="jumlah"]').val(0);
                            } else {
                                $('[name="jumlah"]').removeAttr('readonly');
                                $('[name="jumlah"]').val(1);
                            }
                        });
                } else {
                    $('[name="nama_barang"]').val("");
                    $('[name="harga_beli"]').val("");
                }
            }
        }
    });
}

function hapus_daftar($id) {
    $.ajax({
        url: '/toko/transaksi/retur-titip-jual/delete/' + $id,
        type: 'POST',
        success: function(response) {
            if (response.code == 200) {
                close_popup_hapus();
                tampil_daftar();

                const id_barang = $('[name="kode_barang"]').val();
                const nomor_titip_jual = $('[name="id_titip_jual"] option:selected').text();
                if (id_barang != '') {
                    $('[name="nama_barang"]').val(id_barang);
                    $.get(`${base_url}api/data-retur-titip-jual-detail-barang/${nomor_titip_jual}/${id_barang}`,
                        function(data, status) {
                            $('[name="harga_beli"]').val(data.harga);
                            $('#text-maksimal-retur').text('Maksimal Retur : ' + parseInt(data
                                .jumlah_beli - data.jumlah_retur));
                            $('[name="maksimal_retur"]').val(parseInt(data.jumlah_beli - data
                                .jumlah_retur));
                        });
                } else {
                    $('[name="nama_barang"]').val("");
                    $('[name="harga_beli"]').val("");
                }

                if (parseInt(data.jumlah_beli - data.jumlah_retur) <= 0) {
                    $('[name="jumlah"]').attr('readonly', true);
                    $('[name="jumlah"]').val(0);
                } else {
                    $('[name="jumlah"]').removeAttr('readonly');
                    $('[name="jumlah"]').val(1);
                }
            }
        }
    });
}


function tampil_daftar() {
    var i = 1;
    nomor = $('[name="nomor"]').val();
    jumlah_harga = 0;

    $.ajax({
        url: '/toko/transaksi/retur-titip-jual/' + nomor,
        type: 'GET',
        success: function(response) {
            if (response.code == 200) {
                $('#table-data-retur-titip-jual').empty();
                $.each(response.barang_retur, function(index, value) {
                    $('#table-data-retur-titip-jual').append('<tr>' +
                        '<th class="align-middle text-center">' + i++ + '</th>' +
                        '<td class="align-middle text-center">' + value.kode_barang + '</td>' +
                        '<td class="align-middle">' + value.nama_barang + '</td>' +
                        '<td class="align-middle text-center">' + value.harga_beli.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00' + '</td>' +
                        '<td class="align-middle text-center">' + value.jumlah_retur +
                        '</td>' +
                        '<td class="align-middle text-center">' + value.total_harga.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00' + '</td>' +
                        '<td class="align-middle text-center"><a id="hapus-' + value
                        .id + '" class="btn btn-small btn-danger" onclick="show_popup_hapus(' +
                        value
                        .id + ')"><i class="fas fa-trash-alt p-1"></i> Hapus</a>' +
                        '</td>' +
                        '</tr>')

                    jumlah_harga += value.total_harga;
                })

                $('[name="jumlah_harga"]').val(jumlah_harga);

                $('[name="nomor"]').attr('readonly', true);

                if (response.barang_retur.length > 0) {
                    $('[name="tanggal"]').attr('readonly', true);
                    $('[name="id_titip_jual"]').attr('readonly', true);
                } else {
                    $('[name="id_titip_jual"]').removeAttr('readonly');
                    $('[name="tanggal"]').removeAttr('readonly');
                }

                if (response.supplier_retur) {
                    $('[name="tanggal"]').val(response.supplier_retur.tanggal);
                    $('[name="id_titip_jual"]').val(response.supplier_retur.id_titip_jual);
                    $('[name="nama_supplier"]').append('<option value=' + response.supplier_retur
                        .id_supplier + '>' + response.supplier_retur.nama_supplier + '</option>');

                    data_retur_barang();

                    $('[name="harga_beli"]').val('');
                    $('[name="jumlah"]').val('');
                }
                $('#table-retur-titip-jual').DataTable();
                return false;
            }
        }
    });
}

function batal_transaksi() {
    $.ajax({
        url: '/toko/transaksi/retur-titip-jual/cancel/',
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
    $('#table-retur-titip-jual').DataTable();
    tampil_daftar();

    setNomorBeli();

    $('[name="id_titip_jual"]').change(function() {
        setNomorBeli();
    });

    function setNomorBeli() {
        $('[name="nomor_titip_jual"]').val($('[name="id_beli"] option:selected').text());
    }

    $('[name="jumlah"]').change(function() {
        if ($(this).val() < 1) {
            $(this).val(1);
        }

        if (parseInt($(this).val()) > parseInt($('[name="maksimal_retur"]').val())) {
            $(this).val($('[name="maksimal_retur"]').val());
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