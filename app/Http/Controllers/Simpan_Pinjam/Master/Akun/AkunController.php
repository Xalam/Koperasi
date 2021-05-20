<?php

namespace App\Http\Controllers\Simpan_Pinjam\Master\Akun;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $akun = Akun::get();

        if (request()->ajax()) {
            $data = [];
            $no   = 1;

            foreach ($akun as $key => $value) {
                $data[] = [
                    'no' => $no++,
                    'kode_akun' => 'AKN.' . $value->kode_akun,
                    'nama_akun' => $value->nama_akun,
                    'saldo'     => 'Rp. ' . number_format($value->saldo, 2, ',', '.'),
                    'action' => '<a href="'. route('akun.edit', $value->id) . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Edit</a>
                    &nbsp; <a href="#mymodal" data-remote="' . route('akun.modal', $value->id) . '" data-toggle="modal" data-target="#mymodal" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i>&nbsp; Hapus</a>',
                ];
            }

            return response()->json(compact('data'));
        }
        return view('simpan_pinjam.master.akun.akun');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('simpan_pinjam.master.akun.create');
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
            'kode_akun' => 'required|unique:tb_akun',
            'nama_akun' => 'required|unique:tb_akun',
            'saldo'     => 'required'
        ];

        $messages = [
            'kode_akun.required' => 'Kode wajib diisi',
            'kode_akun.unique'   => 'Kode sudah terdaftar',
            'nama_akun.required' => 'Nama wajib diisi',
            'nama_akun.unique'   => 'Nama sudah terdaftar',
            'saldo.required' => 'Saldo wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $data = $request->all();

        Akun::create($data);

        return redirect()->route('akun.index')->with([
            'success' => 'Berhasil menambahkan akun'
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
        $akun = Akun::findOrFail($id);

        return view('simpan_pinjam.master.akun.edit')->with([
            'akun' => $akun
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
        $akun = Akun::findOrFail($id);

        $rules = [
            'kode_akun' => 'required|unique:tb_akun,kode_akun,' . $id,
            'nama_akun' => 'required|unique:tb_akun,nama_akun,' . $id,
            'saldo'     => 'required'
        ];

        $messages = [
            'kode_akun.required' => 'Kode wajib diisi',
            'kode_akun.unique'   => 'Kode sudah terdaftar',
            'nama_akun.required' => 'Nama wajib diisi',
            'nama_akun.unique'   => 'Nama sudah terdaftar',
            'saldo.required' => 'Saldo wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $data = $request->all();

        $data['saldo'] = str_replace('.', '', $request->saldo);

        $akun->update($data);

        return redirect()->route('akun.index')->with([
            'success' => 'Berhasil mengubah data akun'
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
        $akun = Akun::findOrFail($id);

        $akun->delete();

        return redirect()->route('akun.index')->with([
            'success' => 'Data akun berhasil dihapus'
        ]);
    }

    public function modal($id) 
    {
        $akun = Akun::findOrFail($id);

        return view('simpan_pinjam.master.akun.modal')->with([
            'akun' => $akun
        ]);
    }
}
