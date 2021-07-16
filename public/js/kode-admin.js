$('[name="jabatan"]').change(function () {
    var jabatan = $('[name="jabatan"]').val();
    if (jabatan != '') {
        $.get(`${base_url}api/kode-admin/${jabatan}`, function (data, status) {
            $('[name="kode"]').val(data);
        });
    } else {
        $('[name="kode"]').val('');
    }
});

$(document).ready(function() {
    var jabatan = $('[name="jabatan"]').val();
    if (jabatan != '') {
        $.get(`${base_url}api/kode-admin/${jabatan}`, function (data, status) {
            $('[name="kode"]').val(data);
        });
    } else {
        $('[name="kode"]').val('');
    }
});