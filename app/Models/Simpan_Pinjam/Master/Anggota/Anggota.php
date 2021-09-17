<?php

namespace App\Models\Simpan_Pinjam\Master\Anggota;

use App\Models\Simpan_Pinjam\Master\Instansi\Instansi;
use App\Models\Simpan_Pinjam\Other\Notifikasi;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
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
        'email', 'username', 'password', 'role', 'gaji', 'limit_gaji', 'id_instansi'
    ];

    public function simpanan()
    {
        return $this->hasMany(Simpanan::class, 'id_anggota');
    }

    public function saldo()
    {
        return $this->hasOne(Saldo::class, 'id_anggota');
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'id_anggota');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_anggota');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'id_instansi', 'id');
    }
}
