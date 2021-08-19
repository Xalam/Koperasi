<?php

namespace App\Models\Toko\Transaksi\JurnalUmum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmumModel extends Model
{
    use HasFactory;

    protected $table = 'jurnal_umum';

    protected $fillable = [
        'nomor',
        'id_akun',
        'tanggal',
        'keterangan',
        'debit',
        'kredit'
    ];
}
