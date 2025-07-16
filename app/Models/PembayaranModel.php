<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id_pembayaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'tgl_pembayaran',
        'total_pembayaran',
        'metode_pembayaran',
        'status_pembayaran',
        'id_reservasi'
    ];
}
