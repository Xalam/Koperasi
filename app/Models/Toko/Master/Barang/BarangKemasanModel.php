<?php

namespace App\Models\Toko\Master\Barang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKemasanModel extends Model
{
    use HasFactory;

    protected $table = 'kemasan_barang';

    protected $fillable = [
        'nama'
    ];
}
