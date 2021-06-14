<?php

namespace App\Models\Simpan_Pinjam\Pinjaman;

use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'tb_pinjaman';

    protected $fillable = [
        'kode_pinjaman', 'id_anggota', 'tanggal', 'nominal_pinjaman', 'bunga',
        'tenor', 'total_pinjaman', 'nominal_angsuran', 'angsuran_ke', 'biaya_provisi', 
        'biaya_asuransi', 'biaya_admin', 'lunas', 'status', 'id_jurnal'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id');
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'id_pinjaman');
    }

    public function jurnal()
    {
        return $this->belongsTo(JurnalUmum::class, 'kode_jurnal', 'kode_jurnal');
    }
}
