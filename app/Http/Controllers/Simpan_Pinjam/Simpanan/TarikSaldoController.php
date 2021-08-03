<?php

namespace App\Http\Controllers\Simpan_Pinjam\Simpanan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\KodeJurnal;
use App\Http\Controllers\Simpan_Pinjam\Utils\ResponseMessage;
use App\Http\Controllers\Simpan_Pinjam\Utils\SaveJurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Other\Notifikasi;
use App\Models\Simpan_Pinjam\Simpanan\Saldo;
use App\Models\Simpan_Pinjam\Simpanan\SaldoTarik;
use Illuminate\Http\Request;

class TarikSaldoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tarikSaldo = SaldoTarik::with('saldo.anggota')->orderBy('id', 'DESC')->get();
        $permintaanMasuk = $tarikSaldo->where('status', 0);
        $permintaanProses = $tarikSaldo->where('status', 1);

        if (request()->ajax()) {
            switch (request()->type) {
                case 'permintaan_masuk':
                    $data = [];
                    $no   = 1;

                    foreach ($permintaanMasuk as $key => $value) {
                        $data[] = [
                            'no'       => $no++,
                            'tanggal'  => date('d-m-Y', strtotime($value->tanggal)),
                            'nama'     => $value->saldo->anggota->nama_anggota,
                            'nominal'  => number_format($value->nominal, 2, ',', '.'),
                            'action'   => '<a href="#modalKonfirmasi" data-remote="' . route('tarik-saldo.konfirmasi', $value->id) . '" 
                                        data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-info btn-sm">
                                        <i class="far fa-plus-square"></i>&nbsp; Proses</a>&nbsp; <a href="#modalKonfirmasi" data-remote="' . route('tarik-saldo.modal-delete', $value->id) . '" 
                                        data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>&nbsp; Hapus</a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                case 'permintaan_proses':
                    $data = [];
                    $no   = 1;

                    foreach ($permintaanProses as $key => $value) {
                        $data[] = [
                            'no'       => $no++,
                            'tanggal'  => date('d-m-Y', strtotime($value->tanggal)),
                            'nama'     => $value->saldo->anggota->nama_anggota,
                            'nominal'  => number_format($value->nominal, 2, ',', '.'),
                            'action'   => '<a href="#modalKonfirmasi" data-remote="' . route('tarik-saldo.konfirmasi', $value->id) . '" 
                                        data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-success btn-sm">
                                        <i class="far fa-check-square"></i>&nbsp; Cair</a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                    break;
            }
        }
        return view('Simpan_Pinjam.simpanan.tarik-saldo.tarik-saldo');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anggota = Anggota::get();

        return view('Simpan_Pinjam.simpanan.tarik-saldo.create', compact('anggota'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $saldo = Saldo::where('id_anggota', $request->id_anggota)
            ->where('jenis_simpanan', $request->jenis_simpanan)->first();

        $tarikSaldo = new SaldoTarik();
        $tarikSaldo->id_saldo   = $saldo->id;
        $tarikSaldo->tanggal    = date('Y-m-d');
        $tarikSaldo->nominal    = str_replace('.', '', $request->nominal);
        $tarikSaldo->status     = 0;
        $tarikSaldo->save();

        return redirect()->route('tarik-saldo.index')->with([
            'success' => 'Berhasil menambah penarikan'
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
        //
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
        $tarikSaldo = SaldoTarik::with('saldo')->findOrFail($id);
        $saldo = Saldo::findOrFail($tarikSaldo->saldo->id);
        $error = '';

        $checkAkunKas       = Akun::where('kode_akun', 1101)->first();
        $checkSimSukarela   = Akun::where('kode_akun', 2121)->first();
        $checkSimWajib      = Akun::where('kode_akun', 3102)->first();
        $checkSimPokok      = Akun::where('kode_akun', 3101)->first();

        if ($saldo->saldo >= $tarikSaldo->nominal) {
            $tarikSaldo->update(['status' => $request->status]);

            if ($tarikSaldo->status == 2) {
                #Check Jurnal
                $kodeJurnal = KodeJurnal::kode();

                $penarikan = SaldoTarik::where('id', $id)->first();

                $keterangan = 'Penarikan ( ' . $penarikan->saldo->anggota->nama_anggota . ' )';

                #Simpan Jurnal Kas
                SaveJurnalUmum::save($kodeJurnal, $checkAkunKas->id, $keterangan, 0, $penarikan->nominal);

                if ($saldo->jenis_simpanan == 1) {
                    $idJenisSimpanan = $checkSimPokok->id;
                } elseif ($saldo->jenis_simpanan == 2) {
                    $idJenisSimpanan = $checkSimWajib->id;
                } else {
                    $idJenisSimpanan = $checkSimSukarela->id;
                }
                #Simpan Jurnal Simpanan
                SaveJurnalUmum::save($kodeJurnal, $idJenisSimpanan, $keterangan, $penarikan->nominal, 0);

                $saldo->update([
                    'saldo' => $saldo->saldo - $tarikSaldo->nominal
                ]);

                $tarikSaldo->update([
                    'kode_jurnal' => $kodeJurnal
                ]);

                #Send Whatsapp
                if ($saldo->jenis_simpanan == 1) {
                    $simpananName = 'Simpanan Pokok';
                } else if ($saldo->jenis_simpanan == 2) {
                    $simpananName = 'Simpanan Wajib';
                } else {
                    $simpananName = 'Simpanan Sukarela';
                }

                $phoneNumber = $penarikan->saldo->anggota->no_wa;

                $message = 'Penarikan ' . $simpananName . ' atas nama (' . $penarikan->saldo->anggota->nama_anggota . ') sebesar : *Rp ' . number_format($tarikSaldo->nominal, 0, '', '.') . '* telah disetujui. Saldo akhir : *Rp ' . number_format($saldo->saldo, 0, '', '.') . '*';
                ResponseMessage::send($phoneNumber, $message);
            }
        } else {
            $error = 'error';
        }

        return redirect()->route('tarik-saldo.index')->with([
            $error => 'Saldo anggota tidak cukup'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tarikSaldo = SaldoTarik::findOrFail($id);

        $tarikSaldo->delete();

        $notifikasi = new Notifikasi();

        $notifikasi->create([
            'id_anggota' => $tarikSaldo->saldo->id_anggota,
            'title'      => 'Penolakan Penarikan Simpanan',
            'content'    => 'Pengajuan penarikan simpanan Anda pada tanggal ' . date('d-m-Y', strtotime($tarikSaldo->tanggal)) . ' sebesar Rp ' . number_format($tarikSaldo->nominal, 0, '', '.') . ' ditolak.'
        ]);

        return redirect()->route('tarik-saldo.index')->with([
            'success' => 'Penarikan berhasil dihapus'
        ]);
    }

    public function modal($id)
    {
        $tarikSaldo = SaldoTarik::findOrFail($id);

        return view('Simpan_Pinjam.simpanan.tarik-saldo.modal', compact('tarikSaldo'));
    }

    public function history()
    {
        $history = SaldoTarik::with('saldo.anggota')->where('status', 2)->get();

        if (request()->ajax()) {
            $data = [];
            $no   = 1;

            foreach ($history as $key => $value) {
                $jenisSimpanan = '';

                if ($value->saldo->jenis_simpanan == 1) {
                    $jenisSimpanan = 'Simpanan Pokok';
                } elseif ($value->saldo->jenis_simpanan == 2) {
                    $jenisSimpanan = 'Simpanan Wajib';
                } else {
                    $jenisSimpanan = 'Simpanan Sukarela';
                }

                $data[] = [
                    'no'        => $no++,
                    'tanggal'   => date('d-m-Y', strtotime($value->tanggal)),
                    'nama'      => $value->saldo->anggota->nama_anggota,
                    'nominal'   => number_format($value->nominal, 2, ',', '.'),
                    'jenis'     => $jenisSimpanan,
                    'jurnal'    => $value->kode_jurnal,
                    'action'    => '<a href="' . route('tarik-saldo.print', $value->id) . '" class="btn btn-default btn-sm">
                    <i class="fas fa-print"></i>&nbsp; Cetak</a>'
                ];
            }
            return response()->json(compact('data'));
        }

        return view('Simpan_Pinjam.simpanan.tarik-saldo.riwayat');
    }

    public function print($id)
    {
        $tarikSaldo = SaldoTarik::with('saldo.anggota')->findOrFail($id);

        return view('Simpan_Pinjam.simpanan.tarik-saldo.print', compact('tarikSaldo'));
    }

    public function print_show($id)
    {
        $tarikSaldo = SaldoTarik::with('saldo.anggota')->findOrFail($id);

        return view('Simpan_Pinjam.simpanan.tarik-saldo.print-show', compact('tarikSaldo'));
    }

    public function modal_delete($id)
    {
        $tarikSaldo = SaldoTarik::findOrFail($id);

        return view('Simpan_Pinjam.simpanan.tarik-saldo.modal-delete', compact('tarikSaldo'));
    }

    public function saldo(Request $request)
    {
        $saldo = Saldo::select('saldo')->where('id_anggota', $request->id)
            ->where('jenis_simpanan', $request->id_simpan)->first();

        $data = array(
            'saldo' => 0
        );

        if ($saldo) {
            $data = array(
                'saldo' => $saldo->saldo
            );
            return response()->json($data);
        } else {

            return response()->json($data);
        }
    }
}
