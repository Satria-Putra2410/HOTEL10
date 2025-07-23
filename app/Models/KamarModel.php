<?php

namespace App\Models;

use CodeIgniter\Model;

class KamarModel extends Model
{
    /**
     * Nama tabel yang digunakan oleh model ini.
     * @var string
     */
    protected $table            = 'kamar';

    /**
     * Primary key dari tabel.
     * @var string
     */
    protected $primaryKey       = 'id_kamar';

    /**
     * Mengaktifkan auto-increment untuk primary key.
     * @var bool
     */
    protected $useAutoIncrement = true;

    /**
     * Tipe data yang dikembalikan oleh query.
     * @var string
     */
    protected $returnType       = 'array';

    /**
     * Menggunakan soft deletes untuk menandai data sebagai terhapus tanpa benar-benar menghapusnya.
     * @var bool
     */
    protected $useSoftDeletes   = false;

    /**
     * Kolom yang diizinkan untuk diisi melalui operasi create atau update.
     * Ini melindungi dari mass assignment vulnerabilities.
     * @var array
     */
    protected $allowedFields    = [
        'tipe_kamar',
        'harga_kamar',
        'jenis_ranjang',
        'jumlah_tamu',
        'deskripsi',
        'foto',
        'nomor_kamar' // Kolom baru ditambahkan di sini
    ];

    /**
     * Apakah akan menggunakan timestamps (created_at, updated_at).
     * @var bool
     */
    protected $useTimestamps = false;

    // Aturan validasi yang berlaku saat membuat data baru.
    protected $validationRules    = [
        'tipe_kamar'  => 'required|min_length[3]|max_length[100]',
        'harga_kamar' => 'required|numeric|greater_than[0]',
        'jumlah_tamu' => 'required|integer|greater_than[0]',
        'deskripsi'   => 'required',
        'foto'        => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,webp]',
        'nomor_kamar' => 'required|max_length[20]', // Disesuaikan dengan VARCHAR(20) di DB
    ];

    // Aturan validasi yang berlaku saat memperbarui data.
    // Validasi 'foto' tidak wajib saat update.
    protected $updateValidationRules = [
        'tipe_kamar'  => 'required|min_length[3]|max_length[100]',
        'harga_kamar' => 'required|numeric|greater_than[0]',
        'jumlah_tamu' => 'required|integer|greater_than[0]',
        'deskripsi'   => 'required',
        'nomor_kamar' => 'required|max_length[20]',
    ];
    
    // Pesan error kustom untuk setiap aturan validasi.
    protected $validationMessages = [
        'tipe_kamar' => [
            'required'   => 'Tipe kamar wajib diisi.',
            'min_length' => 'Tipe kamar minimal harus 3 karakter.',
            'max_length' => 'Tipe kamar maksimal 100 karakter.'
        ],
        'harga_kamar' => [
            'required'     => 'Harga kamar wajib diisi.',
            'numeric'      => 'Harga kamar hanya boleh berisi angka.',
            'greater_than' => 'Harga kamar harus lebih besar dari 0.'
        ],
        'jumlah_tamu' => [
            'required'     => 'Kapasitas tamu wajib diisi.',
            'integer'      => 'Kapasitas tamu harus berupa angka bulat.',
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
            'required'   => 'Nomor kamar wajib diisi.',
            'max_length' => 'Nomor kamar maksimal 20 karakter.',
        ]
    ];

    /**
     * Apakah validasi harus dilewati.
     * @var bool
     */
    protected $skipValidation     = false;

    /**
     * Metode untuk mendapatkan aturan validasi untuk operasi update.
     * @return array
     */
    public function getUpdateValidationRules(): array
    {
        return $this->updateValidationRules;
    }

    /**
     * Metode untuk mendapatkan pesan validasi untuk operasi update.
     * @return array
     */
    public function getUpdateValidationMessages(): array
    {
        $messages = $this->validationMessages;
        unset($messages['foto']); // Hapus pesan error terkait foto untuk update
        return $messages;
    }
}