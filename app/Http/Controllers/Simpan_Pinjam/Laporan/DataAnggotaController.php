<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use Illuminate\Http\Request;

class DataAnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::get();

        if (request()->ajax()) {
            $data   = [];
            $no     = 1;
            foreach ($anggota as $key => $value) {
                $data[] = [
                    'no'     => $no++,
                    'kode'   => $value->kd_anggota,
                    'nama'   => $value->nama_anggota,
                    'ttl'    => $value->tempat_lahir .', '. date('d-m-Y', strtotime($value->tanggal_lahir)),
                    'gender' => $value->jenis_kelamin,
                    'agama'  => $value->agama,
                    'alamat' => $value->alamat,
                    'no_hp'  => $value->no_hp,
                    'no_wa'  => $value->no_wa,
                    'jabatan'=> $value->jabatan
                ];
            }
            return response()->json(compact('data'));
        }
        return view('Simpan_Pinjam.laporan.anggota.index');
    }

    public function print_show()
    {
        $anggota = Anggota::get();

        return view('Simpan_Pinjam.laporan.anggota.print-show', compact('anggota'));
    }
}
