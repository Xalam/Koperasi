@extends('toko.layout')

@section('main')
<div class="card m-6">
    <div class="card-body">
        {!! Form::open( ['url' => 'laporan/persediaan/cek', 'method' => 'GET']) !!}
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Laporan Persediaan', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Jumlah barang kurang dari', ['class' => 'col-5']) !!}
            {!! Form::number(null, 0, ['class' => 'col-2 text-center']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-small']) !!}
        </div>
        {!! Form::close() !!}
        <hr>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="text-center text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Kode Akun</th>
                        <th class="col-2">Nama Akun</th>
                        <th>Saldo Awal</th>
                        <th colspan="2">Opsi</th>
                    </tr>
                </thead>
                <tbody class="text-wrap">
                    <tr>
                        <th class="align-middle text-center">1</th>
                        <td class="align-middle text-center">B01</td>
                        <td class="align-middle">Buku</td>
                        <td class="align-middle text-center">5</td>
                        <td class="align-middle text-center"><button class="btn btn-small btn-warning">Edit</button>
                        <td class="align-middle text-center"><button class="btn btn-small btn-danger">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection