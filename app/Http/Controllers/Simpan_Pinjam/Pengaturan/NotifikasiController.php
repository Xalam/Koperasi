<?php

namespace App\Http\Controllers\Simpan_Pinjam\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Other\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $data = Notifikasi::where('type', 1)->orderBy('id', 'DESC')->get();

        return response()->json($data);
    }

    public function clear(Request $request)
    {
        $notifikasi = Notifikasi::findOrFail($request->id);
        $notifikasi->delete();

        $notif = Notifikasi::where('type', 1)->get();

        $count = 0;
        if ($notif) {
            $count = $notif->count();
        }

        $data = array(
            'count' => $count
        );

        return response()->json($data);
    }

    public function destroy()
    {
        Notifikasi::where('type', 1)->delete();

        $data = array(
            'count' => 0
        );

        return response()->json($data);
    }
}
