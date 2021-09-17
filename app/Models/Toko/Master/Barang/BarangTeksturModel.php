<?php

namespace App\Models\Toko\Master\Barang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTeksturModel extends Model
{
    use HasFactory;

    protected $table = 'tekstur_barang';

    protected $fillable = [
        'nama'
    ];
}
