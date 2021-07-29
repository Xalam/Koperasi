<div class="modal-header">
    <h5 class="modal-title">Konfirmasi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p>
        @if ($pinjaman->status == 0)
            Apakah Anda ingin memproses pengajuan pinjaman dari
            <strong>{{ $pinjaman->anggota->nama_anggota }}</strong> sebesar <strong>Rp
                {{ number_format($pinjaman->nominal_pinjaman, 2, ',', '.') }}</strong> ?
        @endif
        @if ($pinjaman->status == 1)
            Apakah sudah mencairkan pinjaman dari
            <strong>{{ $pinjaman->anggota->nama_anggota }}</strong> sebesar <strong>Rp
                {{ number_format($pinjaman->nominal_pinjaman, 2, ',', '.') }}</strong> ?
        @endif
    </p>
</div>
<form action="{{ route('pengajuan.update', $pinjaman->id) }}" method="POST">
    @csrf
    @method('put')
    <input type="hidden" name="status" value="{{ $pinjaman->status == 0 ? 1 : 2 }}">
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">
            {{ $pinjaman->status == 0 ? 'Nanti saja' : 'Masih belum' }}
        </button>
        <button type="submit" class="btn btn-primary">
            {{ $pinjaman->status == 0 ? 'Proses' : 'Cair' }}
        </button>
    </div>
</form>
