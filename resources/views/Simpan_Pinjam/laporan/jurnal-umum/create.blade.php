@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Tambah Jurnal Umum')

@push('style')
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('jurnal.index') }}">Jurnal Umum</a></li>
    <li class="breadcrumb-item active">Tambah Jurnal Umum</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Jurnal Umum</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('jurnal.store') }}" role="form" method="POST">
                        @csrf
                        <p style="margin-bottom: 5px;" class="text-right">Klik "Tambah Jurnal" untuk menambahkan jurnal</p>
                        <div class="form-group text-right">
                            <button type="button" name="add" id="add" class="btn btn-sm btn-success">
                                <i class="fas fa-plus-circle"></i>&nbsp; Tambah Jurnal
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="25%" class="text-center">Keterangan</th>
                                        <th width="25%" class="text-center">Nama Akun</th>
                                        <th width="20%" class="text-center">Debet</th>
                                        <th width="20%" class="text-center">Kredit</th>
                                        <th width="10%">&nbsp;</th>
                                    </tr>
                                </thead>

                                <tbody id="lists">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Balance</th>
                                        {{-- <td>
                                            <input type="number" id="cal-debet" class="form-control" disabled>
                                        </td>
                                        <td>
                                            <input type="number" id="cal-kredit" class="form-control" disabled>
                                        </td> --}}
                                        <td>
                                            <input type="number" id="cal-balance" class="form-control" disabled>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('jurnal.index') }}" class="btn btn-default">Kembali</a>
                            <button type="submit" class="btn btn-primary" id="btn-submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <script src="{{ asset('assets/plugins/jquery-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/jquery-maskmoney/jquery.maskMoney.js') }}"></script>
@endpush

@section('script')
    <script>
        $(function() {
            $('#tanggal').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            function formatMoney(n) {
                return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(n);
            }

            let array = [];
            let count = 0;
            $('#add').click(function(){
                count += 1;
                array.push(count);
                $('#lists').append(
                    '<tr class="list">' +
                        '<input type="hidden" name="rows[]" value="' + count + '">'+
                        '<td><input type="text" name="keterangan[]" id="keterangan' + count + '" required class="form-control"></td>' +
                        '<td width="200">' +
                            '<select name="id_akun[]" id="id-akun' + count + '" required class="form-control">' +
                                '<option value="">Pilih akun</option>' +
                                @foreach($akun as $a)
                                    '<option value="{{ $a->id }}">{{ $a->kode_akun . ' - ' . $a->nama_akun }}</option>' +
                                @endforeach
                            '</select></td>' +
                        '<td>'+
                            '<div class="input-group mb-3">'+
                                '<div class="input-group-prepend">'+
                                    '<span class="input-group-text">Rp</span>'+
                                '</div>'+
                                '<input type="text" name="debet[]" id="debet' + count + '" class="form-control deb">'+
                            '</div>'+
                        '</td>' +
                        '<td>'+
                            '<div class="input-group mb-3">'+
                                '<div class="input-group-prepend">'+
                                    '<span class="input-group-text">Rp</span>'+
                                '</div>'+
                                '<input type="text" name="kredit[]" id="kredit' + count + '" class="form-control kre">'+
                            '</div>'+
                        '</td>' +
                        '<td><button type="button" id="remove' + count + '" class="btn btn-danger btn-flat btn-sm" data-remove="' + count + '"><i class="fa fa-times"></i></button></td>' +
                    '</tr>'
                );
                $('#remove' + count).on('click', function (e) {
					if (e.type == 'click') {
						array.splice(array.indexOf($(this).data('remove')), 1);
						$(this).parents(".list").fadeOut();
						$(this).parents(".list").remove();
					}
				});
                
                $('#id-akun' + count).select2();

                $('#debet' + count).maskMoney({ 
                    allowNegative: true,
                    thousands:'.',
                    decimal: ','     
                });

                $('#kredit' + count).maskMoney({ 
                    allowNegative: true,
                    thousands:'.',
                    decimal: ','     
                });
            });

            document.getElementById('btn-submit').disabled = true;
            
            $('#lists').on('keyup', function() {
                let kredit = 0;
                let debet = 0;

                $('.kre').each(function(){
                    let cleanDot = $(this).val().split('.').join("");
                    let cleanComa = cleanDot.split(',').join(".");

                    kredit += Number(cleanComa);
                });

                $('.deb').each(function(){
                    let cleanDot = $(this).val().split('.').join("");
                    let cleanComa = cleanDot.split(',').join(".");

                    debet += Number(cleanComa);
                });

                // $('#cal-kredit').val(kredit);
                // $('#cal-debet').val(debet);
                $('#cal-balance').val(debet-kredit);

                if (debet - kredit == 0) {
                    document.getElementById('btn-submit').disabled = false;
                } else {
                    document.getElementById('btn-submit').disabled = true;
                }
                
            });
        })

    </script>

    @if (session()->has('error'))
    <script>
        $(document).Toasts('create', {
            class: 'bg-warning',
            title: 'Peringatan!',
            subtitle: '',
            body: "{{ session()->get('error') }}"
        })
    </script>
    @endif
@endsection
