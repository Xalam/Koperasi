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
    <p class="card-header bg-light">Tambah Pembelian</p>
    <div id="form" class="card-body">
        {!! Form::open(['url' => '/toko/transaksi/pembelian/beli']) !!}
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Tanggal', ['class' => 'col-lg-2']) !!}
            {!! Form::date('tanggal', $cur_date, ['class' => 'col-lg-2 form-control form-control-sm', 'required']) !!}
            {!! Form::label(null, 'No. Beli', ['class' => 'offset-lg-2 col-lg-2']) !!}
            {!! Form::text('nomor', null, ['class' => 'col-lg-3 form-control form-control-sm', 'readonly']) !!}
            {!! Form::text('nomor_jurnal', null, ['class' => 'd-none', 'readonly']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Supplier', ['class' => 'col-lg-2 fw-bold']) !!}
            <div class="w-100"></div>
            {!! Form::label(null, 'Kode Supplier', ['class' => 'col-lg-2']) !!}
            {!! Form::select('kode_supplier', $kode_supplier, null, ['class' => 'col-lg-4 form-select
            form-select-sm', 'required']) !!}
            {!! Form::label(null, 'Tempo', ['class' => 'offset-lg-1 col-lg-1']) !!}
            {!! Form::number('tempo', null, ['class' => 'col-lg-1 form-control form-control-sm', 'readonly']) !!}
        </div>
        <div class="row-lg align-item-center mb-2">
            {!! Form::label(null, 'Nama Supplier', ['class' => 'col-lg-2']) !!}
            {!! Form::select('nama_supplier', $supplier, null, ['class' => 'col-lg-9 form-select form-select-sm',
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
                form-select-sm', 'required', 'disabled']) !!}
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
                {!! Form::label('dibayar', 'Dibayar', ['class' => 'col-lg-12 d-none']) !!}
                {!! Form::number('jumlah_bayar', 0, ['class' => 'col-lg-12 form-control form-control-sm d-none']) !!}
            </div>
        </div>
        <hr class="mt-2 mb-2">
        <div class="row-lg align-item-center mb-2">
            <div class="col-lg-4">
                {!! Form::label(null, 'Nama', null) !!}
                {!! Form::select('nama_barang', $barang, [], ['class' => 'col-lg-12 form-select form-select-sm',
                'required', 'disabled']) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label(null, 'Sisa Stok', null) !!}
                {!! Form::number('stok', 0, ['class' => 'col-lg-12 form-control form-control-sm', 'required',
                'readonly']) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label(null, 'Harga Satuan', null) !!}
                {!! Form::number('harga_satuan', 0, ['class' => 'col-lg-12 form-control form-control-sm', 'required',
                'readonly'])
                !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label(null, 'Jumlah', null) !!}
                {!! Form::number('jumlah', 0, ['class' => 'col-lg-12 form-control form-control-sm', 'required',
                'readonly']) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label(null, 'Total Harga', null) !!}
                {!! Form::number('total_harga', 0, ['class' => 'col-lg-12 form-control form-control-sm', 'required',
                'readonly'])
                !!}
            </div>
        </div>
        <div class="d-grid gap-2">
            <a id="tambah" class="btn btn-sm btn-primary">Tambah</a>
        </div>
    </div>
</div>

<div class="card m-6">
    <p class="card-header bg-light">Daftar Pembelian</p>
    <div class="card-body">
        <div class="table-responsive mb-2">
            <table id="table-pembelian" class="table table-striped table-bordered table-hover nowrap">
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
                <tbody id="table-data-pembelian">
                </tbody>
            </table>
        </div>
        <div class="d-grid gap-2 mb-2">
            {!! Form::submit('Beli', ['class' => 'btn btn-sm btn-success', 'disabled']) !!}
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
            window.open("{{url('toko/transaksi/pembelian/nota')}}");
            window.location = "{{url('toko/transaksi/pembelian')}}";
        } else {
            window.location = "{{url('toko/transaksi/pembelian')}}";
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
        icon: 'success',
        title: 'Proses Transaksi',
        text: `{{Session::get('failed')}}`
    });
    setTimeout(function() {
        window.location = "/toko/transaksi/pembelian";
    }, 2000);
});
</script>
@endif

<script src="{{ asset('js/base-url.js') }}"></script>
<script src="{{ asset('js/data-barang.js') }}"></script>
<script src="{{ asset('js/data-supplier.js') }}"></script>
<script src="{{ asset('js/nomor-pembelian.js') }}"></script>
<script>
var nomor;
var jumlah_harga;

function tambah_daftar() {
    $.ajax({
        url: '/toko/transaksi/pembelian/store',
        type: 'POST',
        data: {
            nomor: $('[name="nomor"]').val(),
            id_barang: $('[name="kode_barang"]').val(),
            jumlah: $('[name="jumlah"]').val(),
            harga_satuan: $('[name="harga_satuan"]').val(),
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

function hapus_daftar($id) {
    $.ajax({
        url: '/toko/transaksi/pembelian/delete/' + $id,
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
        url: '/toko/transaksi/pembelian/' + nomor,
        type: 'GET',
        success: function(response) {
            if (response.code == 200) {
                $('#table-data-pembelian').empty();
                $.each(response.barang_pembelian, function(index, value) {
                    $('#table-data-pembelian').append('<tr>' +
                        '<th class="align-middle text-center">' + i++ + '</th>' +
                        '<td class="align-middle text-center">' + value.kode_barang + '</td>' +
                        '<td class="align-middle">' + value.nama_barang + '</td>' +
                        '<td class="align-middle text-center">' + value.harga_satuan.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00' + '</td>' +
                        '<td class="align-middle text-center">' + value.jumlah_barang +
                        '</td>' +
                        '<td class="align-middle text-center">' + value.total_harga.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00' + '</td>' +
                        '<td class="align-middle text-center"><a id="hapus-' + value
                        .id + '" class="btn btn-sm btn-danger" onclick="show_popup_hapus(' +
                        value
                        .id + ')"><i class="fas fa-trash-alt p-1"></i> Hapus</a>' +
                        '</td>' +
                        '</tr>')

                    jumlah_harga += value.total_harga;
                })

                $('[name="nomor"]').attr('readonly', true);

                if (response.barang_pembelian.length > 0) {
                    $('[name="tanggal"]').attr('readonly', true);
                    $('[name="kode_supplier"]').attr('readonly', true);
                    $('[name="nama_supplier"]').attr('readonly', true);
                } else {
                    $('[name="kode_supplier"]').removeAttr('readonly');
                    $('[name="nama_supplier"]').removeAttr('readonly');
                }

                $('#jumlah-harga').html("Rp. " + jumlah_harga.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00' + ",-");
                $('[name="jumlah_harga"]').val(jumlah_harga);

                if (response.supplier_pembelian) {
                    $('[name="jumlah_bayar"]').val(response.supplier_pembelian.jumlah_bayar);
                    $('#jumlah-kembalian').html("Rp. " + response.supplier_pembelian.jumlah_kembalian.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00' +
                        ",-");
                    $('[name="jumlah_kembalian"]').val(response.supplier_pembelian.jumlah_kembalian);

                    $('[name="tanggal"]').val(response.supplier_pembelian.tanggal);
                    $('[name="kode_supplier"]').val(response.supplier_pembelian.id_supplier);
                    $('[name="nama_supplier"]').val(response.supplier_pembelian.id_supplier);
                    $('[name="alamat"]').val(response.supplier_pembelian.alamat);
                    $('[name="telepon"]').val(response.supplier_pembelian.telepon);
                    $('[name="wa"]').val(response.supplier_pembelian.wa);

                    $('[name="pembayaran"]').val(response.supplier_pembelian.pembayaran);
                }

                if ($('[name="pembayaran"]').val() == 1) {
                    $('[for="dibayar"]').addClass('d-none');
                    $('[name="jumlah_bayar"]').addClass('d-none');
                } else {
                    $('[for="dibayar"]').removeClass('d-none');
                    $('[name="jumlah_bayar"]').removeClass('d-none');
                }
                $('#table-pembelian').DataTable();
                kalkulasi_pembayaran();
                return false;
            }
        }
    });
}

function batal_transaksi() {
    $.ajax({
        url: '/toko/transaksi/pembelian/cancel/',
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
    var kembalian = $('[name="jumlah_bayar"]').val() - jumlah_harga;
    $('#jumlah-kembalian').html("Rp. " + kembalian.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00');
    $('[name="jumlah_kembalian"]').val($('[name="jumlah_bayar"]').val() - jumlah_harga);

    if (parseInt($('[name="pembayaran"]').val()) == 2) {
        if (parseInt($('[name="jumlah_bayar"]').val()) >= jumlah_harga) {
            $('[type="submit"]').removeAttr('disabled');
        } else {
            $('[type="submit"]').attr('disabled', 'disabled');
        }
    } else {
        $('[type="submit"]').removeAttr('disabled');
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
    $('#table-pembayaran').DataTable();

    onScan.attachTo(document, {
        onScan: function(key) {
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
                $.get(`${base_url}api/data-barang/${id_barang}`, function(data, status) {
                    data.forEach(x => {
                        $('[name="stok"]').val(x.stok_etalase);
                        $('[name="jumlah"]').val(1);
                        $('[name="total_harga"]').val($('[name="harga_satuan"]')
                            .val() * $('[name="jumlah"]').val());
                        $('[name="jumlah"]').removeAttr('readonly');
                    });
                });
            } else {
                $('[name="nama_barang"]').val("");
                $('[name="stok"]').val(0);
                $('[name="jumlah"]').val(0);
                $('[name="jumlah"]').attr('readonly', true);
            }
        }
    });

    $('[name="pembayaran"]').change(function() {
        if ($(this).val() == 1) {
            $('[for="dibayar"]').addClass('d-none');
            $('[name="jumlah_bayar"]').addClass('d-none');
            $('[name="jumlah_bayar"]').removeAttr('required');
        } else {
            $('[for="dibayar"]').removeClass('d-none');
            $('[name="jumlah_bayar"]').removeClass('d-none');
            $('[name="jumlah_bayar"]').attr('required', true);
        }

        kalkulasi_pembayaran();
    });

    $('[name="jumlah"]').change(function() {
        if ($(this).val() < 1) {
            $(this).val(1);
        }

        $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $(this).val());
    });

    $('[name="harga_satuan"]').change(function() {
        $('[name="total_harga"]').val($('[name="jumlah"]').val() * $(this).val());
    });

    $('[name="kode_barang"]').change(function() {
        $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $('[name="jumlah"]').val());
    });

    $('[name="jumlah_bayar"]').change(function() {
        kalkulasi_pembayaran();
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