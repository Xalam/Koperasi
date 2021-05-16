@extends('layout')

@section('main')
<div class="card m-6">
    <div class="card-body">
        {!! Form::open( ['url' => 'laporan/pembelian/cek', 'method' => 'GET']) !!}
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Laporan Pembelian', ['class' => 'col-3 font-weight-bold']) !!}
        </div>
        <div class="row align-item-center mb-1">
            {!! Form::label(null, 'Laporan Berdasarkan', ['class' => 'col-5']) !!}
            {!! Form::select('select-laporan', ['Pilih' => '-- Pilih Jenis Laporan --', 'Nota' => 'Nota', 'Harian' => 'Harian', 'Mingguan' => 'Mingguan', 'Bulanan' =>
            'Bulanan'], null, ['class' => 'col-8']) !!}
        </div>
        <div id="select-nota" class="hide">
            <div class="row align-item-center mb-1">
                {!! Form::label(null, 'No. Nota', ['class' => 'col-5']) !!}
                {!! Form::text(null, null, ['class' =>'col-8']) !!}
            </div>
            <div class="row align-item-center mb-1">
                {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-small']) !!}
            </div>
        </div>
        <div id="select-harian" class="hide">
            <div class="row align-item-center mb-1">
                {!! Form::label(null, 'Tanggal', ['class' => 'col-5']) !!}
                {!! Form::date(null, null, ['class' =>'col-4']) !!}
            </div>
            <div class="row align-item-center mb-1">
                {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-small']) !!}
            </div>
        </div>
        <div id="select-mingguan" class="hide">
            <div class="row align-item-center mb-1">
                {!! Form::label(null, 'Tanggal Awal', ['class' => 'col-5']) !!}
                {!! Form::date(null, null, ['class' =>'col-4']) !!}
            </div>
            <div class="row align-item-center mb-1">
                {!! Form::label(null, 'Tanggal Akhir', ['class' => 'col-5']) !!}
                {!! Form::date(null, null, ['class' => 'col-4']) !!}
            </div>
            <div class="row align-item-center mb-1">
                {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-small']) !!}
            </div>
        </div>
        <div id="select-bulanan" class="hide">
            <div class="row align-item-center mb-1">
                {!! Form::label(null, 'Bulan', ['class' => 'col-5']) !!}
                {!! Form::select(null, [], null, ['class' =>'col-4']) !!}
            </div>
            <div class="row align-item-center mb-1">
                {!! Form::label(null, 'Tahun', ['class' => 'col-5']) !!}
                {!! Form::select(null, [], null, ['class' => 'col-2']) !!}
            </div>
            <div class="row align-item-center mb-1">
                {!! Form::submit('Cek', ['class' => 'btn btn-primary btn-small']) !!}
            </div>
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

@section('script')
    <script>
        $('[name="select-laporan"]').change(function() {
            if ($(this).val() == "Nota") {
                $('#select-nota').removeClass('hide');
                $('#select-harian').addClass('hide');
                $('#select-mingguan').addClass('hide');
                $('#select-bulanan').addClass('hide');
            } else if ($(this).val() == "Harian") {
                $('#select-nota').addClass('hide');
                $('#select-harian').removeClass('hide');
                $('#select-mingguan').addClass('hide');
                $('#select-bulanan').addClass('hide');
            } else if ($(this).val() == "Mingguan") {
                $('#select-nota').addClass('hide');
                $('#select-harian').addClass('hide');
                $('#select-mingguan').removeClass('hide');
                $('#select-bulanan').addClass('hide');
            }  else if ($(this).val() == "Bulanan") {
                $('#select-nota').addClass('hide');
                $('#select-harian').addClass('hide');
                $('#select-mingguan').addClass('hide');
                $('#select-bulanan').removeClass('hide');
            }  else {
                $('#select-nota').addClass('hide');
                $('#select-harian').addClass('hide');
                $('#select-mingguan').addClass('hide');
                $('#select-bulanan').addClass('hide');
            }
        });
    </script>
@endsection