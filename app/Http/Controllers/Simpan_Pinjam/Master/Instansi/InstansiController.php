<?php

namespace App\Http\Controllers\Simpan_Pinjam\Master\Instansi;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Instansi\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instansi = Instansi::get();

        if (request()->ajax()) {
            $data  = [];
            $no    = 1;

            foreach ($instansi as $key => $value) {
                $data[] = [
                    'no' => $no++,
                    'kode_instansi' => $value->kode_instansi,
                    'nama_instansi' => $value->nama_instansi,
                    'action' => '<a href="' . route('instansi.edit', $value->id) . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Edit</a>
                    &nbsp; <a href="#mymodal" data-remote="' . route('instansi.modal', $value->id) . '" data-toggle="modal" data-target="#mymodal" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i>&nbsp; Hapus</a>',
                ];
            }

            return response()->json(compact('data'));
        }
        return view('Simpan_Pinjam.master.instansi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Simpan_Pinjam.master.instansi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'kode_instansi' => 'required|unique:tb_instansi',
            'nama_instansi' => 'required|unique:tb_instansi'
        ];

        $messages = [
            'kode_instansi.required' => 'Kode wajib diisi',
            'kode_instansi.unique'   => 'Kode sudah terdaftar',
            'nama_instansi.required' => 'Nama wajib diisi',
            'nama_instansi.unique'   => 'Nama sudah terdaftar',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $data = $request->all();

        Instansi::create($data);

        return redirect()->route('instansi.index')->with([
            'success' => 'Berhasil menambahkan instansi'
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
        $instansi = Instansi::findOrFail($id);

        return view('Simpan_Pinjam.master.instansi.edit')->with([
            'instansi' => $instansi
        ]);
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
        $instansi = Instansi::findOrFail($id);

        $rule_nama = '';
        $rule_kode = '';

        if ($instansi->nama_instansi != $request->nama_instansi) {
            $rule_nama = 'unique:tb_instansi';
        }

        if ($instansi->kode_instansi != $request->kode_instansi) {
            $rule_kode = 'unique:tb_instansi';
        }

        $rules = [
            'kode_instansi' => 'required|' . $rule_kode . '',
            'nama_instansi' => 'required|' . $rule_nama . ''
        ];

        $messages = [
            'kode_instansi.required' => 'Kode wajib diisi',
            'kode_instansi.unique'   => 'Kode sudah terdaftar',
            'nama_instansi.required' => 'Nama wajib diisi',
            'nama_instansi.unique'   => 'Nama sudah terdaftar',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $data = $request->all();

        $instansi->update($data);

        return redirect()->route('instansi.index')->with([
            'success' => 'Berhasil mengubah data instansi'
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
        $instansi = Instansi::findOrFail($id);

        $instansi->delete();

        return redirect()->route('instansi.index')->with([
            'success' => 'Data instansi berhasil dihapus'
        ]);
    }

    public function modal($id)
    {
        $instansi = Instansi::findOrFail($id);

        return view('Simpan_Pinjam.master.instansi.modal')->with([
            'instansi' => $instansi
        ]);
    }
}
