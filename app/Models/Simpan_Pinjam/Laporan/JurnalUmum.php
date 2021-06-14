<?php

namespace App\Models\Simpan_Pinjam\Laporan;

use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    use HasFactory;
    
    protected $table = 'tb_jurnal';

    protected $fillable = [
        'kode_jurnal', 'id_akun', 'tanggal', 'keterangan', 'debet', 'kredit'
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id');
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'kode_jurnal');
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'kode_jurnal');
    }
}
