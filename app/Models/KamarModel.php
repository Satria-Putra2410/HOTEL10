<?php

namespace App\Models;

use CodeIgniter\Model;

class KamarModel extends Model
{
    protected $table        = 'kamar';
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
        'foto',
        'nomor_kamar'
    ];

    // Timestamps
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Aturan validasi untuk form (digunakan untuk create)
    protected $validationRules      = [
        'tipe_kamar'    => 'required|min_length[3]|max_length[100]',
        'harga_kamar'   => 'required|numeric|greater_than[0]',
        'jumlah_tamu'   => 'required|integer|greater_than[0]',
        'deskripsi'     => 'required',
        'foto'          => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,webp]',
        'nomor_kamar'   => 'required|alpha_numeric_punct|max_length[10]',
    ];
    
    // Pesan error kustom untuk validasi
    protected $validationMessages   = [
        'tipe_kamar' => [
            'required'   => 'Tipe kamar wajib diisi.',
            'min_length' => 'Tipe kamar minimal harus 3 karakter.',
            'max_length' => 'Tipe kamar maksimal 100 karakter.'
        ],
        'harga_kamar' => [
            'required'    => 'Harga kamar wajib diisi.',
            'numeric'     => 'Harga kamar hanya boleh berisi angka.',
            'greater_than' => 'Harga kamar harus lebih besar dari 0.'
        ],
        'jumlah_tamu' => [
            'required'    => 'Kapasitas tamu wajib diisi.',
            'integer'     => 'Kapasitas tamu harus berupa angka bulat.',
            'greater_than' => 'Kapasitas tamu harus lebih besar dari 0.'
        ],
        'deskripsi' => [
            'required' => 'Deskripsi wajib diisi.'
        ],
        'foto' => [
            'uploaded' => 'Anda wajib memilih sebuah foto kamar.',
            'max_size' => 'Ukuran foto maksimal adalah 2 MB.',
            'is_image' => 'File yang diupload harus berupa gambar.',
            'mime_in'  => 'Format foto harus JPG, JPEG, PNG, atau WEBP.'
        ],
        'nomor_kamar' => [
            'required'         => 'Nomor kamar wajib diisi.',
            'alpha_numeric_punct' => 'Nomor kamar hanya boleh berisi huruf, angka, dan tanda baca.',
            'max_length'       => 'Nomor kamar maksimal 10 karakter.',
        ]
    ];

    // Aturan validasi khusus untuk update (tanpa validasi 'foto' di sini)
    protected $updateValidationRules = [
        'tipe_kamar'    => 'required|min_length[3]|max_length[100]',
        'harga_kamar'   => 'required|numeric|greater_than[0]',
        'jumlah_tamu'   => 'required|integer|greater_than[0]',
        'deskripsi'     => 'required',
        'nomor_kamar'   => 'required|alpha_numeric_punct|max_length[10]',
    ];

    // Metode untuk mendapatkan aturan validasi untuk update
    public function getUpdateValidationRules(): array
    {
        return $this->updateValidationRules;
    }

    // Metode untuk mendapatkan pesan validasi untuk update (jika berbeda dari create)
    // Dalam kasus ini, pesan untuk foto tidak relevan, jadi kita bisa mengembalikan pesan default
    public function getUpdateValidationMessages(): array
    {
        $messages = $this->validationMessages;
        unset($messages['foto']); // Hapus pesan error terkait foto untuk update
        return $messages;
    }

    protected $skipValidation     = false;
}
