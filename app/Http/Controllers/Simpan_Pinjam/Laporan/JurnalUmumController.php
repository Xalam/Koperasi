<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use Illuminate\Http\Request;

class JurnalUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jurnal = JurnalUmum::orderBy('id', 'DESC')->get();

        if (request()->ajax()) {
            $data  = [];
            $no    = 1;
            foreach ($jurnal as $key => $value) {
                $data[] = [
                    'no'         => $no++,
                    'tanggal'    => date('d-m-Y', strtotime($value->tanggal)),
                    'kode'       => $value->kode_jurnal,
                    'keterangan' => $value->keterangan,
                    'kode_akun'  => $value->akun->kode_akun,
                    'nama_akun'  => $value->akun->nama_akun,
                    'debet'      => number_format($value->debet, 0, '', '.'),
                    'kredit'     => number_format($value->kredit, 0, '', '.')
                ];
            }
            return response()->json(compact('data'));
        }

        return view('simpan_pinjam.laporan.jurnal-umum.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $akun = Akun::get();

        return view('simpan_pinjam.laporan.jurnal-umum.create', compact('akun'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        #Kode Jurnal
        $check = JurnalUmum::select('*')->orderBy('id', 'DESC')->first();
        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }

        $data['kode_jurnal'] = 'JU-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $data['debet']       = str_replace('.', '', $request->debet);
        $data['kredit']      = str_replace('.', '', $request->kredit);

        JurnalUmum::create($data);

        return redirect()->route('jurnal.index')->with([
            'success' => 'Berhasil menambahkan jurnal'
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
