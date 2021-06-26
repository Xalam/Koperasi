<?php

namespace App\Models\Simpan_Pinjam\Simpanan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoTarik extends Model
{
    use HasFactory;

    protected $table = 'tb_saldo_tarik';

    protected $fillable = [
        'id_saldo', 'tanggal', 'nominal', 'status', 'kode_jurnal'
    ];

    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'id_saldo', 'id');
    }
}
