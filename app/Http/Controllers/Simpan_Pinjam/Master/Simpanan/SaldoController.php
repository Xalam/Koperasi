<?php

namespace App\Http\Controllers\Simpan_Pinjam\Master\Simpanan;

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
        $saldo      = Saldo::with('anggota')->get();
        $totalSaldo = Simpanan::selectRaw('SUM(nominal) as total_simpanan, id_anggota')->groupBy('id_anggota')->where('status', 1)->pluck('total_simpanan', 'id_anggota');
        // echo($totalSaldo->toArray());
        // exit;

        if (request()->ajax()) {
            $data = [];
            $no   = 1;

            foreach ($saldo as $key => $value) {
                $showSaldo = 0;
                if (isset($totalSaldo[$value->id_anggota])) {
                    $showSaldo = $totalSaldo[$value->id_anggota];
                } else {
                    $showSaldo;
                }

                #Update saldo
                Saldo::where('id_anggota', $value->id_anggota)->update(['saldo' => $showSaldo]);

                $data[] = [
                    'no'    => $no++,
                    'kode'  => $value->anggota->kd_anggota,
                    'nama'  => $value->anggota->nama_anggota,
                    'saldo' => 'Rp. ' . number_format($showSaldo, 2, ',', '.'),
                ];
            }
            return response()->json(compact('data'));
        }
        return view('simpan_pinjam.simpanan.saldo.saldo');
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
