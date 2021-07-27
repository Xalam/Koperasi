<?php

namespace App\Models\Toko\Master\Akun;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewAkunModel extends Model
{
    use HasFactory;

    protected $table = 'view_akun';

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'saldo'
    ];
}
