<?php

namespace App\Models\Simpan_Pinjam\Simpanan;

use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    protected $table = 'tb_simpanan';

    protected $fillable = [
        'kode_simpanan', 'id_anggota', 'tanggal', 'jenis_simpanan', 'nominal', 'image', 'keterangan',
        'status', 'type', 'kode_jurnal'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id');
    }

    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'id_anggota', 'id');
    }
}
