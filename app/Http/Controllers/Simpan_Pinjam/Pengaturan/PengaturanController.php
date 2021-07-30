<?php

namespace App\Http\Controllers\Simpan_Pinjam\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Pengaturan\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengaturan = Pengaturan::get();

        if (request()->ajax()) {
            $data = [];

            foreach ($pengaturan as $key => $value) {
                $data[] = [
                    'no'        => $value->id,
                    'nama'      => $value->nama,
                    'angka'     => $value->angka,
                    'action'    => '<a href="' . route('list.edit', $value->id) . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Edit</a>',
                ];
            }

            return response()->json(compact('data'));
        }
        return view('Simpan_Pinjam.pengaturan.list.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Simpan_Pinjam.pengaturan.list.create');
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
        $pengaturan = Pengaturan::findOrFail($id);

        return view('Simpan_Pinjam.pengaturan.list.edit', compact('pengaturan'));
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
        $rules = [
            'angka'  => 'required|regex:/[0-9\s]+/i',
        ];

        $messages = [
            'angka.required' => 'Angka wajib diisi',
            'angka.regex'  => 'Wajib diisi angka'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $pengaturan = Pengaturan::findOrFail($id);
        $pengaturan->angka = $request->angka;
        $pengaturan->update();

        return redirect()->route('list.index')->with([
            'success' => 'Berhasil mengubah pengaturan'
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
        if ($id == 152) {
            JurnalUmum::truncate();
        }

        return redirect()->route('list.index')->with([
            'success' => 'Berhasil menghapus seluruh jurnal'
        ]);
    }

    public function modal($id)
    {
        $pengaturan = Pengaturan::findOrFail($id);

        return view('Simpan_Pinjam.pengaturan.list.modal', compact('pengaturan'));
    }

    public function modal_all($id)
    {
        return view('Simpan_Pinjam.pengaturan.list.modal-delete');
    }
}
