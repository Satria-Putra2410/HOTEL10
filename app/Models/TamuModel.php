<?php

namespace App\Models;

use CodeIgniter\Model;

class TamuModel extends Model
{
    protected $table            = 'tamu';
    protected $primaryKey       = 'id_tamu';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // PERUBAHAN: Disesuaikan dengan kolom di database baru
    protected $allowedFields    = [
        'nama_tamu',
        'no_hp_tamu',
        'email',
        'password'
    ];
}
