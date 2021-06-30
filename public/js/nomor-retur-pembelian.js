$('[name="tanggal"]').change(function () {
    var temp = $('[name="tanggal"]').val();
    var split = temp.split('-');
    var tanggal = split[1] + split[2] + split[0].substring(2, 4);
    if (tanggal != '') {
        $.get(`${base_url}api/nomor-retur-pembelian/${tanggal}`, function (data, status) {
            $('[name="nomor"]').val(data);
        });
        $.get(`${base_url}api/nomor-jurnal-retur-pembelian/${tanggal}`, function (data, status) {
            $('[name="nomor_jurnal"]').val(data);
        });
    } else {
        $('[name="nomor"]').val("");
        $('[name="nomor_jurnal"]').val("");
    }
});

$(document).ready(function() {
    var temp = $('[name="tanggal"]').val();
    var split = temp.split('-');
    var tanggal = split[1] + split[2] + split[0].substring(2, 4);
    if (tanggal != '') {
        $.get(`${base_url}api/nomor-retur-pembelian/${tanggal}`, function (data, status) {
            $('[name="nomor"]').val(data);
            tampil_daftar();
        });
        $.get(`${base_url}api/nomor-jurnal-retur-pembelian/${tanggal}`, function (data, status) {
            $('[name="nomor_jurnal"]').val(data);
        });
    } else {
        $('[name="nomor"]').val("");
        $('[name="nomor_jurnal"]').val("");
    }
});