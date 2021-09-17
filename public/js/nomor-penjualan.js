$('[name="tanggal"]').change(function () {
    var typeAnggota = $('[name="kode_anggota"]').val();

    var temp = $('[name="tanggal"]').val();
    var split = temp.split('-');
    var tanggal = split[1] + split[2] + split[0].substring(2, 4);
    if (tanggal != '') {
        $.get(`${base_url}api/nomor-penjualan/${tanggal}/${typeAnggota}`, function (data, status) {
            $('[name="nomor"]').val(data);
        });
        $.get(`${base_url}api/nomor-jurnal-penjualan/${tanggal}/${typeAnggota}`, function (data, status) {
            $('[name="nomor_jurnal"]').val(data);
        });
    } else {
        $('[name="nomor"]').val("");
        $('[name="nomor_jurnal"]').val("");
    }
});

$('[name="kode_anggota"]').change(function () {
    var typeAnggota = $(this).val();

    var temp = $('[name="tanggal"]').val();
    var split = temp.split('-');
    var tanggal = split[1] + split[2] + split[0].substring(2, 4);
    if (tanggal != '') {
        $.get(`${base_url}api/nomor-penjualan/${tanggal}/${typeAnggota}`, function (data, status) {
            $('[name="nomor"]').val(data);
        });
        $.get(`${base_url}api/nomor-jurnal-penjualan/${tanggal}/${typeAnggota}`, function (data, status) {
            $('[name="nomor_jurnal"]').val(data);
        });
    } else {
        $('[name="nomor"]').val("");
        $('[name="nomor_jurnal"]').val("");
    }
});

$('[name="nama_anggota"]').change(function () {
    var typeAnggota = $(this).val();

    var temp = $('[name="tanggal"]').val();
    var split = temp.split('-');
    var tanggal = split[1] + split[2] + split[0].substring(2, 4);
    if (tanggal != '') {
        $.get(`${base_url}api/nomor-penjualan/${tanggal}/${typeAnggota}`, function (data, status) {
            $('[name="nomor"]').val(data);
        });
        $.get(`${base_url}api/nomor-jurnal-penjualan/${tanggal}/${typeAnggota}`, function (data, status) {
            $('[name="nomor_jurnal"]').val(data);
        });
    } else {
        $('[name="nomor"]').val("");
        $('[name="nomor_jurnal"]').val("");
    }
});

$(document).ready(function() {
    var type = $('[name="kode_anggota"]').val();

    var temp = $('[name="tanggal"]').val();
    var split = temp.split('-');
    var tanggal = split[1] + split[2] + split[0].substring(2, 4);
    if (tanggal != '') {
        $.get(`${base_url}api/nomor-penjualan/${tanggal}/${typeAnggota}`, function (data, status) {
            $('[name="nomor"]').val(data);
            tampil_daftar();
        });
        $.get(`${base_url}api/nomor-jurnal-penjualan/${tanggal}/${typeAnggota}`, function (data, status) {
            $('[name="nomor_jurnal"]').val(data);
        });
    } else {
        $('[name="nomor"]').val("");
        $('[name="nomor_jurnal"]').val("");
    }
});