<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitKamarModel extends Model
{
    protected $table      = 'unit_kamar';
    protected $primaryKey = 'id_unit_kamar';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_kamar', 'nomor_kamar']; // id_kamar disini adalah FK ke tabel kamar (tipe)

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
