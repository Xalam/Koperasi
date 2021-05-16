$('[name="kode_supplier"]').change(function () {
    const id_supplier = $('[name="kode_supplier"]').val();
    if (id_supplier != '') {
        $('[name="nama_supplier"]').val(id_supplier);
        $.get(`${base_url}api/data-supplier/${id_supplier}`, function (data, status) {
            data.forEach(x => {
                $('[name="alamat"]').val(x.alamat);
                $('[name="telepon"]').val(x.telepon);
            });
        });
    } else {
        $('[name="nama_supplier"]').val("");
        $('[name="alamat"]').val("");
        $('[name="telepon"]').val("");
    }
});

$('[name="nama_supplier"]').change(function () {
    const id_supplier = $('[name="nama_supplier"]').val();
    if (id_supplier != '') {
        $('[name="kode_supplier"]').val(id_supplier);
        $.get(`${base_url}api/data-supplier/${id_supplier}`, function (data, status) {
            data.forEach(x => {
                $('[name="alamat"]').val(x.alamat);
                $('[name="telepon"]').val(x.telepon);
            });
        });
    } else {
        $('[name="kode_supplier"]').val("");
        $('[name="alamat"]').val("");
        $('[name="telepon"]').val("");
    }
});