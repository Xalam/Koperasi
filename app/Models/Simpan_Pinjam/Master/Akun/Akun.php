<?php

namespace App\Models\Simpan_Pinjam\Master\Akun;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;

    protected $table = 'tb_akun';

    protected $fillable = [
        'kode_akun', 'nama_akun', 'saldo'
    ];
}
