$('[name="kode_pelanggan"]').change(function () {
    const id_pelanggan = $('[name="kode_pelanggan"]').val();
    if (id_pelanggan != '') {
        $('[name="nama_pelanggan"]').val(id_pelanggan);
        $.get(`${base_url}api/data-pelanggan/${id_pelanggan}`, function (data, status) {
            data.forEach(x => {
                $('[name="alamat"]').val(x.alamat);
                $('[name="telepon"]').val(x.telepon);
            });
        });
    } else {
        $('[name="nama_pelanggan"]').val("");
        $('[name="alamat"]').val("");
        $('[name="telepon"]').val("");
    }
});

$('[name="nama_pelanggan"]').change(function () {
    const id_pelanggan = $('[name="nama_pelanggan"]').val();
    if (id_pelanggan != '') {
        $('[name="kode_pelanggan"]').val(id_pelanggan);
        $.get(`${base_url}api/data-pelanggan/${id_pelanggan}`, function (data, status) {
            data.forEach(x => {
                $('[name="alamat"]').val(x.alamat);
                $('[name="telepon"]').val(x.telepon);
            });
        });
    } else {
        $('[name="kode_pelanggan"]').val("");
        $('[name="alamat"]').val("");
        $('[name="telepon"]').val("");
    }
});