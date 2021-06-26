@extends('Simpan_Pinjam.layout')

@section('title', 'Laporan')

@section('content_header', 'Laporan Simpanan')

    @push('style')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
    <li class="breadcrumb-item active">Laporan Simpanan</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <a href="{{ route('lap-simpanan.print-all') }}" class="btn btn-info btn-sm float-left" style="margin-top: 10px; margin-left: 8px;"><i class="fas fa-print"></i>&nbsp;Cetak Semua</a>
                    <form id="form-simpanan" action="{{ route('lap-simpanan.show') }}" role="form" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-4 float-right" style="margin-left: -20px;">
                            <b>Masukkan Kode Anggota&nbsp;</b> 
                            <div class="">
                                <select class="form-control select2" name="id_anggota">
                                    <option selected="selected" style="width: 100%;" value="">Pilih anggota</option>
                                    @foreach ($anggota as $a)
                                        <option value="{{ $a->id }}">{{ $a->kd_anggota . ' - ' . $a->nama_anggota }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" style="float: right; margin-top: 10px;" class="btn btn-info btn-sm"><i class="fas fa-search"></i>&nbsp;Tampil</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@section('script')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>

        $(function() {
            $('.select2').select2();

            $.validator.setDefaults({
                submitHandler: function () {
                    form.submit();
                }
            });

            $('#form-simpanan').validate({
                rules: {
                    id_anggota: {
                        required: true
                    }
                },
                messages: {
                    id_anggota: "Kode Anggota wajib diisi",
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });

    </script>

    @if (session()->has('success'))
        <script>
            toastr.success("{{ session()->get('success') }}");

        </script>
    @endif

    @if (session()->has('error'))
        <script>
            Swal.fire({
                title: 'Peringatan!',
                text: '{{ session()->get('error') }}',
                icon: 'warning',
                confirmButtonText: 'OK'
            })

        </script>
    @endif
@endsection
