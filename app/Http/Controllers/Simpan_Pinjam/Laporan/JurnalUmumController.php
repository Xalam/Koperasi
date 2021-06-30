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
        $debet  = $jurnal->sum('debet');
        $kredit = $jurnal->sum('kredit');

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
                    'debet'      => number_format($value->debet, 2, ',', '.'),
                    'kredit'     => number_format($value->kredit, 2, ',', '.'),
                    'action'     => '<a href="'. route('jurnal.edit', $value->id) . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Edit</a>
                                    &nbsp; <a href="#mymodalJurnal" data-remote="' . route('jurnal.modal', $value->id) . '" data-toggle="modal" data-target="#mymodalJurnal" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i>&nbsp; Hapus</a>'
                ];
            }
            return response()->json(compact('data'));
        }

        return view('Simpan_Pinjam.laporan.jurnal-umum.index', compact('debet', 'kredit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $akun = Akun::get();

        return view('Simpan_Pinjam.laporan.jurnal-umum.create', compact('akun'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #Kode Jurnal
        $check = JurnalUmum::select('*')->orderBy('id', 'DESC')->first();
        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }
        
        if (count($request->rows) > 0) {
            for ($i = 0; $i < count($request->rows); $i++) {

                $cleanDebet[$i]  = str_replace('.', '', $request->debet[$i]);
                $cleanKredit[$i] = str_replace('.', '', $request->kredit[$i]);

                JurnalUmum::create([
                    'kode_jurnal' => 'JU-' . str_pad($id, 6, '0', STR_PAD_LEFT),
                    'id_akun'     => $request->id_akun[$i],
                    'tanggal'     => date('Y-m-d'),
                    'keterangan'  => $request->keterangan[$i],
                    'debet'       => str_replace(',', '.', $cleanDebet[$i]),
                    'kredit'      => str_replace(',', '.', $cleanKredit[$i])
                ]);
            }
        }

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
        $jurnal = JurnalUmum::findOrFail($id);
        $akun = Akun::get();

        return view('Simpan_Pinjam.laporan.jurnal-umum.edit', compact('akun', 'jurnal'));
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
        $jurnal = JurnalUmum::findOrFail($id);

        $data = $request->all();

        $cleanKredit = str_replace('.', '', $request->kredit);
        $cleanDebet = str_replace('.', '', $request->debet);
        $data['kredit'] = str_replace(',', '.', $cleanKredit);
        $data['debet'] = str_replace(',', '.', $cleanDebet);

        $jurnal->update($data);

        return redirect()->route('jurnal.index')->with([
            'success' => 'Berhasil mengubah data jurnal'
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
        $jurnal = JurnalUmum::findOrFail($id);

        $jurnal->delete();

        return redirect()->route('jurnal.index')->with([
            'success' => 'Jurnal berhasil dihapus'
        ]);
    }

    public function print_show(Request $request)
    {
        $reqStart  = $request->start_date;
        $reqEnd    = $request->end_date;

        if ($request->start_date != null & $request->end_date != null) {
            
            $startDate = date('Y-m-d', strtotime($request->start_date));
            $endDate   = date('Y-m-d', strtotime($request->end_date));

            $jurnal = JurnalUmum::whereBetween('tanggal', [$startDate, $endDate])->orderBy('id', 'DESC')->get();
            return view('Simpan_Pinjam.laporan.jurnal-umum.print-show', compact('jurnal', 'reqStart', 'reqEnd'));
        } else {
            $jurnal = JurnalUmum::orderBy('id', 'DESC')->get();
            return view('Simpan_Pinjam.laporan.jurnal-umum.print-show', compact('jurnal', 'reqStart', 'reqEnd'));
        }
    }

    public function modal($id)
    {
        $jurnal = JurnalUmum::findOrFail($id);

        return view('Simpan_Pinjam.laporan.jurnal-umum.modal')->with([
            'jurnal' => $jurnal
        ]);
    }
}
