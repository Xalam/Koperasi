$('[name="kode_akun"]').change(function () {
    const id_akun = $('[name="kode_akun"]').val();
    if (id_akun != '') {
        $('[name="nama_akun"]').val(id_akun);
    } else {
        $('[name="nama_akun"]').val("");
    }
});

$('[name="nama_akun"]').change(function () {
    const id_akun = $('[name="nama_akun"]').val();
    if (id_akun != '') {
        $('[name="kode_akun"]').val(id_akun);
    } else {
        $('[name="kode_akun"]').val("");
    }
});