<?php

namespace App\Http\Controllers\Simpan_Pinjam\Pinjaman;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Pengaturan\Pengaturan;
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
        $pinjaman = Pinjaman::with('anggota')->orderBy('id', 'DESC')->get();
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
                            'status'        => (($value->lunas == 1) ? '<span class="badge badge-success">Lunas</span>' : '<span class="badge badge-danger">Belum Lunas</span>'),
                            'action'        => '<a href="' . route('pengajuan.print', $value->id) . '" class="btn btn-light btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak</a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                    break; 
            }
        }
        return view('Simpan_Pinjam.pinjaman.pengajuan.pengajuan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anggota     = Anggota::get();
        $bunga       = Pengaturan::where('id', 1)->first();
        $tenor       = Pengaturan::where('id', 2)->first();
        $provisi     = Pengaturan::where('id', 3)->first();
        $asuransi    = Pengaturan::where('id', 4)->first();

        $expBunga    = explode(" ", $bunga->angka);
        $expTenor    = explode(" ", $tenor->angka);
        $expProvisi  = explode(" ", $provisi->angka);
        $expAsuransi = explode(" ", $asuransi->angka);

        $prov        = $expProvisi[0];
        $asur        = $expAsuransi[0];

        return view('Simpan_Pinjam.pinjaman.pengajuan.create', compact('anggota', 'expBunga', 'expTenor', 'prov', 'asur'));
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
        $totalPinjaman = $nominal + (($nominal * ($request->bunga / 100)) * $request->tenor);
        
        #Rumus Angsuran
        $angsuran = ($nominal / $request->tenor) + ($nominal * ($request->bunga / 100));
        
        $intNumber = (int) $angsuran;

        $ratusan = substr($intNumber, -3);

        $bulat = $intNumber - $ratusan;
        $newRatusan = 0;

        if ($ratusan > 0 && $ratusan <= 100) {
            $newRatusan = 100;
        } else if($ratusan > 100 && $ratusan <= 200) {
            $newRatusan = 200;
        } else if($ratusan > 200 && $ratusan <= 300) {
            $newRatusan = 300;
        } else if($ratusan > 300 && $ratusan <= 400) {
            $newRatusan = 400;
        } else if($ratusan > 400 && $ratusan <= 500) {
            $newRatusan = 500;
        } else if($ratusan > 500 && $ratusan <= 600) {
            $newRatusan = 600;
        } else if($ratusan > 600 && $ratusan <= 700) {
            $newRatusan = 700;
        } else if($ratusan > 700 && $ratusan <= 800) {
            $newRatusan = 800;
        } else if($ratusan > 800 && $ratusan <= 900) {
            $newRatusan = 900;
        } else if($ratusan > 900 && $ratusan <= 999) {
            $newRatusan = 1000;
        } else {
            $newRatusan = $ratusan;
        }

        $uangAngsuran = $bulat + $newRatusan;

        #Biaya Provisi & Asuransi
        $provisi     = Pengaturan::where('id', 3)->first();
        $asuransi    = Pengaturan::where('id', 4)->first();
        
        $expProvisi  = explode(" ", $provisi->angka);
        $expAsuransi = explode(" ", $asuransi->angka);

        $prov        = $expProvisi[0];
        $asur        = $expAsuransi[0];

        $countProv   = ($prov / 100) * $nominal;
        $countAsur   = ($asur / 100) * $nominal;

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
                #Simpan Pinjaman
                $pinjaman = new Pinjaman();
                $pinjaman->kode_pinjaman    = 'PNJ-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
                $pinjaman->id_anggota       = $request->id_anggota;
                $pinjaman->tanggal          = $request->tanggal;
                $pinjaman->nominal_pinjaman = $nominal;
                $pinjaman->bunga            = $request->bunga;
                $pinjaman->tenor            = $request->tenor;
                $pinjaman->total_pinjaman   = $totalPinjaman;
                $pinjaman->nominal_angsuran = $uangAngsuran;
                $pinjaman->biaya_provisi    = $countProv;
                $pinjaman->biaya_asuransi   = $countAsur;
                $pinjaman->biaya_admin      = $biaya_admin;
                $pinjaman->save();

                return redirect()->route('pengajuan.index')->with([
                    'success' => 'Berhasil menambah pinjaman'
                ]);
            }
        } else {
            #Simpan Pinjaman
            $pinjaman = new Pinjaman();
            $pinjaman->kode_pinjaman    = 'PNJ-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $pinjaman->id_anggota       = $request->id_anggota;
            $pinjaman->tanggal          = $request->tanggal;
            $pinjaman->nominal_pinjaman = $nominal;
            $pinjaman->bunga            = $request->bunga;
            $pinjaman->tenor            = $request->tenor;
            $pinjaman->total_pinjaman   = $totalPinjaman;
            $pinjaman->nominal_angsuran = $uangAngsuran;
            $pinjaman->biaya_provisi    = $countProv;
            $pinjaman->biaya_asuransi   = $countAsur;
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

        return view('Simpan_Pinjam.pinjaman.pengajuan.show', compact('data'));
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

        //Input Jurnal Umum
        #Check Akun
        $checkAkunKas        = Akun::where('kode_akun', 1101)->first();
        $checkAkunPiutang    = Akun::where('kode_akun', 1121)->first();
        $checkAkunPendapatan = Akun::where('kode_akun', 4101)->first();
        $checkAkunAsuransi   = Akun::where('kode_akun', 2115)->first();
        
        if ($checkAkunKas == null) {
            $idKas = 0;
        } else {
            $idKas = $checkAkunKas->id;
        }

        if ($checkAkunPiutang == null) {
            $idPiutang = 0;
        } else {
            $idPiutang = $checkAkunPiutang->id;
        }

        if ($checkAkunPendapatan == null) {
            $idPendapatan = 0;
        } else {
            $idPendapatan = $checkAkunPendapatan->id;
        }

        if ($checkAkunAsuransi == null) {
            $idAsuransi = 0;
        } else {
            $idAsuransi = $checkAkunAsuransi->id;
        }

        #Check Jurnal
        $checkJurnal = JurnalUmum::select('*')->orderBy('id', 'DESC')->first();
        if ($checkJurnal == null) {
            $idJurnal = 1;
        } else {
            $substrKode = substr($checkJurnal->kode_jurnal, 3);
            $idJurnal   = $substrKode + 1;
        }

        $pinjaman->status = $request->status;
        $pinjaman->kode_jurnal = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $pinjaman->update();

        $kodePinjaman = Pinjaman::where('id', $id)->first();

        #Simpan Dana Asuransi
        $jurnal = new JurnalUmum();
        $jurnal-> kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $jurnal->id_akun        = $idAsuransi;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = 'Pinjaman ( ' . $kodePinjaman->kode_pinjaman . ' )';
        $jurnal->debet          = 0;
        $jurnal->kredit         = $kodePinjaman->biaya_asuransi;
        $jurnal->save();

        $akun = Akun::findOrFail($idAsuransi);
        $akun->saldo = $akun->saldo - $kodePinjaman->biaya_asuransi;
        $akun->update();

        #Simpan Jurnal Pendapatan
        $jurnal = new JurnalUmum();
        $jurnal-> kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $jurnal->id_akun        = $idPendapatan;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = 'Pinjaman ( ' . $kodePinjaman->kode_pinjaman . ' )';
        $jurnal->debet          = 0;
        $jurnal->kredit         = $kodePinjaman->biaya_admin;
        $jurnal->save(); 

        $akun = Akun::findOrFail($idPendapatan);
        $akun->saldo = $akun->saldo - $kodePinjaman->biaya_admin;
        $akun->update();

        #Simpan Jurnal Kas
        $jurnal = new JurnalUmum();
        $jurnal-> kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $jurnal->id_akun        = $idKas;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = 'Pinjaman ( ' . $kodePinjaman->kode_pinjaman . ' )';
        $jurnal->debet          = 0;
        $jurnal->kredit         = $kodePinjaman->nominal_pinjaman - $kodePinjaman->biaya_asuransi - $kodePinjaman->biaya_admin;
        $jurnal->save();

        $akun = Akun::findOrFail($idKas);
        $akun->saldo = $akun->saldo - ($kodePinjaman->nominal_pinjaman - $kodePinjaman->biaya_admin);
        $akun->update();

        #Simpan Jurnal Piutang
        $jurnal = new JurnalUmum();
        $jurnal-> kode_jurnal   = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);
        $jurnal->id_akun        = $idPiutang;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = 'Pinjaman ( ' . $kodePinjaman->kode_pinjaman . ' )';
        $jurnal->debet          = $kodePinjaman->nominal_pinjaman;
        $jurnal->kredit         = 0;
        $jurnal->save();

        $akun = Akun::findOrFail($idPiutang);
        $akun->saldo = $akun->saldo + $kodePinjaman->nominal_pinjaman;
        $akun->update();

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

        return view('Simpan_Pinjam.pinjaman.pengajuan.modal', compact('pinjaman'));
    }

    public function print($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.pengajuan.print', compact('pinjaman'));
    }

    public function print_show($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.pengajuan.print-show', compact('pinjaman'));
    }

    public function modal($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.pengajuan.modal-delete', compact('pinjaman'));
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
