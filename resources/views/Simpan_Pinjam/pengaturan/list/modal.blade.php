<div class="modal-header">
    <h6 class="modal-title">Konfirmasi</h6>
    <button type="button" class="close" data-dismiss="modal" aria-label="close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p>Apakah Anda ingin menghapus pengaturan <br> <strong>"{{ $pengaturan->nama }}"</strong> ?</p>
</div>
<form action="{{ route('list.destroy', $pengaturan->id) }}" method="POST">
    @csrf
    @method('delete')
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Tidak</button>
        <button type="submit" class="btn btn-danger">Hapus</button>
    </div>
</form>