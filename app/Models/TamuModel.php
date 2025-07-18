<?php

namespace App\Models;

use CodeIgniter\Model;

class TamuModel extends Model
{
    protected $table            = 'tamu';
    protected $primaryKey       = 'id_tamu';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nama_tamu', 'no_hp_tamu', 'email', 'password'];

    protected $useTimestamps = false;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Aturan validasi
    protected $validationRules = [
        // --- TAMBAHKAN BARIS INI ---
        'id_tamu'    => 'permit_empty|is_natural_no_zero', 
        // -------------------------

        'nama_tamu'  => 'required|min_length[3]|max_length[100]',
        'no_hp_tamu' => 'required|min_length[10]|max_length[20]',
        'email'      => 'required|valid_email|is_unique[tamu.email,id_tamu,{id_tamu}]',
        'password'   => 'required|min_length[6]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}
