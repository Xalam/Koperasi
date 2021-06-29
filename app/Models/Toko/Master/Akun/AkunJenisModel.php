<?php

namespace App\Models\Toko\Master\Akun;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunJenisModel extends Model
{
    use HasFactory;

    protected $table = 'jenis_akun';

    protected $fillable = [
        'nama'
    ];
}
