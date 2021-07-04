<?php

namespace App\Http\Controllers\API\Simpan_Pinjam;

use App\Http\Controllers\API\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Simpanan\Saldo;
use App\Models\Simpan_Pinjam\Simpanan\SaldoTarik;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    public function index()
    {
        $idAnggota = getallheaders()['id'];

        $data['saldo'] = Saldo::where('id_anggota', $idAnggota)->orderBy('jenis_simpanan', 'DESC')->get();

        $data['saldo_masuk'] = Simpanan::where('id_anggota', $idAnggota)->orderBy('tanggal', 'DESC')->get();

        $data['saldo_keluar'] = SaldoTarik::with('saldo')
            ->whereHas('saldo', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })->get();

        return ResponseFormatter::success($data, 'Berhasil mendapatkan saldo');
    }
}
