<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\KodeJurnal;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\JurnalUmum\JurnalUmumModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurnalUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $a = JurnalUmumModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"));
        $b = JurnalModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"));
        $jurnal = JurnalUmum::select(DB::raw("id, CONVERT(kode_jurnal USING utf8) as kode_jurnal, id_akun, tanggal, CONVERT(keterangan USING utf8) as keterangan, debet, kredit"))->orderBy('id', 'DESC')->union($a)->union($b)->orderBy('tanggal', 'DESC')->get();

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
                    'action'     => ((substr($value->kode_jurnal, 0, 3) == 'JU-') ? '<a href="' . route('jurnal.edit', $value->id) . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>
                                    <a href="#mymodalJurnal" data-remote="' . route('jurnal.modal', $value->id) . '" data-toggle="modal" data-target="#mymodalJurnal" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>' : '')
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
        $kodeJurnal = KodeJurnal::kode();

        if (count($request->rows) > 0) {
            for ($i = 0; $i < count($request->rows); $i++) {

                $cleanDebet[$i]  = str_replace('.', '', $request->debet[$i]);
                $cleanKredit[$i] = str_replace('.', '', $request->kredit[$i]);

                JurnalUmum::create([
                    'kode_jurnal' => $kodeJurnal,
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

            $a = JurnalUmumModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->whereBetween('tanggal', [$startDate, $endDate]);
            $b = JurnalModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->whereBetween('tanggal', [$startDate, $endDate]);
            $jurnal = JurnalUmum::select(DB::raw("id, CONVERT(kode_jurnal USING utf8) as kode_jurnal, id_akun, tanggal, CONVERT(keterangan USING utf8) as keterangan, debet, kredit"))->whereBetween('tanggal', [$startDate, $endDate])->orderBy('id', 'DESC')->union($a)->union($b)->orderBy('tanggal', 'DESC')->get();

            return view('Simpan_Pinjam.laporan.jurnal-umum.print-show', compact('jurnal', 'reqStart', 'reqEnd'));
        } else {
            $a = JurnalUmumModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"));
            $b = JurnalModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"));
            $jurnal = JurnalUmum::select(DB::raw("id, CONVERT(kode_jurnal USING utf8) as kode_jurnal, id_akun, tanggal, CONVERT(keterangan USING utf8) as keterangan, debet, kredit"))->orderBy('id', 'DESC')->union($a)->union($b)->orderBy('tanggal', 'DESC')->get();

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
