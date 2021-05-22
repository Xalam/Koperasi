<?php

namespace App\Models\Simpan_Pinjam\Simpanan;

use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    protected $table = 'tb_saldo';

    protected $fillable = [
        'id_anggota', 'saldo'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id');
    }

    public function masuk() {
        return $this->hasMany(Simpanan::class, 'id_anggota');
    }
}
