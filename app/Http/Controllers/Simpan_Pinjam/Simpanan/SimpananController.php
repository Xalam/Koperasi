<?php

namespace App\Http\Controllers\Simpan_Pinjam\Simpanan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Simpanan\Saldo;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $simpanan   = Simpanan::with('anggota')->orderBy('id', 'DESC')->get();
        $pokok      = $simpanan->where('jenis_simpanan', 1);
        $wajib      = $simpanan->where('jenis_simpanan', 2);
        $sukarela   = $simpanan->where('jenis_simpanan', 3);

        if (request()->ajax()) {
            switch (request()->filter) {
                case 'pokok':
                    $data = [];
                    $no   = 1;
                    foreach ($pokok as $key => $value) {

                        $data[] = [
                            'no' => $no++,
                            'kode_simpanan'  => $value->kode_simpanan,
                            'tanggal'        => date('d-m-Y', strtotime($value->tanggal)),
                            'jenis_simpanan' => 'Simpanan Pokok',
                            'nama_anggota'   => $value->anggota->nama_anggota,
                            'nominal'        => 'Rp. ' . number_format($value->nominal, 2, ',', '.'),
                            'status'         => ($value->status == 0) ? '<span class="badge bg-danger">Belum Bayar</span>' : '<span class="badge bg-success">Sudah Bayar</span>',
                            'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                            'action'         => '<a href="' . route('data.print', $value->id) . '" class="btn btn-light btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak</a>
                                                &nbsp; <a href="#" class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Edit</a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                case 'wajib':
                    $data = [];
                    $no   = 1;
                    foreach ($wajib as $key => $value) {
                
                        $data[] = [
                            'no' => $no++,
                            'kode_simpanan'  => $value->kode_simpanan,
                            'tanggal'        => date('d-m-Y', strtotime($value->tanggal)),
                            'jenis_simpanan' => 'Simpanan Wajib',
                            'nama_anggota'   => $value->anggota->nama_anggota,
                            'nominal'        => 'Rp. ' . number_format($value->nominal, 2, ',', '.'),
                            'status'         => ($value->status == 0) ? '<span class="badge bg-danger">Belum Bayar</span>' : '<span class="badge bg-success">Sudah Bayar</span>',
                            'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                            'action'         => '<a href="' . route('data.print', $value->id) . '" class="btn btn-light btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak</a>
                                                &nbsp; <a href="#" class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Edit</a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                case 'sukarela':
                    $data = [];
                    $no   = 1;
                    foreach ($sukarela as $key => $value) {
                
                        $data[] = [
                            'no' => $no++,
                            'kode_simpanan'  => $value->kode_simpanan,
                            'tanggal'        => date('d-m-Y', strtotime($value->tanggal)),
                            'jenis_simpanan' => 'Simpanan Sukarela',
                            'nama_anggota'   => $value->anggota->nama_anggota,
                            'nominal'        => 'Rp. ' . number_format($value->nominal, 2, ',', '.'),
                            'status'         => ($value->status == 0) ? '<span class="badge bg-danger">Belum Bayar</span>' : '<span class="badge bg-success">Sudah Bayar</span>',
                            'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                            'action'         => '<a href="' . route('data.print', $value->id) . '" class="btn btn-light btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak</a>
                                                &nbsp; <a href="#" class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Edit</a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                default:
                    $data = [];
                    $no   = 1;
                    $jenis = '';
                    
                    foreach ($simpanan as $key => $value) {
                        if ($value->jenis_simpanan == 1) {
                            $jenis = 'Simpanan Pokok';
                        } else if ($value->jenis_simpanan == 2) {
                            $jenis = 'Simpanan Wajib';
                        } else {
                            $jenis = 'Simpanan Sukarela';
                        }

                        $data[] = [
                            'no' => $no++,
                            'kode_simpanan'  => $value->kode_simpanan,
                            'tanggal'        => date('d-m-Y', strtotime($value->tanggal)),
                            'jenis_simpanan' => $jenis,
                            'nama_anggota'   => $value->anggota->nama_anggota,
                            'nominal'        => 'Rp. ' . number_format($value->nominal, 2, ',', '.'),
                            'status'         => ($value->status == 0) ? '<span class="badge bg-danger">Belum Bayar</span>' : '<span class="badge bg-success">Sudah Bayar</span>',
                            'keterangan'     => $value->keterangan == null ? '-' : $value->keterangan,
                            'action'         => '<a href="' . route('data.print', $value->id) . '" class="btn btn-light btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak</a>
                                                &nbsp; <a href="' . route('data.edit', $value->id) . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Edit</a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
            }
            
        }
        return view('Simpan_Pinjam.simpanan.simpanan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anggota = Anggota::get();

        return view('simpan_pinjam.simpanan.create')->with([
            'anggota' => $anggota
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check = Simpanan::select('id')->orderBy('id', 'DESC')->first();
        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }

        $data = $request->all();

        $data['kode_simpanan'] = 'SMP-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $data['nominal'] = str_replace('.', '', $request->nominal);

        Simpanan::create($data);

        return redirect()->route('data.index')->with([
            'success' => 'Berhasil tambah penyimpanan'
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
        $simpanan = Simpanan::with('anggota')->findOrFail($id);

        return view('Simpan_Pinjam.simpanan.edit')->with([
            'simpanan' => $simpanan
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
        $simpanan = Simpanan::findOrFail($id);

        $data = $request->all();

        $simpanan->update($data);

        return redirect()->route('data.index')->with([
            'success' => 'Berhasil mengubah data simpanan'
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
        // $simpanan = Simpanan::findOrFail($id);

        // $simpanan->delete();

        // return redirect()->route('data.index')->with([
        //     'success' => 'Simpanan anggota berhasil dihapus'
        // ]);
    }

    public function print($id) 
    {
        $simpanan = Simpanan::with('anggota')->findOrFail($id);

        return view('Simpan_Pinjam.simpanan.print')->with([
            'simpanan' => $simpanan
        ]);
    }

    public function print_show($id) 
    {
        $simpanan = Simpanan::with('anggota')->findOrFail($id);

        return view('Simpan_Pinjam.simpanan.print-show')->with([
            'simpanan' => $simpanan
        ]);
    }

    public function modal($id)
    {
        $simpanan = Simpanan::findOrFail($id);

        return view('Simpan_Pinjam.simpanan.modal')->with([
            'simpanan' => $simpanan
        ]);
    }

    public function store_all(Request $request) 
    {
        $anggota  = Anggota::pluck('id');
        $count = $anggota->count();
        
        for ($i = 0; $i < $count; $i++) {
            $check = Simpanan::orderBy('id', 'DESC')->first();

            if ($check == null) {
                $id = 1;
            } else {
                $id = $check->id + 1;
            }

            Simpanan::create([
                'kode_simpanan'  => 'SMP-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT),
                'id_anggota'     => $anggota[$i],
                'tanggal'        => $request->tanggal,
                'jenis_simpanan' => 2,
                'nominal'        => str_replace('.', '', $request->nominal),
                'keterangan'     => $request->keterangan,
                'status'         => $request->status
            ]);
        }

        return redirect()->route('data.index')->with([
            'success' => 'Data simpanan wajib berhasil ditambah'
        ]);
    }
}
