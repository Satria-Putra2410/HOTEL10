<?php

namespace App\Models;

use CodeIgniter\Model;

class KamarModel extends Model
{
    protected $table            = 'kamar';
    protected $primaryKey       = 'id_kamar';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'harga_kamar',
        'tipe_kamar',
        'jenis_ranjang',
        'jumlah_tamu',
        'foto',
        'deskripsi'
    ];

    // --- PERBAIKAN DI SINI ---
    // Kita kembali menggunakan properti, bukan method, untuk menghindari error.
    protected $validationRules = [
        'tipe_kamar'    => 'required|min_length[5]',
        'harga_kamar'   => 'required|numeric',
        'jenis_ranjang' => 'required',
        'jumlah_tamu'   => 'required|integer',
        'deskripsi'     => 'required',
        'foto'          => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
    ];

    protected $validationMessages = [
        'tipe_kamar' => [
            'required' => 'Tipe kamar wajib diisi.'
        ],
        'harga_kamar' => [
            'required' => 'Harga wajib diisi.',
            'numeric'  => 'Harga harus berupa angka.'
        ],
        'foto' => [
            'uploaded' => 'Anda harus memilih sebuah gambar untuk diunggah.',
            'max_size' => 'Ukuran gambar maksimal adalah 2MB.',
            'is_image' => 'File yang diunggah harus berupa gambar.',
            'mime_in'  => 'Format gambar harus JPG, JPEG, atau PNG.'
        ]
    ];
}