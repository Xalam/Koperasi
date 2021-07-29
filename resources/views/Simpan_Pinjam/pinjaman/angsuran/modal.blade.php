<div class="modal-header">
    <h5 class="modal-title">Konfirmasi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p>
        Apakah Anda mengubah pembayaran angsuran dari
        <strong>{{ $angsuran->pinjaman->anggota->nama_anggota }}</strong> ?
    </p>
</div>
<form id="form-edit-bayar" action="{{ route('angsuran.update-bayar', $angsuran->id) }}" method="POST" role="form">
    @csrf
    @method('put')
    <input type="hidden" name="status" id="status-edit" value="{{ $angsuran->status == 0 ? 1 : 0 }}">
    <div class="modal-footer">
        <input type="button" class="btn btn-light" data-dismiss="modal" value="Tidak" />
        <input type="button" onclick="edit_angsuran({{ $angsuran->id }})" class="btn btn-primary" value="Proses" />
    </div>
</form>
