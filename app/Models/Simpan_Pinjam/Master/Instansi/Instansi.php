<?php

namespace App\Models\Simpan_Pinjam\Master\Instansi;

use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'tb_instansi';

    protected $fillable = [
        'kode_instansi', 'nama_instansi'
    ];

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_instansi');
    }
}
