@extends('Simpan_Pinjam.layout')

@section('title', 'Pengaturan')

@section('content_header', 'Pengaturan')

@push('style')
<link rel="stylesheet" href="{{ asset('assets/plugins/tags-input/tagin.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('list.index') }}">Daftar Pengaturan</a></li>
    <li class="breadcrumb-item active">Edit Pengaturan</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Edit Pengaturan</h3>
                </div>
                <div class="card-body col-md-6 mx-auto">
                    <form id="form-setting" action="{{ route('list.update', $pengaturan->id) }}" role="form" method="post"
                        autocomplete="off">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="nama">Nama Pengaturan</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Bunga" value="{{ $pengaturan->nama }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="angka">Angka</label>
                            <input type="text" class="form-control tagin" id="angka" name="angka"
                                data-placeholder="Gunakan spasi untuk menambahkan" value="{{ $pengaturan->angka }}" data-separator=" " hidden>
                            @if ($errors->has('angka'))
                                <span class="text-danger">{{ $errors->first('angka') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('list.index') }}" class="btn btn-light">Kembali</a>&nbsp;
                        <button type="submit" id="submit" class="btn btn-primary">Ubah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <script src="{{ asset('assets/plugins/tags-input/tagin.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
@endpush

@section('script')
    <script>
        for (const el of document.querySelectorAll('.tagin')) {
            tagin (el)
        }

        $(function() {
            
        })
    </script>
@endsection
