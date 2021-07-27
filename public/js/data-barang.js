$('[name="kode_barang"]').change(function () {
    const id_barang = $('[name="kode_barang"]').val();
    if (id_barang != '') {
        $('[name="nama_barang"]').val(id_barang);
        $.get(`${base_url}api/data-barang/${id_barang}`, function (data, status) {
            data.forEach(x => {
                $('[name="stok"]').val(x.stok_etalase);
                $('[name="jumlah"]').val(1);
                $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $('[name="jumlah"]').val());
                $('[name="jumlah"]').removeAttr('readonly');
            });
        });
    } else {
        $('[name="nama_barang"]').val("");
        $('[name="stok"]').val(0);
        $('[name="jumlah"]').val(0);
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
                $('[name="total_harga"]').val($('[name="harga_satuan"]').val() * $('[name="jumlah"]').val());
                $('[name="jumlah"]').removeAttr('readonly');
            });
        });
    } else {
        $('[name="kode_barang"]').val("");
        $('[name="stok"]').val(0);
        $('[name="jumlah"]').val(0);
        $('[name="jumlah"]').attr('readonly', true);
    }
});

$('[name="kode_supplier"]').change(function () {
    const kode_supplier = $('[name="kode_supplier"]').val();
    if (kode_supplier > 0) {
        $('[name="kode_barang"]').empty();
        $('[name="nama_barang"]').empty();
        $.get(`${base_url}api/data-beli-barang/${kode_supplier}`, function (data, status) {
            if (data.length > 0) {
                $('[name="kode_barang"]').removeAttr('disabled');
                $('[name="nama_barang"]').removeAttr('disabled');
                $('[name="harga_satuan"]').removeAttr('readonly');
                $('[name="jumlah"]').removeAttr('readonly');
                $('[name="kode_barang"]').append(`<option value="">-- Pilih Kode Barang --</option>`);
                $('[name="nama_barang"]').append(`<option value="">-- Pilih Nama Barang --</option>`);
            } else {
                $('[name="kode_barang"]').attr('disabled', true);
                $('[name="nama_barang"]').attr('disabled', true);
                $('[name="harga_satuan"]').attr('readonly', true);
                $('[name="jumlah"]').attr('readonly', true);
                $('[name="kode_barang"]').append(`<option value="">-- Belum Ada Barang Yang Terdaftar --</option>`);
                $('[name="nama_barang"]').append(`<option value="">-- Belum Ada Barang Yang Terdaftar --</option>`);
            }
            data.forEach(x => {
                $('[name="kode_barang"]').append('<option value='+x.id+'>'+x.kode+'</option>');
                $('[name="nama_barang"]').append('<option value='+x.id+'>'+x.nama+'</option>');
            });
        });
    } else {
        $('[name="kode_barang"]').empty();
        $('[name="nama_barang"]').empty();
        $('[name="kode_barang"]').attr('disabled', true);
        $('[name="nama_barang"]').attr('disabled', true);
        $('[name="jumlah"]').val(0);
        $('[name="harga_beli"]').val(0);
        $('[name="harga_satuan"]').attr('readonly', true);
        $('[name="jumlah"]').attr('readonly', true);
    }
});

$('[name="nama_supplier"]').change(function () {
    const nama_supplier = $('[name="nama_supplier"]').val();
    if (nama_supplier > 0) {
        $('[name="kode_barang"]').empty();
        $('[name="nama_barang"]').empty();
        $.get(`${base_url}api/data-beli-barang/${nama_supplier}`, function (data, status) {
            if (data.length > 0) {
                $('[name="kode_barang"]').removeAttr('disabled');
                $('[name="nama_barang"]').removeAttr('disabled');
                $('[name="harga_satuan"]').removeAttr('readonly');
                $('[name="jumlah"]').removeAttr('readonly');
                $('[name="kode_barang"]').append(`<option value="">-- Pilih Kode Barang --</option>`);
                $('[name="nama_barang"]').append(`<option value="">-- Pilih Nama Barang --</option>`);
            } else {
                $('[name="kode_barang"]').attr('disabled', true);
                $('[name="nama_barang"]').attr('disabled', true);
                $('[name="harga_satuan"]').attr('readonly', true);
                $('[name="jumlah"]').attr('readonly', true);
                $('[name="kode_barang"]').append(`<option value="">-- Belum Ada Barang Yang Terdaftar --</option>`);
                $('[name="nama_barang"]').append(`<option value="">-- Belum Ada Barang Yang Terdaftar --</option>`);
            }
            data.forEach(x => {
                $('[name="kode_barang"]').append('<option value='+x.id+'>'+x.kode+'</option>');
                $('[name="nama_barang"]').append('<option value='+x.id+'>'+x.nama+'</option>');
            });
        });
    } else {
        $('[name="kode_barang"]').empty();
        $('[name="nama_barang"]').empty();
        $('[name="kode_barang"]').attr('disabled', true);
        $('[name="nama_barang"]').attr('disabled', true);
        $('[name="jumlah"]').val(0);
        $('[name="harga_beli"]').val(0);
        $('[name="harga_satuan"]').attr('readonly', true);
        $('[name="jumlah"]').attr('readonly', true);
    }
});