@extends('Simpan_Pinjam.layout')

@section('title', 'Anggota')

@section('content_header', 'Profil Anggota')

@push('style')
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Master</a></li>
    <li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
    <li class="breadcrumb-item active">Profil Anggota</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ $anggota->foto == null ? asset('assets/dist/img/avatar5.png') : asset('storage/foto/' . $anggota->foto) }}"
                            alt="{{ $anggota->foto }}">
                    </div>
                    <h3 class="profile-username text-center">{{ $anggota->nama_anggota }}</h3>
                    <p class="text-muted text-center"></p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Kode Anggota</b> <a class="float-right">{{ $anggota->kd_anggota }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Username</b> <a class="float-right">{{ $anggota->username }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Role</b> <a class="float-right">
                                @if ($anggota->role == 'anggota')
                                    Anggota
                                @elseif ($anggota->role == 'bendahara')
                                    Bendahara
                                @elseif ($anggota->role == 'bendahara_pusat')
                                    Bendahara Pusat
                                @elseif ($anggota->role == 'ketua_koperasi')
                                    Ketua Koperasi
                                @elseif ($anggota->role == 'simpan_pinjam')
                                    Unit Simpan Pinjam
                                @endif
                            </a>
                        </li>
                    </ul>
                    <a href="{{ route('anggota.index') }}" class="btn btn-default">Kembali</a>
                    <a href="{{ route('anggota.print', $anggota->id) }}" class="btn btn-danger float-right">Cetak</a>
                </div>
                <!-- /.card-body -->
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#identitasDiri" data-toggle="tab">Identitas Anggota</a>
                        </li>
                    </ul>
                </div><!-- /.card-header -->

                <div class="card-body">
                    <div class="tab-content">

                        <!-- Data Diri -->
                        <div class="active tab-pane" id="identitasDiri">
                            <form class="form-horizontal">
                                <!-- Name -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-readonly"
                                            value="{{ $anggota->nama_anggota }}" readonly>
                                    </div>
                                </div>
                                <!-- Instansi -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Instansi</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-readonly"
                                            value="{{ $instansi->kode_instansi . ' - ' . $instansi->nama_instansi }}"
                                            readonly>
                                    </div>
                                </div>
                                <!-- Gender -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-readonly"
                                            value="{{ $anggota->jenis_kelamin }}" readonly>
                                    </div>
                                </div>
                                <!-- Agama -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Agama</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-readonly"
                                            value="{{ $anggota->agama }}" readonly>
                                    </div>
                                </div>
                                <!-- TTL -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tempat Tanggal Lahir</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-readonly"
                                            value="{{ $anggota->tempat_lahir . ', ' . $anggota->tanggal_lahir }}"
                                            readonly>
                                    </div>
                                </div>
                                <!-- Address -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Alamat</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control form-readonly" cols="30" rows="3" style="resize: none"
                                            readonly>{{ $anggota->alamat }}</textarea>
                                    </div>
                                </div>
                                <!-- Phone -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">No. Handphone</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-readonly"
                                            value="{{ $anggota->no_hp }}" readonly>
                                    </div>
                                </div>
                                <!-- Whatsapp -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">No. Whatsapp</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-readonly"
                                            value="{{ $anggota->no_wa }}" readonly>
                                    </div>
                                </div>
                                <!-- Status -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Status Pernikahan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-readonly"
                                            value="{{ $anggota->status == 'kawin' ? 'Menikah' : 'Belum Menikah' }}"
                                            readonly>
                                    </div>
                                </div>
                                <!-- Jabatan -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Jabatan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-readonly"
                                            value="{{ $anggota->jabatan }}" readonly>
                                    </div>
                                </div>
                                <!-- Email -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-readonly"
                                            value="{{ $anggota->email }}" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
