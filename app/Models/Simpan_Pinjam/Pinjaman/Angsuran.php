<?php

namespace App\Models\Simpan_Pinjam\Pinjaman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = 'tb_angsuran';

    protected $fillable = [
        'kode_angsuran', 'id_pinjaman', 'tanggal', 'nominal_angsuran',
        'sisa_angsuran', 'sisa_bayar', 'status', 'lunas'
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'id_pinjaman', 'id');
    }
}
