<?php

namespace App\Models\Toko\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasukanModel extends Model
{
    use HasFactory;

    protected $table = 'pemasukan';

    protected $fillable = [
        'nama'
    ];
}
