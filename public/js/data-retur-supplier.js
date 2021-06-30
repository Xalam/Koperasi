$('[name="id_beli"]').change(function () {
    const id_beli = $('[name="id_beli"]').val();
    if (id_beli != 0) {
        $('[name="nama_supplier"]').empty();
        $.get(`${base_url}api/data-retur-supplier/${id_beli}`, function (data, status) {
            data.forEach(x => {
                $('[name="nama_supplier"]').append('<option value='+x.id+'>'+x.nama+'</option>');
            });
        });
    } else {
        $('[name="nama_supplier"]').val("");
    }
});