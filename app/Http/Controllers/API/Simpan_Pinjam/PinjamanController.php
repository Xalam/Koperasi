<?php

namespace App\Http\Controllers\API\Simpan_Pinjam;

use App\Http\Controllers\API\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Pengaturan\Pengaturan;
use App\Models\Simpan_Pinjam\Pinjaman\Angsuran;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use App\Models\Toko\Transaksi\Piutang\PiutangModel;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $idAnggota = getallheaders()['id'];

        $checkAnggota = Pinjaman::where('id_anggota', $idAnggota)
            ->orderBy('id', 'DESC')->first();

        $checkLimit = Anggota::where('id', $idAnggota)->first();
        $checkPiutang = PiutangModel::where('id_anggota', $idAnggota)->orderBy('id', 'DESC')->first();

        if ($checkPiutang) {
            $piutangAnggota = $checkPiutang->sisa_piutang;
        } else {
            $piutangAnggota = 0;
        }

        $check = Pinjaman::select('id')->orderBy('id', 'DESC')->first();

        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }

        #Biaya Provisi & Asuransi
        $bunga       = Pengaturan::where('id', 1)->first();
        $provisi     = Pengaturan::where('id', 3)->first();
        $asuransi    = Pengaturan::where('id', 4)->first();

        $expBunga    = explode(" ", $bunga->angka);
        $expProvisi  = explode(" ", $provisi->angka);
        $expAsuransi = explode(" ", $asuransi->angka);

        $bung        = $expBunga[0];
        $prov        = $expProvisi[0];
        $asur        = $expAsuransi[0];

        $countProv   = ($prov / 100) * $request->nominal;
        $countAsur   = ($asur / 100) * $request->nominal;

        $totalPinjaman = $request->nominal + (($request->nominal * ($bung / 100)) * $request->tenor);

        if (($checkLimit->limit_gaji - $piutangAnggota) < $request->angsuran) {
            return ResponseFormatter::error('Angsuran melebihi ketentuan');
        } else {
            if ($checkAnggota) {
                $checkStatus = Pinjaman::select('*')
                    ->where('id_anggota', $idAnggota)
                    ->where('status', 0)
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($checkStatus) {
                    return ResponseFormatter::error('Masih terdapat pengajuan yang belum disetujui');
                } else {
                    $checkLunas = Pinjaman::select('*')
                        ->where('id_anggota', $idAnggota)
                        ->where('lunas', 0)
                        ->orderBy('id', 'DESC')
                        ->first();

                    if ($checkLunas) {
                        return ResponseFormatter::error('Pinjaman sebelumnya belum lunas');
                    } else {
                        #Simpan Pinjaman
                        $pinjaman = new Pinjaman();
                        $pinjaman->kode_pinjaman    = 'PNJ-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
                        $pinjaman->id_anggota       = $idAnggota;
                        $pinjaman->tanggal          = $request->tanggal;
                        $pinjaman->nominal_pinjaman = $request->nominal;
                        $pinjaman->bunga            = $bung;
                        $pinjaman->tenor            = $request->tenor;
                        $pinjaman->total_pinjaman   = $totalPinjaman;
                        $pinjaman->nominal_angsuran = $request->angsuran;
                        $pinjaman->biaya_provisi    = $countProv;
                        $pinjaman->biaya_asuransi   = $countAsur;
                        $pinjaman->save();

                        $data = Pinjaman::orderBy('id', 'DESC')->first();
                        return ResponseFormatter::success($data, 'Berhasil menambah pengajuan pinjaman');
                    }
                }
            } else {
                #Simpan Pinjaman
                $pinjaman = new Pinjaman();
                $pinjaman->kode_pinjaman    = 'PNJ-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
                $pinjaman->id_anggota       = $idAnggota;
                $pinjaman->tanggal          = $request->tanggal;
                $pinjaman->nominal_pinjaman = $request->nominal;
                $pinjaman->bunga            = $bung;
                $pinjaman->tenor            = $request->tenor;
                $pinjaman->total_pinjaman   = $totalPinjaman;
                $pinjaman->nominal_angsuran = $request->angsuran;
                $pinjaman->biaya_provisi    = $countProv;
                $pinjaman->biaya_asuransi   = $countAsur;
                $pinjaman->save();

                $data = Pinjaman::orderBy('id', 'DESC')->first();
                return ResponseFormatter::success($data, 'Berhasil menambah pengajuan pinjaman');
            }
        }
    }

    public function history()
    {
        $idAnggota = getallheaders()['id'];

        $data['pengajuan'] = Pinjaman::where('id_anggota', $idAnggota)->orderBy('tanggal', 'DESC')->get();

        $data['angsuran']  = Angsuran::with('pinjaman')
            ->whereHas('pinjaman', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })->where('jenis', 1)->orderBy('tanggal', 'DESC')->get();

        $data['pelunasan']  = Angsuran::with('pinjaman')
            ->whereHas('pinjaman', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })->where('jenis', 2)->orderBy('tanggal', 'DESC')->get();

        return ResponseFormatter::success($data, 'Berhasil mendapatkan data');
    }
}
