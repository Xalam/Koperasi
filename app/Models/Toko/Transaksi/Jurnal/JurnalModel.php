<?php

namespace App\Models\Toko\Transaksi\Jurnal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalModel extends Model
{
    use HasFactory;

    protected $table = 'jurnal';

    protected $fillable = [
        'nomor',
        'tanggal',
        'keterangan',
        'id_akun',
        'debit', 
        'kredit'
    ];
}
