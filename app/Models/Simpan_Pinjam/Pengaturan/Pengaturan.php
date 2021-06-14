<?php

namespace App\Models\Simpan_Pinjam\Pengaturan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'tb_pengaturan';

    protected $fillable = [
        'nama', 'angka'
    ];
}
