@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Bendahara Pusat')

    @push('style')
        <link rel="stylesheet"
            href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item active">Bendahara Pusat</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="col-md-4 float-right">
                        <form action="{{ route('bendahara.show-data') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Bulan</label>
                                <div class="input-group date" id="tanggal" data-target-input="nearest">
                                    <input type="text" id="tanggal-input" class="form-control datetimepicker-input"
                                        data-target="#tanggal" name="tanggal" placeholder="Bulan" />
                                    <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" id="btn-tampil" class="btn btn-sm btn-info"
                                style="float: right; margin-top: -5px;"><i class="fas fa-search"></i>&nbsp;Tampil</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/plugins/jquery-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <script>
        $(function() {
            $('#tanggal').datetimepicker({
                format: 'yyyy-MM',
            });

            $('#tanggal-input').keydown(function(event) {
                event.preventDefault();
            })
        });
    </script>
@endsection
