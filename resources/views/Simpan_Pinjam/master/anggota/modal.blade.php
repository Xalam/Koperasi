<div class="modal-header">
    <h6 class="modal-title">Konfirmasi</h6>
    <button type="button" class="close" data-dismiss="modal" aria-label="close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p>Apakah Anda yakin ingin menghapus anggota <br> <strong>"{{ $anggota->nama_anggota }}"</strong> ?</p>
    <span class="text-sm text-danger">(Semua transaksi dari yang bersangkutan akan terhapus!)</span>
</div>
<form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST">
    @csrf
    @method('delete')
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Tidak</button>
        <button type="submit" class="btn btn-danger">Hapus</button>
    </div>
</form>
