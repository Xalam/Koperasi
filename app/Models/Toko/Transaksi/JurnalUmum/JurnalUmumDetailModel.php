<?php

namespace App\Models\Toko\Transaksi\JurnalUmum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmumDetailModel extends Model
{
    use HasFactory;

    protected $table = 'detail_jurnal_umum';

    protected $fillable = [
        'nomor',
        'id_akun',
        'debit', 
        'kredit',
        'submited'
    ];
}
