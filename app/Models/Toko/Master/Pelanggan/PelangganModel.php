<?php

namespace App\Models\Master\Pelanggan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelangganModel extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'telepon'
    ];
}
