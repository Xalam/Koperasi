$('[name="kode_barang"]').change(function () {
    const id_barang = $('[name="kode_barang"]').val();
    if (id_barang != '') {
        $('[name="nama_barang"]').val(id_barang);
        $.get(`${base_url}api/data-barang/${id_barang}`, function (data, status) {
            data.forEach(x => {
                $('[name="stok"]').val(x.stok_etalase);
                $('[name="jumlah"]').val(1);
                $('[name="harga_satuan"]').val(x.harga_jual);
                $('[name="jumlah_grosir"]').val(x.minimal_grosir);
                $('[name="harga_grosir"]').val(x.harga_grosir);
                $('[name="harga_normal"]').val(x.harga_jual);
                $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $('[name="jumlah"]').val());
                $('[name="harga_satuan"]').removeAttr('readonly');
                $('[name="jumlah"]').removeAttr('readonly');
            });
        });
    } else {
        $('[name="nama_barang"]').val("");
        $('[name="stok"]').val(0);
        $('[name="harga_satuan"]').val(0);
        $('[name="jumlah"]').val(0);
        $('[name="harga_grosir"]').val(0);
        $('[name="harga_normal"]').val(0);
        $('[name="jumlah_grosir"]').val(0);
        $('[name="total_harga"]').val(0);

        $('[name="harga_satuan"]').attr('readonly', true);
        $('[name="jumlah"]').attr('readonly', true);
    }
});

$('[name="nama_barang"]').change(function () {
    const id_barang = $('[name="nama_barang"]').val();
    if (id_barang != '') {
        $('[name="kode_barang"]').val(id_barang);
        $.get(`${base_url}api/data-barang/${id_barang}`, function (data, status) {
            data.forEach(x => {
                $('[name="stok"]').val(x.stok_etalase);
                $('[name="jumlah"]').val(1);
                $('[name="harga_satuan"]').val(x.harga_jual);
                $('[name="jumlah_grosir"]').val(x.minimal_grosir);
                $('[name="harga_grosir"]').val(x.harga_grosir);
                $('[name="harga_normal"]').val(x.harga_jual);
                $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $('[name="jumlah"]').val());
                $('[name="harga_satuan"]').removeAttr('readonly');
                $('[name="jumlah"]').removeAttr('readonly');
            });
        });
    } else {
        $('[name="kode_barang"]').val("");
        $('[name="stok"]').val(0);
        $('[name="harga_satuan"]').val(0);
        $('[name="jumlah"]').val(0);
        $('[name="harga_grosir"]').val(0);
        $('[name="harga_normal"]').val(0);
        $('[name="jumlah_grosir"]').val(0);
        $('[name="total_harga"]').val(0);

        $('[name="harga_satuan"]').attr('readonly', true);
        $('[name="jumlah"]').attr('readonly', true);
    }
});