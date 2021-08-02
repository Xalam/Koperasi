@extends('Simpan_Pinjam.layout')

@section('title', 'Akun')

@section('content_header', 'Edit Akun')

    @push('style')

    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Master</a></li>
    <li class="breadcrumb-item"><a href="{{ route('akun.index') }}">Akun</a></li>
    <li class="breadcrumb-item active">Edit Akun</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Edit Akun</h3>
                </div>
                <div class="card-body col-md-6 mx-auto">
                    <form action="{{ route('akun.update', $akun->id) }}" role="form" method="post"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="kode-akun">Kode Akun</label>
                            <input type="text" class="form-control number" id="kode-akun" name="kode_akun"
                                placeholder="Masukkan kode akun" value="{{ $akun->kode_akun }}">
                            @if ($errors->has('kode_akun'))
                                <span class="text-danger">{{ $errors->first('kode_akun') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="nama-akun">Nama Akun</label>
                            <input type="text" class="form-control" id="nama-akun" name="nama_akun"
                                placeholder="Masukkan nama akun" value="{{ $akun->nama_akun }}">
                            @if ($errors->has('nama_akun'))
                                <span class="text-danger">{{ $errors->first('nama_akun') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Saldo Awal</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Masukkan jumlah saldo" id="saldo"
                                    name="saldo" value="{{ $akun->saldo }}">
                            </div>
                            @if ($errors->has('saldo'))
                                <span class="text-danger">{{ $errors->first('saldo') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('akun.index') }}" class="btn btn-default">Kembali</a>&nbsp;
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <script src="{{ asset('assets/plugins/jquery-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-maskmoney/jquery.maskMoney.js') }}"></script>
@endpush

@section('script')
    <script>
        $(function() {
            $('#saldo').maskMoney({
                allowNegative: true,
                thousands: '.',
                decimal: ',',
                allowZero: true
            });

            $('input.number').keyup(function(event) {
                if (event.which !== 8 && event.which !== 0 && event.which < 48 || event.which > 57) {
                    $(this).val(function(index, value) {
                        return value.replace(/\D/g, "");
                    });
                }
            });
        })
    </script>
@endsection
