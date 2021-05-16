<?php

namespace App\Models\Toko\Master\Barang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode', 
        'nama', 
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan'
    ];
}
