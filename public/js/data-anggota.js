$('[name="kode_anggota"]').change(function () {
    const id_anggota = $('[name="kode_anggota"]').val();
    if (id_anggota != '') {
        $('[name="nama_anggota"]').val(id_anggota);
        $.get(`${base_url}api/data-anggota/${id_anggota}`, function (data, status) {
            data.forEach(x => {
                if (x.status_pinjaman == 1) {
                    $('[name="limit_toko"]').val(x.limit_gaji - x.sisa_piutang);
                } else {
                    $('[name="limit_toko"]').val(x.limit_gaji - x.angsuran - x.sisa_piutang);
                }
                $('[name="alamat"]').val(x.alamat);
                $('[name="telepon"]').val(x.no_hp);
                $('[name="wa"]').val(x.no_wa);
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
                if (x.status_pinjaman == 1) {
                    $('[name="limit_toko"]').val(x.limit_gaji - x.sisa_piutang);
                } else {
                    $('[name="limit_toko"]').val(x.limit_gaji - x.angsuran - x.sisa_piutang);
                }
                $('[name="alamat"]').val(x.alamat);
                $('[name="telepon"]').val(x.no_hp);
                $('[name="wa"]').val(x.no_wa);
            });
        });
    } else {
        $('[name="kode_anggota"]').val("");
        $('[name="alamat"]').val("");
        $('[name="telepon"]').val("");
        $('[name="wa"]').val("");
    }
});