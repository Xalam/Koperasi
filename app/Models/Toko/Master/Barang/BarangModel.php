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
        'hpp',
        'harga_jual',
        'minimal_grosir',
        'harga_grosir',
        'stok',
        'stok_minimal',
        'satuan',
        'foto',
        'expired',
        'alert_status'
    ];
}
