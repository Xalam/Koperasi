@extends('Simpan_Pinjam.layout')

@section('title', 'Pinjaman')

@section('content_header', 'Pembayaran Angsuran')

    @push('style')

    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
    <li class="breadcrumb-item"><a href="{{ route('angsuran.index') }}">Angsuran</a></li>
    <li class="breadcrumb-item active">Pembayaran Angsuran</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
                <!-- Table row -->
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Pinjaman</th>
                                    <th>Kode Anggota</th>
                                    <th>Nama Anggota</th>
                                    <th>Angsuran#</th>
                                    <th>Sisa Angsuran (x)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $data->kode_pinjaman }}</td>
                                    <td>{{ $data->anggota->kd_anggota }}</td>
                                    <td>{{ $data->anggota->nama_anggota }}</td>
                                    <td>Rp. {{ number_format($data->nominal_angsuran, 2, ',', '.') }}</td>
                                    <td>{{ $data->tenor - $data->angsuran_ke }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <div class="row">
                    <!-- accepted payments column -->
                    <div class="col-6">

                    </div>
                    <!-- /.col -->
                    <div class="col-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:50%">Angsuran Ke</th>
                                    <td><b>: {{ $data->angsuran_ke + 1 }}</b></td>
                                </tr>
                                <tr>
                                    <th style="width:50%">Jumlah Bayar</th>
                                    <td><b>: Rp. {{ number_format($data->nominal_angsuran, 2, ',', '.') }}</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- this row will not appear when printing -->
                <div class="row no-print">
                    <div class="col-12">
                        <form action="{{ route('angsuran.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_pinjaman" value="{{ $data->id }}">
                            <button class="btn btn-info float-right"><i class="fas fa-credit-card"></i>&nbsp; Bayar</button>
                        </form>
                        <a href="{{ route('angsuran.index') }}" class="btn btn-default float-right"
                            style="margin-right: 5px;"><i></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
