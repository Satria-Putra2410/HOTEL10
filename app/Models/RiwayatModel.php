<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatModel extends Model
{
    // Nama tabel yang akan digunakan oleh model ini
    protected $table = 'riwayat';
    // Kunci utama tabel
    protected $primaryKey = 'id_riwayat';
    // Mengizinkan auto-increment untuk kunci utama
    protected $useAutoIncrement = true;
    // Tipe data yang akan dikembalikan oleh find() atau findAll()
    protected $returnType     = 'array';
    // Mengizinkan penggunaan soft deletes (tidak menghapus data secara fisik)
    protected $useSoftDeletes = false;

    // Kolom-kolom yang dapat diisi (fillable)
    protected $allowedFields = [
        'nama_tamu',
        'tipe_kamar',
        'tgl_masuk',
        'tgl_keluar',
        'total_bayar',
        'tgl_penyelesaian',
        'status_saat_selesai'
    ];

    // Menggunakan timestamps untuk created_at dan updated_at
    protected $useTimestamps = false; // Karena tabel riwayat tidak memiliki kolom created_at/updated_at

    // Aturan validasi untuk setiap kolom (opsional, bisa ditambahkan sesuai kebutuhan)
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
