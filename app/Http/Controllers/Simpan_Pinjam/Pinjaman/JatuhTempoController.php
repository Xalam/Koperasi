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
use App\Models\Simpan_Pinjam\Pinjaman\Angsuran;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JatuhTempoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $angsuran = Angsuran::with('pinjaman')->whereIn('id', function ($q) {
        //     $q->select(DB::raw('MAX(id) FROM tb_angsuran'))->groupBy('id_pinjaman');
        // })->where('jenis', 2)->orderBy('id', 'DESC')->get();

        $angsuran = Angsuran::with('pinjaman')->where('jenis', 2)->orderBy('id', 'DESC')->get();
        $angsuranWaiting = $angsuran->where('status', 0);
        $angsuranAcc     = $angsuran->where('status', 1);

        if (request()->ajax()) {
            switch (request()->type) {
                case 'waiting':
                    $data = [];
                    $no   = 1;
                    foreach ($angsuranWaiting as $key => $value) {
                        $data[] = [
                            'no'            => $no++,
                            'kode'          => $value->pinjaman->kode_pinjaman,
                            'kode_anggota'  => $value->pinjaman->anggota->kd_anggota,
                            'tanggal'       => date('d-m-Y', strtotime($value->tanggal)),
                            'nama'          => $value->pinjaman->anggota->nama_anggota,
                            'nominal'       => 'Rp. ' . number_format($value->total_bayar, '0', '', '.'),
                            'angsuran'      => $value->pinjaman->tenor - $value->sisa_bayar,
                            'status'        => (($value->status == 0) ? '<a href="#modalKonfirmasi" data-remote="' . route('tempo.konfirmasi', $value->id) . '" 
                                            data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-primary btn-sm"><i class="far fa-plus-square"></i>&nbsp; Proses</a>' :
                                '<span class="badge badge-success">Disetujui</span>') . (($value->lunas == 1) ? '<span class="badge badge-success">Lunas</span>' : ''),
                            'image'         => (($value->image != null) ? '<a href="#modalKonfirmasi" data-remote="' . route('tempo.image', $value->id) . '" 
                                            data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-info btn-sm">
                                            <i class="fas fa-image"></i></a>' : '-'),
                            'action'        => (($value->status == 1) ? '<a href="' . route('tempo.print-show', $value->id) . '" class="btn btn-light btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak</a>' :
                                '<a href="#modalKonfirmasi" data-remote="' . route('tempo.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>&nbsp; Hapus</a>')
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                case 'accept':
                    $data = [];
                    $no   = 1;
                    foreach ($angsuranAcc as $key => $value) {
                        $data[] = [
                            'no'            => $no++,
                            'kode'          => $value->pinjaman->kode_pinjaman,
                            'kode_anggota'  => $value->pinjaman->anggota->kd_anggota,
                            'tanggal'       => date('d-m-Y', strtotime($value->tanggal)),
                            'nama'          => $value->pinjaman->anggota->nama_anggota,
                            'nominal'       => 'Rp. ' . number_format($value->total_bayar, '0', '', '.'),
                            'angsuran'      => $value->pinjaman->tenor - $value->sisa_bayar,
                            'status'        => (($value->status == 0) ? '<a href="#modalKonfirmasi" data-remote="' . route('tempo.konfirmasi', $value->id) . '" 
                                            data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-primary btn-sm"><i class="far fa-plus-square"></i>&nbsp; Proses</a>' :
                                '<span class="badge badge-success">Disetujui</span>') . (($value->lunas == 1) ? '<span class="badge badge-success">Lunas</span>' : ''),
                            'jurnal'        => (($value->kode_jurnal == null) ? '-' : $value->kode_jurnal),
                            'action'        => (($value->status == 1) ? '<a href="' . route('tempo.print-show', $value->id) . '" class="btn btn-light btn-sm"><i class="fas fa-print"></i>&nbsp; Cetak</a>' :
                                '<a href="#modalKonfirmasi" data-remote="' . route('tempo.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>&nbsp; Hapus</a>')
                        ];
                    }
                    return response()->json(compact('data'));
                    break;
                    break;
            }
        }
        return view('Simpan_Pinjam.pinjaman.angsuran-tempo.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #Update Pinjaman
        $pinjamanUpdate = Pinjaman::findOrFail($request->id_pinjaman);

        $pinjamanUpdate->angsuran_ke = $pinjamanUpdate->angsuran_ke += 1;
        $pinjamanUpdate->lunas = 1;

        $pinjamanUpdate->update();

        #Kode Angsuran
        $check = Angsuran::select('*')->orderBy('id', 'DESC')->first();
        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }

        #Check Jurnal
        $kodeJurnal = KodeJurnal::kode();

        $angsuran = new Angsuran();
        $angsuran->kode_angsuran    = 'ASN-' . str_replace('-', '', date('Y-m-d')) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $angsuran->id_pinjaman      = $request->id_pinjaman;
        $angsuran->tanggal          = $request->tanggal;
        $angsuran->nominal_angsuran = $pinjamanUpdate->nominal_angsuran;
        $angsuran->sisa_angsuran    = 0;
        $angsuran->sisa_bayar       = $pinjamanUpdate->tenor - $pinjamanUpdate->angsuran_ke;
        $angsuran->potongan         = str_replace(',', '', $request->potongan);
        $angsuran->status           = 1;
        $angsuran->lunas            = 1;
        $angsuran->jenis            = 2;
        $angsuran->total_bayar      = str_replace(',', '', $request->total_bayar);
        $angsuran->keterangan       = $request->keterangan;
        $angsuran->kode_jurnal      = $kodeJurnal;
        $angsuran->save();

        //Input Jurnal Umum
        #Check Akun
        $checkAkunKas        = Akun::where('kode_akun', 1101)->first();
        $checkAkunPiutang    = Akun::where('kode_akun', 1121)->first();
        $checkAkunPendapatan = Akun::where('kode_akun', 4101)->first();

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

        $kodeAngsuran = Angsuran::orderBy('id', 'DESC')->first();

        #Pembulatan Pendapatan
        $pendapatan = round(($kodeAngsuran->pinjaman->total_pinjaman - $kodeAngsuran->pinjaman->nominal_pinjaman) / $kodeAngsuran->pinjaman->tenor, 2);

        $newPendapatan = Ratusan::edit_ratusan($pendapatan);

        #Pembulatan Piutang
        $piutang = round($kodeAngsuran->pinjaman->nominal_pinjaman / $kodeAngsuran->pinjaman->tenor, 2);

        $newPiutang = Ratusan::edit_ratusan($piutang);

        $keterangan = 'Angsuran ( ' . $kodeAngsuran->kode_angsuran . ' )';

        #Simpan Jurnal Pendapatan
        SaveJurnalUmum::save($kodeJurnal, $idPendapatan, $keterangan, 0, $newPendapatan);

        #Simpan Jurnal Piutang
        SaveJurnalUmum::save($kodeJurnal, $idPiutang, $keterangan, 0, $newPiutang);

        #Simpan Jurnal Kas
        SaveJurnalUmum::save($kodeJurnal, $idKas, $keterangan, $kodeAngsuran->nominal_angsuran, 0);

        #Send Whatsapp
        $anggotaSend = Anggota::where('id', $kodeAngsuran->pinjaman->id_anggota)->first();
        $phoneNumber = $anggotaSend->no_wa;

        $message = 'Pelunasan pinjaman atas nama (' . $anggotaSend->nama_anggota . ') telah dibayar. Sebesar  : *Rp ' . number_format($kodeAngsuran->total_bayar, 0, '', '.') . '*';
        ResponseMessage::send($phoneNumber, $message);

        return redirect()->route('tempo.index')->with([
            'success' => 'Berhasil melunasi angsuran'
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
        $angsuran = Angsuran::findOrFail($id);

        $checkStatusPnj = Pinjaman::where('id', $angsuran->id_pinjaman)->where('status', 0)->first();
        $checkLunasPnj  = Pinjaman::where('id', $angsuran->id_pinjaman)->where('status', 1)->where('lunas', 1)->first();

        if (!$checkStatusPnj) {
            #Update Pinjaman

            if ($checkLunasPnj) {
                return redirect()->route('tempo.index')->with([
                    'error' => 'Pinjaman sudah lunas, harap hapus dari daftar pengajuan'
                ]);
            }

            $pinjamanUpdate = Pinjaman::findOrFail($angsuran->id_pinjaman);

            $pinjamanUpdate->angsuran_ke += 1;
            $pinjamanUpdate->lunas = 1;

            $pinjamanUpdate->update();

            //Input Jurnal Umum
            #Check Akun
            $checkAkunKas        = Akun::where('kode_akun', 1101)->first();
            $checkAkunPiutang    = Akun::where('kode_akun', 1121)->first();
            $checkAkunPendapatan = Akun::where('kode_akun', 4101)->first();

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

            #Check Jurnal
            $kodeJurnal = KodeJurnal::kode();

            $angsuran->status = $request->status;
            $angsuran->lunas = 1;
            $angsuran->kode_jurnal = $kodeJurnal;
            $angsuran->update();

            $kodeAngsuran = Angsuran::where('id', $id)->first();

            //Input Jurnal
            $bunga = $kodeAngsuran->pinjaman->nominal_pinjaman * ($kodeAngsuran->pinjaman->bunga / 100);
            $pokok = $kodeAngsuran->total_bayar - $bunga;

            $keterangan = 'Angsuran ( ' . $kodeAngsuran->kode_angsuran . ' )';

            #Simpan Jurnal Pendapatan
            SaveJurnalUmum::save($kodeJurnal, $idPendapatan, $keterangan, 0, $bunga);

            #Simpan Jurnal Piutang
            SaveJurnalUmum::save($kodeJurnal, $idPiutang, $keterangan, 0, $pokok);

            #Simpan Jurnal Kas
            SaveJurnalUmum::save($kodeJurnal, $idKas, $keterangan, $kodeAngsuran->total_bayar, 0);

            #Send Whatsapp
            $anggotaSend = Anggota::where('id', $kodeAngsuran->pinjaman->id_anggota)->first();
            $phoneNumber = $anggotaSend->no_wa;

            $message = 'Pelunasan pinjaman atas nama (' . $anggotaSend->nama_anggota . ') telah dibayar. Sebesar  : *Rp ' . number_format($kodeAngsuran->total_bayar, 0, '', '.') . '*';
            ResponseMessage::send($phoneNumber, $message);

            return redirect()->route('tempo.index');
        } else {
            return redirect()->route('tempo.index')->with([
                'error' => 'Pinjaman belum disetujui'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        $angsuran->delete();

        $notifikasi = new Notifikasi();

        $notifikasi->create([
            'id_anggota' => $angsuran->pinjaman->id_anggota,
            'title'      => 'Penolakan Pelunasan Sebelum Jatuh Tempo',
            'content'    => 'Pengajuan pelunasan Anda pada tanggal ' . date('d-m-Y', strtotime($angsuran->tanggal)) . ' sebesar Rp ' . number_format($angsuran->total_bayar, 0, '', '.') . ' ditolak.'
        ]);

        return redirect()->route('tempo.index')->with([
            'success' => 'Berhasil menghapus pengajuan angsuran'
        ]);
    }

    public function bayar(Request $request)
    {
        $pinjaman = Pinjaman::where('kode_pinjaman', $request->kode_bayar);

        if ($pinjaman->get()->count() > 0) {

            if ($pinjaman->where('status', 2)->get()->count() > 0) {
                if ($pinjaman->where('lunas', 0)->get()->count() > 0) {

                    $data = $pinjaman->firstOrFail();

                    return view('Simpan_Pinjam.pinjaman.angsuran-tempo.bayar', compact('data'));
                } else {
                    return redirect()->route('tempo.index')->with([
                        'error' => 'Kode pinjaman sudah lunas'
                    ]);
                }
            } else {
                return redirect()->route('tempo.index')->with([
                    'error' => 'Kode pinjaman belum disetujui/dicairkan'
                ]);
            }
        } else {
            return redirect()->route('tempo.index')->with([
                'error' => 'Kode pinjaman tidak ditemukan'
            ]);
        }
    }

    public function print_show($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.angsuran-tempo.print-show', compact('angsuran'));
    }

    public function konfirmasi($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.angsuran-tempo.modal', compact('angsuran'));
    }

    public function modal($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.angsuran-tempo.modal-delete', compact('angsuran'));
    }

    public function modal_image($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.angsuran-tempo.image', compact('angsuran'));
    }

    public function delete_angsuran()
    {
        $angsuran = Angsuran::where('status', 0)->where('jenis', 2)->whereNull('image')->where(DB::raw("DATE_ADD(created_at, INTERVAL 1 DAY)"), '<', DB::raw("NOW()"))->get();

        for ($i = 0; $i < sizeof($angsuran); $i++) {
            $notifikasi = new Notifikasi();

            $notifikasi->create([
                'id_anggota' => $angsuran[$i]->pinjaman->id_anggota,
                'title'      => 'Penolakan Pelunasan Sebelum Jatuh Tempo',
                'content'    => 'Pengajuan pelunasan Anda pada tanggal ' . date('d-m-Y', strtotime($angsuran[$i]->tanggal)) . ' sebesar Rp ' . number_format($angsuran[$i]->total_bayar, 0, '', '.') . ' ditolak karena melebihi batas waktu yang telah ditentukan.'
            ]);

            $angsuran[$i]->delete();
        }
    }
}
