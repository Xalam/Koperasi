<?php

namespace App\Models\Simpan_Pinjam\Other;

use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'tb_notifikasi';

    protected $fillable = ['id_anggota', 'title', 'content', 'status', 'type'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id');
    }
}
