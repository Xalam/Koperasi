$('[name="tanggal"]').change(function () {
    var temp = $('[name="tanggal"]').val();
    var split = temp.split('-');
    var tanggal = split[1] + split[2] + split[0].substring(2, 4);
    if (tanggal != '') {
        $.get(`${base_url}api/nomor-penjualan/${tanggal}`, function (data, status) {
            $('[name="nomor"]').val(data);
        });
    } else {
        $('[name="nomor"]').val("");
    }
});