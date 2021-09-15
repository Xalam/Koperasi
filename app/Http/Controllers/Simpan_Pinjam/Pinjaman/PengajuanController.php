<?php

namespace App\Http\Controllers\Simpan_Pinjam\Pinjaman;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\KodeJurnal;
use App\Http\Controllers\Simpan_Pinjam\Utils\Ratusan;
use App\Http\Controllers\Simpan_Pinjam\Utils\ResponseMessage;
use App\Http\Controllers\Simpan_Pinjam\Utils\SaveJurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Other\Notifikasi;
use App\Models\Simpan_Pinjam\Pengaturan\Pengaturan;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use App\Models\Toko\Transaksi\Piutang\PiutangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $pinjamanCair    = $pinjaman->where('status', 2);

        if (request()->ajax()) {
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
                            <i class="far fa-plus-square"></i>&nbsp; Proses</a>',
                            'action'        => '<a href="#modalShow" data-remote="' . route('pengajuan.show', $value->id) . '" data-toggle="modal" data-target="#modalShow" 
                            class="btn btn-default btn-sm"><i class="fas fa-search"></i></a>&nbsp;
                            <a href="#modalKonfirmasi" data-remote="' . route('pengajuan.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>'
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
                            'status'        => '<a href="#modalKonfirmasi" data-remote="' . route('pengajuan.konfirmasi', $value->id) . '" 
                            data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-success btn-sm">
                            <i class="far fa-check-square"></i>&nbsp; Cair</a>',
                            'action'        => '<a href="#modalShow" data-remote="' . route('pengajuan.show', $value->id) . '" data-toggle="modal" data-target="#modalShow" 
                            class="btn btn-default btn-sm"><i class="fas fa-search"></i></a>&nbsp;
                            <a href="#modalKonfirmasi" data-remote="' . route('pengajuan.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>'
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                case 'cair':
                    $data = [];
                    $no   = 1;

                    foreach ($pinjamanCair as $key => $value) {
                        $data[] = [
                            'no'            => $no++,
                            'kode'          => $value->kode_pinjaman,
                            'kode_anggota'  => $value->anggota->kd_anggota,
                            'tanggal'       => date('d-m-Y', strtotime($value->tanggal)),
                            'nama'          => $value->anggota->nama_anggota,
                            'nominal'       => 'Rp. ' . number_format($value->nominal_pinjaman, 2, ',', '.'),
                            'status'        => (($value->lunas == 1) ? '<span class="badge badge-success">Lunas</span>' : '<span class="badge badge-danger">Belum Lunas</span>'),
                            'jurnal'        => $value->kode_jurnal,
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

        $uangAngsuran = Ratusan::edit_ratusan($angsuran);

        #Biaya Provisi & Asuransi
        $provisi     = Pengaturan::where('id', 3)->first();
        $asuransi    = Pengaturan::where('id', 4)->first();

        $expProvisi  = explode(" ", $provisi->angka);
        $expAsuransi = explode(" ", $asuransi->angka);

        $prov        = $expProvisi[0];
        $asur        = $expAsuransi[0];

        $countProv   = ($prov / 100) * $nominal;
        $countAsur   = ($asur / 100) * $nominal;

        #Check Jika Belum Lunas
        $checkAnggota = Pinjaman::select('*')
            ->where('id_anggota', $request->id_anggota)
            ->orderBy('id', 'DESC')
            ->first();

        if ($checkAnggota) {
            $checkAcc = Pinjaman::select('*')
                ->where('id_anggota', $request->id_anggota)
                ->where('status', 0)
                ->orderBy('id', 'DESC')
                ->first();

            if ($checkAcc) {
                return redirect()->route('pengajuan.create')->with([
                    'error' => 'Pinjaman sebelumnya masih belum disetujui'
                ]);
            } else {
                $checkLunas = Pinjaman::select('*')
                    ->where('id_anggota', $request->id_anggota)
                    ->where('lunas', 0)
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($checkLunas) {
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
                    $pinjaman->save();

                    return redirect()->route('pengajuan.index')->with([
                        'success' => 'Berhasil menambah pinjaman'
                    ]);
                }
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
        $checkAkunAsuransi   = Akun::where('kode_akun', 2115)->first();
        $checkAkunProvisi    = Akun::where('kode_akun', 4203)->first();

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

        if ($checkAkunAsuransi == null) {
            $idAsuransi = 0;
        } else {
            $idAsuransi = $checkAkunAsuransi->id;
        }

        if ($checkAkunProvisi == null) {
            $idProvisi = 0;
        } else {
            $idProvisi = $checkAkunProvisi->id;
        }

        $pinjaman->update(['status' => $request->status]);

        #Send Whatsapp
        $anggotaSend = Anggota::where('id', $pinjaman->id_anggota)->first();
        $phoneNumber = $anggotaSend->no_wa;

        $message = 'Pengajuan pinjaman atas nama (' . $anggotaSend->nama_anggota . ') sebesar : *Rp ' . number_format($pinjaman->nominal_pinjaman, 0, '', '.') . '* telah disetujui.';
        ResponseMessage::send($phoneNumber, $message);

        if ($pinjaman->status == 2) {
            $kodeJurnal   = KodeJurnal::kode();

            $kodePinjaman = Pinjaman::where('id', $id)->first();
            $kodePinjaman->kode_jurnal = $kodeJurnal;
            $kodePinjaman->update();

            $keterangan   = 'Pinjaman ( ' . $kodePinjaman->kode_pinjaman . ' )';

            #Simpan Dana Asuransi
            SaveJurnalUmum::save($kodeJurnal, $idAsuransi, $keterangan, 0, $kodePinjaman->biaya_asuransi);

            #Simpan Dana Provisi
            SaveJurnalUmum::save($kodeJurnal, $idProvisi, $keterangan, 0, $kodePinjaman->biaya_provisi);

            #Simpan Jurnal Kas
            $kredit = $kodePinjaman->nominal_pinjaman - $kodePinjaman->biaya_asuransi - $kodePinjaman->biaya_provisi;

            SaveJurnalUmum::save($kodeJurnal, $idKas, $keterangan, 0, $kredit);

            #Simpan Jurnal Piutang
            SaveJurnalUmum::save($kodeJurnal, $idPiutang, $keterangan, $kodePinjaman->nominal_pinjaman, 0);

            #Send Whatsapp
            $anggotaSend = Anggota::where('id', $kodePinjaman->id_anggota)->first();
            $phoneNumber = $anggotaSend->no_wa;

            $message = 'Pinjaman atas nama (' . $anggotaSend->nama_anggota . ') sebesar : *Rp ' . number_format($kodePinjaman->nominal_pinjaman, 0, '', '.') . '* telah dicairkan.';
            ResponseMessage::send($phoneNumber, $message);
        }

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

        $notifikasi = new Notifikasi();

        $notifikasi->create([
            'id_anggota' => $pinjaman->id_anggota,
            'title'      => 'Penolakan Pengajuan Pinjaman',
            'content'    => 'Pengajuan pinjaman Anda pada tanggal ' . date('d-m-Y', strtotime($pinjaman->tanggal)) . ' sebesar Rp ' . number_format($pinjaman->nominal_pinjaman, 0, '', '.') . ' ditolak.'
        ]);

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
        $piutang = PiutangModel::where('id_anggota', $request->id)->orderBy('id', 'DESC')->first();
        $anggota = Anggota::select('limit_gaji', 'tanggal_lahir')->where('id', $request->id)->first();

        $masa_kerja = date('Y-m', strtotime(date('Y-m', strtotime($anggota->tanggal_lahir)) . ' + 58 year'));
        $sisa_masa_kerja = date_diff(date_create($masa_kerja), date_create(date('Y-m')));

        $totalPiutang = 0;
        if ($piutang) {
            $totalPiutang = $piutang->sisa_piutang;
        }

        $data = array(
            'limit' => $anggota->limit_gaji - $totalPiutang,
            'masa_kerja' => $sisa_masa_kerja->y . ' Tahun ' . $sisa_masa_kerja->m . ' Bulan',
            'sisa_bulan' => ($sisa_masa_kerja->y * 12) + $sisa_masa_kerja->m
        );

        return response()->json($data);
    }

    public function delete_pinjaman()
    {
        $pinjaman = Pinjaman::whereIn('status', [0, 1])->where(DB::raw("DATE_ADD(tanggal, INTERVAL 1 DAY)"), '<', date('Y-m-d'))->get();

        for ($i = 0; $i < sizeof($pinjaman); $i++) {
            $notifikasi = new Notifikasi();

            $notifikasi->create([
                'id_anggota' => $pinjaman[$i]->id_anggota,
                'title'      => 'Penolakan Pengajuan Pinjaman',
                'content'    => 'Pengajuan pinjaman Anda pada tanggal ' . date('d-m-Y', strtotime($pinjaman[$i]->tanggal)) . ' sebesar Rp ' . number_format($pinjaman[$i]->nominal_pinjaman, 0, '', '.') . ' ditolak karena melebihi batas waktu yang telah ditentukan.'
            ]);

            $pinjaman[$i]->delete();
        }
    }
}
