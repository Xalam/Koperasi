<?php

namespace App\Models\Toko\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranModel extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'nama'
    ];
}
