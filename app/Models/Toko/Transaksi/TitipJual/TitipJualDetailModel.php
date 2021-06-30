<?php

namespace App\Models\Toko\Transaksi\TitipJual;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitipJualDetailModel extends Model
{
    use HasFactory;
    
    protected $table = 'detail_titip_jual';

    protected $fillable = [
        'nomor',
        'id_barang',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'submited'
    ];
}
