<?php

namespace App\Http\Controllers\API\Simpan_Pinjam;

use App\Http\Controllers\API\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Other\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $data = Notifikasi::where('id_anggota', getallheaders()['id'])->where('type', 0)->orderBy('created_at', 'DESC')->get();

        if ($data->count() > 0) {
            return ResponseFormatter::success($data, 'Berhasil mendapatkan notifikasi');
        }

        return ResponseFormatter::error('Belum ada notifikasi');
    }

    public function detail($id)
    {
        $data = Notifikasi::find($id);

        if ($data) {
            $data->update([
                'status' => 1
            ]);

            return ResponseFormatter::success($data, 'Detail notifikasi');
        }

        return ResponseFormatter::error('Notifikasi tidak ada');
    }

    public function delete()
    {
        $data = Notifikasi::where('id_anggota', getallheaders()['id'])->where('type', 0);

        if ($data) {
            $data->delete();

            $data = Notifikasi::where('id_anggota', getallheaders()['id'])->orderBy('updated_at', 'DESC')->get();

            return ResponseFormatter::success($data, 'Notifikasi berhasil dihapus');
        }

        return ResponseFormatter::error('Notifikasi tidak ada');
    }

    public function unread()
    {
        $data = Notifikasi::where('id_anggota', getallheaders()['id'])->orderBy('updated_at', 'DESC')->where('status', 0)->where('type', 0)->get();

        if ($data->count() > 0) {
            return ResponseFormatter::success($data, 'Terdapat notifikasi yang belum dibaca');
        }

        return ResponseFormatter::error('Notifikasi terbaca');
    }
}
