<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservasiModel extends Model
{
    protected $table            = 'reservasi';
    protected $primaryKey       = 'id_reservasi';
    protected $allowedFields    = [
        'tgl_masuk',
        'tgl_keluar',
        'total_harga_reservasi',
        'id_tamu'
    ];

    /**
     * Mengambil semua reservasi aktif dengan detailnya.
     * PERUBAHAN: Fungsi ini sekarang tidak akan mengambil reservasi yang sudah ada di tabel riwayat.
     */
    public function getActiveReservations()
    {
        return $this->db->table('reservasi r')
            ->select('r.id_reservasi, r.tgl_masuk, r.tgl_keluar, t.nama_tamu, k.tipe_kamar, p.id_pembayaran')
            ->join('tamu t', 't.id_tamu = r.id_tamu')
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