$('[name="id_beli"]').change(function () {
    const id_beli = $('[name="id_beli"]').val();
    if (id_beli != 0) {
        $('[name="kode_barang"]').empty();
        $('[name="nama_barang"]').empty();
        $.get(`${base_url}api/data-retur-barang/${id_beli}`, function (data, status) {
            $('[name="kode_barang"]').append(`<option value="">-- Pilih Kode Barang --</option>`);
            $('[name="nama_barang"]').append(`<option value="">-- Pilih Nama Barang --</option>`);
            data.forEach(x => {
                $('[name="kode_barang"]').append('<option value='+x.id+'>'+x.kode+'</option>');
                $('[name="nama_barang"]').append('<option value='+x.id+'>'+x.nama+'</option>');
            });
        });
    } else {
        $('[name="kode_barang"]').empty();
        $('[name="nama_barang"]').empty();
    }
});

function data_retur_barang() {
    const id_beli = $('[name="id_beli"]').val();
    if (id_beli != 0) {
        $('[name="kode_barang"]').empty();
        $('[name="nama_barang"]').empty();
        $.get(`${base_url}api/data-retur-barang/${id_beli}`, function (data, status) {
            $('[name="kode_barang"]').append(`<option value="">-- Pilih Kode Barang --</option>`);
            $('[name="nama_barang"]').append(`<option value="">-- Pilih Nama Barang --</option>`);
            data.forEach(x => {
                $('[name="kode_barang"]').append('<option value='+x.id+'>'+x.kode+'</option>');
                $('[name="nama_barang"]').append('<option value='+x.id+'>'+x.nama+'</option>');
            });
        });
    } else {
        $('[name="kode_barang"]').empty();
        $('[name="nama_barang"]').empty();
    }
}

$('[name="kode_barang"]').change(function () {
    const id_barang = $('[name="kode_barang"]').val();
    const nomor_beli = $('[name="id_beli"] option:selected').text();
    if (id_barang != '') {
        $('[name="nama_barang"]').val(id_barang);
        $.get(`${base_url}api/data-retur-detail-barang/${nomor_beli}/${id_barang}`, function (data, status) {
            $('[name="harga_beli"]').val(data.harga);
            $('#text-maksimal-retur').text('Maksimal Retur : ' + parseInt(data.jumlah_beli - data.jumlah_retur));
            $('[name="maksimal_retur"]').val(parseInt(data.jumlah_beli - data.jumlah_retur));
        });
    } else {
        $('[name="nama_barang"]').val("");
        $('[name="harga_beli"]').val("");
    }
});

$('[name="nama_barang"]').change(function () {
    const id_barang = $('[name="nama_barang"]').val();
    const nomor_beli = $('[name="id_beli"] option:selected').text();
    if (id_barang != '') {
        $('[name="kode_barang"]').val(id_barang);
        $.get(`${base_url}api/data-retur-detail-barang/${nomor_beli}/${id_barang}`, function (data, status) {
            $('[name="harga_beli"]').val(data.harga);
            $('#text-maksimal-retur').text('Maksimal Retur : ' + parseInt(data.jumlah_beli - data.jumlah_retur));
            $('[name="maksimal_retur"]').val(parseInt(data.jumlah_beli - data.jumlah_retur));
        });
    } else {
        $('[name="kode_barang"]').val("");
        $('[name="harga_beli"]').val("");
    }
});