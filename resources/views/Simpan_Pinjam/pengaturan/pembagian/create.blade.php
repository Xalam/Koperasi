@extends('Simpan_Pinjam.layout')

@section('title', 'Pengaturan')

@section('content_header', 'Pembagian SHU')

    @push('style')

    @endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pembagian.index') }}">Pembagian SHU</a></li>
    <li class="breadcrumb-item active">Tambah Pembagian SHU</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Tambah Pembagian SHU</h3>
                </div>
                <div class="card-body col-md-6 mx-auto">
                    <form id="form-pembagian" action="{{ route('pembagian.store') }}" role="form" method="post"
                        autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama Pembagian SHU</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pembagian SHU">
                        </div>
                        <div class="form-group">
                            <label for="angka">Angka (%)</label>
                            <input type="text" class="form-control decimal" id="angka" name="angka" placeholder="Angka (%)">
                        </div>
                        <a href="{{ route('pembagian.index') }}" class="btn btn-light">Kembali</a>&nbsp;
                        <button type="submit" id="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('before-script')
    <!-- jquery-validation -->
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
@endpush

@section('script')
    <script>
        $(function() {
            $(".decimal").on("input", function(evt) {
                var self = $(this);
                self.val(self.val().replace(/[^0-9\.]/g, ''));
                if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which >
                        57)) {
                    evt.preventDefault();
                }
            });

            $.validator.setDefaults({
                submitHandler: function() {
                    form.submit();
                }
            });

            $('#form-pembagian').validate({
                rules: {
                    nama: {
                        required: true
                    },
                    angka: {
                        required: true
                    }
                },
                messages: {
                    nama: "Nama Pembagian SHU wajib diisi",
                    angka: "Angka (%) wajib diisi"
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
        })
    </script>
@endsection
