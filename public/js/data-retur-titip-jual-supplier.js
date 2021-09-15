$('[name="id_titip_jual"]').change(function () {
    const id_titip_jual = $('[name="id_titip_jual"]').val();
    if (id_titip_jual != 0) {
        $('[name="nama_supplier"]').empty();
        $.get(`${base_url}api/data-retur-titip-jual-supplier/${id_titip_jual}`, function (data, status) {
            data.forEach(x => {
                $('[name="nama_supplier"]').append('<option value='+x.id+'>'+x.nama+'</option>');
            });
        });
    } else {
        $('[name="nama_supplier"]').val("");
    }
});