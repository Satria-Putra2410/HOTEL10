<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReservasiModel;
use App\Models\RiwayatModel;
use App\Models\PembayaranModel;

class AdminController extends BaseController
{
    public function index()
    {
        $reservasiModel = new ReservasiModel();
        $data = [
            'reservations' => $reservasiModel->getActiveReservations()
        ];
        return view('admin_dashboard', $data);
    }

    /**
     * PERBAIKAN TOTAL: Fungsi ini sekarang berfungsi untuk memindahkan reservasi ke riwayat.
     */
    public function selesaikanReservasi($id_reservasi = null)
    {
        if ($id_reservasi === null) {
            return redirect()->to('/admin/dashboard')->with('error', 'ID Reservasi tidak valid.');
        }

        // Inisialisasi model
        $pembayaranModel = new PembayaranModel();
        $riwayatModel = new RiwayatModel();

        // 1. Cari pembayaran yang sesuai dengan reservasi
        $pembayaran = $pembayaranModel->where('id_reservasi', $id_reservasi)->first();

        // Jika tidak ada data pembayaran, jangan lanjutkan
        if (!$pembayaran) {
            return redirect()->to('/admin/dashboard')->with('error', 'Data pembayaran untuk reservasi ini tidak ditemukan. Tidak dapat menyelesaikan.');
        }

        // 2. Cek apakah reservasi ini sudah ada di riwayat untuk mencegah duplikasi
        $existingRiwayat = $riwayatModel->where('id_reservasi', $id_reservasi)->first();
        if ($existingRiwayat) {
            return redirect()->to('/admin/dashboard')->with('error', 'Reservasi ini sudah ada di dalam riwayat.');
        }

        // 3. Siapkan data untuk dimasukkan ke tabel riwayat
        $riwayatData = [
            'id_pembayaran' => $pembayaran['id_pembayaran'],
            'id_reservasi'  => $id_reservasi
        ];

        // 4. Simpan ke tabel riwayat
        if ($riwayatModel->save($riwayatData)) {
            return redirect()->to('/admin/dashboard')->with('success', 'Reservasi berhasil diselesaikan dan dipindahkan ke riwayat.');
        } else {
            return redirect()->to('/admin/dashboard')->with('error', 'Gagal menyimpan data ke riwayat.');
        }
    }

    public function history()
    {
        // Logika untuk menampilkan riwayat juga perlu disesuaikan
        // $riwayatModel = new RiwayatModel();
        // $data['histories'] = $riwayatModel->getHistoryDetails();
        return view('admin_history', ['histories' => []]); // Tampilkan halaman kosong dulu
    }
}