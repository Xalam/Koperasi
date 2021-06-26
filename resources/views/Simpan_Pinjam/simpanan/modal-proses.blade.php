<div class="modal-header">
    <h5 class="modal-title">Konfirmasi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p>
        Apakah Anda ingin memproses pembayaran simpanan dari <strong>{{ $simpanan->anggota->nama_anggota }}</strong> ?
    </p>
</div>
<form action="{{ route('data.update', $simpanan->id) }}" method="POST">
    @csrf
    @method('put')
    <input type="hidden" name="status" value="1">
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">
            Nanti saja
        </button>
        <button type="submit" class="btn btn-primary">
            Proses
        </button>
    </div>
</form>
