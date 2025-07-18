<?php

namespace App\Models;

use CodeIgniter\Model;

class KamarModel extends Model
{
    protected $table      = 'kamar';
    protected $primaryKey = 'id_kamar';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // Pastikan semua nama kolom dari form Anda ada di sini.
    protected $allowedFields    = [
        'tipe_kamar',
        'harga_kamar',
        'jenis_ranjang',
        'jumlah_tamu',
        'deskripsi',
        'foto'
    ];

    // Timestamps
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Aturan validasi untuk form
    protected $validationRules      = [
        'tipe_kamar'  => 'required|min_length[3]|max_length[100]',
        'harga_kamar' => 'required|numeric|greater_than[0]',
        'jumlah_tamu' => 'required|integer|greater_than[0]',
        'deskripsi'   => 'required',
        'foto'        => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,webp]',
    ];
    
    // Pesan error kustom untuk validasi
    protected $validationMessages   = [
        'tipe_kamar' => [
            'required' => 'Tipe kamar wajib diisi.',
            'min_length' => 'Tipe kamar minimal harus 3 karakter.'
        ],
        'harga_kamar' => [
            'required' => 'Harga kamar wajib diisi.',
            'numeric' => 'Harga kamar hanya boleh berisi angka.'
        ],
        'jumlah_tamu' => [
            'required' => 'Kapasitas tamu wajib diisi.',
            'integer' => 'Kapasitas tamu harus berupa angka bulat.'
        ],
        'deskripsi' => [
            'required' => 'Deskripsi wajib diisi.'
        ],
        'foto' => [
            'uploaded' => 'Anda wajib memilih sebuah foto kamar.',
            'max_size' => 'Ukuran foto maksimal adalah 2 MB.',
            'is_image' => 'File yang diupload harus berupa gambar.',
            'mime_in'  => 'Format foto harus JPG, JPEG, PNG, atau WEBP.'
        ]
    ];
    protected $skipValidation     = false;
}
