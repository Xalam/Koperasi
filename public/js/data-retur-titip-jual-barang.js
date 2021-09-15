$('[name="id_titip_jual"]').change(function () {
    const id_titip_jual = $('[name="id_titip_jual"]').val();
    if (id_titip_jual != 0) {
        $('[name="kode_barang"]').empty();
        $('[name="nama_barang"]').empty();
        $.get(`${base_url}api/data-retur-titip-jual-barang/${id_titip_jual}`, function (data, status) {
            $('[name="kode_barang"]').removeAttr('disabled');
            $('[name="nama_barang"]').removeAttr('disabled');

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
        $('[name="kode_barang"]').attr('disabled', true);
        $('[name="nama_barang"]').attr('disabled', true);
        $('[name="jumlah"]').val(0);
        $('[name="harga_beli"]').val(0);
        $('[name="jumlah"]').attr('readonly', true);
    }
});

function data_retur_barang() {
    const id_titip_jual = $('[name="id_titip_jual"]').val();
    if (id_titip_jual != 0) {
        $('[name="kode_barang"]').empty();
        $('[name="nama_barang"]').empty();
        $.get(`${base_url}api/data-retur-titip-jual-barang/${id_titip_jual}`, function (data, status) {
            $('[name="kode_barang"]').removeAttr('disabled');
            $('[name="nama_barang"]').removeAttr('disabled');

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
        $('[name="kode_barang"]').attr('disabled', true);
        $('[name="nama_barang"]').attr('disabled', true);
        $('[name="jumlah"]').val(0);
        $('[name="harga_beli"]').val(0);
        $('[name="jumlah"]').attr('readonly', true);
    }
}

$('[name="kode_barang"]').change(function () {
    const id_barang = $('[name="kode_barang"]').val();
    const nomor_titip_jual = $('[name="id_titip_jual"] option:selected').text();
    if (id_barang != '') {
        $('[name="nama_barang"]').val(id_barang);
        $.get(`${base_url}api/data-retur-titip-jual-detail-barang/${nomor_titip_jual}/${id_barang}`, function (data, status) {
            $('[name="harga_beli"]').val(data.harga);
            $('#text-maksimal-retur').text('Maksimal Retur : ' + parseInt(data.jumlah_beli - data.jumlah_retur));
            $('[name="maksimal_retur"]').val(parseInt(data.jumlah_beli - data.jumlah_retur));
            if (parseInt(data.jumlah_beli - data.jumlah_retur) <= 0) {
                $('[name="jumlah"]').attr('readonly', true);
                $('[name="jumlah"]').val(0);
            } else {
                $('[name="jumlah"]').removeAttr('readonly');
                $('[name="jumlah"]').val(1);
            }
        });
    } else {
        $('[name="nama_barang"]').val("");
        $('[name="harga_beli"]').val("");
        $('[name="jumlah"]').val(0);
        $('[name="jumlah"]').attr('readonly', true);
    }
});

$('[name="nama_barang"]').change(function () {
    const id_barang = $('[name="nama_barang"]').val();
    const nomor_titip_jual = $('[name="id_titip_jual"] option:selected').text();
    if (id_barang != '') {
        $('[name="kode_barang"]').val(id_barang);
        $.get(`${base_url}api/data-retur-titip-jual-detail-barang/${nomor_titip_jual}/${id_barang}`, function (data, status) {
            $('[name="harga_beli"]').val(data.harga);
            $('#text-maksimal-retur').text('Maksimal Retur : ' + parseInt(data.jumlah_beli - data.jumlah_retur));
            $('[name="maksimal_retur"]').val(parseInt(data.jumlah_beli - data.jumlah_retur));
            if (parseInt(data.jumlah_beli - data.jumlah_retur) <= 0) {
                $('[name="jumlah"]').attr('readonly', true);
                $('[name="jumlah"]').val(0);
            } else {
                $('[name="jumlah"]').removeAttr('readonly');
                $('[name="jumlah"]').val(1);
            }
        });
    } else {
        $('[name="kode_barang"]').val("");
        $('[name="harga_beli"]').val("");
        $('[name="jumlah"]').val(0);
        $('[name="jumlah"]').attr('readonly', true);
    }
});