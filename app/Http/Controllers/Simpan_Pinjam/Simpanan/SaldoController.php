<?php

namespace App\Http\Controllers\Simpan_Pinjam\Simpanan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Simpanan\Saldo;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $saldoSukarela = Saldo::with('anggota')->where('jenis_simpanan', 3)->get();
        $saldoWajib    = Saldo::with('anggota')->where('jenis_simpanan', 2)->get();
        $saldoPokok    = Saldo::with('anggota')->where('jenis_simpanan', 1)->get();
        //$totalSaldo = Simpanan::selectRaw('SUM(nominal) as total_simpanan, id_anggota')->groupBy('id_anggota')->where('status', 1)->pluck('total_simpanan', 'id_anggota');
        // echo($totalSaldo->toArray());
        // exit;

        if (request()->ajax()) {
            $data = [];
            $no   = 1;

            switch (request()->type) {
                case 'sukarela':
                    foreach ($saldoSukarela as $key => $value) {

                        $data[] = [
                            'no'    => $no++,
                            'kode'  => $value->anggota->kd_anggota,
                            'nama'  => $value->anggota->nama_anggota,
                            'saldo' => number_format($value->saldo, 2, ',', '.'),
                        ];
                    }
                    return response()->json(compact('data'));

                    break;
                case 'wajib':
                    foreach ($saldoWajib as $key => $value) {

                        $data[] = [
                            'no'    => $no++,
                            'kode'  => $value->anggota->kd_anggota,
                            'nama'  => $value->anggota->nama_anggota,
                            'saldo' => number_format($value->saldo, 2, ',', '.'),
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                case 'pokok':
                    foreach ($saldoPokok as $key => $value) {

                        $data[] = [
                            'no'    => $no++,
                            'kode'  => $value->anggota->kd_anggota,
                            'nama'  => $value->anggota->nama_anggota,
                            'saldo' => number_format($value->saldo, 2, ',', '.'),
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                    break;
            }
        }
        return view('Simpan_Pinjam.simpanan.saldo.saldo');
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
        //
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
}
