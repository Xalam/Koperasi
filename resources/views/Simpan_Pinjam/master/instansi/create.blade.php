@extends('Simpan_Pinjam.layout')

@section('title', 'Instansi')

@section('content_header', 'Tambah Instansi')

@push('style')

@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Master</a></li>
    <li class="breadcrumb-item"><a href="{{ route('instansi.index') }}">Instansi</a></li>
    <li class="breadcrumb-item active">Tambah Instansi</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Tambah Instansi</h3>
                </div>
                <div class="card-body col-md-6 mx-auto">
                    <form action="{{ route('instansi.store') }}" role="form" method="post" enctype="multipart/form-data"
                        autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label for="kode-instansi">Kode Instansi</label>
                            <input type="text" class="form-control number" id="kode-instansi" name="kode_instansi"
                                placeholder="Masukkan kode instansi">
                            @if ($errors->has('kode_instansi'))
                                <span class="text-danger">{{ $errors->first('kode_instansi') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="nama-instansi">Nama Instansi</label>
                            <input type="text" class="form-control" id="nama-instansi" name="nama_instansi"
                                placeholder="Masukkan nama instansi">
                            @if ($errors->has('nama_instansi'))
                                <span class="text-danger">{{ $errors->first('nama_instansi') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('instansi.index') }}" class="btn btn-default">Kembali</a>&nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')

@endpush

@section('script')

@endsection
