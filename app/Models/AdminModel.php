<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admin';
    protected $primaryKey       = 'id_admin';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // PERUBAHAN: Disesuaikan dengan kolom di database baru
    protected $allowedFields    = [
        'nama_admin',
        'no_hp_admin',
        'email',
        'password'
    ];
}
