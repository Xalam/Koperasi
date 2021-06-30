<?php

namespace App\Models\Toko\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranModel extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'nama'
    ];
}
