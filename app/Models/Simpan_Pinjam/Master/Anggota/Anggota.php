<?php

namespace App\Models\Simpan_Pinjam\Master\Anggota;

use App\Models\Simpan_Pinjam\Simpanan\Saldo;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'tb_anggota';

    protected $fillable = [
        'kd_anggota', 'nama_anggota', 'jenis_kelamin', 'agama', 'tempat_lahir',
        'tanggal_lahir', 'alamat', 'no_hp', 'no_wa', 'foto', 'status', 'jabatan', 
        'email', 'username', 'password', 'role'
    ];

    public function simpanan() 
    {
        return $this->hasMany(Simpanan::class, 'id_anggota');
    }

    public function saldo()
    {
        return $this->hasOne(Saldo::class, 'id_anggota');
    }
}
