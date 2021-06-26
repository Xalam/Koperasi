@extends('Simpan_Pinjam.layout')

@section('content_header')
    <a href="{{ route('anggota.show', $anggota->id) }}" class="btn btn-default">Kembali</a>
@endsection

@section('content_main')
    <div class="col-md-5">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" class="img-circle" alt="User Image" style="height: 30px; margin-bottom: 10px;">
                    <b>Primkop Polrestabes Semarang</b>
                </div>
                <h4 class="text-center text-primary">Data Anggota</h4>
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                        src="{{ $anggota->foto == null ? asset('assets/dist/img/avatar5.png') : asset('storage/foto/' . $anggota->foto) }}"
                        alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ $anggota->nama_anggota }}</h3>
                <p class="text-muted text-center"></p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <span>Kode Anggota</span> <b class="float-right">{{ $anggota->kd_anggota }}</b>
                    </li>
                    <li class="list-group-item">
                        <span>Nama</span> <b class="float-right">{{ $anggota->nama_anggota }}</b>
                    </li>
                    <li class="list-group-item">
                        <span>Tempat dan Tanggal Lahir</span> <b class="float-right">{{ $anggota->tempat_lahir . ', ' . $anggota->tanggal_lahir }}</b>
                    </li>
                    <li class="list-group-item">
                        <span>Alamat</span> <b class="float-right">{{ $anggota->alamat }}</b>
                    </li>
                    <li class="list-group-item">
                        <span>Jenis Kelamin</span> <b class="float-right">{{ $anggota->jenis_kelamin }}</b>
                    </li>
                    <li class="list-group-item">
                        <span>No Handphone</span> <b class="float-right">{{ $anggota->no_hp }}</b>
                    </li>
                    <li class="list-group-item">
                        <span>No Whatsapp</span> <b class="float-right">{{ $anggota->no_wa }}</b>
                    </li>
                    <li class="list-group-item">
                        <span>Status Pernikahan</span> <b class="float-right">{{ $anggota->status == 'kawin' ? 'Menikah' : 'Belum Menikah' }}</b>
                    </li>
                    <li class="list-group-item">
                        <span>Jabatan</span> <b class="float-right">{{ $anggota->jabatan }}</b>
                    </li>
                    <li class="list-group-item">
                        <span>Role</span> <b class="float-right">
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
                        </b>
                    </li>
                </ul>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
@endsection

@section('script')
    <script>window.addEventListener("load", window.print());</script>
@endsection