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

    // Aturan validasi
    protected $validationRules = [
        'nama_tamu'  => 'required|min_length[3]|max_length[100]',
        'no_hp_tamu' => 'required|min_length[10]|max_length[20]',
        'email'      => 'required|valid_email|is_unique[tamu.email,id_tamu,{id_tamu}]',
        'password'   => 'required', // Validasi hanya untuk memastikan password tidak kosong
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    // PERBAIKAN: Callback untuk hashing otomatis telah dihapus
    // agar hanya MD5 dari AuthController yang digunakan.
    protected $beforeInsert = [];
    protected $beforeUpdate = [];

    // PERBAIKAN: Fungsi hashPassword() juga telah dihapus.
}
