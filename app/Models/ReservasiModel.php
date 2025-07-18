<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservasiModel extends Model
{
    protected $table            = 'reservasi';
    protected $primaryKey       = 'id_reservasi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    /**
     * Kolom-kolom yang diizinkan untuk diisi (fillable fields).
     * Ini mencakup semua kolom yang relevan untuk proses reservasi dan update status.
     */
    protected $allowedFields    = [
        'tgl_masuk',
        'tgl_keluar',
        'total_harga_reservasi',
        'id_tamu',
        'status' // Kolom 'status' sangat penting untuk perubahan status reservasi.
    ];

    // Timestamps (saat ini tidak digunakan, sesuai konfigurasi Anda)
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Aturan validasi (saat ini kosong, Anda bisa menambahkannya jika diperlukan)
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    /**
     * Fungsi kustom untuk mengambil reservasi aktif yang belum masuk ke riwayat.
     * Fungsi ini dipertahankan sesuai dengan struktur yang Anda berikan.
     */
    public function getActiveReservations()
    {
        return $this->db->table('reservasi r')
            ->select('r.id_reservasi, r.tgl_masuk, r.tgl_keluar, t.nama_tamu, k.tipe_kamar, p.id_pembayaran, r.status') // Menambahkan r.status
            ->join('tamu t', 't.id_tamu = r.id_tamu', 'left')
            ->join('detail_reservasi_kamar drk', 'drk.id_reservasi = r.id_reservasi', 'left')
            ->join('kamar k', 'k.id_kamar = drk.id_kamar', 'left')
            ->join('pembayaran p', 'p.id_reservasi = r.id_reservasi', 'left')
            ->join('riwayat rw', 'rw.id_reservasi = r.id_reservasi', 'left') // Join ke tabel riwayat
            ->where('rw.id_riwayat IS NULL') // Hanya ambil yang BELUM ada di riwayat
            ->orderBy('r.tgl_masuk', 'ASC')
            ->get()
            ->getResultArray();
    }
}
