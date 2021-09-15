<?php

namespace App\Http\Controllers\API\Simpan_Pinjam;

use App\Events\PusherNotification;
use App\Http\Controllers\API\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\Ratusan;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Other\Notifikasi;
use App\Models\Simpan_Pinjam\Pengaturan\Pengaturan;
use App\Models\Simpan_Pinjam\Pinjaman\Angsuran;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use App\Models\Toko\Transaksi\Piutang\PiutangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $idAnggota = getallheaders()['id'];

        $checkAnggota = Pinjaman::where('id_anggota', $idAnggota)
            ->orderBy('id', 'DESC')->first();

        $checkLimit = Anggota::where('id', $idAnggota)->first();
        $checkPiutang = PiutangModel::where('id_anggota', $idAnggota)->orderBy('id', 'DESC')->first();

        #Check Jangka Waktu
        $masa_kerja = date('Y-m', strtotime(date('Y-m', strtotime($checkLimit->tanggal_lahir)) . ' + 58 year'));
        $sisa_masa_kerja = date_diff(date_create($masa_kerja), date_create(date('Y-m')));
        $sisa_bulan = ($sisa_masa_kerja->y * 12) + $sisa_masa_kerja->m;

        if ($sisa_bulan < $request->tenor) {
            return ResponseFormatter::error('Jangka waktu melebihi sisa masa kerja');
        }

        #Check Nominal
        if ($request->nominal > 75000000) {
            return ResponseFormatter::error('Pinjaman melebihi ketentuan');
        }

        if (date('Y-m-d', strtotime($request->tanggal)) < date('Y-m-d')) {
            return ResponseFormatter::error('Tanggal sudah lewat');
        }

        if ($checkPiutang) {
            $piutangAnggota = $checkPiutang->sisa_piutang;
        } else {
            $piutangAnggota = 0;
        }

        $check = Pinjaman::select('id')->orderBy('id', 'DESC')->first();

        if ($check == null) {
            $id = 1;
        } else {
            $id = $check->id + 1;
        }

        #Biaya Provisi & Asuransi
        $bunga       = Pengaturan::where('id', 1)->first();
        $provisi     = Pengaturan::where('id', 3)->first();
        $asuransi    = Pengaturan::where('id', 4)->first();

        $expBunga    = explode(" ", $bunga->angka);
        $expProvisi  = explode(" ", $provisi->angka);
        $expAsuransi = explode(" ", $asuransi->angka);

        $bung        = $expBunga[0];
        $prov        = $expProvisi[0];
        $asur        = $expAsuransi[0];

        $countProv   = ($prov / 100) * $request->nominal;
        $countAsur   = ($asur / 100) * $request->nominal;

        $totalPinjaman = $request->nominal + (($request->nominal * ($bung / 100)) * $request->tenor);

        if (($checkLimit->limit_gaji - $piutangAnggota) < $request->angsuran) {
            return ResponseFormatter::error('Angsuran melebihi ketentuan');
        } else {
            if ($checkAnggota) {
                $checkStatus = Pinjaman::select('*')
                    ->where('id_anggota', $idAnggota)
                    ->where('status', 0)
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($checkStatus) {
                    return ResponseFormatter::error('Masih terdapat pengajuan yang belum disetujui');
                } else {
                    $checkLunas = Pinjaman::select('*')
                        ->where('id_anggota', $idAnggota)
                        ->where('lunas', 0)
                        ->orderBy('id', 'DESC')
                        ->first();

                    if ($checkLunas) {
                        return ResponseFormatter::error('Pinjaman sebelumnya belum lunas');
                    } else {
                        #Simpan Pinjaman
                        $pinjaman = new Pinjaman();
                        $pinjaman->kode_pinjaman    = 'PNJ-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
                        $pinjaman->id_anggota       = $idAnggota;
                        $pinjaman->tanggal          = $request->tanggal;
                        $pinjaman->nominal_pinjaman = $request->nominal;
                        $pinjaman->bunga            = $bung;
                        $pinjaman->tenor            = $request->tenor;
                        $pinjaman->total_pinjaman   = $totalPinjaman;
                        $pinjaman->nominal_angsuran = $request->angsuran;
                        $pinjaman->biaya_provisi    = $countProv;
                        $pinjaman->biaya_asuransi   = $countAsur;
                        $pinjaman->save();

                        $data = Pinjaman::orderBy('id', 'DESC')->first();

                        #Pusher
                        event(new PusherNotification(3, 'Notifikasi Baru'));

                        #Save Notifikasi
                        $notifikasi = new Notifikasi();

                        $notifikasi->create([
                            'id_anggota' => $idAnggota,
                            'title'      => 'Pengajuan Pinjaman Baru!',
                            'content'    => 'Pengajuan pinjaman ' . $data->anggota->kd_anggota . ' untuk tanggal ' . date('d-m-Y', strtotime($data->tanggal)) . ' sebesar Rp ' . number_format($pinjaman->nominal_pinjaman, 0, '', '.'),
                            'type'       => 1
                        ]);

                        return ResponseFormatter::success($data, 'Berhasil menambah pengajuan pinjaman');
                    }
                }
            } else {
                #Simpan Pinjaman
                $pinjaman = new Pinjaman();
                $pinjaman->kode_pinjaman    = 'PNJ-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
                $pinjaman->id_anggota       = $idAnggota;
                $pinjaman->tanggal          = $request->tanggal;
                $pinjaman->nominal_pinjaman = $request->nominal;
                $pinjaman->bunga            = $bung;
                $pinjaman->tenor            = $request->tenor;
                $pinjaman->total_pinjaman   = $totalPinjaman;
                $pinjaman->nominal_angsuran = $request->angsuran;
                $pinjaman->biaya_provisi    = $countProv;
                $pinjaman->biaya_asuransi   = $countAsur;
                $pinjaman->save();

                $data = Pinjaman::orderBy('id', 'DESC')->first();

                #Pusher
                event(new PusherNotification(3, 'Notifikasi Baru'));

                #Save Notifikasi
                $notifikasi = new Notifikasi();

                $notifikasi->create([
                    'id_anggota' => $idAnggota,
                    'title'      => 'Pengajuan Pinjaman Baru!',
                    'content'    => 'Pengajuan pinjaman ' . $data->anggota->kd_anggota . ' untuk tanggal ' . date('d-m-Y', strtotime($data->tanggal)) . ' sebesar Rp ' . number_format($pinjaman->nominal_pinjaman, 0, '', '.'),
                    'type'       => 1
                ]);

                return ResponseFormatter::success($data, 'Berhasil menambah pengajuan pinjaman');
            }
        }
    }

    public function history()
    {
        $idAnggota = getallheaders()['id'];

        $data['pengajuan'] = Pinjaman::where('id_anggota', $idAnggota)->orderBy('tanggal', 'DESC')->orderBy('id', 'DESC')->get();

        $data['angsuran']  = Angsuran::with('pinjaman')
            ->whereHas('pinjaman', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })->where('jenis', 1)->orderBy('tanggal', 'DESC')->orderBy('id', 'DESC')->get();

        $data['pelunasan']  = Angsuran::with('pinjaman')
            ->whereHas('pinjaman', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })->where('jenis', 2)->orderBy('tanggal', 'DESC')->orderBy('id', 'DESC')->get();

        return ResponseFormatter::success($data, 'Berhasil mendapatkan data');
    }

    public function kode()
    {
        $idAnggota = getallheaders()['id'];
        $pinjaman = Pinjaman::where('lunas', 0)->where('id_anggota', $idAnggota)->orderBy('id', 'DESC')->first();

        $totalBayar = 0;

        if ($pinjaman) {
            $bayarAngsuran  = $pinjaman->nominal_pinjaman / $pinjaman->tenor;
            $tenorAngsuran  = $pinjaman->tenor - $pinjaman->angsuran_ke;
            $bunga          = $pinjaman->nominal_pinjaman * ($pinjaman->bunga / 100);
            $totalBayar     = $bayarAngsuran * $tenorAngsuran + $bunga;

            $resultBayar = Ratusan::edit_ratusan($totalBayar);
            $data['id'] = $pinjaman->id;
            $data['kode_pinjaman'] = $pinjaman->kode_pinjaman;
            $data['total_bayar'] = $resultBayar;

            return ResponseFormatter::success($data, 'Berhasil mendapatkan data');
        }

        return ResponseFormatter::error('Tidak ada pinjaman');
    }

    public function angsuran(Request $request)
    {
        $checkAngsuran = Angsuran::where('id_pinjaman', $request->id_pinjaman)->where('status', 0)->orderBy('id', 'DESC')->first();

        if ($checkAngsuran) {
            return ResponseFormatter::error('Masih terdapat angsuran yang belum disetujui');
        } else {
            #Update Pinjaman
            $pinjamanUpdate = Pinjaman::findOrFail($request->id_pinjaman);

            #Kode Angsuran
            $check = Angsuran::select('*')->orderBy('id', 'DESC')->first();
            if ($check == null) {
                $id = 1;
            } else {
                $id = $check->id + 1;
            }

            #Sisa Angsuran
            $sisaAngsuran = $pinjamanUpdate->total_pinjaman - ($pinjamanUpdate->nominal_angsuran * $pinjamanUpdate->angsuran_ke);

            if ($sisaAngsuran < 0) {
                $sisaAngsuran = 0;
            }

            $angsuran = new Angsuran();
            $angsuran->kode_angsuran    = 'ASN-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $angsuran->id_pinjaman      = $request->id_pinjaman;
            $angsuran->tanggal          = $request->tanggal;
            $angsuran->nominal_angsuran = $pinjamanUpdate->nominal_angsuran;
            $angsuran->sisa_angsuran    = $sisaAngsuran;
            $angsuran->sisa_bayar       = $pinjamanUpdate->tenor - $pinjamanUpdate->angsuran_ke - 1;
            $angsuran->status           = 0;
            $angsuran->lunas            = $pinjamanUpdate->lunas;
            $angsuran->keterangan       = '(Mobile)';
            $angsuran->save();

            $data = Angsuran::orderBy('id', 'DESC')->first();

            return ResponseFormatter::success($data, 'Berhasil menambah pengajuan angsuran');
        }
    }

    public function angsuran_lunas(Request $request)
    {
        $checkAngsuran = Angsuran::where('id_pinjaman', $request->id_pinjaman)->where('status', 0)->orderBy('id', 'DESC')->first();
        $checkPinjaman = Pinjaman::where('id', $request->id_pinjaman)->whereIn('status', [0, 1])->first();

        if (date('Y-m-d', strtotime($request->tanggal)) < date('Y-m-d')) {
            return ResponseFormatter::error('Tanggal sudah lewat');
        }

        if ($checkPinjaman) {
            return ResponseFormatter::error('Masih terdapat pinjaman yang belum disetujui atau dicairkan');
        }

        if ($checkAngsuran) {
            return ResponseFormatter::error('Masih terdapat angsuran yang belum disetujui');
        } else {
            $pinjamanUpdate = Pinjaman::findOrFail($request->id_pinjaman);

            #Kode Angsuran
            $check = Angsuran::select('*')->orderBy('id', 'DESC')->first();
            if ($check == null) {
                $id = 1;
            } else {
                $id = $check->id + 1;
            }

            $bayarAngsuran  = $pinjamanUpdate->nominal_pinjaman / $pinjamanUpdate->tenor;
            $tenorAngsuran  = $pinjamanUpdate->tenor - $pinjamanUpdate->angsuran_ke;
            $bunga          = $pinjamanUpdate->nominal_pinjaman * ($pinjamanUpdate->bunga / 100);
            $totalBayar     = $bayarAngsuran * $tenorAngsuran + $bunga;
            $potongan       = $bunga * $tenorAngsuran;

            $angsuran = new Angsuran();
            $angsuran->kode_angsuran    = 'ASN-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $angsuran->id_pinjaman      = $request->id_pinjaman;
            $angsuran->tanggal          = $request->tanggal;
            $angsuran->nominal_angsuran = $pinjamanUpdate->nominal_angsuran;
            $angsuran->sisa_angsuran    = 0;
            $angsuran->sisa_bayar       = $pinjamanUpdate->tenor - $pinjamanUpdate->angsuran_ke - 1;
            $angsuran->potongan         = str_replace(',', '', $potongan);
            $angsuran->status           = 0;
            $angsuran->lunas            = 0;
            $angsuran->jenis            = 2;
            $angsuran->total_bayar      = str_replace(',', '', $totalBayar);
            $angsuran->keterangan       = '(Mobile)';
            $angsuran->save();

            $data = Angsuran::orderBy('id', 'DESC')->first();

            #Pusher
            event(new PusherNotification(4, 'Notifikasi Baru'));

            #Save Notifikasi
            $notifikasi = new Notifikasi();

            $notifikasi->create([
                'id_anggota' => $data->pinjaman->id_anggota,
                'title'      => 'Pengajuan Pelunasan Baru!',
                'content'    => 'Pengajuan pelunasan pinjaman ' . $data->pinjaman->anggota->kd_anggota . ' untuk tanggal ' . $data->tanggal . ' sebesar Rp ' . number_format($data->total_bayar, 0, '', '.'),
                'type'       => 1
            ]);

            return ResponseFormatter::success($data, 'Berhasil menambah pengajuan pelunasan angsuran');
        }
    }

    public function angsuran_pinjaman()
    {
        $idAnggota = getallheaders()['id'];

        $pinjaman = Pinjaman::where('id_anggota', $idAnggota)->where('lunas', 0)->where('status', 2)->orderBy('id', 'DESC')->first();
        $angsuran = Angsuran::where('id_pinjaman', $pinjaman->id)->where('status', 1)->orderBy('id', 'DESC')->first();

        if ($pinjaman) {
            $data['nominal_pinjaman'] = $pinjaman->nominal_pinjaman;

            if ($angsuran) {
                $data['sisa_angsuran'] = $angsuran->sisa_angsuran;
            } else {
                $data['sisa_angsuran'] = $pinjaman->nominal_angsuran * $pinjaman->tenor;
            }

            return ResponseFormatter::success($data, 'Berhasil mendapatkan data');
        }

        return ResponseFormatter::error('Tidak terdapat pinjaman');
    }

    public function upload_transfer_angsuran(Request $request)
    {
        $angsuran = Angsuran::findOrFail($request->id);

        if ($angsuran->image != null) {
            Storage::delete('public/transfer/' . $angsuran->image);
        }

        $extension = $request->file('image')->extension();

        $imageName =  $angsuran->kode_angsuran . '.' . $extension;

        Storage::putFileAs('public/transfer', $request->file('image'), $imageName);

        $angsuran->image = $imageName;
        $angsuran->save();

        #Pusher
        event(new PusherNotification(4, 'Notifikasi Baru'));

        #Save Notifikasi
        $notifikasi = new Notifikasi();

        $notifikasi->create([
            'id_anggota' => $angsuran->pinjaman->id_anggota,
            'title'      => 'Upload Bukti Pelunasan Baru!',
            'content'    => 'Bukti transfer angsuran pelunasan pinjaman ' . $angsuran->kode_angsuran . '.',
            'type'       => 1
        ]);

        $data['image'] = asset('storage/transfer/' . $angsuran->image);

        return ResponseFormatter::success($data, 'Berhasil upload bukti transfer');
    }

    public function upload_info_angsuran()
    {
        $idAnggota = getallheaders()['id'];

        $pengaturan = Pengaturan::where('id', 6)->first();

        $pinjaman = Pinjaman::where('id_anggota', $idAnggota)->where('lunas', 0)->where('status', 2)->orderBy('id', 'DESC')->first();
        $angsuran = Angsuran::where('id_pinjaman', $pinjaman->id)->where('status', 0)->where('jenis', 2)->orderBy('id', 'DESC')->first();

        if ($angsuran) {
            $data['max_date'] = date('d-m-Y H:i', strtotime($angsuran->created_at . '+1 days'));
            $data['account_bank']   = $pengaturan->nama;
            $data['account_number'] = $pengaturan->angka;

            $data['id_upload'] = $angsuran->id;
            $data['nominal'] = $angsuran->total_bayar;

            return ResponseFormatter::success($data, 'Berhasil mendapatkan pelunasan pinjaman');
        }

        return ResponseFormatter::error('Tidak terdapat pelunasan pinjaman');
    }
}
