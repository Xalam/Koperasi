<?php

namespace App\Models\Simpan_Pinjam\Pengaturan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembagianSHU extends Model
{
    use HasFactory;

    protected $table = 'tb_pembagian_shu';

    protected $fillable = [
        'nama', 'angka'
    ];
}
