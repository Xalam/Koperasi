<?php

namespace App\Http\Controllers\Simpan_Pinjam\Pinjaman;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $pinjaman = Pinjaman::with('anggota')->get();
        $pinjamanWaiting = $pinjaman->where('status', 0);
        $pinjamanAccept  = $pinjaman->where('status', 1);

        if(request()->ajax()) {
            switch (request()->type) {
                case 'waiting':
                    $data = [];
                    $no   = 1;

                    foreach ($pinjamanWaiting as $key => $value) {
                        $data[] = [
                            'no'            => $no++,
                            'kode'          => $value->kode_pinjaman,
                            'kode_anggota'  => $value->anggota->kd_anggota,
                            'tanggal'       => date('d-m-Y', strtotime($value->tanggal)),
                            'nama'          => $value->anggota->nama_anggota,
                            'nominal'       => 'Rp. ' . number_format($value->nominal_pinjaman, 2, ',', '.'),
                            'status'        => '<a href="#modalKonfirmasi" data-remote="' . route('pengajuan.konfirmasi', $value->id) . '" 
                            data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-info btn-sm">
                            <i class="far fa-plus-square"></i>&nbsp; Setujui</a>',
                            'action'        => '<a href="#modalShow" data-remote="' . route('pengajuan.show', $value->id) . '" data-toggle="modal" data-target="#modalShow" 
                            class="btn btn-default btn-sm"><i class="fas fa-search"></i>&nbsp; Lihat</a>&nbsp;
                            <a href="#modalKonfirmasi" data-remote="' . route('pengajuan.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>&nbsp; Hapus</a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                case 'accept':
                    $data = [];
                    $no   = 1;

                    foreach ($pinjamanAccept as $key => $value) {
                        $data[] = [
                            'no'            => $no++,
                            'kode'          => $value->kode_pinjaman,
                            'kode_anggota'  => $value->anggota->kd_anggota,
                            'tanggal'       => date('d-m-Y', strtotime($value->tanggal)),
                            'nama'          => $value->anggota->nama_anggota,
                            'nominal'       => 'Rp. ' . number_format($value->nominal_pinjaman, 2, ',', '.'),
                            'status'        => '<span class="badge badge-success">Disetujui</span>&nbsp;' . (($value->lunas == 1) ? '<span class="badge badge-success">Lunas</span>' : '<span class="badge badge-danger">Belum Lunas</span>'),
                            'action'        => '<a href="' . route('pengajuan.print', $value->id) . '" class="btn btn-light btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak</a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                    break; 
            }
        }
        return view('simpan_pinjam.pinjaman.pengajuan.pengajuan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anggota = Anggota::get();

        return view('simpan_pinjam.pinjaman.pengajuan.create', compact('anggota'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check = Pinjaman::select('id')->orderBy('id', 'DESC')->first();
        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }

        $nominal = str_replace('.', '', $request->nominal_pinjaman);

        #Rumus Bunga
        $totalPinjaman = $nominal + ($nominal * ($request->bunga / 100) * ($request->tenor / 12));

        #Rumus Angsuran
        $angsuran = $totalPinjaman / $request->tenor;
        
        if ($request->biaya_admin == null) {
            $biaya_admin = 0;
        } else {
            $biaya_admin = str_replace('.', '', $request->biaya_admin);
        }

        #Check Jika Belum Lunas
        $checkAnggota = Pinjaman::select('*')
                            ->where('id_anggota', $request->id_anggota)
                            ->orderBy('id', 'DESC')
                            ->first();
        if ($checkAnggota != null) {
            $checkLunas = Pinjaman::select('*')
                            ->where('id_anggota', $request->id_anggota)
                            ->where('lunas', 1)
                            ->orderBy('id', 'DESC')
                            ->first();
            if ($checkLunas == null) {
                return redirect()->route('pengajuan.create')->with([
                    'error' => 'Pinjaman sebelumnya belum lunas'
                ]);
            } else {
                $pinjaman = new Pinjaman();
                $pinjaman->kode_pinjaman    = 'PNJ-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
                $pinjaman->id_anggota       = $request->id_anggota;
                $pinjaman->tanggal          = $request->tanggal;
                $pinjaman->nominal_pinjaman = (int) $nominal;
                $pinjaman->bunga            = $request->bunga;
                $pinjaman->tenor            = $request->tenor;
                $pinjaman->total_pinjaman   = $totalPinjaman;
                $pinjaman->nominal_angsuran = round($angsuran, 2);
                $pinjaman->biaya_admin      = $biaya_admin;
                $pinjaman->save();

                return redirect()->route('pengajuan.index')->with([
                    'success' => 'Berhasil menambah pinjaman'
                ]);
            }
        } else {
            $pinjaman = new Pinjaman();
            $pinjaman->kode_pinjaman    = 'PNJ-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $pinjaman->id_anggota       = $request->id_anggota;
            $pinjaman->tanggal          = $request->tanggal;
            $pinjaman->nominal_pinjaman = (int) $nominal;
            $pinjaman->bunga            = $request->bunga;
            $pinjaman->tenor            = $request->tenor;
            $pinjaman->total_pinjaman   = $totalPinjaman;
            $pinjaman->nominal_angsuran = round($angsuran, 2);
            $pinjaman->biaya_admin      = $biaya_admin;
            $pinjaman->save();

            return redirect()->route('pengajuan.index')->with([
                'success' => 'Berhasil menambah pinjaman'
            ]);
        }     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Pinjaman::with('anggota')->findOrFail($id);

        return view('simpan_pinjam.pinjaman.pengajuan.show', compact('data'));
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
        $pinjaman = Pinjaman::findOrFail($id);

        $pinjaman->status = $request->status;
        $pinjaman->update();

        return redirect()->route('pengajuan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        $pinjaman->delete();

        return redirect()->route('pengajuan.index')->with([
            'success' => 'Berhasil menghapus pengajuan pinjaman'
        ]);
    }

    public function konfirmasi($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        return view('simpan_pinjam.pinjaman.pengajuan.modal', compact('pinjaman'));
    }

    public function print($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        return view('simpan_pinjam.pinjaman.pengajuan.print', compact('pinjaman'));
    }

    public function print_show($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        return view('simpan_pinjam.pinjaman.pengajuan.print-show', compact('pinjaman'));
    }

    public function modal($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        return view('simpan_pinjam.pinjaman.pengajuan.modal-delete', compact('pinjaman'));
    }

    public function limit(Request $request)
    {
        $anggota = Anggota::select('limit_gaji')->where('id', $request->id)->first();
        $data = array(
            'limit' => $anggota->limit_gaji
        );

        return response()->json($data);
    }
}
