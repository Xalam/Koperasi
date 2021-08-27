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
    <p class="card-header bg-light">Tambah Penjualan</p>
    <div id="form" class="card-body">
        {!! Form::open(['url' => '/toko/transaksi/penjualan/jual']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal', $cur_date, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
            {!! Form::label(null, 'No. Jual', ['class' => 'offset-lg-2 col-lg-2']) !!}
            {!! Form::text('nomor', null, ['class' => 'col-lg-3 form-control form-control-sm', 'readonly']) !!}
            {!! Form::text('nomor_jurnal', null, ['class' => 'd-none', 'readonly']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Anggota', ['class' => 'col-lg-2 fw-bold']) !!}
            <div class="w-100"></div>
            {!! Form::label(null, 'Kode Anggota', ['class' => 'col-lg-2']) !!}
            {!! Form::select('kode_anggota', $kode_anggota, null, ['class' => 'col-lg-4 form-select
            form-select-sm', 'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Anggota', ['class' => 'col-lg-2']) !!}
            {!! Form::select('nama_anggota', $anggota, null, ['class' => 'col-lg-9 form-select form-select-sm',
            'required']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Alamat', ['class' => 'col-lg-2']) !!}
            {!! Form::text('alamat', null, ['class' => 'col-lg-9 form-control form-control-sm', 'required', 'readonly'])
            !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nomor Telepon', ['class' => 'col-lg-2']) !!}
            {!! Form::number('telepon', null, ['class' => 'col-lg-2 form-control form-control-sm', 'required',
            'readonly']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nomor WA', ['class' => 'col-lg-2']) !!}
            {!! Form::number('wa', null, ['class' => 'col-lg-2 form-control form-control-sm', 'required', 'readonly'])
            !!}
        </div>
        <br>
        <div class="row-lg mb-2">
            <div class="col-lg-6">
                {!! Form::label(null, 'Barang', ['class' => 'fw-bold']) !!}
                <div class="w-100"></div>
                {!! Form::label(null, 'Kode') !!}
                {!! Form::select('kode_barang', $kode_barang, null, ['class' => 'col-lg-12 form-select
                form-select-sm', 'required']) !!}
            </div>
            <div class="col-lg-6">
                {!! Form::label(null, 'Pembayaran', ['class' => 'fw-bold']) !!}
                <div class="row-lg text-center">
                    {!! Form::number('jumlah_harga', null, ['class' => 'd-none']) !!}
                    <h3 id="jumlah-harga" class="color-danger col-lg-12">Rp. 0,-</h3>
                    {!! Form::text('jumlah_kembalian', null, ['class' => 'd-none']) !!}
                    <h3 id="jumlah-kembalian" class="color-success col-lg-6 d-none">Rp. 0,-</h3>
                </div>
            </div>
        </div>
        <div class="offset-lg-6 row-lg align-item-center mb-2">
            <div class="col-lg-6">
                {!! Form::label(null, 'Jenis Pembayaran') !!}
                {!! Form::select('pembayaran', $pembayaran, [], ['class' =>
                'col-lg-12 form-select form-select-sm']) !!}
            </div>
            <div class="col-lg-6">
                {!! Form::label('limit_toko', 'Sisa Limit Toko', ['class' => 'col-lg-12']) !!}
                {!! Form::number('limit_toko', 0, ['class' => 'col-lg-12 form-control form-control-sm', 'readonly']) !!}
                {!! Form::label('dibayar', 'Dibayar', ['class' => 'col-lg-12 d-none']) !!}
                {!! Form::number('jumlah_bayar', 0, ['class' => 'col-lg-12 form-control form-control-sm d-none']) !!}
            </div>
        </div>
        <hr class="mt-2 mb-2">
        <div class="row-lg align-item-center mb-2">
            <div class="col-lg-4">
                {!! Form::label(null, 'Nama', null) !!}
                {!! Form::select('nama_barang', $barang, [], ['class' => 'col-lg-12 form-select form-select-sm',
                'required']) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label(null, 'Sisa Stok', null) !!}
                {!! Form::number('stok', 0, ['class' => 'col-lg-12 form-control form-control-sm', 'required',
                'readonly']) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label(null, 'Harga Satuan', null) !!}
                {!! Form::number('harga_satuan', 0, ['class' => 'col-lg-12 form-control form-control-sm', 'required',
                'readonly']) !!}
                {!! Form::number('harga_grosir', 0, ['class' => 'd-none', 'readonly']) !!}
                {!! Form::number('harga_normal', 0, ['class' => 'd-none', 'readonly']) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label(null, 'Jumlah', null) !!}
                {!! Form::number('jumlah', 0, ['class' => 'col-lg-12 form-control form-control-sm', 'required',
                'readonly']) !!}
                {!! Form::number('jumlah_grosir', 0, ['class' => 'd-none', 'readonly']) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label(null, 'Total Harga', null) !!}
                {!! Form::number('total_harga', 0, ['class' => 'col-lg-12 form-control form-control-sm', 'required',
                'readonly']) !!}
            </div>
        </div>
        <div class="d-grid gap-2">
            <a id="tambah" class="btn btn-sm btn-primary">Tambah</a>
        </div>
    </div>
</div>

<div class="card m-6">
    <p class="card-header bg-light">Daftar Penjualan</p>
    <div class="card-body">
        <div class="table-responsive mb-2">
            <table id="table-penjualan" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
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
                <tbody id="table-data-penjualan">
                </tbody>
            </table>
        </div>
        <div class="d-grid gap-2 mb-2">
            {!! Form::submit('Jual', ['class' => 'btn btn-success btn-sm']) !!}
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
            window.open("{{url('toko/transaksi/penjualan/nota')}}");
            window.location = "{{url('toko/transaksi/penjualan')}}";
        } else {
            window.location = "{{url('toko/transaksi/penjualan')}}";
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
        window.location.reload();
    }, 2000);
});
</script>
@endif

<script src="{{ asset('js/base-url.js') }}"></script>
<script src="{{ asset('js/data-barang-jual.js') }}"></script>
<script src="{{ asset('js/data-anggota.js') }}"></script>
<script src="{{ asset('js/nomor-penjualan.js') }}"></script>
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
                $('[name="jumlah"]').val(1);
                $('[name="total_harga"]').val(parseInt($('[name="harga_satuan"]').val()) * parseInt($(
                    '[name="jumlah"]').val()));
            }
        }
    });
}

function hapus_daftar($id) {
    $.ajax({
        url: '/toko/transaksi/penjualan/delete/' + $id,
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
        url: '/toko/transaksi/penjualan/' + nomor,
        type: 'GET',
        success: function(response) {
            if (response.code == 200) {
                $('#table-data-penjualan').empty();
                $.each(response.barang_penjualan, function(index, value) {
                    var harga = (value.jumlah_barang >= value.minimal_grosir) ? value.harga_grosir :
                        value.harga_jual;
                    $('#table-data-penjualan').append('<tr>' +
                        '<th class="align-middle text-center">' + i++ + '</th>' +
                        '<td class="align-middle text-center">' + value.kode_barang + '</td>' +
                        '<td class="align-middle">' + value.nama_barang + '</td>' +
                        '<td class="align-middle text-center">' + harga.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00' + '</td>' +
                        '<td class="align-middle text-center">' + value.jumlah_barang +
                        '</td>' +
                        '<td class="align-middle text-center">' + (harga * value.jumlah_barang).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00' +
                        '</td>' +
                        '<td class="align-middle text-center"><a id="hapus-' + value
                        .id + '" class="btn btn-sm btn-danger" onclick="show_popup_hapus(' +
                        value
                        .id + ')"><i class="fas fa-trash-alt p-1"></i> Hapus</a>' +
                        '</td>' +
                        '</tr>')

                    jumlah_harga += value.total_harga;
                })

                $('[name="nomor"]').attr('readonly', true);

                if (response.barang_penjualan.length > 0) {
                    $('[name="tanggal"]').attr('readonly', true);
                    $('[name="kode_anggota"]').attr('readonly', true);
                    $('[name="nama_anggota"]').attr('readonly', true);
                } else {
                    $('[name="kode_anggota"]').removeAttr('readonly');
                    $('[name="nama_anggota"]').removeAttr('readonly');
                }

                $('#jumlah-harga').html("Rp. " + jumlah_harga.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00');
                $('[name="jumlah_harga"]').val(jumlah_harga);

                if (response.anggota_penjualan) {
                    $('[name="jumlah_bayar"]').val(response.anggota_penjualan.jumlah_bayar);
                    $('#jumlah-kembalian').html("Rp. " + response.anggota_penjualan.jumlah_kembalian.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00');
                    $('[name="jumlah_kembalian"]').val(response.anggota_penjualan.jumlah_kembalian);

                    $('[name="tanggal"]').val(response.anggota_penjualan.tanggal);
                    $('[name="kode_anggota"]').val(response.anggota_penjualan.id_anggota);
                    $('[name="nama_anggota"]').val(response.anggota_penjualan.id_anggota);
                    $('[name="alamat"]').val(response.anggota_penjualan.alamat);
                    $('[name="telepon"]').val(response.anggota_penjualan.telepon);
                    $('[name="wa"]').val(response.anggota_penjualan.wa);

                    $('[name="pembayaran"]').val(response.anggota_penjualan.pembayaran);
                }

                if ($('[name="pembayaran"]').val() == 1) {
                    $('[for="dibayar"]').addClass('d-none');
                    $('[name="jumlah_bayar"]').addClass('d-none');
                } else {
                    $('[for="dibayar"]').removeClass('d-none');
                    $('[name="jumlah_bayar"]').removeClass('d-none');
                }
                $('#table-penjualan').DataTable();
                kalkulasi_pembayaran();
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

function kalkulasi_pembayaran() {
    var kembalian = $('[name="jumlah_bayar"]').val() - jumlah_harga
    $('#jumlah-kembalian').html("Rp. " + kembalian.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00');
    $('[name="jumlah_kembalian"]').val($('[name="jumlah_bayar"]').val() - jumlah_harga);

    activeBeli();
}

function activeBeli() {
    if (parseInt($('[name="jumlah_harga"]').val()) > 0) {
        if (parseInt($('[name="pembayaran"]').val()) == 1) {
            if (parseInt($('[name="jumlah_harga"]').val()) <= parseInt($('[name="limit_toko"]').val())) {
                $('[type="submit"]').removeAttr('disabled');
            } else {
                $('[type="submit"]').attr('disabled', true);
            }
        } else {
            if (parseInt($('[name="jumlah_bayar"]').val()) >= parseInt($('[name="jumlah_harga"]').val()) && parseInt($(
                    '[name="jumlah_bayar"]').val()) > 0) {
                $('[type="submit"]').removeAttr('disabled');
            } else {
                $('[type="submit"]').attr('disabled', true);
            }
        }
    } else {
        $('[type="submit"]').attr('disabled', true);
    }
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

    $('[name="jumlah"]').change(function() {
        if (parseInt($(this).val()) >= parseInt($('[name="jumlah_grosir"]').val())) {
            $('[name="harga_satuan"]').val($('[name="harga_grosir"]').val());
        } else {
            $('[name="harga_satuan"]').val($('[name="harga_normal"]').val());
        }
    });

    onScan.attachTo(document, {
        onScan: function(key) {
            $.ajax({
                url: '/toko/transaksi/penjualan/scan',
                type: 'POST',
                data: {
                    nomor: $('[name="nomor"]').val(),
                    kode_barang: key,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.code == 200) {
                        $('[name="kode_barang"] option:selected').removeAttr(
                            'selected');
                        $('[name="kode_barang"] option:contains(' + key + ')').attr(
                            'selected', 'selected');
                        $('[name="nama_barang"]').val($(
                                '[name="kode_barang"] option:contains(' + key + ')')
                            .val());

                        const id_barang = $('[name="kode_barang"]').val();
                        
                        if (id_barang != '') {
                            $('[name="nama_barang"]').val(id_barang);
                            $.get(`${base_url}api/data-barang/${id_barang}`, function (data, status) {
                                data.forEach(x => {
                                    $('[name="stok"]').val(x.stok_etalase);
                                    $('[name="jumlah"]').val(1);
                                    $('[name="harga_satuan"]').val(x.harga_jual);
                                    $('[name="jumlah_grosir"]').val(x.minimal_grosir);
                                    $('[name="harga_grosir"]').val(x.harga_grosir);
                                    $('[name="harga_normal"]').val(x.harga_jual);
                                    $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $('[name="jumlah"]').val());
                                    $('[name="harga_satuan"]').removeAttr('readonly');
                                    $('[name="jumlah"]').removeAttr('readonly');
                                });
                            });
                        } else {
                            $('[name="nama_barang"]').val("");
                            $('[name="stok"]').val(0);
                            $('[name="harga_satuan"]').val(0);
                            $('[name="jumlah"]').val(0);
                            $('[name="harga_grosir"]').val(0);
                            $('[name="harga_normal"]').val(0);
                            $('[name="jumlah_grosir"]').val(0);
                            $('[name="total_harga"]').val(0);

                            $('[name="harga_satuan"]').attr('readonly', true);
                            $('[name="jumlah"]').attr('readonly', true);
                        }

                        tampil_daftar();
                    }
                }
            });
        }
    });

    $('#cek-nomor').click(function() {
        tampil_daftar();
    });

    $('[name="pembayaran"]').parent().click(function() {
        if (parseInt($('[name="kode_anggota"]').children('option:selected').val()) == 0) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'middle',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: 'warning',
                title: 'Metode Pembayaran',
                text: 'Kredit hanya dapat digunakan oleh anggota'
            });
        }
    });

    $('[name="pembayaran"]').change(function() {
        if ($(this).val() == 1) {
            $('[for="dibayar"]').addClass('d-none');
            $('[for="limit_toko"]').removeClass('d-none');
            $('[name="limit_toko"]').attr('required', true);
            $('[name="limit_toko"]').removeClass('d-none');
            $('[name="jumlah_bayar"]').addClass('d-none');
            $('[name="jumlah_bayar"]').removeAttr('required');
            $('#jumlah-kembalian').addClass('d-none');
            $('#jumlah-harga').removeClass('col-lg-6');
            $('#jumlah-harga').addClass('col-lg-12');
        } else {
            $('[for="dibayar"]').removeClass('d-none');
            $('[for="limit_toko"]').addClass('d-none');
            $('[name="limit_toko"]').addClass('d-none');
            $('[name="limit_toko"]').removeAttr('required');
            $('[name="jumlah_bayar"]').removeClass('d-none');
            $('[name="jumlah_bayar"]').attr('required', true);
            $('#jumlah-kembalian').removeClass('d-none');
            $('#jumlah-harga').removeClass('col-lg-12');
            $('#jumlah-harga').addClass('col-lg-6');
        }

        activeBeli();
    });

    $('[name="jumlah"]').change(function() {
        if ($(this).val() < 1) {
            $(this).val(1);
        }

        if (parseInt($(this).val()) > parseInt($('[name="stok"]').val())) {
            $(this).val($('[name="stok"]').val());
        }
        $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $(this).val());
    });

    $('[name="harga_satuan"]').change(function() {
        $('[name="total_harga"]').val($('[name="jumlah"]').val() * $(this).val());
    });

    $('[name="jumlah_bayar"]').change(function() {
        kalkulasi_pembayaran();
    });

    $('[name="kode_anggota"]').change(function() {
        if (parseInt($(this).children('option:selected').val()) == 0) {
            $('[name="alamat"]').val('-');
            $('[name="telepon"]').val('0');
            $('[name="wa"]').val('0');
            $('[name="pembayaran"]').attr('readonly', true);
            $('[name="pembayaran"]').val(2);
            $('[name="pembayaran"]').children('option:selected').val(2);
            $('[for="dibayar"]').removeClass('d-none');
            $('[name="jumlah_bayar"]').removeClass('d-none');
            $('[name="jumlah_bayar"]').attr('required', true);
            $('#jumlah-kembalian').addClass('d-none');
            $('#jumlah-harga').removeClass('col-lg-6');
            $('#jumlah-harga').addClass('col-lg-12');
        } else {
            $('[name="pembayaran"]').removeAttr('readonly');
        }
    });

    $('[name="nama_anggota"]').change(function() {
        if (parseInt($(this).children('option:selected').val()) == 0) {
            $('[name="alamat"]').val('-');
            $('[name="telepon"]').val('0');
            $('[name="wa"]').val('0');
            $('[name="pembayaran"]').val(2);
            $('[name="pembayaran"]').children('option:selected').val(2);
            $('[for="dibayar"]').removeAttr('d-none');
            $('[name="jumlah_bayar"]').removeAttr('d-none');
            $('[name="jumlah_bayar"]').attr('required', true);
            $('#jumlah-kembalian').addClass('d-none');
            $('#jumlah-harga').removeClass('col-lg-6');
            $('#jumlah-harga').addClass('col-lg-12');
        } else {
            $('[name="pembayaran"]').removeAttr('readonly');
        }
    });

    $('#tambah').click(function() {
        if (parseInt($('[name="jumlah_harga"]').val()) > parseInt($('[name="limit_toko"]').val())) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'middle',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: 'warning',
                title: 'Pembayaran Kredit',
                text: 'Sisa limit toko tidak mencukupi.'
            });
        } else {
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
        }

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