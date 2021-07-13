<?php

namespace App\Http\Controllers\Simpan_Pinjam\Simpanan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Other\Notifikasi;
use App\Models\Simpan_Pinjam\Simpanan\Saldo;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
use Illuminate\Http\Request;

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
        $pokok      = $simpanan->where('jenis_simpanan', 1);
        $wajib      = $simpanan->where('jenis_simpanan', 2);
        $sukarela   = $simpanan->where('jenis_simpanan', 3);

        $anggota    = Anggota::all();

        if (request()->ajax()) {
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
                            'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                            'action'         => ($value->status == 0) ? '<span data-remote="' . route('data.modal', $value->id) . '" data-toggle="modal" data-target="#mymodal"><a href="#mymodal" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="far fa-trash-alt"></i></a></span>'
                                : '<a href="' . route('data.print', $value->id) . '" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fas fa-print"></i></a>' . '<a href="' . route('data.edit', $value->id) . '" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>'
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
                            'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                            'action'         => ($value->status == 0) ? '<span data-remote="' . route('data.modal', $value->id) . '" data-toggle="modal" data-target="#mymodal"><a href="#mymodal" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="far fa-trash-alt"></i></a></span>'
                                : '<a href="' . route('data.print', $value->id) . '" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fas fa-print"></i></a>' . '<a href="' . route('data.edit', $value->id) . '" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>'
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
                            'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                            'action'         => ($value->status == 0) ? '<span data-remote="' . route('data.modal', $value->id) . '" data-toggle="modal" data-target="#mymodal"><a href="#mymodal" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="far fa-trash-alt"></i></a></span>'
                                : '<a href="' . route('data.print', $value->id) . '" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fas fa-print"></i></a>' . '<a href="' . route('data.edit', $value->id) . '" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                default:
                    $data = [];
                    $no   = 1;
                    $jenis = '';

                    foreach ($simpanan as $key => $value) {
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
                            'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                            'action'         => ($value->status == 0) ? '<span data-remote="' . route('data.modal', $value->id) . '" data-toggle="modal" data-target="#mymodal"><a href="#mymodal" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="far fa-trash-alt"></i></a></span>'
                                : '<a href="' . route('data.print', $value->id) . '" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fas fa-print"></i></a>' . '<a href="' . route('data.edit', $value->id) . '" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
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
        $checkJurnal = JurnalUmum::select('*')->orderBy('id', 'DESC')->first();
        if ($checkJurnal == null) {
            $idJurnal = 1;
        } else {
            $substrKode = substr($checkJurnal->kode_jurnal, 3);
            $idJurnal   = $substrKode + 1;
        }

        $data = $request->all();

        $data['kode_simpanan'] = 'SMP-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $data['nominal'] = str_replace('.', '', $request->nominal);
        $data['kode_jurnal'] = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);

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
            #Simpan Jurnal Simpanan
            $jurnal = new JurnalUmum();
            $jurnal->kode_jurnal    = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
            $jurnal->id_akun        = $idSimpan;
            $jurnal->tanggal        = date('Y-m-d');
            $jurnal->keterangan     = 'Simpanan ( ' . $kodeSimpanan->kode_simpanan . ' )';
            $jurnal->debet          = 0;
            $jurnal->kredit         = $kodeSimpanan->nominal;
            $jurnal->save();

            #Simpan Jurnal Kas
            $jurnal = new JurnalUmum();
            $jurnal->kode_jurnal    = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
            $jurnal->id_akun        = $idKas;
            $jurnal->tanggal        = date('Y-m-d');
            $jurnal->keterangan     = 'Simpanan ( ' . $kodeSimpanan->kode_simpanan . ' )';
            $jurnal->debet          = $kodeSimpanan->nominal;
            $jurnal->kredit         = 0;
            $jurnal->save();

            #Update Saldo
            if ($kodeSimpanan->jenis_simpanan == 3) {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 3)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);
            } elseif ($kodeSimpanan->jenis_simpanan == 2) {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 2)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);
            } else {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 1)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);
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
        $checkJurnal = JurnalUmum::select('*')->orderBy('id', 'DESC')->first();
        if ($checkJurnal == null) {
            $idJurnal = 1;
        } else {
            $substrKode = substr($checkJurnal->kode_jurnal, 3);
            $idJurnal   = $substrKode + 1;
        }

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
        $simpanan->kode_jurnal = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
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
            #Simpan Jurnal Simpanan
            $jurnal = new JurnalUmum();
            $jurnal->kode_jurnal    = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
            $jurnal->id_akun        = $idSimpan;
            $jurnal->tanggal        = date('Y-m-d');
            $jurnal->keterangan     = 'Simpanan ( ' . $kodeSimpanan->kode_simpanan . ' )';
            $jurnal->debet          = 0;
            $jurnal->kredit         = $kodeSimpanan->nominal;
            $jurnal->save();

            #Simpan Jurnal Kas
            $jurnal = new JurnalUmum();
            $jurnal->kode_jurnal    = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
            $jurnal->id_akun        = $idKas;
            $jurnal->tanggal        = date('Y-m-d');
            $jurnal->keterangan     = 'Simpanan ( ' . $kodeSimpanan->kode_simpanan . ' )';
            $jurnal->debet          = $kodeSimpanan->nominal;
            $jurnal->kredit         = 0;
            $jurnal->save();

            #Update Saldo
            if ($kodeSimpanan->jenis_simpanan == 3) {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 3)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);
            } elseif ($kodeSimpanan->jenis_simpanan == 2) {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 2)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);
            } else {
                $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                    ->where('jenis_simpanan', 1)->first();

                $checkSaldo->update([
                    'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
                ]);
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
            $checkJurnal = JurnalUmum::select('*')->orderBy('id', 'DESC')->first();
            if ($checkJurnal == null) {
                $idJurnal = 1;
            } else {
                $substrKode = substr($checkJurnal->kode_jurnal, 3);
                $idJurnal   = $substrKode + 1;
            }

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
                'kode_jurnal'    => 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT)
            ]);

            $kodeSimpanan = Simpanan::orderBy('id', 'DESC')->first();

            #Simpan Jurnal Simpanan Wajib
            $jurnal = new JurnalUmum();
            $jurnal->kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
            $jurnal->id_akun        = $idWajib;
            $jurnal->tanggal        = date('Y-m-d');
            $jurnal->keterangan     = 'Simpanan ( ' . $kodeSimpanan->kode_simpanan . ' )';
            $jurnal->debet          = 0;
            $jurnal->kredit         = $kodeSimpanan->nominal;
            $jurnal->save();

            #Simpan Jurnal Kas
            $jurnal = new JurnalUmum();
            $jurnal->kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
            $jurnal->id_akun        = $idKas;
            $jurnal->tanggal        = date('Y-m-d');
            $jurnal->keterangan     = 'Simpanan ( ' . $kodeSimpanan->kode_simpanan . ' )';
            $jurnal->debet          = $kodeSimpanan->nominal;
            $jurnal->kredit         = 0;
            $jurnal->save();

            $checkSaldo = Saldo::where('id_anggota', $kodeSimpanan->id_anggota)
                ->where('jenis_simpanan', 2)->first();

            $checkSaldo->update([
                'saldo' => $checkSaldo->saldo + $kodeSimpanan->nominal
            ]);
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
}
