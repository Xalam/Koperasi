$('[name="kode_supplier"]').change(function () {
    const id_supplier = $('[name="kode_supplier"]').val();
    if (id_supplier != '') {
        $('[name="nama_supplier"]').val(id_supplier);
        $.get(`${base_url}api/data-supplier/${id_supplier}`, function (data, status) {
            data.forEach(x => {
                $('[name="alamat"]').val(x.alamat);
                $('[name="telepon"]').val(x.telepon);
                $('[name="wa"]').val(x.wa);
                $('[name="jarak"]').val(x.jarak);
                $('[name="email"]').val(x.email);
                $('[name="keterangan"]').val(x.keterangan);
                $('[name="tempo"]').val(x.tempo);
            });
        });
    } else {
        $('[name="nama_supplier"]').val("");
        $('[name="alamat"]').val("");
        $('[name="telepon"]').val("");
        $('[name="wa"]').val("");
        $('[name="jarak"]').val("");
        $('[name="email"]').val("");
        $('[name="keterangan"]').val("");
        $('[name="tempo"]').val("");
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
                $('[name="wa"]').val(x.wa);
                $('[name="jarak"]').val(x.jarak);
                $('[name="email"]').val(x.email);
                $('[name="keterangan"]').val(x.keterangan);
                $('[name="tempo"]').val(x.tempo);
            });
        });
    } else {
        $('[name="kode_supplier"]').val("");
        $('[name="alamat"]').val("");
        $('[name="telepon"]').val("");
        $('[name="wa"]').val("");
        $('[name="jarak"]').val("");
        $('[name="email"]').val("");
        $('[name="keterangan"]').val("");
        $('[name="tempo"]').val("");
    }
});