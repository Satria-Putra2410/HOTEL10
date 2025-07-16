<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReservasiModel;
use App\Models\RiwayatModel;

class AdminController extends BaseController
{
    /**
     * Menampilkan dashboard dengan reservasi yang statusnya 'Reserved'.
     */
    public function index()
    {
        $reservasiModel = new ReservasiModel();
        $data['reservations'] = $reservasiModel
            ->join('tamu', 'tamu.id_tamu = reservasi.id_tamu', 'left')
            ->join('detail_reservasi_kamar', 'detail_reservasi_kamar.id_reservasi = reservasi.id_reservasi', 'left')
            ->join('kamar', 'kamar.id_kamar = detail_reservasi_kamar.id_kamar', 'left')
            ->where('reservasi.status', 'Reserved')
            ->select('reservasi.*, tamu.nama_tamu, kamar.tipe_kamar')
            ->findAll();
        $data['title'] = 'Dashboard Admin';
        
        // PERBAIKAN: Mengubah path view sesuai struktur folder Anda
        return view('admin_dashboard', $data);
    }
    
    /**
     * Menampilkan halaman untuk tamu yang sudah Check-In.
     */
    public function checkinPage()
    {
        $reservasiModel = new ReservasiModel();
        $data['reservations'] = $reservasiModel
            ->join('tamu', 'tamu.id_tamu = reservasi.id_tamu', 'left')
            ->join('detail_reservasi_kamar', 'detail_reservasi_kamar.id_reservasi = reservasi.id_reservasi', 'left')
            ->join('kamar', 'kamar.id_kamar = detail_reservasi_kamar.id_kamar', 'left')
            ->where('reservasi.status', 'Check-In')
            ->select('reservasi.*, tamu.nama_tamu, kamar.tipe_kamar')
            ->findAll();
        $data['title'] = 'Manajemen Check-In';
        
        // PERBAIKAN: Mengubah path view sesuai struktur folder Anda
        return view('checkin', $data); 
    }

    /**
     * Memproses aksi Check-In (mengubah status menjadi 'Check-In').
     */
    public function checkIn($id_reservasi)
    {
        $reservasiModel = new ReservasiModel();
        $reservasiModel->update($id_reservasi, ['status' => 'Check-In']);
        return redirect()->to('/admin/dashboard')->with('success', 'Tamu berhasil Check-In.');
    }

    /**
     * Menampilkan halaman riwayat.
     */
    public function history()
    {
        $reservasiModel = new ReservasiModel();
        $data['reservations'] = $reservasiModel
            ->join('tamu', 'tamu.id_tamu = reservasi.id_tamu', 'left')
            ->join('detail_reservasi_kamar', 'detail_reservasi_kamar.id_reservasi = reservasi.id_reservasi', 'left')
            ->join('kamar', 'kamar.id_kamar = detail_reservasi_kamar.id_kamar', 'left')
            ->where('reservasi.status', 'selesai')
            ->select('reservasi.*, tamu.nama_tamu, kamar.tipe_kamar')
            ->findAll();
        $data['title'] = 'Riwayat Reservasi';
        
        // PERBAIKAN: Mengubah path view sesuai struktur folder Anda
        return view('admin_history', $data);
    }

    /**
     * Memproses aksi Selesaikan (mengubah status menjadi 'selesai').
     */
    public function selesaikanReservasi($id_reservasi)
    {
        $reservasiModel = new ReservasiModel();
        $riwayatModel = new RiwayatModel(); // Inisialisasi RiwayatModel

        // Ambil detail reservasi yang akan diselesaikan
        // Pastikan Anda mengambil semua data yang diperlukan untuk tabel riwayat
        $reservation = $reservasiModel
            ->join('tamu', 'tamu.id_tamu = reservasi.id_tamu', 'left')
            ->join('detail_reservasi_kamar', 'detail_reservasi_kamar.id_reservasi = reservasi.id_reservasi', 'left')
            ->join('kamar', 'kamar.id_kamar = detail_reservasi_kamar.id_kamar', 'left')
            ->select('reservasi.*, tamu.nama_tamu, kamar.tipe_kamar')
            ->find($id_reservasi);

        if ($reservation) {
            // Siapkan data untuk dimasukkan ke tabel riwayat
            $dataToHistory = [
                'nama_tamu' => $reservation['nama_tamu'],
                'tipe_kamar' => $reservation['tipe_kamar'],
                'tgl_masuk' => $reservation['tgl_masuk'], // Disesuaikan dengan nama kolom di tabel reservasi Anda
                'tgl_keluar' => $reservation['tgl_keluar'], // Disesuaikan dengan nama kolom di tabel reservasi Anda
                'total_bayar' => $reservation['total_harga_reservasi'], // Disesuaikan dengan nama kolom di tabel reservasi Anda
                'tgl_penyelesaian' => date('Y-m-d H:i:s'), // Tanggal dan waktu saat ini
                'status_saat_selesai' => 'selesai'
            ];

            // Masukkan data ke tabel riwayat
            $riwayatModel->insert($dataToHistory);

            // Perbarui status reservasi menjadi 'selesai'
            $reservasiModel->update($id_reservasi, ['status' => 'selesai']);

            return redirect()->to('/admin/checkin')->with('success', 'Reservasi telah diselesaikan dan masuk ke riwayat.');
        } else {
            return redirect()->to('/admin/checkin')->with('error', 'Reservasi tidak ditemukan.');
        }
    }
}
