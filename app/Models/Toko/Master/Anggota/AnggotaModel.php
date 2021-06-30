<?php

namespace App\Models\Toko\Master\Anggota;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaModel extends Model
{
    use HasFactory;

    protected $table = 'tb_anggota';

    protected $fillable = [
        'kd_anggota',
        'nama_anggota',
        'jenis_kelamin',
        'agama',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'no_wa',
        'foto',
        'status',
        'jabatan',
        'email',
        'username',
        'password',
        'role',
        'gaji',
        'limit_gaji'
    ];
}
