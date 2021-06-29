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
<a href="{{url('toko/master/akun/create')}}" class="btn btn-sm btn-success mt-4 ms-4 pe-4"><i class="fas fa-plus"></i><b>Tambah</b></a>
<div class="card m-6">
    <p class="card-header bg-light">Daftar Akun</p>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-striped table-bordered table-hover nowrap">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th class="w-20">Opsi</th>
                    </tr>
                </thead>
                @if (count($akun) > 0)
                <tbody>
                    @php
                    $i = 1;
                    $total_debit = 0;
                    $total_kredit = 0;
                    @endphp
                    @foreach ($akun as $data)
                    <tr>
                        <th class="align-middle text-center">
                            <div>{{$i++}}</div>
                        </th>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_kode', $data->kode, ['class' => 'd-none', 'id' =>
                            'edit-kode-'.$data->id]) !!}
                            <div id="kode-<?php echo $data->id ?>">{{$data->kode}}</div>
                        </td>
                        <td class="align-middle">
                            {!! Form::text('edit_nama', $data->nama, ['class' => 'd-none', 'id' =>
                            'edit-nama-'.$data->id]) !!}
                            <div id="nama-<?php echo $data->id ?>">{{$data->nama}}</div>
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_debit', $data->debit, ['class' => 'd-none', 'id' =>
                            'edit-debit-'.$data->id]) !!}
                            <div id="debit-<?php echo $data->id ?>">{{$data->debit}}</div>
                            @php
                            $total_debit += $data->debit;
                            @endphp
                        </td>
                        <td class="align-middle text-center">
                            {!! Form::text('edit_kredit', $data->kredit, ['class' => 'd-none', 'id' =>
                            'edit-kredit-'.$data->id]) !!}
                            <div id="kredit-<?php echo $data->id ?>">{{$data->kredit}}</div>
                            @php
                            $total_kredit += $data->kredit;
                            @endphp
                        </td>
                        <td class="align-middle text-center">
                            <a id=<?php echo "edit-" . $data->id ?> class="w-48 btn btn-sm btn-warning"
                                onclick="edit(<?php echo $data->id ?>)"><i class="fas fa-edit p-1"></i> Edit</a>
                            <a id=<?php echo "terapkan-" . $data->id ?> class="w-48 btn btn-sm btn-warning d-none"
                                onclick="terapkan(<?php echo $data->id ?>)">Terapkan</a>
                            <a id=<?php echo "hapus-" . $data->id ?> class="w-52 btn btn-sm btn-danger"
                                onclick="show_popup_hapus(<?php echo $data->id ?>)"><i class="fas fa-trash-alt p-1"></i> Hapus</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
                    <tbody>
                        <tr>
                            <td colspan="3" class="align-middle text-center">
                                <div class="fs-5 fw-bold">Balance</div>
                            </td>
                            <td class="d-none"></td>
                            <td class="d-none"></td>
                            <td class="align-middle text-center">
                                <div id="total-debit">{{$total_debit}}</div>
                            </td>
                            <td class="align-middle text-center">
                                <div id="total-kredit">{{$total_kredit}}</div>
                            </td>
                            <td class="fw-bold align-middle text-center">
                                @if ($total_debit > $total_kredit)
                                    <div>Total Debit > Total Kredit</div>
                                @elseif ($total_debit < $total_kredit)
                                    <div>Total Debit < Total Kredit</div>
                                @else
                                    <div>Total Debit = Total Kredit</div>
                                @endif
                            </td>
                        </tr>
                    </tbody>
            </table>
            <p class="fw-bold p-2">Jumlah Data Akun : {{count($akun)}}</p>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function edit(id) {
    $("#kode-" + id).addClass("d-none");
    $("#edit-kode-" + id).removeClass("d-none");
    $("#nama-" + id).addClass("d-none");
    $("#edit-nama-" + id).removeClass("d-none");
    $("#debit-" + id).addClass("d-none");
    $("#edit-debit-" + id).removeClass("d-none");
    $("#kredit-" + id).addClass("d-none");
    $("#edit-kredit-" + id).removeClass("d-none");
    $("#edit-" + id).addClass("d-none");
    $("#terapkan-" + id).removeClass("d-none");
}

function terapkan(id) {
    $.ajax({
        url: '/toko/master/akun/update/',
        type: 'POST',
        data: {
            id: id,
            kode: $('#edit-kode-' + id).val(),
            nama: $('#edit-nama-' + id).val(),
            debit: $('#edit-debit-' + id).val(),
            kredit: $('#edit-kredit-' + id).val()
        },
        success: function(response) {
            if (response.code == 200) {
                $("#kode-" + id).removeClass("d-none");
                $("#edit-kode-" + id).addClass("d-none");
                $("#nama-" + id).removeClass("d-none");
                $("#edit-nama-" + id).addClass("d-none");
                $("#debit-" + id).removeClass("d-none");
                $("#edit-debit-" + id).addClass("d-none");
                $("#kredit-" + id).removeClass("d-none");
                $("#edit-kredit-" + id).addClass("d-none");
                $("#edit-" + id).removeClass("d-none");
                $("#terapkan-" + id).addClass("d-none");

                $("#kode-" + id).html(response.akun.kode);
                $("#nama-" + id).html(response.akun.nama);
                $("#debit-" + id).html(response.akun.debit);
                $("#kredit-" + id).html(response.akun.kredit);
            }
        }
    });
}

function hapus(id) {
    $.ajax({
        url: '/toko/master/akun/delete',
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