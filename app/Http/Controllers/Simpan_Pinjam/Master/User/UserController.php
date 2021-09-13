<?php

namespace App\Http\Controllers\Simpan_Pinjam\Master\User;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();

        if (request()->ajax()) {
            $data = [];
            $no   = 1;

            foreach ($users as $key => $value) {
                $data[] = [
                    'no'        => $no++,
                    'nama'      => $value->name,
                    'username'  => $value->username,
                    'role'      => $value->role,
                    'action'    => ($key != 0) ? '<a href="#mymodal" data-remote="' . route('admin.modal', $value->id) . '" data-toggle="modal" data-target="#mymodal" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i>&nbsp; Hapus</a>' : '',
                ];
            }
            return response()->json(compact('data'));
        }

        return view('Simpan_Pinjam.master.admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Simpan_Pinjam.master.admin.create');
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

        $data['password'] = Hash::make($data['password']);

        $checkUsername = User::where('username', $data['username'])->get();

        if ($checkUsername) {
            return redirect()->route('admin.create')->with([
                'error' => 'Username telah digunakan'
            ]);
        }

        User::create($data);
        
        return redirect()->route('admin.index')->with([
            'success' => 'Berhasil menambahkan data'
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
        $users = User::findOrFail($id);

        $users->delete();

        return redirect()->route('admin.index')->with([
            'success' => 'Data berhasil dihapus'
        ]);
    }

    public function modal($id)
    {
        $users = User::findOrFail($id);

        return view('Simpan_Pinjam.master.admin.modal', compact('users'));
    }
}
