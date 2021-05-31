<?php

namespace App\Models\Simpan_Pinjam\Laporan;

use App\Models\Simpan_Pinjam\Master\Akun\Akun;
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
}
