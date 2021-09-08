<?php

namespace App\Http\Controllers\Simpan_Pinjam\Simpanan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\KodeJurnal;
use App\Http\Controllers\Simpan_Pinjam\Utils\ResponseMessage;
use App\Http\Controllers\Simpan_Pinjam\Utils\SaveJurnalUmum;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Other\Notifikasi;
use App\Models\Simpan_Pinjam\Simpanan\Saldo;
use App\Models\Simpan_Pinjam\Simpanan\SaldoTarik;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $simpanan   = Simpanan::with('anggota')->orderBy('id', 'DESC')->get();
        $simWaiting = $simpanan->where('status', 0);
        $simAccept  = $simpanan->where('status', 1);
        $pokok      = $simpanan->where('status', 1)->where('jenis_simpanan', 1);
        $wajib      = $simpanan->where('status', 1)->where('jenis_simpanan', 2);
        $sukarela   = $simpanan->where('status', 1)->where('jenis_simpanan', 3);

        $anggota    = Anggota::all();

        if (request()->ajax()) {

            if (request()->type == 'waiting') {
                $data = [];
                $no   = 1;
                $jenis = '';

                foreach ($simWaiting as $key => $value) {
                    if ($value->jenis_simpanan == 1) {
                        $jenis = 'Simpanan Pokok';
                    } else if ($value->jenis_simpanan == 2) {
                        $jenis = 'Simpanan Wajib';
                    } else {
                        $jenis = 'Simpanan Sukarela';
                    }

                    $data[] = [
                        'no' => $no++,
                        'kode_simpanan'  => $value->kode_simpanan,
                        'tanggal'        => date('d-m-Y', strtotime($value->tanggal)),
                        'jenis_simpanan' => $jenis,
                        'nama_anggota'   => $value->anggota->nama_anggota,
                        'nominal'        => number_format($value->nominal, 2, ',', '.'),
                        'status'         => ($value->status == 0) ? '<a href="#modalKonfirmasi" data-remote="' . route('data.konfirmasi', $value->id) . '" 
                                                    data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-info btn-sm">
                                                    <i class="far fa-plus-square"></i>&nbsp; Proses</a>' : '<span class="badge bg-success">Sudah Bayar</span>',
                        'image'          => (($value->image != null) ? '<a href="#modalTransfer" data-remote="' . route('data.image', $value->id) . '" 
                                                    data-toggle="modal" data-target="#modalTransfer" class="btn btn-info btn-sm">
                                                    <i class="fas fa-image"></i></a>' : '-'),
                        'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                        'action'         => ($value->status == 0) ? '<span data-remote="' . route('data.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi"><a href="#modalKonfirmasi" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a></span>'
                            : '<a href="' . route('data.print', $value->id) . '" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fas fa-print"></i></a>' . '<a href="' . route('data.edit', $value->id) . '" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>'
                    ];
                }
                return response()->json(compact('data'));
            } else {
                switch (request()->filter) {
                    case 'pokok':
                        $data = [];
                        $no   = 1;
                        foreach ($pokok as $key => $value) {

                            $data[] = [
                                'no' => $no++,
                                'kode_simpanan'  => $value->kode_simpanan,
                                'tanggal'        => date('d-m-Y', strtotime($value->tanggal)),
                                'jenis_simpanan' => 'Simpanan Pokok',
                                'nama_anggota'   => $value->anggota->nama_anggota,
                                'nominal'        => number_format($value->nominal, 2, ',', '.'),
                                'status'         => ($value->status == 0) ? '<a href="#modalKonfirmasi" data-remote="' . route('data.konfirmasi', $value->id) . '" 
                                                    data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-info btn-sm">
                                                    <i class="far fa-plus-square"></i>&nbsp; Proses</a>' : '<span class="badge bg-success">Sudah Bayar</span>',
                                'image'          => (($value->image != null) ? '<a href="#modalTransfer" data-remote="' . route('data.image', $value->id) . '" 
                                                    data-toggle="modal" data-target="#modalTransfer" class="btn btn-info btn-sm">
                                                    <i class="fas fa-image"></i></a>' : '-'),
                                'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                                'action'         => ($value->status == 0) ? '<span data-remote="' . route('data.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi"><a href="#modalKonfirmasi" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a></span>'
                                    : '<a href="' . route('data.print', $value->id) . '" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fas fa-print"></i></a>' . '<a href="' . route('data.edit', $value->id) . '" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>'
                            ];
                        }
                        return response()->json(compact('data'));
                        break;
                    case 'wajib':
                        $data = [];
                        $no   = 1;
                        foreach ($wajib as $key => $value) {

                            $data[] = [
                                'no' => $no++,
                                'kode_simpanan'  => $value->kode_simpanan,
                                'tanggal'        => date('d-m-Y', strtotime($value->tanggal)),
                                'jenis_simpanan' => 'Simpanan Wajib',
                                'nama_anggota'   => $value->anggota->nama_anggota,
                                'nominal'        => number_format($value->nominal, 2, ',', '.'),
                                'status'         => ($value->status == 0) ? '<a href="#modalKonfirmasi" data-remote="' . route('data.konfirmasi', $value->id) . '" 
                                                    data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-info btn-sm">
                                                    <i class="far fa-plus-square"></i>&nbsp; Proses</a>' : '<span class="badge bg-success">Sudah Bayar</span>',
                                'image'          => (($value->image != null) ? '<a href="#modalTransfer" data-remote="' . route('data.image', $value->id) . '" 
                                                    data-toggle="modal" data-target="#modalTransfer" class="btn btn-info btn-sm">
                                                    <i class="fas fa-image"></i></a>' : '-'),
                                'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                                'action'         => ($value->status == 0) ? '<span data-remote="' . route('data.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi"><a href="#modalKonfirmasi" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a></span>'
                                    : '<a href="' . route('data.print', $value->id) . '" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fas fa-print"></i></a>' . '<a href="' . route('data.edit', $value->id) . '" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>'
                            ];
                        }
                        return response()->json(compact('data'));
                        break;
                    case 'sukarela':
                        $data = [];
                        $no   = 1;
                        foreach ($sukarela as $key => $value) {

                            $data[] = [
                                'no' => $no++,
                                'kode_simpanan'  => $value->kode_simpanan,
                                'tanggal'        => date('d-m-Y', strtotime($value->tanggal)),
                                'jenis_simpanan' => 'Simpanan Sukarela',
                                'nama_anggota'   => $value->anggota->nama_anggota,
                                'nominal'        => number_format($value->nominal, 2, ',', '.'),
                                'status'         => ($value->status == 0) ? '<a href="#modalKonfirmasi" data-remote="' . route('data.konfirmasi', $value->id) . '" 
                                                    data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-info btn-sm">
                                                    <i class="far fa-plus-square"></i>&nbsp; Proses</a>' : '<span class="badge bg-success">Sudah Bayar</span>',
                                'image'          => (($value->image != null) ? '<a href="#modalTransfer" data-remote="' . route('data.image', $value->id) . '" 
                                                    data-toggle="modal" data-target="#modalTransfer" class="btn btn-info btn-sm">
                                                    <i class="fas fa-image"></i></a>' : '-'),
                                'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                                'action'         => ($value->status == 0) ? '<span data-remote="' . route('data.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi"><a href="#modalKonfirmasi" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a></span>'
                                    : '<a href="' . route('data.print', $value->id) . '" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fas fa-print"></i></a>' . '<a href="' . route('data.edit', $value->id) . '" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>'
                            ];
                        }
                        return response()->json(compact('data'));
                        break;
                    default:
                        $data = [];
                        $no   = 1;
                        $jenis = '';

                        foreach ($simAccept as $key => $value) {
                            if ($value->jenis_simpanan == 1) {
                                $jenis = 'Simpanan Pokok';
                            } else if ($value->jenis_simpanan == 2) {
                                $jenis = 'Simpanan Wajib';
                            } else {
                                $jenis = 'Simpanan Sukarela';
                            }

                            $data[] = [
                                'no' => $no++,
                                'kode_simpanan'  => $value->kode_simpanan,
                                'tanggal'        => date('d-m-Y', strtotime($value->tanggal)),
                                'jenis_simpanan' => $jenis,
                                'nama_anggota'   => $value->anggota->nama_anggota,
                                'nominal'        => number_format($value->nominal, 2, ',', '.'),
                                'status'         => ($value->status == 0) ? '<a href="#modalKonfirmasi" data-remote="' . route('data.konfirmasi', $value->id) . '" 
                                                    data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-info btn-sm">
                                                    <i class="far fa-plus-square"></i>&nbsp; Proses</a>' : '<span class="badge bg-success">Sudah Bayar</span>',
                                'image'          => (($value->image != null) ? '<a href="#modalTransfer" data-remote="' . route('data.image', $value->id) . '" 
                                                    data-toggle="modal" data-target="#modalTransfer" class="btn btn-info btn-sm">
                                                    <i class="fas fa-image"></i></a>' : '-'),
                                'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                                'action'         => ($value->status == 0) ? '<span data-remote="' . route('data.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi"><a href="#modalKonfirmasi" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a></span>'
                                    : '<a href="' . route('data.print', $value->id) . '" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fas fa-print"></i></a>' . '<a href="' . route('data.edit', $value->id) . '" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>'
                            ];
                        }
                        return response()->json(compact('data'));
                        break;
                }
            }
        }
        return view('Simpan_Pinjam.simpanan.simpanan', compact('anggota'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anggota = Anggota::get();

        return view('Simpan_Pinjam.simpanan.create')->with([
            'anggota' => $anggota
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check = Simpanan::select('id')->orderBy('id', 'DESC')->first();
        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }

        $checkAkunKas       = Akun::where('kode_akun', 1101)->first();
        $checkSimPokok      = Akun::where('kode_akun', 3101)->first();
        $checkSimWajib      = Akun::where('kode_akun', 3102)->first();
        $checkSimSukarela   = Akun::where('kode_akun', 2121)->first();

        if ($checkAkunKas == null) {
            $idKas = 0;
        } else {
            $idKas = $checkAkunKas->id;
        }

        if ($checkSimPokok == null) {
            $idPokok = 0;
        } else {
            $idPokok = $checkSimPokok->id;
        }

        if ($checkSimWajib == null) {
            $idWajib = 0;
        } else {
            $idWajib = $checkSimWajib->id;
        }

        if ($checkSimSukarela == null) {
            $idSukarela = 0;
        } else {
            $idSukarela = $checkSimSukarela->id;
        }

        #Check Jurnal
        $kodeJurnal = KodeJurnal::kode();

        $data = $request->all();

        $data['kode_simpanan'] = 'SMP-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $data['nominal'] = str_replace('.', '', $request->nominal);
        
        if ($data['status'] == 1) {
            $data['kode_jurnal'] = $kodeJurnal;
        }

        Simpanan::create($data);

        $kodeSimpanan = Simpanan::orderBy('id', 'DESC')->first();

        if ($kodeSimpanan->status == 1) {
            if ($kodeSimpanan->jenis_simpanan == 1) {
                $idSimpan = $idPokok;
            } elseif ($kodeSimpanan->jenis_simpanan == 2) {
                $idSimpan = $idWajib;
            } else {
                $idSimpan = $idSukarela;
            }

            $keterangan = 'Simpanan ( ' . $kodeSimpanan->kode_simpanan . ' )';

            #Simpan Jurnal Simpanan
            SaveJurnalUmum::save($kodeJurnal, $idSimpan, $keterangan, 0, $kodeSimpanan->nominal);

            #Simpan Jurnal Kas
            SaveJurnalUmum::save($kodeJurnal, $idKas, $keterangan, $kodeSimpanan->nominal, 0);

            $anggotaSend = Anggota::where('id', $kodeSimpanan->id_anggota)->first();
            $phoneNumber = $anggotaSend->no_wa;

            #Insert Saldo
            $insertSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)->first();

            if (!$insertSaldo) {
                for ($i = 1; $i < 4; $i++) {
                    $saldo = new Saldo();
                    $saldo->id_anggota = $kodeSimpanan->id_anggota;
                    $saldo->saldo = 0;
                    $saldo->jenis_simpanan = $i;
                    $saldo->save();
                }
            }

            #Update Saldo
            if ($kodeSimpanan->jenis_simpanan == 3) {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 3)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);

                #Send Whatsapp
                $message = 'Simpanan Sukarela atas nama (' . $anggotaSend->nama_anggota . ') sebesar : *Rp ' . number_format($kodeSimpanan->nominal, 0, '', '.') . '* telah ditambahkan. Saldo akhir : *Rp ' . number_format($checkSaldo->saldo, 0, '', '.') . '*';
                ResponseMessage::send($phoneNumber, $message);
            } elseif ($kodeSimpanan->jenis_simpanan == 2) {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 2)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);

                #Send Whatsapp
                $message = 'Simpanan Wajib atas nama (' . $anggotaSend->nama_anggota . ') sebesar : *Rp ' . number_format($kodeSimpanan->nominal, 0, '', '.') . '* telah ditambahkan. Saldo akhir : *Rp ' . number_format($checkSaldo->saldo, 0, '', '.') . '*';
                ResponseMessage::send($phoneNumber, $message);
            } else {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 1)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);

                #Send Whatsapp
                $message = 'Simpanan Pokok atas nama (' . $anggotaSend->nama_anggota . ') sebesar : *Rp ' . number_format($kodeSimpanan->nominal, 0, '', '.') . '* telah ditambahkan. Saldo akhir : *Rp ' . number_format($checkSaldo->saldo, 0, '', '.') . '*';
                ResponseMessage::send($phoneNumber, $message);
            }
        }
        return redirect()->route('data.index')->with([
            'success' => 'Berhasil tambah penyimpanan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $simpanan = Simpanan::with('anggota')->findOrFail($id);

        return view('Simpan_Pinjam.simpanan.edit')->with([
            'simpanan' => $simpanan
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $simpanan = Simpanan::findOrFail($id);

        $checkAkunKas       = Akun::where('kode_akun', 1101)->first();
        $checkSimPokok      = Akun::where('kode_akun', 3101)->first();
        $checkSimWajib      = Akun::where('kode_akun', 3102)->first();
        $checkSimSukarela   = Akun::where('kode_akun', 2121)->first();

        #Check Jurnal
        $kodeJurnal = KodeJurnal::kode();

        if ($checkAkunKas == null) {
            $idKas = 0;
        } else {
            $idKas = $checkAkunKas->id;
        }

        if ($checkSimPokok == null) {
            $idPokok = 0;
        } else {
            $idPokok = $checkSimPokok->id;
        }

        if ($checkSimWajib == null) {
            $idWajib = 0;
        } else {
            $idWajib = $checkSimWajib->id;
        }

        if ($checkSimSukarela == null) {
            $idSukarela = 0;
        } else {
            $idSukarela = $checkSimSukarela->id;
        }

        $simpanan->status = $request->status;
        $simpanan->kode_jurnal = $kodeJurnal;
        $simpanan->update();

        $kodeSimpanan = Simpanan::where('id', $id)->first();

        if ($request->status == 1) {
            if ($kodeSimpanan->jenis_simpanan == 1) {
                $idSimpan = $idPokok;
            } elseif ($kodeSimpanan->jenis_simpanan == 2) {
                $idSimpan = $idWajib;
            } else {
                $idSimpan = $idSukarela;
            }

            $keterangan = 'Simpanan ( ' . $kodeSimpanan->kode_simpanan . ' )';

            #Simpan Jurnal Simpanan
            SaveJurnalUmum::save($kodeJurnal, $idSimpan, $keterangan, 0, $kodeSimpanan->nominal);

            #Simpan Jurnal Kas
            SaveJurnalUmum::save($kodeJurnal, $idKas, $keterangan, $kodeSimpanan->nominal, 0);

            $anggotaSend = Anggota::where('id', $kodeSimpanan->id_anggota)->first();
            $phoneNumber = $anggotaSend->no_wa;

            #Insert Saldo
            $insertSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)->first();

            if (!$insertSaldo) {
                for ($i = 1; $i < 4; $i++) {
                    $saldo = new Saldo();
                    $saldo->id_anggota = $kodeSimpanan->id_anggota;
                    $saldo->saldo = 0;
                    $saldo->jenis_simpanan = $i;
                    $saldo->save();
                }
            }

            #Update Saldo
            if ($kodeSimpanan->jenis_simpanan == 3) {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 3)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);

                #Send Whatsapp
                $message = 'Simpanan Sukarela atas nama (' . $anggotaSend->nama_anggota . ') sebesar : *Rp ' . number_format($kodeSimpanan->nominal, 0, '', '.') . '* telah ditambahkan. Saldo akhir : *Rp ' . number_format($checkSaldo->saldo, 0, '', '.') . '*';
                ResponseMessage::send($phoneNumber, $message);
            } elseif ($kodeSimpanan->jenis_simpanan == 2) {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 2)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);

                #Send Whatsapp
                $message = 'Simpanan Wajib atas nama (' . $anggotaSend->nama_anggota . ') sebesar : *Rp ' . number_format($kodeSimpanan->nominal, 0, '', '.') . '* telah ditambahkan. Saldo akhir : *Rp ' . number_format($checkSaldo->saldo, 0, '', '.') . '*';
                ResponseMessage::send($phoneNumber, $message);
            } else {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 1)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);

                #Send Whatsapp
                $message = 'Simpanan Pokok atas nama (' . $anggotaSend->nama_anggota . ') sebesar : *Rp ' . number_format($kodeSimpanan->nominal, 0, '', '.') . '* telah ditambahkan. Saldo akhir : *Rp ' . number_format($checkSaldo->saldo, 0, '', '.') . '*';
                ResponseMessage::send($phoneNumber, $message);
            }
        }

        return redirect()->route('data.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $simpanan = Simpanan::findOrFail($id);

        $simpanan->delete();

        $notifikasi = new Notifikasi();

        $notifikasi->create([
            'id_anggota' => $simpanan->id_anggota,
            'title'      => 'Penolakan Simpanan',
            'content'    => 'Pengajuan simpanan Anda pada tanggal ' . date('d-m-Y', strtotime($simpanan->tanggal)) . ' sebesar Rp ' . number_format($simpanan->nominal, 0, '', '.') . ' ditolak.'
        ]);

        return redirect()->route('data.index')->with([
            'success' => 'Simpanan anggota berhasil dihapus'
        ]);
    }

    public function print($id)
    {
        $simpanan = Simpanan::with('anggota')->findOrFail($id);

        return view('Simpan_Pinjam.simpanan.print')->with([
            'simpanan' => $simpanan
        ]);
    }

    public function print_show($id)
    {
        $simpanan = Simpanan::with('anggota')->findOrFail($id);

        return view('Simpan_Pinjam.simpanan.print-show')->with([
            'simpanan' => $simpanan
        ]);
    }

    public function modal($id)
    {
        $simpanan = Simpanan::findOrFail($id);

        return view('Simpan_Pinjam.simpanan.modal')->with([
            'simpanan' => $simpanan
        ]);
    }

    public function store_all(Request $request)
    {
        $anggota        = Anggota::pluck('id');
        $count          = $anggota->count();
        $checkAkunKas   = Akun::where('kode_akun', 1101)->first();
        $checkSimWajib  = Akun::where('kode_akun', 3102)->first();

        if ($checkAkunKas == null) {
            $idKas = 0;
        } else {
            $idKas = $checkAkunKas->id;
        }

        if ($checkSimWajib == null) {
            $idWajib = 0;
        } else {
            $idWajib = $checkSimWajib->id;
        }

        for ($i = 0; $i < $count; $i++) {
            #Check Jurnal
            $kodeJurnal = KodeJurnal::kode();

            $check = Simpanan::orderBy('id', 'DESC')->first();

            if ($check == null) {
                $id = 1;
            } else {
                $id = $check->id + 1;
            }

            Simpanan::create([
                'kode_simpanan'  => 'SMP-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT),
                'id_anggota'     => $anggota[$i],
                'tanggal'        => $request->tanggal,
                'jenis_simpanan' => 2,
                'nominal'        => str_replace('.', '', $request->nominal),
                'keterangan'     => $request->keterangan,
                'status'         => 1,
                'kode_jurnal'    => $kodeJurnal
            ]);

            $kodeSimpanan = Simpanan::orderBy('id', 'DESC')->first();

            $keterangan = 'Simpanan ( ' . $kodeSimpanan->kode_simpanan . ' )';

            #Simpan Jurnal Simpanan Wajib
            SaveJurnalUmum::save($kodeJurnal, $idWajib, $keterangan, 0, $kodeSimpanan->nominal);

            #Simpan Jurnal Kas
            SaveJurnalUmum::save($kodeJurnal, $idKas, $keterangan, $kodeSimpanan->nominal, 0);

            #Insert Saldo
            $insertSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)->first();

            if (!$insertSaldo) {
                for ($i = 1; $i < 4; $i++) {
                    $saldo = new Saldo();
                    $saldo->id_anggota = $kodeSimpanan->id_anggota;
                    $saldo->saldo = 0;
                    $saldo->jenis_simpanan = $i;
                    $saldo->save();
                }
            }

            $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                ->where('jenis_simpanan', 2)->first();

            $checkSaldo->update([
                'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
            ]);

            #Send Whatsapp
            $anggotaSend = Anggota::where('id', $anggota[$i])->first();
            $phoneNumber = $anggotaSend->no_wa;

            $message = 'Simpanan Wajib atas nama (' . $anggotaSend->nama_anggota . ') sebesar : *Rp ' . number_format($kodeSimpanan->nominal, 0, '', '.') . '* telah ditambahkan. Saldo akhir : *Rp ' . number_format($checkSaldo->saldo, 0, '', '.') . '*';
            ResponseMessage::send($phoneNumber, $message);
        }

        return redirect()->route('data.index')->with([
            'success' => 'Data simpanan wajib berhasil ditambah'
        ]);
    }

    public function konfirmasi($id)
    {
        $simpanan = Simpanan::findOrFail($id);

        return view('Simpan_Pinjam.simpanan.modal-proses', compact('simpanan'));
    }

    public function edit_all(Request $request, $id)
    {
        $simpanan = Simpanan::findOrFail($id);

        if ($request->status == 0) {
            //Delete Jurnal
            JurnalUmum::where('kode_jurnal', $simpanan->kode_jurnal)->delete();

            $simpanan->kode_jurnal = null;
            //Kurangi Saldo
            $saldo = Saldo::where('id_anggota', $simpanan->id_anggota)->where('jenis_simpanan', $simpanan->jenis_simpanan)->first();

            $saldo->saldo -= $simpanan->nominal;
            $saldo->update();
        }

        $simpanan->status = $request->status;
        $simpanan->update();

        return redirect()->route('data.index')->with([
            'success' => 'Berhasil mengubah data'
        ]);
    }

    public function modal_image($id)
    {
        $simpanan = Simpanan::findOrFail($id);

        return view('Simpan_Pinjam.simpanan.image', compact('simpanan'));
    }

    public function delete_simpanan()
    {
        $simpanan       = Simpanan::where('status', 0)->whereNull('image')->where(DB::raw("DATE_ADD(created_at, INTERVAL 1 DAY)"), '<', DB::raw("NOW()"))->get();
        $tarikSaldo     = SaldoTarik::where('status', 0)->where(DB::raw("DATE_ADD(tanggal, INTERVAL 1 DAY)"), '<', date('Y-m-d'))->get();
        $tarikSaldo_    = SaldoTarik::where('status', 1)->where(DB::raw("DATE_ADD(updated_at, INTERVAL 1 DAY)"), '<', DB::raw("NOW()"))->get();

        for ($i = 0; $i < sizeof($simpanan); $i++) {
            $notifikasi = new Notifikasi();

            $notifikasi->create([
                'id_anggota' => $simpanan[$i]->id_anggota,
                'title'      => 'Penolakan Simpanan',
                'content'    => 'Pengajuan simpanan Anda pada tanggal ' . date('d-m-Y', strtotime($simpanan[$i]->tanggal)) . ' sebesar Rp ' . number_format($simpanan[$i]->nominal, 0, '', '.') . ' ditolak karena melebihi batas waktu yang telah ditentukan.'
            ]);

            $simpanan[$i]->delete();
        }

        for ($i = 0; $i < sizeof($tarikSaldo); $i++) {
            $notifikasi = new Notifikasi();

            $notifikasi->create([
                'id_anggota' => $tarikSaldo[$i]->saldo->id_anggota,
                'title'      => 'Penolakan Penarikan Simpanan',
                'content'    => 'Pengajuan penarikan simpanan Anda pada tanggal ' . date('d-m-Y', strtotime($tarikSaldo[$i]->tanggal)) . ' sebesar Rp ' . number_format($tarikSaldo[$i]->nominal, 0, '', '.') . ' ditolak karena melebihi batas waktu yang telah ditentukan.'
            ]);

            $tarikSaldo[$i]->delete();
        }

        for ($i = 0; $i < sizeof($tarikSaldo_); $i++) {
            $notifikasi = new Notifikasi();

            $notifikasi->create([
                'id_anggota' => $tarikSaldo_[$i]->saldo->id_anggota,
                'title'      => 'Penolakan Penarikan Simpanan',
                'content'    => 'Pengajuan penarikan simpanan Anda pada tanggal ' . date('d-m-Y', strtotime($tarikSaldo_[$i]->tanggal)) . ' sebesar Rp ' . number_format($tarikSaldo_[$i]->nominal, 0, '', '.') . ' ditolak karena melebihi batas waktu yang telah ditentukan.'
            ]);

            $tarikSaldo_[$i]->delete();
        }
    }
}
