<?php

namespace App\Http\Controllers;

use App\Models\Simpan_Pinjam\Other\Notifikasi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $notifikasi_items = Notifikasi::where('type', 1)->orderBy('id', 'DESC')->get();

        $notifikasi_count = $notifikasi_items->count();

        view()->share(['notifikasi_count' => $notifikasi_count, 'notifikasi_items' => $notifikasi_items]);
    }
}
