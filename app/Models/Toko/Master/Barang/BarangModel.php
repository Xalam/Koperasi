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
        'id_supplier',
        'hpp',
        'harga_jual',
        'minimal_grosir',
        'harga_grosir',
        'stok_minimal',
        'stok_etalase',
        'stok_gudang',
        'satuan',
        'foto',
        'tanggal_beli',
        'expired_bulan',
        'expired_tahun',
        'alert_status'
    ];
}
