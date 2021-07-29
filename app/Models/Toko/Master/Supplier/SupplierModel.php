<?php

namespace App\Models\Toko\Master\Supplier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'telepon',
        'wa',
        'jarak',
        'lama_kirim',
        'email',
        'keterangan',
        'tempo'
    ];
}
