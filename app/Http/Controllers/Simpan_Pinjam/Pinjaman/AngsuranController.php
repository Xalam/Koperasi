<?php

namespace App\Http\Controllers\Simpan_Pinjam\Pinjaman;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Pinjaman\Angsuran;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use Illuminate\Http\Request;

class AngsuranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $angsuran = Angsuran::with('pinjaman')->get();

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
                    'angsuran'      => $value->pinjaman->tenor - $value->sisa_bayar,
                    'status'        => (($value->status == 0) ? '<a href="#modalKonfirmasi" data-remote="' . route('angsuran.konfirmasi', $value->id) . '" 
                                       data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-primary btn-sm"><i class="far fa-plus-square"></i>&nbsp; Proses</a>' :
                                       '<span class="badge badge-success">Disetujui</span>') . (($value->lunas == 1) ? '<span class="badge badge-success">Lunas</span>' : ''),
                    'action'        => (($value->status == 1) ? '<a href="' . route('angsuran.print-show', $value->id) . '" class="btn btn-light btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak</a>' : '')
                ];
            }
            return response()->json(compact('data'));
        }
        return view('simpan_pinjam.pinjaman.angsuran.angsuran');
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

        if ($pinjamanUpdate->tenor == $pinjamanUpdate->angsuran_ke) {
            $pinjamanUpdate->lunas = 1;
        }

        $pinjamanUpdate->update();

        #Kode Angsuran
        $check = Angsuran::select('*')->orderBy('id', 'DESC')->first();
        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }

        #Sisa Angsuran
        $sisaAngsuran = $pinjamanUpdate->total_pinjaman - ($pinjamanUpdate->nominal_angsuran * $pinjamanUpdate->angsuran_ke);

        if ($sisaAngsuran < 0) {
            $sisaAngsuran = 0;
        }

        $angsuran = new Angsuran();
        $angsuran->kode_angsuran    = 'ASN-' . str_replace('-', '', date('Y-m-d')) . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
        $angsuran->id_pinjaman      = $request->id_pinjaman;
        $angsuran->tanggal          = date('Y-m-d');
        $angsuran->nominal_angsuran = $pinjamanUpdate->nominal_angsuran;
        $angsuran->sisa_angsuran    = $sisaAngsuran;
        $angsuran->sisa_bayar       = $pinjamanUpdate->tenor - $pinjamanUpdate->angsuran_ke;
        $angsuran->status           = 1;
        $angsuran->lunas            = $pinjamanUpdate->lunas;
        $angsuran->save();

        return redirect()->route('angsuran.index')->with([
            'success' => 'Berhasil membayar angsuran'
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

        $angsuran->status = $request->status;
        $angsuran->update();

        return redirect()->route('angsuran.index');
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

            if ($pinjaman->where('lunas', 0)->get()->count() > 0) {

                $data = $pinjaman->firstOrFail();

                return view('simpan_pinjam.pinjaman.angsuran.bayar', compact('data'));
            } else {
                return redirect()->route('angsuran.index')->with([
                    'error' => 'Kode pinjaman sudah lunas'
                ]);
            }
        } else {
            return redirect()->route('angsuran.index')->with([
                'error' => 'Kode pinjaman tidak ditemukan'
            ]);
        }
    }

    public function print_show($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('simpan_pinjam.pinjaman.angsuran.print-show', compact('angsuran'));
    }

    public function konfirmasi($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('simpan_pinjam.pinjaman.angsuran.modal', compact('angsuran'));
    }
}
