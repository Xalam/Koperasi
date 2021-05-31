<?php

namespace App\Models\Simpan_Pinjam\Master\Akun;

use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;

    protected $table = 'tb_akun';

    protected $fillable = [
        'kode_akun', 'nama_akun', 'saldo'
    ];

    public function jurnal()
    {
        return $this->hasMany(JurnalUmum::class, 'id_akun');
    }
}
