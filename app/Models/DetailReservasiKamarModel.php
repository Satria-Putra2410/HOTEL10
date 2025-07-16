<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailReservasiKamarModel extends Model
{
    protected $table            = 'detail_reservasi_kamar';
    protected $primaryKey       = 'id_detail_reservasi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['jumlah_malam_kamar', 'id_reservasi', 'id_kamar'];
}