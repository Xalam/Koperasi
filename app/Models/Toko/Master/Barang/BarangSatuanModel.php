<?php

namespace App\Models\Toko\Master\Barang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangSatuanModel extends Model
{
    use HasFactory;

    protected $table = 'satuan_barang';

    protected $fillable = [
        'nama'
    ];
}
