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

    protected $allowedFields    = [
        'tgl_masuk',
        'tgl_keluar',
        'total_harga_reservasi',
        'id_tamu',
        'status'
    ];

    protected $useTimestamps = false;

    public function getActiveReservations()
    {
        return $this->db->table('reservasi r')
            ->select('r.id_reservasi, r.tgl_masuk, r.tgl_keluar, t.nama_tamu, k.tipe_kamar, p.id_pembayaran, r.status')
            ->join('tamu t', 't.id_tamu = r.id_tamu', 'left')
            ->join('detail_reservasi_kamar drk', 'drk.id_reservasi = r.id_reservasi', 'left')
            ->join('kamar k', 'k.id_kamar = drk.id_kamar', 'left')
            ->join('pembayaran p', 'p.id_reservasi = r.id_reservasi', 'left')
            ->join('riwayat rw', 'rw.id_riwayat IS NULL', 'left')
            ->where('rw.id_riwayat IS NULL')
            ->orderBy('r.tgl_masuk', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * ====================================================================
     * FUNGSI UNTUK RIWAYAT TAMU (DENGAN KUERI YANG SUDAH DIPERBAIKI)
     * ====================================================================
     */
    public function getRiwayatByTamu(int $id_tamu)
    {
        // PERBAIKAN:
        // 1. Mengambil 'unit_kamar.nomor_kamar' bukan 'kamar.nomor_kamar'.
        // 2. Memperbaiki logika JOIN: reservasi -> detail -> unit_kamar -> kamar.
        // 3. Mengambil 'kamar.jumlah_tamu' bukan 'reservasi.jumlah_tamu'.
        return $this->select('
                reservasi.id_reservasi, 
                reservasi.tgl_masuk AS tgl_checkin, 
                reservasi.tgl_keluar AS tgl_checkout, 
                reservasi.total_harga_reservasi AS total_harga, 
                reservasi.status, 
                kamar.tipe_kamar AS nama_kamar, 
                unit_kamar.nomor_kamar, 
                kamar.jumlah_tamu 
            ')
            ->join('detail_reservasi_kamar', 'detail_reservasi_kamar.id_reservasi = reservasi.id_reservasi', 'left')
            ->join('unit_kamar', 'unit_kamar.id_unit_kamar = detail_reservasi_kamar.id_kamar', 'left')
            ->join('kamar', 'kamar.id_kamar = unit_kamar.id_kamar', 'left')
            ->where('reservasi.id_tamu', $id_tamu)
            ->orderBy('reservasi.tgl_masuk', 'DESC')
            ->findAll();
    }
}
