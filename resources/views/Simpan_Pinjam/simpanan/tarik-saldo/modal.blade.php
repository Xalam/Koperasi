<div class="modal-header">
    <h5 class="modal-title">Konfirmasi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p>
        @if ($tarikSaldo->status == 0)
            Apakah Anda ingin memproses permintaan penarikan dari
            <strong>{{ $tarikSaldo->saldo->anggota->nama_anggota }}</strong> sebesar <strong>Rp
                {{ number_format($tarikSaldo->nominal, 2, ',', '.') }}</strong> ?
        @endif
        @if ($tarikSaldo->status == 1)
            Apakah proses permintaan penarikan dari
            <strong>{{ $tarikSaldo->saldo->anggota->nama_anggota }}</strong> sebesar <strong>Rp
                {{ number_format($tarikSaldo->nominal, 2, ',', '.') }}</strong> sudah selesai diproses
            ?
        @endif
    </p>
</div>
<form action="{{ route('tarik-saldo.update', $tarikSaldo->id) }}" method="POST">
    @csrf
    @method('put')
    <input type="hidden" name="status" value="{{ $tarikSaldo->status == 0 ? 1 : 2 }}">
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">
            {{ $tarikSaldo->status == 0 ? 'Nanti saja' : 'Masih belum' }}
        </button>
        <button type="submit" class="btn btn-primary">
            {{ $tarikSaldo->status == 0 ? 'Proses' : 'Selesai' }}
        </button>
    </div>
</form>
