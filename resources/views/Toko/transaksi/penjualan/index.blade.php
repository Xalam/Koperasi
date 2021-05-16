@extends('layout')

@section('main')
<div class="card m-6">
    <div class="card-body">
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Tanggal', ['class' => 'col-4']) !!}
            {!! Form::label(null, '2021-05-03', ['class' => 'col-4']) !!}
            {!! Form::label(null, 'No. Jual', ['class' => 'offset-6 col-4']) !!}
            {!! Form::text(null, '0', ['class' => 'col-6']) !!}
        </div>
        <br>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Pelanggan', ['class' => 'col-5 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Kode Pelanggan', ['class' => 'col-5']) !!}
            {!! Form::select(null, ['Test01' => 'Test01', 'Test02' => 'Test02'], null, ['class' => 'col-8']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Nama Pelanggan', ['class' => 'col-5']) !!}
            {!! Form::select(null, ['Pelanggan 1' => 'Pelanggan 1', 'Pelanggan 2' => 'Pelanggan 2'], null, ['class'
            =>
            'col-14']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Alamat', ['class' => 'col-5']) !!}
            {!! Form::text(null, null, ['class' => 'col-14']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Phone', ['class' => 'col-5']) !!}
            {!! Form::text(null, null, ['class' => 'col-8']) !!}
        </div>
        <br>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Barang', ['class' => 'font-weight-bold']) !!}
            {!! Form::label(null, 'Pembayaran', ['class' => 'ms-2 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            <div class="col-8">
                {!! Form::label(null, 'Kode', ['class' => 'col-5']) !!}
                {!! Form::text(null, null, ['class' => 'col-8']) !!}
            </div>
            <div class="offset-8 col-5 text-center">
                <h2 class="color-danger">Rp. 1.000.000.000,-</h2>
            </div>
            <div class="col-5 text-center">
                <h2 class="color-success">Rp. 1.000.000.000,-</h2>
            </div>
        </div>
        <div class="row align-item-center mb-1">
            <div class="offset-50 col-4">
                {!! Form::label(null, 'Jenis Pembayaran', ['class' => 'col-4']) !!}
                {!! Form::select('jenis_pembayaran', ['Kredit' => 'Kredit', 'Tunai' => 'Tunai'], [], ['class' => 'col-4']) !!}
            </div>
            <div class="col-5 offset-5">
                {!! Form::label('dibayar', 'Dibayar', ['class' => 'col-4 hide']) !!}
                {!! Form::text('jumlah_bayar', null, ['class' => 'col-6 hide']) !!}
            </div>
        </div>
        <hr class="mt-2 mb-2">
        <div class="row align-item-center mb-2">
            <div class="col-5">
                {!! Form::label(null, 'Nama', null) !!}
                {!! Form::text(null, null, null) !!}
            </div>
            <div class="col-3 offset-3">
                {!! Form::label(null, 'Sisa Stok', null) !!}
                {!! Form::text(null, null, null) !!}
            </div>
            <div class="col-3 offset-4">
                {!! Form::label(null, 'Harga Satuan', null) !!}
                {!! Form::text(null, null, null) !!}
            </div>
            <div class="col-3 offset-4">
                {!! Form::label(null, 'Jumlah Beli', null) !!}
                {!! Form::text(null, null, null) !!}
            </div>
            <div class="col-3 offset-4">
                {!! Form::label(null, 'Total Harga', null) !!}
                {!! Form::text(null, null, null) !!}
            </div>
        </div>
        <div class="row align-item-center mb-1">
            <a href="" class="btn btn-small btn-primary">Tambah</a>
        </div>
        <hr>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Daftar Barang', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="text-center text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th class="col-2">Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Harga Total</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody class="text-wrap">
                    <tr>
                        <th class="align-middle text-center">1</th>
                        <td class="align-middle text-center">B01</td>
                        <td class="align-middle">Buku</td>
                        <td class="align-middle text-center">5</td>
                        <td class="align-middle text-center">2500</td>
                        <td class="align-middle text-center">12500</td>
                        <td class="align-middle text-center"><button class="btn btn-small btn-danger">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::submit('Submit', ['class' => 'btn btn-success btn-small']) !!}
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $('[name="jenis_pembayaran"]').change(function() {
            if ($(this).val() == "Kredit") {
                $('[for="dibayar"]').addClass('hide');
                $('[name="jumlah_bayar"]').addClass('hide');
            } else {
                $('[for="dibayar"]').removeClass('hide');
                $('[name="jumlah_bayar"]').removeClass('hide');
            }
        });
    </script>
@endsection