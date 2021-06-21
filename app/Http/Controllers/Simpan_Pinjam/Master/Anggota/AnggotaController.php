<?php

namespace App\Http\Controllers\Simpan_Pinjam\Master\Anggota;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Pengaturan\Pengaturan;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use App\Models\Simpan_Pinjam\Simpanan\Saldo;
use App\Models\Simpan_Pinjam\Simpanan\SaldoTarik;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
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
                    'ttl'    => $value->tempat_lahir .', '. date('d-m-Y', strtotime($value->tanggal_lahir)),
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
        return view('Simpan_Pinjam.master.anggota.anggota');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $simWajib    = Pengaturan::where('id', 5)->first();
        
        $expWajib    = explode(" ", $simWajib->angka);

        $wajib        = $expWajib[0];

        return view('Simpan_Pinjam.master.anggota.create', compact('wajib'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #Insert data Anggota
        $rules = [
            'email'         => 'unique:tb_anggota',
            'username'      => 'unique:tb_anggota',
        ];

        $messages = [
            'email.unique'          => 'Email sudah terdaftar',
            'username.unique'       => 'Username sudah terdaftar',
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
        //Storage::putFileAs('foto', $request->file('foto'), $imageName);

        $request->foto->move(public_path('storage/foto'), $imageName);

        $data['kd_anggota'] = $kode_anggota;
        $data['foto'] = $imageName;
        $data['password'] = Hash::make($data['password']);

        $clean = str_replace('.', '', $request->gaji);
        $data['gaji'] = str_replace(',', '.', $clean);

        Anggota::create($data);

        #Insert Saldo
        $anggotaId = Anggota::orderBy('id', 'DESC')->first();

        $saldo = new Saldo();
        $saldo->id_anggota = $anggotaId->id;
        $saldo->saldo = 0;
        $saldo->save();

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
        
        return view('Simpan_Pinjam.master.anggota.show')->with([
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

        $simWajib    = Pengaturan::where('id', 5)->first();
        
        $expWajib    = explode(" ", $simWajib->angka);

        $wajib        = $expWajib[0];
        
        return view('Simpan_Pinjam.master.anggota.edit', compact('anggota', 'wajib'));
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

        $data = $request->all();
        
        $user = $anggota->username;

        if ($request->foto == null) {
            $anggota->foto == $anggota->foto;
        } else {
            //Storage::delete('foto/' . $anggota->foto);
            File::delete('storage/foto/' . $anggota->foto);

            $extension = $request->file('foto')->extension();
            $imageName = $user . '.' . $extension;
            
            //Storage::putFileAs('foto', $request->file('foto'), $imageName);
            $request->foto->move(public_path('storage/foto'), $imageName);

            $data['foto'] = $imageName;
        }
        
        if ($request->password == null) {
            $data['password'] = $anggota->password;
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $clean = str_replace('.', '', $request->gaji);
        $data['gaji'] = str_replace(',', '.', $clean);

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
        #Hapus anggota
        $anggota = Anggota::findOrFail($id);

        Storage::delete('foto/' . $anggota->foto);

        $anggota->delete();

        #Hapus simpanan
        $simpanan = new Simpanan();
        $simpanan->where('id_anggota', $id)->delete();

        #Hapus saldo
        $saldo = Saldo::select('id')->where('id_anggota', $id)->first();
        if ($saldo != null ){
            Saldo::where('id_anggota', $id)->delete();
            SaldoTarik::where('id_saldo', $saldo->id)->delete();
        }

        #Hapus pinjaman
        $pinjaman = new Pinjaman();
        $pinjaman->where('id_anggota', $id)->delete();

        #Hapus angsuran
        

        return redirect()->route('anggota.index')->with([
            'success' => 'Data anggota berhasil dihapus'
        ]);
    }

    public function modal($id)
    {
        $anggota = Anggota::findOrFail($id);

        return view('Simpan_Pinjam.master.anggota.modal')->with([
            'anggota' => $anggota
        ]);
    }

    public function print($id)
    {
        $anggota = Anggota::findOrFail($id);

        return view('Simpan_Pinjam.master.anggota.print')->with([
            'anggota' => $anggota
        ]);
    }
}
