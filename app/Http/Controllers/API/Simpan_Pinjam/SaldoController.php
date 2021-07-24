<?php

namespace App\Http\Controllers\API\Simpan_Pinjam;

use App\Events\PusherNotification;
use App\Http\Controllers\API\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Other\Notifikasi;
use App\Models\Simpan_Pinjam\Simpanan\Saldo;
use App\Models\Simpan_Pinjam\Simpanan\SaldoTarik;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    public function index()
    {
        $idAnggota = getallheaders()['id'];

        $data['saldo'] = Saldo::where('id_anggota', $idAnggota)->orderBy('jenis_simpanan', 'DESC')->get();

        $data['saldo_masuk'] = Simpanan::where('id_anggota', $idAnggota)->orderBy('tanggal', 'DESC')->orderBy('id', 'DESC')->get();

        $data['saldo_keluar'] = SaldoTarik::with('saldo')
            ->whereHas('saldo', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })->orderBy('tanggal', 'DESC')->orderBy('id', 'DESC')->get();

        return ResponseFormatter::success($data, 'Berhasil mendapatkan saldo');
    }

    public function deposit(Request $request)
    {
        $checkSimpanan = Simpanan::where('id_anggota', getallheaders()['id'])
            ->where('status', 0)->orderBy('id', 'DESC')->first();

        if ($checkSimpanan) {
            return ResponseFormatter::error('Masih terdapat pengajuan yang belum disetujui');
        } else {
            $check = Simpanan::select('id')->orderBy('id', 'DESC')->first();
            if ($check == null) {
                $id = 1;
            } else {
                $id = $check->id + 1;
            }

            $item = $request->all();

            $item['kode_simpanan'] = 'SMP-' . str_replace('-', '', $request->tanggal) . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $item['id_anggota'] = getallheaders()['id'];
            $item['tanggal'] = $request->tanggal;
            $item['jenis_simpanan'] = 3;
            $item['nominal'] = $request->nominal;
            $item['status'] = 0;
            $item['keterangan'] = '(Mobile)';

            Simpanan::create($item);

            $data = Simpanan::orderBy('id', 'DESC')->first();

            #Pusher
            event(new PusherNotification(1, 'Notifikasi Baru'));

            #Save Notifikasi
            $notifikasi = new Notifikasi();

            $notifikasi->create([
                'id_anggota' => $data->id_anggota,
                'title'      => 'Pengajuan Simpanan Baru!',
                'content'    => 'Pengajuan simpanan ' . $data->anggota->kd_anggota . ' untuk tanggal ' . $data->tanggal . ' sebesar Rp ' . number_format($data->total_bayar, 0, '', '.'),
                'type'       => 1
            ]);

            return ResponseFormatter::success($data, 'Berhasil mengajukan simpanan');
        }
    }

    public function withdraw(Request $request)
    {
        $idAnggota = getallheaders()['id'];

        $checkSaldo = SaldoTarik::with('saldo')
            ->whereHas('saldo', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
                $query->where('jenis_simpanan', 3);
            })->where('status', 0)->first();

        if ($checkSaldo) {
            return ResponseFormatter::error('Masih terdapat penarikan yang belum disetujui');
        } else {
            $saldo = Saldo::where('id_anggota', $idAnggota)->where('jenis_simpanan', 3)->first();

            if ($saldo->saldo < $request->nominal) {
                return ResponseFormatter::error('Jumlah saldo kurang');
            } else {
                $item = $request->all();

                $item['id_saldo'] = $saldo->id;
                $item['status'] = 0;

                SaldoTarik::create($item);

                $data = SaldoTarik::orderBy('id', 'DESC')->first();

                #Pusher
                event(new PusherNotification(2, 'Notifikasi Baru'));

                #Save Notifikasi
                $notifikasi = new Notifikasi();

                $notifikasi->create([
                    'id_anggota' => $idAnggota,
                    'title'      => 'Pengajuan Penarikan Baru!',
                    'content'    => 'Pengajuan penarikan simpanan ' . $data->saldo->anggota->kd_anggota . ' untuk tanggal ' . date('d-m-Y', strtotime($data->tanggal)) . ' sebesar Rp ' . number_format($data->nominal, 0, '', '.'),
                    'type'       => 1
                ]);

                return ResponseFormatter::success($data, 'Berhasil mengajukan penarikan');
            }
        }
    }
}
