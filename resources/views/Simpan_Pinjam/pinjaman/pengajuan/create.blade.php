@extends('simpan_pinjam.layout')

@section('title', 'Pinjaman')

@section('content_header', 'Tambah Pengajuan Pinjaman')

@push('style')
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Pinjaman</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pengajuan.index') }}">Pengajuan Pinjaman</a></li>
    <li class="breadcrumb-item active">Tambah Pengajuan</li>
@endsection

@section('content_main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Pengajuan</h3>
                </div>
                <div class="card-body col-md-6 mx-auto">
                    <form id="form-pengajuan" action="{{ route('pengajuan.store') }}" role="form" method="post">
                        @csrf
                        <input type="text" class="form-control" name="kode_simpanan" id="kode_simpanan" placeholder="Masukkan kode" hidden>
                        <div class="form-group">
                            <label>Nama Anggota</label>
                            <select class="form-control select2" style="width: 100%;" name="id_anggota" id="id-anggota">
                                <option selected="selected" value="">Pilih anggota</option>
                                @foreach ($anggota as $a)
                                    <option value="{{ $a->id }}">{{ $a->kd_anggota . ' - ' . $a->nama_anggota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cal-angsuran">
                            <div class="form-group">
                                <label>Limit</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Limit Gaji" id="limit" disabled>
                                    <input type="hidden" class="form-control" id="limit-gaji">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group date" id="tanggal" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#tanggal"
                                        name="tanggal" placeholder="Tanggal"/>
                                    <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Pinjaman</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Jumlah Pinjaman" id="nominal"
                                        name="nominal_pinjaman">
                                </div>
                                @if ($errors->has('nominal'))
                                    <span class="text-danger">{{ $errors->first('nominal') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Bunga % Per Tahun</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Dalam Persen %" id="bunga"
                                        name="bunga">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tenor">Jangka Waktu (x)</label>
                                <input type="text" class="form-control number" name="tenor" id="tenor" placeholder="Jangka Waktu" min="1">
                            </div>
                            <div class="form-group">
                                <label>Angsuran</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Angsuran" id="angsuran" disabled>
                                </div>
                                <span id="danger-limit" style="visibility: hidden;" class="text-danger text-sm">Angsuran tidak boleh melebihi limit</span>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: -10px;">
                            <label>Biaya Administrasi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Biaya Administrasi" id="biaya-admin"
                                    name="biaya_admin">
                            </div>
                        </div>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-light">Kembali</a>&nbsp;
                        <button id="btn-angsuran" type="submit" class="btn btn-primary">Simpan</button>
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
@endpush

@section('script')
    <script>
        $(function() {
            $('#nominal').mask('#.##0', {
                reverse: true
            });

            $('#biaya-admin').mask('#.##0', {
                reverse: true
            });

            $('#bunga').inputmask('decimal', { rightAlign: false });

            $('#tanggal').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('.select2').select2();

            $.validator.setDefaults({
                submitHandler: function () {
                    form.submit();
                }
            });

            $('#form-pengajuan').validate({
                rules: {
                    id_anggota: {
                        required: true
                    },
                    tanggal: {
                        required: true
                    },
                    nominal_pinjaman: {
                        required: true
                    },
                    bunga: {
                        required: true
                    },
                    tenor: {
                        required: true
                    },
                },
                messages: {
                    id_anggota: "Nama Anggota wajib diisi",
                    tanggal: "Tanggal wajib diisi",
                    nominal_pinjaman: "Nominal wajib diisi",
                    bunga: "Bunga wajib diisi",
                    tenor: "Jangka waktu wajib diisi",
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

            $('#id-anggota').bind('change', function() {
                let id = $(this).val();
                console.log(id);
                $.ajax({
                    method: 'POST',
                    url: '{{ route('pengajuan.limit') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id':id},
                    success: function(data) {
                        console.log(data.limit);
                        $('#limit').attr('value', formatMoney(data.limit));
                        $('#limit-gaji').attr('value', data.limit);
                    }
                })
            });

            $('.cal-angsuran').keyup(function() {
                let jumPinjaman = $('#nominal').val();
                let bunga = $('#bunga').val();
                let tenor = $('#tenor').val();
                let limit = $('#limit-gaji').val();
                
                let newJumPinjaman = 0;
                let newBunga = 0;
                let newTenor = 0;
                let newLimit = parseFloat(limit);

                if (jumPinjaman != null) {
                    newJumPinjaman = parseFloat(jumPinjaman.split('.').join(""));
                }

                if (bunga != null) {
                    newBunga = parseFloat(bunga);
                }

                if (tenor != null) {
                    newTenor = parseFloat(tenor);
                }

                let calBunga = newJumPinjaman + (newJumPinjaman * (newBunga / 100) * (newTenor / 12));
                let result = calBunga / newTenor;

                if (result > newLimit) {
                    document.getElementById('danger-limit').style.visibility = 'visible';
                    document.getElementById('btn-angsuran').disabled = true;
                } else {
                    document.getElementById('danger-limit').style.visibility = 'hidden';
                    document.getElementById('btn-angsuran').disabled = false;
                }

                $('#angsuran').attr('value', formatMoney(result));
            });

            function formatMoney(n) {
                return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(n);
            }

            $('input.number').keyup(function(event) {
                if (event.which !== 8 && event.which !== 0 && event.which < 48 || event.which > 57) {
                    $(this).val(function(index, value) {
                        return value.replace(/\D/g, "");
                    });
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
