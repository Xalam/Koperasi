<?php

namespace App\Models\Toko\Master\Anggota;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaModel extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'kode',
        'nama',
        'jabatan',
        'alamat',
        'gaji',
        'limit_belanja',
        'telepon',
        'wa',
        'status'
    ];
}
