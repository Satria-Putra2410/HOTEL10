<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user'; // Nama tabel
    protected $primaryKey = 'id'; // Kolom primary key
    protected $allowedFields = ['id', 'email', 'password', 'nama', 'role']; // Kolom yang diizinkan
}
