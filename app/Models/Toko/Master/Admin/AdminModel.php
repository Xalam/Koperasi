<?php

namespace App\Models\Toko\Master\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    use HasFactory;

    protected $table = 'admin';

    protected $fillable = [
        'kode',
        'nama',
        'password',
        'jabatan'
    ];
}
