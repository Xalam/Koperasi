<?php

namespace App\Http\Controllers\Simpan_Pinjam\Simpanan;

use App\Http\Controllers\Controller;
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
                            'nominal'  => 'Rp. ' . number_format($value->nominal, 2, ',', '.'),
                            'action'   => '<a href="#modalKonfirmasi" data-remote="' . route('tarik-saldo.konfirmasi', $value->id) . '" 
                                        data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-primary btn-sm">
                                        <i class="far fa-plus-square"></i>&nbsp; Proses</a>'
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
                            'nominal'  => 'Rp. ' . number_format($value->nominal, 2, ',', '.'),
                            'action'   => '<a href="#modalKonfirmasi" data-remote="' . route('tarik-saldo.konfirmasi', $value->id) . '" 
                                        data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-success btn-sm">
                                        <i class="far fa-check-square"></i>&nbsp; Selesai</a>'
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
        //
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

        if ($saldo->saldo >= $tarikSaldo->nominal) {
            $tarikSaldo->update(['status' => $request->status]);

            if ($tarikSaldo->status == 2)
            {
                $saldo->update([
                    'saldo' => $saldo->saldo - $tarikSaldo->nominal
                ]);
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
        //
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
                $data[] = [
                    'no'        => $no++,
                    'tanggal'   => date('d-m-Y', strtotime($value->tanggal)),
                    'nama'      => $value->saldo->anggota->nama_anggota,
                    'nominal'   => number_format($value->nominal, 2, ',', '.'),
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
}
