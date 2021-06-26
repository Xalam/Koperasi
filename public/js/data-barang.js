$('[name="kode_barang"]').change(function () {
    const id_barang = $('[name="kode_barang"]').val();
    if (id_barang != '') {
        $('[name="nama_barang"]').val(id_barang);
        $.get(`${base_url}api/data-barang/${id_barang}`, function (data, status) {
            data.forEach(x => {
                $('[name="stok"]').val(x.stok);
            });
        });
    } else {
        $('[name="nama_barang"]').val("");
        $('[name="stok"]').val(0);
    }
});

$('[name="nama_barang"]').change(function () {
    const id_barang = $('[name="nama_barang"]').val();
    if (id_barang != '') {
        $('[name="kode_barang"]').val(id_barang);
        $.get(`${base_url}api/data-barang/${id_barang}`, function (data, status) {
            data.forEach(x => {
                $('[name="stok"]').val(x.stok);
            });
        });
    } else {
        $('[name="kode_barang"]').val("");
        $('[name="stok"]').val(0);
    }
});