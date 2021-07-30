<?php

namespace App\Http\Controllers\Simpan_Pinjam\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Pengaturan\PembagianSHU;
use Illuminate\Http\Request;

class PembagianSHUController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pembagianSHU = PembagianSHU::get();
        $total = $pembagianSHU->sum('angka');

        if (request()->ajax()) {
            $data = [];

            foreach ($pembagianSHU as $key => $value) {
                $data[] = [
                    'no'        => $value->id,
                    'nama'      => $value->nama,
                    'angka'     => $value->angka,
                    'action'    => '<a href="' . route('pembagian.edit', $value->id) . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Edit</a>&nbsp;<a href="#mymodal" data-remote="' . route('pembagian.modal', $value->id) . '" data-toggle="modal" data-target="#mymodal" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>&nbsp; Hapus</a>',
                ];
            }

            return response()->json(compact('data'));
        }
        return view('Simpan_Pinjam.pengaturan.pembagian.index', compact('total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Simpan_Pinjam.pengaturan.pembagian.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pembagianSHU = new PembagianSHU();

        $pembagianSHU->nama     = $request->nama;
        $pembagianSHU->angka    = $request->angka;
        $pembagianSHU->save();

        return redirect()->route('pembagian.index')->with([
            'success' => 'Berhasil menambahkan pembagian SHU'
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
        $pembagianSHU = PembagianSHU::findOrFail($id);

        return view('Simpan_Pinjam.pengaturan.pembagian.edit', compact('pembagianSHU'));
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
        $pembagianSHU = PembagianSHU::findOrFail($id);

        $data = $request->all();

        $pembagianSHU->update($data);

        return redirect()->route('pembagian.index')->with([
            'success' => 'Berhasil memperbarui pembagian SHU'
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
        $pembagianSHU = PembagianSHU::findOrFail($id);

        $pembagianSHU->delete();

        return redirect()->route('pembagian.index')->with([
            'success' => 'Berhasil menghapus pembagian SHU'
        ]);
    }

    public function modal($id)
    {
        $pembagianSHU = PembagianSHU::findOrFail($id);

        return view('Simpan_Pinjam.pengaturan.pembagian.modal-delete', compact('pembagianSHU'));
    }
}
