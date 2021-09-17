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
    <div class="card-header bg-light d-flex flex-row">
        <p class="col-form-label me-4">Daftar Barang</p> {!! Form::select('filter', ['barang' => 'Per Nama Barang',
        'supplier' => 'Detail Persediaan'], null, ['class' => 'col-lg-2 form-select form-select-sm']) !!}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data-per-supplier" class="d-none table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>HPP</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Stok Etalase</th>
                        <th>Stok Gudang</th>
                        <th>Satuan</th>
                        <th>Tanggal Beli</th>
                        <th>Expired</th>
                        <th>Kode Supplier</th>
                        <th>Nama Supplier</th>
                        @if (auth()->user()->jabatan != 'Kasir')
                        <th>Transfer Barang</th>
                        <th class="w-20">Opsi</th>
                        @endif
                    </tr>
                </thead>
                @if (count($per_supplier) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($per_supplier as $data)
                    <tr id="row-<?php echo $data->id ?>">
                        <th class="align-middle text-center">
                            <div>{{$i++}}</div>
                        </th>
                        <td class="align-middle text-center">
                            <div id="kode-<?php echo $data->id ?>">{{$data->kode}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_nama', $data->nama, ['class' => 'd-none form-control form-control-sm',
                            'id' =>
                            'edit-nama-'.$data->id, 'required']) !!}
                            <div id="nama-<?php echo $data->id ?>">{{$data->nama}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_hpp', $data->hpp, ['class' => 'd-none form-control form-control-sm',
                            'id' =>
                            'edit-hpp-'.$data->id, 'required']) !!}
                            <div id="hpp-<?php echo $data->id ?>">{{number_format($data->hpp, 2, ',', '.')}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_harga_jual', $data->harga_jual, ['class' => 'd-none form-control
                            form-control-sm', 'id' =>
                            'edit-harga-jual-'.$data->id]) !!}
                            <div id="harga-jual-<?php echo $data->id ?>">{{number_format($data->harga_jual, 2, ',', '.')}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <div id="stok-<?php echo $data->id ?>">{{$data->stok_etalase + $data->stok_gudang}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::number('edit_stok_etalase', $data->stok_etalase, ['class' => 'd-none form-control
                            form-control-sm', 'id' =>
                            'edit-stok-etalase-'.$data->id, 'required']) !!}
                            <div id="stok-etalase-<?php echo $data->id ?>">{{$data->stok_etalase}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::number('edit_stok_gudang', $data->stok_gudang, ['class' => 'd-none form-control
                            form-control-sm', 'id' =>
                            'edit-stok-gudang-'.$data->id, 'required']) !!}
                            <div id="stok-gudang-<?php echo $data->id ?>">{{$data->stok_gudang}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::select('edit_satuan', ['Pack' => 'Pack', 'Pcs' => 'Pcs', 'Kg' => 'Kg', 'Liter' => 'Liter'], $data->satuan, ['class' => 'd-none form-control
                            form-control-sm', 'id' =>
                            'edit-satuan-'.$data->id, 'required']) !!}
                            <div id="satuan-<?php echo $data->id ?>">{{$data->satuan}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <div id="tanggal-beli-<?php echo $data->id ?>">{{$data->tanggal_beli}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <div id="expired-<?php echo $data->id ?>">{{'01/' . $data->expired_bulan . '/20' . $data->expired_tahun}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <div id="kode-supplier-<?php echo $data->id ?>">{{$data->kode_supplier}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="nama-supplier-<?php echo $data->id ?>">{{$data->nama_supplier}}</div>
                        </td>
                        @if (auth()->user()->jabatan != 'Kasir')
                        <td class="align-middle text-center">
                            <a id=<?php echo "transfer-" . $data->id ?> class="btn btn-sm btn-info"
                                onclick="transfer(<?php echo $data->id ?>)"><i class="fas fa-exchange-alt p-1"></i> Transfer</a>
                        </td>
                        <td class="align-middle text-center">
                            <a id=<?php echo "edit-" . $data->id ?> class="w-48 btn btn-sm btn-warning"
                                onclick="edit(<?php echo $data->id ?>)"><i class="fas fa-edit p-1"></i> Edit</a>
                            <a id=<?php echo "terapkan-" . $data->id ?> class="w-48 btn btn-sm btn-warning d-none"
                                onclick="terapkan(<?php echo $data->id ?>)">Terapkan</a>
                            <a id=<?php echo "hapus-" . $data->id ?> class="w-50 btn btn-sm btn-danger"
                                onclick="show_popup_hapus(<?php echo $data->id ?>)"><i class="fas fa-trash-alt p-1"></i>
                                Hapus</a>
                            <a id=<?php echo "batal-" . $data->id ?> class="w-50 btn btn-sm btn-danger d-none"
                                onclick="batal(<?php echo $data->id ?>)">Batal</a>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>

            <table id="table-data-per-barang" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Stok Etalase</th>
                        <th>Stok Gudang</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                @if (count($per_barang) > 0)
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($per_barang as $data)
                    <tr id="barang-row-<?php echo $data->id ?>">
                        <th class="align-middle text-center">
                            <div>{{$i++}}</div>
                        </th>
                        <td class="align-middle text-center">
                            <div id="barang-kode-<?php echo $data->id ?>">{{$data->kode}}</div>
                        </td>
                        <td class="align-middle">
                            <div id="barang-nama-<?php echo $data->id ?>">{{$data->nama}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <div id="barang-stok-<?php echo $data->id ?>">{{$data->stok_etalase + $data->stok_gudang}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <div id="barang-stok-etalase-<?php echo $data->id ?>">{{$data->stok_etalase}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <div id="barang-stok-gudang-<?php echo $data->id ?>">{{$data->stok_gudang}}</div>
                        </td>
                        <td class="align-middle text-center">
                            <div id="barang-satuan-<?php echo $data->id ?>">{{$data->satuan}}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('#table-data-per-barang').DataTable();

    $('[name="filter"]').change(function() {
        if ($(this).val() == "barang") {
            $('#table-data-per-barang').DataTable();
            $('#table-data-per-barang').removeClass('d-none');
            $('#table-data-per-supplier').addClass('d-none');
            $('#table-data-per-supplier').DataTable().destroy();
        } else {
            $('#table-data-per-supplier').DataTable();
            $('#table-data-per-supplier').removeClass('d-none');
            $('#table-data-per-barang').addClass('d-none');
            $('#table-data-per-barang').DataTable().destroy();
        }
    });
});

function transfer(id) {
    Swal.fire({
        title: '<b>Transfer Barang</b>',
        html: 
            '<label for="jumlah_transfer">Jumlah barang yang akan ditransfer</label>' +
            '<input type="number" class="form-control form-control-sm" name="jumlah_transfer" onkeyup="formatNumber(event)" onchange="minMaxValue(`jumlah_transfer`)" value="0">',
        position: 'center',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Transfer',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/toko/master/barang/update',
                type: 'POST',
                data: {
                    id: id,
                    stok_etalase: parseInt($('#stok-etalase-' + id).text()) + parseInt($('[name="jumlah_transfer"]').val()),
                    stok_gudang: parseInt($('#stok-gudang-' + id).text()) - parseInt($('[name="jumlah_transfer"]').val())
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
                            title: 'Transfer Barang',
                            text: 'Transfer Barang Berhasil'
                        });
                        setTimeout(function() {
                            window.location = "{{url('toko/transaksi/persediaan')}}";
                        }, 2000);
                    }
                },
                error: function(error) {
                    alert(error.responseText);
                }
            });
        }
    });
}

function edit(id) {
    $("#nama-" + id).addClass("d-none");
    $("#edit-nama-" + id).removeClass("d-none");
    $("#hpp-" + id).addClass("d-none");
    $("#edit-hpp-" + id).removeClass("d-none");
    $("#harga-jual-" + id).addClass("d-none");
    $("#edit-harga-jual-" + id).removeClass("d-none");
    $("#stok-etalase-" + id).addClass("d-none");
    $("#edit-stok-etalase-" + id).removeClass("d-none");
    $("#stok-gudang-" + id).addClass("d-none");
    $("#edit-stok-gudang-" + id).removeClass("d-none");
    $("#satuan-" + id).addClass("d-none");
    $("#edit-satuan-" + id).removeClass("d-none");
    $("#edit-" + id).addClass("d-none");
    $("#hapus-" + id).addClass("d-none");
    $("#terapkan-" + id).removeClass("d-none");
    $("#batal-" + id).removeClass("d-none");
}

function batal(id) {
    $("#nama-" + id).removeClass("d-none");
    $("#edit-nama-" + id).val($("#nama-" + id).text());
    $("#edit-nama-" + id).addClass("d-none");
    $("#hpp-" + id).removeClass("d-none");
    $("#edit-hpp-" + id).val($("#hpp-" + id).text());
    $("#edit-hpp-" + id).addClass("d-none");
    $("#harga-jual-" + id).removeClass("d-none");
    $("#edit-harga-jual-" + id).val($("#harga-jual-" + id).text());
    $("#edit-harga-jual-" + id).addClass("d-none");
    $("#stok-etalase-" + id).removeClass("d-none");
    $("#edit-stok-etalase-" + id).val($("#stok-etalase-" + id).text());
    $("#edit-stok-etalase-" + id).addClass("d-none");
    $("#stok-gudang-" + id).removeClass("d-none");
    $("#edit-stok-gudang-" + id).val($("#stok-gudang-" + id).text());
    $("#edit-stok-gudang-" + id).addClass("d-none");
    $("#satuan-" + id).removeClass("d-none");
    $("#edit-satuan-" + id).val($("#satuan-" + id).text());
    $("#edit-satuan-" + id).addClass("d-none");
    $("#edit-" + id).removeClass("d-none");
    $("#hapus-" + id).removeClass("d-none");
    $("#terapkan-" + id).addClass("d-none");
    $("#batal-" + id).addClass("d-none");
}

function terapkan(id) {
    var allFilled = false;
    var index = 0;

    $('#row-' + id).find('input').each(function() {
        if (!$(this).val()) {
            $(this).popover({
                content: "Tidak boleh kosong",
                placement: "top",
                trigger: "focus"
            }).popover('show');
            allFilled = false;
        } else {
            if (index == 0) {
                allFilled = true;
            } else {
                if (allFilled == true) {
                    allFilled = true;
                } else {
                    return false;
                }
            }
        }

        index++;
    });

    if (allFilled) {
        $.ajax({
            url: '/toko/master/barang/update',
            type: 'POST',
            data: {
                id: id,
                nama: $('#edit-nama-' + id).val(),
                hpp: $('#edit-hpp-' + id).val(),
                harga_jual: $('#edit-harga-jual-' + id).val(),
                stok_etalase: $('#edit-stok-etalase-' + id).val(),
                stok_gudang: $('#edit-stok-gudang-' + id).val(),
                satuan: $('#edit-satuan-' + id).val()
            },
            success: function(response) {
                if (response.code == 200) {
                    $("#nama-" + id).removeClass("d-none");
                    $("#edit-nama-" + id).addClass("d-none");
                    $("#hpp-" + id).removeClass("d-none");
                    $("#edit-hpp-" + id).addClass("d-none");
                    $("#harga-jual-" + id).removeClass("d-none");
                    $("#edit-harga-jual-" + id).addClass("d-none");
                    $("#stok-etalase-" + id).removeClass("d-none");
                    $("#edit-stok-etalase-" + id).addClass("d-none");
                    $("#stok-gudang-" + id).removeClass("d-none");
                    $("#edit-stok-gudang-" + id).addClass("d-none");
                    $("#satuan-" + id).removeClass("d-none");
                    $("#edit-satuan-" + id).addClass("d-none");
                    $("#edit-" + id).removeClass("d-none");
                    $("#hapus-" + id).removeClass("d-none");
                    $("#terapkan-" + id).addClass("d-none");
                    $("#batal-" + id).addClass("d-none");

                    $("#nama-" + id).html(response.barang.nama);
                    $("#hpp-" + id).html(response.barang.hpp.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00');
                    $("#harga-jual-" + id).html(response.barang.harga_jual.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + ',00');
                    $("#stok-" + id).html(response.barang.stok_etalase + response.barang.stok_gudang);
                    $("#stok-etalase-" + id).html(response.barang.stok_etalase);
                    $("#stok-gudang-" + id).html(response.barang.stok_gudang);
                    $("#satuan-" + id).html(response.barang.satuan);
                }
            }
        });
    }
}

function hapus(id) {
    $.ajax({
        url: '/toko/master/barang/delete',
        type: 'POST',
        data: {
            id: id
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
        '<a class="btn btn-block btn-sm btn-success mt-1" onclick="hapus(' + id + ')">Hapus</a>' +
        '<a class="btn btn-block btn-sm btn-danger mt-1" onclick="close_popup_hapus()">Batal</a>' +
        '</div>')
}

function close_popup_hapus() {
    $("#popup-delete").addClass("d-none");
}
</script>
@endsection