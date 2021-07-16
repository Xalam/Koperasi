<div class="modal-header">
    <h5 class="modal-title">Konfirmasi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p>
        Apakah Anda ingin menghapus Pelunasan Sebelum Jatuh Tempo dari kode pinjaman
        <strong>{{ $angsuran->pinjaman->kode_pinjaman }}</strong> ?
    </p>
</div>
<form action="{{ route('tempo.destroy', $angsuran->id) }}" method="POST">
    @csrf
    @method('delete')
    <input type="hidden" name="status" value="1">
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">
            Tidak
        </button>
        <button type="submit" class="btn btn-danger">
            Hapus
        </button>
    </div>
</form>
