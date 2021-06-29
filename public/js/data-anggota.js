$('[name="kode_anggota"]').change(function () {
    const id_anggota = $('[name="kode_anggota"]').val();
    if (id_anggota != '') {
        $('[name="nama_anggota"]').val(id_anggota);
        $.get(`${base_url}api/data-anggota/${id_anggota}`, function (data, status) {
            data.forEach(x => {
                $('[name="alamat"]').val(x.alamat);
                $('[name="telepon"]').val(x.telepon);
                $('[name="wa"]').val(x.wa);
            });
        });
    } else {
        $('[name="nama_anggota"]').val("");
        $('[name="alamat"]').val("");
        $('[name="telepon"]').val("");
        $('[name="wa"]').val("");
    }
});

$('[name="nama_anggota"]').change(function () {
    const id_anggota = $('[name="nama_anggota"]').val();
    if (id_anggota != '') {
        $('[name="kode_anggota"]').val(id_anggota);
        $.get(`${base_url}api/data-anggota/${id_anggota}`, function (data, status) {
            data.forEach(x => {
                $('[name="alamat"]').val(x.alamat);
                $('[name="telepon"]').val(x.telepon);
                $('[name="wa"]').val(x.wa);
            });
        });
    } else {
        $('[name="kode_anggota"]').val("");
        $('[name="alamat"]').val("");
        $('[name="telepon"]').val("");
        $('[name="wa"]').val("");
    }
});