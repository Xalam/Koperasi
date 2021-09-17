@extends('Simpan_Pinjam.layout')

@section('title', 'Pinjaman')

@section('content_header', 'Tambah Pengajuan Pinjaman')

@push('style')
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
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
                        <input type="text" class="form-control" name="kode_simpanan" id="kode_simpanan"
                            placeholder="Masukkan kode" hidden>
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
                                <label>Sisa Masa Kerja</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Sisa masa kerja" id="masa-kerja"
                                        disabled>
                                    <input type="hidden" class="form-control" id="sisa-masa-kerja">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Limit</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Limit - Piutang Toko" id="limit"
                                        disabled>
                                    <input type="hidden" class="form-control" id="limit-gaji">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" id="tanggal" value="{{ date('Y-m-d') }}"
                                    disabled>
                                <input type="text" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}"
                                    hidden>
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
                                <label>Bunga</label>
                                <select class="form-control select2" style="width: 100%;" name="bunga" id="bunga">
                                    <option selected="selected" value="">Pilih bunga</option>
                                    @foreach ($expBunga as $bunga)
                                        <option value="{{ $bunga }}">{{ $bunga }} %
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" id="hide-bunga" hidden>
                            </div>
                            <div class="form-group">
                                <label>Jangka Waktu (x)</label>
                                <select class="form-control select2" style="width: 100%;" name="tenor" id="tenor">
                                    <option selected="selected" value="">Pilih jangka waktu</option>
                                    @foreach ($expTenor as $tenor)
                                        <option value="{{ $tenor }}">{{ $tenor }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" id="hide-tenor" hidden>
                                <span id="danger-kerja" style="visibility: hidden;" class="text-danger text-sm">Jangka
                                    waktu melebihi sisa masa kerja</span>
                            </div>
                            <div class="form-group" style="margin-top: -15px;">
                                <label>Angsuran</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Angsuran" id="angsuran" disabled>
                                </div>
                                <span id="danger-limit" style="visibility: hidden;" class="text-danger text-sm">Angsuran
                                    tidak boleh melebihi limit</span>
                            </div>
                            <div class="form-group" style="margin-top: -15px;">
                                <label>Biaya Provisi</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Biaya provisi" id="biaya-provisi"
                                        name="biaya_provisi" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Biaya Asuransi</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Biaya asuransi"
                                        id="biaya-asuransi" name="biaya_asuransi" disabled>
                                </div>
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
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@section('script')
    <script>
        $(function() {
            $('#nominal').mask('#.##0', {
                reverse: true
            });

            $('#bunga').inputmask('decimal', {
                rightAlign: false
            });

            $('#tanggal').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('#tanggal-input').keydown(function(event) {
                event.preventDefault();
            });

            $('.select2').select2();

            $.validator.setDefaults({
                submitHandler: function() {
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
                        required: true,
                        max: function() {
                            var str = $('#nominal').val()
                            var res = str.replace(/\./g, '');
                            if (parseInt(res) > 75000000) {
                                return false;
                            }
                        }
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
                    nominal_pinjaman: {
                        required: "Nominal wajib diisi",
                        max: "Melebihi batas ketentuan"
                    },
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
                        'id': id
                    },
                    success: function(data) {
                        $('#limit').attr('value', formatMoney(data.limit));
                        $('#limit-gaji').attr('value', data.limit);
                        $('#masa-kerja').attr('value', data.masa_kerja);
                        $('#sisa-masa-kerja').attr('value', data.sisa_bulan);
                    }
                })
            });

            $("#bunga").change(function() {
                $('#hide-bunga').attr('value', $(this).val());
            });
            $("#tenor").change(function() {
                $('#hide-tenor').attr('value', $(this).val());
            });

            $('.cal-angsuran').change(function() {
                let jumPinjaman = $('#nominal').val();
                let bunga = $('#hide-bunga').val();
                let tenor = $('#hide-tenor').val();
                let limit = $('#limit-gaji').val();
                let sisa_bulan = $('#sisa-masa-kerja').val();

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

                let result = (newJumPinjaman / newTenor) + (newJumPinjaman * (newBunga / 100));

                if (sisa_bulan < newTenor) {
                    document.getElementById('danger-kerja').style.visibility = 'visible';
                } else {
                    document.getElementById('danger-kerja').style.visibility = 'hidden';
                }

                if (result > newLimit) {
                    document.getElementById('danger-limit').style.visibility = 'visible';
                } else {
                    document.getElementById('danger-limit').style.visibility = 'hidden';
                }

                if (sisa_bulan < newTenor || result > newLimit) {
                    document.getElementById('btn-angsuran').disabled = true;
                } else {
                    document.getElementById('btn-angsuran').disabled = false;
                }

                // Ratusan
                let intNumber = result.toFixed(0);
                let ratusanSub = intNumber.toString().substring(intNumber.length - 3, intNumber.length);
                let ratusan = parseInt(ratusanSub);
                let bulat = intNumber - ratusan;
                let newRatusan = 0;

                if (ratusan > 0 && ratusan <= 100) {
                    newRatusan = 100;
                } else if (ratusan > 100 && ratusan <= 200) {
                    $newRatusan = 200;
                } else if (ratusan > 200 && ratusan <= 300) {
                    newRatusan = 300;
                } else if (ratusan > 300 && ratusan <= 400) {
                    newRatusan = 400;
                } else if (ratusan > 400 && ratusan <= 500) {
                    newRatusan = 500;
                } else if (ratusan > 500 && ratusan <= 600) {
                    newRatusan = 600;
                } else if (ratusan > 600 && ratusan <= 700) {
                    newRatusan = 700;
                } else if (ratusan > 700 && ratusan <= 800) {
                    newRatusan = 800;
                } else if (ratusan > 800 && ratusan <= 900) {
                    newRatusan = 900;
                } else if (ratusan > 900 && ratusan <= 999) {
                    newRatusan = 1000;
                } else {
                    newRatusan = ratusan;
                }

                uang = bulat + newRatusan;

                $('#angsuran').attr('value', formatMoney(uang));
                $('#biaya-provisi').attr('value', formatMoney(newJumPinjaman * parseFloat(
                    '{{ $prov }}') / 100));
                $('#biaya-asuransi').attr('value', formatMoney(newJumPinjaman * parseFloat(
                    '{{ $asur }}') / 100));
            });

            function formatMoney(n) {
                return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR"
                }).format(n);
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
            Swal.fire({
                title: 'Error!',
                text: '{{ session()->get('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            })
        </script>
    @endif
@endsection
