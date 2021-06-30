<?php

namespace App\Models\Toko\Master\Akun;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunModel extends Model
{
    use HasFactory;

    protected $table = 'akun';

    protected $fillable = [
        'kode',
        'nama',
        'debit', 
        'kredit'
    ];
}
