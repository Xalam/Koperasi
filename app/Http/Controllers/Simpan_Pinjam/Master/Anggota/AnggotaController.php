<?php

namespace App\Http\Controllers\Simpan_Pinjam\Master\Anggota;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anggota = Anggota::get();

        if(request()->ajax()) {
            $data = [];
            $no   = 1;
            foreach ($anggota as $key => $value) {
                $data[] = [
                    'no'     => $no++,
                    'kode'   => $value->kd_anggota,
                    'nama'   => $value->nama_anggota,
                    'ttl'    => $value->tempat_lahir .', '. $value->tanggal_lahir,
                    'gender' => $value->jenis_kelamin,
                    'agama'  => $value->agama,
                    'alamat' => $value->alamat,
                    'nowa'   => $value->no_wa,
                    'action' => '<a href="'. route('anggota.show', $value->id) . '" class="btn btn-info btn-sm"><i class="far fa-file-alt"></i>&nbsp; Detail</a>
                    &nbsp; <a href="'. route('anggota.edit', $value->id) . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Edit</a>
                    &nbsp; <a href="#mymodal" data-remote="' . route('anggota.modal', $value->id) . '" data-toggle="modal" data-target="#mymodal" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i>&nbsp; Hapus</a>',
                ];
            }
            return response()->json(compact('data'));
        }
        return view('simpan_pinjam.master.anggota.anggota');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('simpan_pinjam.master.anggota.create');
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
            'kd_anggota'  => 'max:255',
            'nama_anggota'  => 'required',
            'tempat_lahir'  => 'required', 
            'tanggal_lahir' => 'required',
            'alamat'        => 'required|max:255',
            'no_hp'         => 'required', 
            'no_wa'         => 'required',
            'jabatan'       => 'required',
            'email'         => 'required|email|unique:tb_anggota',
            'username'      => 'required|unique:tb_anggota',
            'password'      => 'required|min:8',
            'foto'          => 'required|image|mimes:jpg,png,jpeg|max:5120',
        ];

        $messages = [
            'nama_anggota.required' => 'Nama wajib diisi',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'alamat.required'       => 'Alamat wajib diisi',
            'alamat.max'            => 'Alamat terlalu panjang',
            'no_hp.required'        => 'No handphone wajib diisi',
            'no_wa.required'        => 'No whatsapp wajib diisi',
            'jabatan.required'      => 'Jabatan wajib diisi',
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',
            'username.required'     => 'Username wajib diisi',
            'username.unique'       => 'Username sudah terdaftar',
            'password.required'     => 'Password wajib diisi',
            'password.min'          => 'Password minimal 8 karakter',
            'foto.required'         => 'File foto wajib diunggah',
            'foto.image'            => 'File foto harus berbentuk jpg atau png',
            'foto.max'              => 'Ukuran file foto maksimal 5mb'

        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $data = $request->all();
        
        $user = $data['username'];
        $kode_anggota = 'ANG-' . $user;

        $extension = $request->file('foto')->extension();
        $imageName = $user . '.' . $extension;
        Storage::putFileAs('foto', $request->file('foto'), $imageName);

        $request->foto->move(public_path('storage/foto'), $imageName);

        $data['kd_anggota'] = $kode_anggota;
        $data['foto'] = $imageName;
        $data['password'] = Hash::make($data['password']);

        Anggota::create($data);

        return redirect()->route('anggota.index')->with([
            'success' => 'Berhasil menambahkan anggota'
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
        $anggota = Anggota::findOrFail($id);
        
        return view('simpan_pinjam.master.anggota.show')->with([
            'anggota' => $anggota
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        
        return view('simpan_pinjam.master.anggota.edit')->with([
            'anggota' => $anggota
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
        $anggota = Anggota::findOrFail($id);

        $rules = [
            'nama_anggota'  => 'required',
            'tempat_lahir'  => 'required', 
            'tanggal_lahir' => 'required',
            'alamat'        => 'required|max:255',
            'no_hp'         => 'required', 
            'no_wa'         => 'required',
            'jabatan'       => 'required',
            'foto'          => 'image|mimes:jpg,png,jpeg|max:5120',
        ];

        $messages = [
            'nama_anggota.required' => 'Nama wajib diisi',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'alamat.required'       => 'Alamat wajib diisi',
            'alamat.max'            => 'Alamat terlalu panjang',
            'no_hp.required'        => 'No handphone wajib diisi',
            'no_wa.required'        => 'No whatsapp wajib diisi',
            'jabatan.required'      => 'Jabatan wajib diisi',
            'foto.image'            => 'File foto harus berbentuk jpg atau png',
            'foto.max'              => 'Ukuran file foto maksimal 5mb'

        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        // dd($anggota->password);
        // exit;
        $data = $request->all();
        
        $user = $anggota->username;

        if ($request->foto == null) {
            $anggota->foto == $anggota->foto;
        } else {
            Storage::delete('foto/' . $anggota->foto);
            File::delete('storage/foto/' . $anggota->foto);

            $extension = $request->file('foto')->extension();
            $imageName = $user . '.' . $extension;
            
            Storage::putFileAs('foto', $request->file('foto'), $imageName);
            $request->foto->move(public_path('storage/foto'), $imageName);

            $data['foto'] = $imageName;
        }
        
        if ($request->password == null) {
            $data['password'] = $anggota->password;
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        
        $anggota->update($data);

        return redirect()->route('anggota.index')->with([
            'success' => 'Berhasil mengubah data anggota'
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
        $anggota = Anggota::findOrFail($id);

        Storage::delete('foto/' . $anggota->foto);

        $anggota->delete();

        return redirect()->route('anggota.index')->with([
            'success' => 'Data anggota berhasil dihapus'
        ]);
    }

    public function modal($id)
    {
        $anggota = Anggota::findOrFail($id);

        return view('simpan_pinjam.master.anggota.modal')->with([
            'anggota' => $anggota
        ]);
    }

    public function print($id)
    {
        $anggota = Anggota::findOrFail($id);

        return view('simpan_pinjam.master.anggota.print')->with([
            'anggota' => $anggota
        ]);
    }
}
