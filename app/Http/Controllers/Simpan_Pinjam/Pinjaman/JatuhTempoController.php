<?php

namespace App\Http\Controllers\Simpan_Pinjam\Pinjaman;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Pinjaman\Angsuran;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JatuhTempoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $angsuran = Angsuran::with('pinjaman')->whereIn('id', function ($q) {
            $q->select(DB::raw('MAX(id) FROM tb_angsuran'))->groupBy('id_pinjaman');
        })->where('jenis', 2)->orderBy('id', 'DESC')->get();

        if (request()->ajax()) {
            $data = [];
            $no   = 1;
            foreach ($angsuran as $key => $value) {
                $data[] = [
                    'no'            => $no++,
                    'kode'          => $value->pinjaman->kode_pinjaman,
                    'kode_anggota'  => $value->pinjaman->anggota->kd_anggota,
                    'tanggal'       => date('d-m-Y', strtotime($value->tanggal)),
                    'nama'          => $value->pinjaman->anggota->nama_anggota,
                    'nominal'       => 'Rp. ' . number_format($value->nominal_angsuran, '2', ',', '.'),
                    'angsuran'      => $value->pinjaman->angsuran_ke,
                    'status'        => (($value->status == 0) ? '<a href="#modalKonfirmasi" data-remote="' . route('tempo.konfirmasi', $value->id) . '" 
                           data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-primary btn-sm"><i class="far fa-plus-square"></i>&nbsp; Proses</a>' :
                        '<span class="badge badge-success">Disetujui</span>') . (($value->lunas == 1) ? '<span class="badge badge-success">Lunas</span>' : ''),
                    'action'        => (($value->status == 1) ? '<a href="' . route('tempo.print-show', $value->id) . '" class="btn btn-light btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak</a>' : '')
                ];
            }
            return response()->json(compact('data'));
        }
        return view('Simpan_Pinjam.pinjaman.angsuran-tempo.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #Update Pinjaman
        $pinjamanUpdate = Pinjaman::findOrFail($request->id_pinjaman);

        $pinjamanUpdate->angsuran_ke = $pinjamanUpdate->angsuran_ke += 1;
        $pinjamanUpdate->lunas = 1;

        $pinjamanUpdate->update();

        #Kode Angsuran
        $check = Angsuran::select('*')->orderBy('id', 'DESC')->first();
        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }

        #Check Jurnal
        $checkJurnal = JurnalUmum::select('*')->orderBy('id', 'DESC')->first();
        if ($checkJurnal == null) {
            $idJurnal = 1;
        } else {
            $substrKode = substr($checkJurnal->kode_jurnal, 3);
            $idJurnal   = $substrKode + 1;
        }

        $angsuran = new Angsuran();
        $angsuran->kode_angsuran    = 'ASN-' . str_replace('-', '', date('Y-m-d')) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $angsuran->id_pinjaman      = $request->id_pinjaman;
        $angsuran->tanggal          = $request->tanggal;
        $angsuran->nominal_angsuran = $pinjamanUpdate->nominal_angsuran;
        $angsuran->sisa_angsuran    = 0;
        $angsuran->sisa_bayar       = 0;
        $angsuran->potongan         = str_replace('.', '', $request->potongan);
        $angsuran->status           = 1;
        $angsuran->lunas            = 1;
        $angsuran->jenis            = 2;
        $angsuran->keterangan       = $request->keterangan;
        $angsuran->kode_jurnal      = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $angsuran->save();

        //Input Jurnal Umum
        #Check Akun
        $checkAkunKas        = Akun::where('kode_akun', 1101)->first();
        $checkAkunPiutang    = Akun::where('kode_akun', 1121)->first();
        $checkAkunPendapatan = Akun::where('kode_akun', 4101)->first();

        if ($checkAkunKas == null) {
            $idKas = 0;
        } else {
            $idKas = $checkAkunKas->id;
        }

        if ($checkAkunPiutang == null) {
            $idPiutang = 0;
        } else {
            $idPiutang = $checkAkunPiutang->id;
        }

        if ($checkAkunPendapatan == null) {
            $idPendapatan = 0;
        } else {
            $idPendapatan = $checkAkunPendapatan->id;
        }

        $kodeAngsuran = Angsuran::orderBy('id', 'DESC')->first();

        #Pembulatan Pendapatan
        $pendapatan = round(($kodeAngsuran->pinjaman->total_pinjaman - $kodeAngsuran->pinjaman->nominal_pinjaman) / $kodeAngsuran->pinjaman->tenor, 2);

        $intNumberPen = (int) $pendapatan;

        $ratusanPen = substr($intNumberPen, -3);

        $bulatPen = $intNumberPen - $ratusanPen;
        $newRatusanPen = 0;

        if ($ratusanPen > 0 && $ratusanPen <= 100) {
            $newRatusanPen = 100;
        } else if ($ratusanPen > 100 && $ratusanPen <= 200) {
            $newRatusanPen = 200;
        } else if ($ratusanPen > 200 && $ratusanPen <= 300) {
            $newRatusanPen = 300;
        } else if ($ratusanPen > 300 && $ratusanPen <= 400) {
            $newRatusanPen = 400;
        } else if ($ratusanPen > 400 && $ratusanPen <= 500) {
            $newRatusanPen = 500;
        } else if ($ratusanPen > 500 && $ratusanPen <= 600) {
            $newRatusanPen = 600;
        } else if ($ratusanPen > 600 && $ratusanPen <= 700) {
            $newRatusanPen = 700;
        } else if ($ratusanPen > 700 && $ratusanPen <= 800) {
            $newRatusanPen = 800;
        } else if ($ratusanPen > 800 && $ratusanPen <= 900) {
            $newRatusanPen = 900;
        } else if ($ratusanPen > 900 && $ratusanPen <= 999) {
            $newRatusanPen = 1000;
        } else {
            $newRatusanPen = $ratusanPen;
        }

        $newPendapatan = $bulatPen + $newRatusanPen;

        #Pembulatan Piutang
        $piutang = round($kodeAngsuran->pinjaman->nominal_pinjaman / $kodeAngsuran->pinjaman->tenor, 2);
        $intNumberPi = (int) $piutang;

        $ratusanPi = substr($intNumberPi, -3);

        $bulatPi = $intNumberPi - $ratusanPi;
        $newRatusanPi = 0;

        if ($ratusanPi > 0 && $ratusanPi <= 100) {
            $newRatusanPi = 100;
        } else if ($ratusanPi > 100 && $ratusanPi <= 200) {
            $newRatusanPi = 200;
        } else if ($ratusanPi > 200 && $ratusanPi <= 300) {
            $newRatusanPi = 300;
        } else if ($ratusanPi > 300 && $ratusanPi <= 400) {
            $newRatusanPi = 400;
        } else if ($ratusanPi > 400 && $ratusanPi <= 500) {
            $newRatusanPi = 500;
        } else if ($ratusanPi > 500 && $ratusanPi <= 600) {
            $newRatusanPi = 600;
        } else if ($ratusanPi > 600 && $ratusanPi <= 700) {
            $newRatusanPi = 700;
        } else if ($ratusanPi > 700 && $ratusanPi <= 800) {
            $newRatusanPi = 800;
        } else if ($ratusanPi > 800 && $ratusanPi <= 900) {
            $newRatusanPi = 900;
        } else if ($ratusanPi > 900 && $ratusanPi <= 999) {
            $newRatusanPi = 1000;
        } else {
            $newRatusanPi = $ratusanPi;
        }

        $newPiutang = $bulatPi + $newRatusanPi;

        #Simpan Jurnal Pendapatan
        $jurnal = new JurnalUmum();
        $jurnal->kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $jurnal->id_akun        = $idPendapatan;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = 'Angsuran ( ' . $kodeAngsuran->kode_angsuran . ' )';
        $jurnal->debet          = 0;
        $jurnal->kredit         = $newPendapatan;
        $jurnal->save();

        #Simpan Jurnal Piutang
        $jurnal = new JurnalUmum();
        $jurnal->kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $jurnal->id_akun        = $idPiutang;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = 'Angsuran ( ' . $kodeAngsuran->kode_angsuran . ' )';
        $jurnal->debet          = 0;
        $jurnal->kredit         = $newPiutang;
        $jurnal->save();

        #Simpan Jurnal Kas
        $jurnal = new JurnalUmum();
        $jurnal->kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $jurnal->id_akun        = $idKas;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = 'Angsuran ( ' . $kodeAngsuran->kode_angsuran . ' )';
        $jurnal->debet          = $kodeAngsuran->nominal_angsuran;
        $jurnal->kredit         = 0;
        $jurnal->save();

        return redirect()->route('angsuran.index')->with([
            'success' => 'Berhasil melunasi angsuran'
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
        $angsuran = Angsuran::findOrFail($id);

        #Update Pinjaman
        $pinjamanUpdate = Pinjaman::findOrFail($angsuran->id_pinjaman);

        $pinjamanUpdate->angsuran_ke = $pinjamanUpdate->angsuran_ke += 1;
        $pinjamanUpdate->lunas = 1;

        $pinjamanUpdate->update();

        //Input Jurnal Umum
        #Check Akun
        $checkAkunKas        = Akun::where('kode_akun', 1101)->first();
        $checkAkunPiutang    = Akun::where('kode_akun', 1121)->first();
        $checkAkunPendapatan = Akun::where('kode_akun', 4101)->first();

        if ($checkAkunKas == null) {
            $idKas = 0;
        } else {
            $idKas = $checkAkunKas->id;
        }

        if ($checkAkunPiutang == null) {
            $idPiutang = 0;
        } else {
            $idPiutang = $checkAkunPiutang->id;
        }

        if ($checkAkunPendapatan == null) {
            $idPendapatan = 0;
        } else {
            $idPendapatan = $checkAkunPendapatan->id;
        }

        #Check Jurnal
        $checkJurnal = JurnalUmum::select('*')->orderBy('id', 'DESC')->first();
        if ($checkJurnal == null) {
            $idJurnal = 1;
        } else {
            $substrKode = substr($checkJurnal->kode_jurnal, 3);
            $idJurnal   = $substrKode + 1;
        }

        $angsuran->status = $request->status;
        $angsuran->lunas = 1;
        $angsuran->kode_jurnal = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $angsuran->update();

        $kodeAngsuran = Angsuran::where('id', $id)->first();

        #Pembulatan Pendapatan
        $pendapatan = round(($kodeAngsuran->pinjaman->total_pinjaman - $kodeAngsuran->pinjaman->nominal_pinjaman) / $kodeAngsuran->pinjaman->tenor, 2);

        $intNumberPen = (int) $pendapatan;

        $ratusanPen = substr($intNumberPen, -3);

        $bulatPen = $intNumberPen - $ratusanPen;
        $newRatusanPen = 0;

        if ($ratusanPen > 0 && $ratusanPen <= 100) {
            $newRatusanPen = 100;
        } else if ($ratusanPen > 100 && $ratusanPen <= 200) {
            $newRatusanPen = 200;
        } else if ($ratusanPen > 200 && $ratusanPen <= 300) {
            $newRatusanPen = 300;
        } else if ($ratusanPen > 300 && $ratusanPen <= 400) {
            $newRatusanPen = 400;
        } else if ($ratusanPen > 400 && $ratusanPen <= 500) {
            $newRatusanPen = 500;
        } else if ($ratusanPen > 500 && $ratusanPen <= 600) {
            $newRatusanPen = 600;
        } else if ($ratusanPen > 600 && $ratusanPen <= 700) {
            $newRatusanPen = 700;
        } else if ($ratusanPen > 700 && $ratusanPen <= 800) {
            $newRatusanPen = 800;
        } else if ($ratusanPen > 800 && $ratusanPen <= 900) {
            $newRatusanPen = 900;
        } else if ($ratusanPen > 900 && $ratusanPen <= 999) {
            $newRatusanPen = 1000;
        } else {
            $newRatusanPen = $ratusanPen;
        }

        $newPendapatan = $bulatPen + $newRatusanPen;

        #Pembulatan Piutang
        $piutang = round($kodeAngsuran->pinjaman->nominal_pinjaman / $kodeAngsuran->pinjaman->tenor, 2);
        $intNumberPi = (int) $piutang;

        $ratusanPi = substr($intNumberPi, -3);

        $bulatPi = $intNumberPi - $ratusanPi;
        $newRatusanPi = 0;

        if ($ratusanPi > 0 && $ratusanPi <= 100) {
            $newRatusanPi = 100;
        } else if ($ratusanPi > 100 && $ratusanPi <= 200) {
            $newRatusanPi = 200;
        } else if ($ratusanPi > 200 && $ratusanPi <= 300) {
            $newRatusanPi = 300;
        } else if ($ratusanPi > 300 && $ratusanPi <= 400) {
            $newRatusanPi = 400;
        } else if ($ratusanPi > 400 && $ratusanPi <= 500) {
            $newRatusanPi = 500;
        } else if ($ratusanPi > 500 && $ratusanPi <= 600) {
            $newRatusanPi = 600;
        } else if ($ratusanPi > 600 && $ratusanPi <= 700) {
            $newRatusanPi = 700;
        } else if ($ratusanPi > 700 && $ratusanPi <= 800) {
            $newRatusanPi = 800;
        } else if ($ratusanPi > 800 && $ratusanPi <= 900) {
            $newRatusanPi = 900;
        } else if ($ratusanPi > 900 && $ratusanPi <= 999) {
            $newRatusanPi = 1000;
        } else {
            $newRatusanPi = $ratusanPi;
        }

        $newPiutang = $bulatPi + $newRatusanPi;

        #Simpan Jurnal Pendapatan
        $jurnal = new JurnalUmum();
        $jurnal->kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $jurnal->id_akun        = $idPendapatan;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = 'Angsuran ( ' . $kodeAngsuran->kode_angsuran . ' )';
        $jurnal->debet          = 0;
        $jurnal->kredit         = $newPendapatan;
        $jurnal->save();

        #Simpan Jurnal Piutang
        $jurnal = new JurnalUmum();
        $jurnal->kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $jurnal->id_akun        = $idPiutang;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = 'Angsuran ( ' . $kodeAngsuran->kode_angsuran . ' )';
        $jurnal->debet          = 0;
        $jurnal->kredit         = $newPiutang;
        $jurnal->save();

        #Simpan Jurnal Kas
        $jurnal = new JurnalUmum();
        $jurnal->kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $jurnal->id_akun        = $idKas;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = 'Angsuran ( ' . $kodeAngsuran->kode_angsuran . ' )';
        $jurnal->debet          = $kodeAngsuran->nominal_angsuran;
        $jurnal->kredit         = 0;
        $jurnal->save();

        return redirect()->route('tempo.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function bayar(Request $request)
    {
        $pinjaman = Pinjaman::where('kode_pinjaman', $request->kode_bayar);

        if ($pinjaman->get()->count() > 0) {

            if ($pinjaman->where('status', 1)->get()->count() > 0) {
                if ($pinjaman->where('lunas', 0)->get()->count() > 0) {

                    $data = $pinjaman->firstOrFail();

                    return view('Simpan_Pinjam.pinjaman.angsuran-tempo.bayar', compact('data'));
                } else {
                    return redirect()->route('tempo.index')->with([
                        'error' => 'Kode pinjaman sudah lunas'
                    ]);
                }
            } else {
                return redirect()->route('tempo.index')->with([
                    'error' => 'Kode pinjaman belum disetujui'
                ]);
            }
        } else {
            return redirect()->route('tempo.index')->with([
                'error' => 'Kode pinjaman tidak ditemukan'
            ]);
        }
    }

    public function print_show($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.angsuran-tempo.print-show', compact('angsuran'));
    }

    public function konfirmasi($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.angsuran-tempo.modal', compact('angsuran'));
    }
}
