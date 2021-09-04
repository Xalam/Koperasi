<?php

namespace App\Http\Controllers\Simpan_Pinjam\Pinjaman;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\KodeJurnal;
use App\Http\Controllers\Simpan_Pinjam\Utils\Ratusan;
use App\Http\Controllers\Simpan_Pinjam\Utils\ResponseMessage;
use App\Http\Controllers\Simpan_Pinjam\Utils\SaveJurnalUmum;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Pinjaman\Angsuran;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AngsuranController extends Controller
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
        // })->where('jenis', 1)->orderBy('id', 'DESC')->get();
        $angsuran = Angsuran::with('pinjaman')->where('jenis', 1)->orderBy('id', 'DESC')->get();

        if (request()->ajax()) {
            $data = [];
            $no   = 1;
            foreach ($angsuran as $key => $value) {
                $data[] = [
                    'no'            => $no++,
                    'kode'          => $value->pinjaman->kode_pinjaman,
                    'kode_anggota'  => $value->pinjaman->anggota->kd_anggota,
                    'tanggal'       => date('d-m-Y', strtotime($value->tanggal)),
                    'nama'          => $value->pinjaman->anggota->nama_anggota,
                    'nominal'       => 'Rp. ' . number_format($value->nominal_angsuran, '2', ',', '.'),
                    'angsuran'      => $value->pinjaman->tenor - $value->sisa_bayar,
                    'status'        => (($value->status == 1) ? '<a href="#modalKonfirmasi" data-remote="' . route('angsuran.konfirmasi', $value->id) . '" 
                            data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-success btn-xs"><i class="fas fa-check"></i></a>' :
                        '<a href="#modalKonfirmasi" data-remote="' . route('angsuran.konfirmasi', $value->id) . '" 
                            data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-danger btn-xs">&nbsp;<i class="fas fa-times"></i>&nbsp;</a>') . (($value->lunas == 1) ? '<br><span class="badge badge-success">Lunas</span>' : ''),
                    'jurnal'        => (($value->kode_jurnal == null) ? '-' : $value->kode_jurnal),
                    'action'        => '<a href="' . route('angsuran.print-show', $value->id) . '" class="btn btn-default btn-sm"><i class="fas fa-print"></i></a>&nbsp;' .
                        (($value->status == 0) ? '<a href="#modalKonfirmasi" data-remote="' . route('angsuran.modal', $value->id) . '" data-toggle="modal" data-target="#modalKonfirmasi" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>' : '')
                ];
            }
            return response()->json(compact('data'));
        }
        return view('Simpan_Pinjam.pinjaman.angsuran.angsuran');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Simpan_Pinjam.pinjaman.angsuran.create');
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

        if ($pinjamanUpdate->tenor == $pinjamanUpdate->angsuran_ke) {
            $pinjamanUpdate->lunas = 1;
        }

        $pinjamanUpdate->update();

        #Kode Angsuran
        $check = Angsuran::select('*')->orderBy('id', 'DESC')->first();
        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }

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

        #Sisa Angsuran
        $sisaAngsuran = $pinjamanUpdate->total_pinjaman - ($pinjamanUpdate->nominal_angsuran * $pinjamanUpdate->angsuran_ke);

        if ($sisaAngsuran < 0) {
            $sisaAngsuran = 0;
        }

        $angsuran = new Angsuran();
        $angsuran->kode_angsuran    = 'ASN-' . str_replace('-', '', date('Y-m-d')) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $angsuran->id_pinjaman      = $request->id_pinjaman;
        $angsuran->tanggal          = date('Y-m-d');
        $angsuran->nominal_angsuran = $pinjamanUpdate->nominal_angsuran;
        $angsuran->sisa_angsuran    = $sisaAngsuran;
        $angsuran->sisa_bayar       = $pinjamanUpdate->tenor - $pinjamanUpdate->angsuran_ke;
        $angsuran->status           = 1;
        $angsuran->lunas            = $pinjamanUpdate->lunas;
        $angsuran->kode_jurnal      = $kodeJurnal;
        $angsuran->save();

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

        $message = 'Angsuran ke - *' . $kodeAngsuran->pinjaman->angsuran_ke . '* atas nama (' . $anggotaSend->nama_anggota . ') telah dibayar. Sebesar  : *Rp ' . number_format($kodeAngsuran->nominal_angsuran, 0, '', '.') . '*';
        ResponseMessage::send($phoneNumber, $message);

        return redirect()->route('angsuran.index')->with([
            'success' => 'Berhasil membayar angsuran'
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

        $checkPinjaman = Pinjaman::where('id', $angsuran->id_pinjaman)->where('status', 0)->first();

        if (!$checkPinjaman) {
            #Update Pinjaman
            $pinjamanUpdate = Pinjaman::findOrFail($angsuran->id_pinjaman);

            $pinjamanUpdate->angsuran_ke = $pinjamanUpdate->angsuran_ke += 1;

            if ($pinjamanUpdate->tenor == $pinjamanUpdate->angsuran_ke) {
                $pinjamanUpdate->lunas = 1;
            }

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
            $angsuran->kode_jurnal = $kodeJurnal;
            $angsuran->update();

            $kodeAngsuran = Angsuran::where('id', $id)->first();

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

            $message = 'Angsuran ke - *' . $kodeAngsuran->pinjaman->angsuran_ke . '* atas nama (' . $anggotaSend->nama_anggota . ') telah dibayar. Sebesar  : *Rp ' . number_format($kodeAngsuran->nominal_angsuran, 0, '', '.') . '*';
            ResponseMessage::send($phoneNumber, $message);

            return redirect()->route('angsuran.index');
        } else {
            return redirect()->route('angsuran.index')->with([
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

        return redirect()->route('angsuran.index')->with([
            'success' => 'Berhasil menghapus pengajuan angsuran'
        ]);
    }

    public function bayar(Request $request)
    {
        $pinjaman = Pinjaman::where('kode_pinjaman', $request->kode_bayar);

        if ($pinjaman->get()->count() > 0) {

            if ($pinjaman->where('status', 1)->get()->count() > 0) {
                if ($pinjaman->where('lunas', 0)->get()->count() > 0) {

                    $data = $pinjaman->firstOrFail();

                    return view('Simpan_Pinjam.pinjaman.angsuran.bayar', compact('data'));
                } else {
                    return redirect()->route('angsuran.index')->with([
                        'error' => 'Kode pinjaman sudah lunas'
                    ]);
                }
            } else {
                return redirect()->route('angsuran.index')->with([
                    'error' => 'Kode pinjaman belum disetujui'
                ]);
            }
        } else {
            return redirect()->route('angsuran.index')->with([
                'error' => 'Kode pinjaman tidak ditemukan'
            ]);
        }
    }

    public function print_show($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.angsuran.print-show', compact('angsuran'));
    }

    public function konfirmasi($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.angsuran.modal', compact('angsuran'));
    }

    public function modal($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        return view('Simpan_Pinjam.pinjaman.angsuran.modal-delete', compact('angsuran'));
    }

    public function store_all(Request $request)
    {
        $date           = $request->tanggal . '-' . date('d');
        $pinjaman       = Pinjaman::where('status', 2)->where('lunas', 0)->where(DB::raw("DATE_FORMAT(tanggal, '%Y-%m')"), '<', date('Y-m'))->get();
        // dd($pinjaman);
        $angsuranGet    = Angsuran::where('jenis', 1)->orderBy('id', 'DESC')->first();
        $count          = $pinjaman->count();

        if ($request->tanggal < date('Y-m')) {
            return redirect()->route('angsuran.create')->with([
                'error' => 'Bulan yang dimasukkan tidak boleh sebelum bulan sekarang.'
            ]);
        }

        if ($request->tanggal > date('Y-m')) {
            return redirect()->route('angsuran.create')->with([
                'error' => 'Bulan yang dimasukkan tidak boleh sesudah bulan sekarang.'
            ]);
        }

        if ($count > 0) {
            if ($angsuranGet) {
                if (date('Y-m', strtotime($angsuranGet->tanggal)) == $request->tanggal) {
                    return redirect()->route('angsuran.create')->with([
                        'error' => 'Angsuran bulan ini sudah ditambahkan.'
                    ]);
                }
            }
            for ($i = 0; $i < $count; $i++) {
                #Update Pinjaman
                $pinjamanUpdate = Pinjaman::findOrFail($pinjaman[$i]->id);

                $pinjamanUpdate->angsuran_ke = $pinjamanUpdate->angsuran_ke += 1;

                if ($pinjamanUpdate->tenor == $pinjamanUpdate->angsuran_ke) {
                    $pinjamanUpdate->lunas = 1;
                }

                $pinjamanUpdate->update();

                #Kode Angsuran
                $check = Angsuran::select('*')->orderBy('id', 'DESC')->first();
                if ($check == null) {
                    $id = 1;
                } else {
                    $id = $check->id + 1;
                }

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

                #Sisa Angsuran
                $sisaAngsuran = $pinjamanUpdate->total_pinjaman - ($pinjamanUpdate->nominal_angsuran * $pinjamanUpdate->angsuran_ke);

                if ($sisaAngsuran < 0) {
                    $sisaAngsuran = 0;
                }

                $angsuran = new Angsuran();
                $angsuran->kode_angsuran    = 'ASN-' . str_replace('-', '', date('Y-m-d')) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
                $angsuran->id_pinjaman      = $pinjaman[$i]->id;
                $angsuran->tanggal          = $date;
                $angsuran->nominal_angsuran = $pinjamanUpdate->nominal_angsuran;
                $angsuran->sisa_angsuran    = $sisaAngsuran;
                $angsuran->sisa_bayar       = $pinjamanUpdate->tenor - $pinjamanUpdate->angsuran_ke;
                $angsuran->status           = 1;
                $angsuran->lunas            = $pinjamanUpdate->lunas;
                $angsuran->kode_jurnal      = $kodeJurnal;
                $angsuran->save();

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

                $message = 'Angsuran ke - *' . $kodeAngsuran->pinjaman->angsuran_ke . '* atas nama (' . $anggotaSend->nama_anggota . ') telah dibayar. Sebesar  : *Rp ' . number_format($kodeAngsuran->nominal_angsuran, 0, '', '.') . '*';
                ResponseMessage::send($phoneNumber, $message);
            }

            return redirect()->route('angsuran.index')->with([
                'success' => 'Berhasil menambahkan angsuran pinjaman anggota'
            ]);
        } else {
            return redirect()->route('angsuran.index')->with([
                'error' => 'Belum terdapat pinjaman'
            ]);
        }
    }

    public function update_bayar(Request $request)
    {
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

        $angsuran = Angsuran::findOrFail($request->id);
        $status   = $request->status;

        $pinjamanUpdate = Pinjaman::findOrFail($angsuran->id_pinjaman);

        #Check tanggal perubahan
        if (date('Y-m', strtotime($angsuran->tanggal)) < date('Y-m')) {
            $data = array(
                'tanggal' => 1
            );
            return response()->json($data);
        }

        $checkLunas = Angsuran::where('id_pinjaman', $angsuran->id_pinjaman)->where('jenis', 2)->first();
        #Check lunas angsuran
        if ($checkLunas) {
            $data = array(
                'lunas' => 1
            );
            return response()->json($data);
        }

        if ($status == 1) {
            $message = 'Jurnal Ditambahkan';

            #Update Pinjaman
            $pinjamanUpdate->angsuran_ke += 1;

            if ($pinjamanUpdate->tenor == $pinjamanUpdate->angsuran_ke) {
                $pinjamanUpdate->lunas = 1;
            }

            $pinjamanUpdate->update();

            #Pembulatan Pendapatan
            $pendapatan = round(($angsuran->pinjaman->total_pinjaman - $angsuran->pinjaman->nominal_pinjaman) / $angsuran->pinjaman->tenor, 2);

            $newPendapatan = Ratusan::edit_ratusan($pendapatan);

            #Pembulatan Piutang
            $piutang = round($angsuran->pinjaman->nominal_pinjaman / $angsuran->pinjaman->tenor, 2);

            $newPiutang = Ratusan::edit_ratusan($piutang);

            $keterangan = 'Angsuran ( ' . $angsuran->kode_angsuran . ' )';

            #Simpan Jurnal Pendapatan
            SaveJurnalUmum::save($kodeJurnal, $idPendapatan, $keterangan, 0, $newPendapatan);

            #Simpan Jurnal Piutang
            SaveJurnalUmum::save($kodeJurnal, $idPiutang, $keterangan, 0, $newPiutang);

            #Simpan Jurnal Kas
            SaveJurnalUmum::save($kodeJurnal, $idKas, $keterangan, $angsuran->nominal_angsuran, 0);

            $angsuran->status = $status;
            $angsuran->kode_jurnal = $kodeJurnal;
            $angsuran->update();
        } else {
            $message = 'Jurnal Dihapus';

            #Update Pinjaman
            $pinjamanUpdate->angsuran_ke -= 1;

            if ($pinjamanUpdate->tenor == $pinjamanUpdate->angsuran_ke) {
                $pinjamanUpdate->lunas = 1;
            }

            $pinjamanUpdate->update();

            #Delete Jurnal
            JurnalUmum::where('kode_jurnal', $angsuran->kode_jurnal)->delete();

            $angsuran->status = $status;
            $angsuran->kode_jurnal = null;
            $angsuran->update();
        }

        $data = array(
            'id' => $request->id,
            'status' => $request->status,
            'message' => $message
        );

        return response()->json($data);
    }
}
