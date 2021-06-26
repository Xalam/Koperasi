<?php

namespace App\Models\Simpan_Pinjam\Master\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = ['name', 'username', 'password', 'role'];
}
