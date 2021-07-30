<div class="modal-header">
    <h5 class="modal-title">Detail</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <dl class="row">
        <dt class="col-sm-4">Kode Pinjaman</dt>
        <dd class="col-sm-8">: &nbsp;{{ $data->kode_pinjaman }}</dd>
        <dt class="col-sm-4">Kode Anggota</dt>
        <dd class="col-sm-8">: &nbsp;{{ $data->anggota->kd_anggota }}</dd>
        <dt class="col-sm-4">Nama Anggota</dt>
        <dd class="col-sm-8">: &nbsp;{{ $data->anggota->nama_anggota }}</dd>
        <dt class="col-sm-4">Tanggal</dt>
        <dd class="col-sm-8">: &nbsp;{{ date('j-m-Y', strtotime($data->tanggal)) }}</dd>
        <dt class="col-sm-4">Nominal Pinjaman</dt>
        <dd class="col-sm-8">: &nbsp;Rp {{ number_format($data->nominal_pinjaman, 2, ',', '.') }}</dd>
        <dt class="col-sm-4">Bunga</dt>
        <dd class="col-sm-8">: &nbsp;{{ $data->bunga }} %</dd>
        <dt class="col-sm-4">Jangka Waktu</dt>
        <dd class="col-sm-8">: &nbsp;{{ $data->tenor }} x</dd>
        <dt class="col-sm-4">Total Pinjaman</dt>
        <dd class="col-sm-8">: &nbsp;Rp {{ number_format($data->total_pinjaman, 2, ',', '.') }}</dd>
        <dt class="col-sm-4">Angsuran</dt>
        <dd class="col-sm-8">: &nbsp;Rp {{ number_format($data->nominal_angsuran, 2, ',', '.') }}</dd>
        <dt class="col-sm-4">Biaya Provisi</dt>
        <dd class="col-sm-8">: &nbsp;Rp {{ number_format($data->biaya_provisi, 2, ',', '.') }}</dd>
        <dt class="col-sm-4">Biaya Asuransi</dt>
        <dd class="col-sm-8">: &nbsp;Rp {{ number_format($data->biaya_asuransi, 2, ',', '.') }}</dd>
    </dl>
</div>
