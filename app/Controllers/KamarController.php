<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\DetailReservasiKamarModel; // Diperlukan untuk pengecekan delete

class KamarController extends BaseController
{
    /**
     * Menampilkan halaman daftar semua kamar.
     */
    public function index()
    {
        $kamarModel = new KamarModel();
        
        $data = [
            'title' => 'Manajemen Kamar',
            'rooms' => $kamarModel->findAll(),
        ];
        return view('kamar/index', $data);
    }

    /**
     * Menampilkan form untuk menambah kamar baru.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Kamar Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('kamar/create', $data);
    }

    /**
     * Menyimpan data kamar baru ke database.
     */
    public function store()
    {
        $kamarModel = new KamarModel();
        
        // 1. Validasi input menggunakan aturan dari KamarModel.
        if (!$this->validate($kamarModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // 2. Proses upload file.
        $foto = $this->request->getFile('foto');
        if (!$foto->isValid() || $foto->hasMoved()) {
            return redirect()->back()->withInput()->with('errors', ['foto' => 'File foto tidak valid atau sudah dipindahkan.']);
        }

        $namaFoto = $foto->getRandomName();
        $uploadPath = FCPATH . 'uploads/kamar/'; // FCPATH menunjuk ke folder 'public'

        // Buat direktori jika belum ada.
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if (!$foto->move($uploadPath, $namaFoto)) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupload file foto.');
        }

        // 3. Gunakan transaksi database untuk keamanan data.
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $kamarData = [
                'tipe_kamar'  => $this->request->getPost('tipe_kamar'),
                'harga_kamar' => $this->request->getPost('harga_kamar'),
                'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
                'jumlah_tamu' => $this->request->getPost('jumlah_tamu'),
                'deskripsi'   => $this->request->getPost('deskripsi'),
                'nomor_kamar' => $this->request->getPost('nomor_kamar'),
                'foto'        => $namaFoto,
            ];
            
            // PERBAIKAN KUNCI: Lewati validasi di model karena sudah divalidasi di controller.
            // Ini mencegah error validasi 'uploaded[foto]' yang gagal setelah file dipindahkan.
            if (!$kamarModel->skipValidation(true)->insert($kamarData)) {
                throw new \Exception('Gagal menyimpan data ke database: ' . implode(', ', $kamarModel->errors()));
            }

            $db->transCommit();
            return redirect()->to('/admin/kamar')->with('success', 'Kamar baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            $db->transRollback();

            // Hapus file yang sudah terupload jika terjadi error database.
            if (file_exists($uploadPath . $namaFoto)) {
                unlink($uploadPath . $namaFoto);
            }

            log_message('error', '[KamarController] Store Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit kamar yang sudah ada.
     */
    public function edit($id)
    {
        $kamarModel = new KamarModel();
        $room = $kamarModel->find($id);

        if (!$room) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kamar dengan ID ' . $id . ' tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Kamar',
            'room'  => $room,
            'validation' => \Config\Services::validation()
        ];
        return view('kamar/edit', $data);
    }

    /**
     * Memperbarui data kamar di database.
     */
    public function update($id)
    {
        $kamarModel = new KamarModel();

        $room = $kamarModel->find($id);
        if (!$room) {
            return redirect()->to('/admin/kamar')->with('error', 'Data kamar tidak ditemukan.');
        }

        // Gunakan aturan validasi untuk update dari model.
        if (!$this->validate($kamarModel->getUpdateValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kamarData = [
            'tipe_kamar'  => $this->request->getPost('tipe_kamar'),
            'harga_kamar' => $this->request->getPost('harga_kamar'),
            'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
            'jumlah_tamu' => $this->request->getPost('jumlah_tamu'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'nomor_kamar' => $this->request->getPost('nomor_kamar'),
        ];
        
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $uploadPath = FCPATH . 'uploads/kamar/';

            if ($foto->move($uploadPath, $namaFoto)) {
                if ($room['foto'] && file_exists($uploadPath . $room['foto'])) {
                    unlink($uploadPath . $room['foto']);
                }
                $kamarData['foto'] = $namaFoto;
            }
        }
        
        // PERBAIKAN KUNCI: Lewati validasi juga saat update.
        if ($kamarModel->skipValidation(true)->update($id, $kamarData)) {
            return redirect()->to('/admin/kamar')->with('success', 'Data kamar berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * Menghapus data kamar dari database.
     */
    public function delete($id)
    {
        $kamarModel = new KamarModel();
        $detailReservasiKamarModel = new DetailReservasiKamarModel();

        $isUsed = $detailReservasiKamarModel->where('id_kamar', $id)->first();
        if ($isUsed) {
            return redirect()->to('/admin/kamar')->with('error', 'Gagal! Kamar ini tidak dapat dihapus karena memiliki riwayat reservasi.');
        }

        $room = $kamarModel->find($id);
        if (!$room) {
            return redirect()->to('/admin/kamar')->with('error', 'Data kamar tidak ditemukan.');
        }
        
        if ($kamarModel->delete($id)) {
            if ($room['foto'] && file_exists(FCPATH . 'uploads/kamar/' . $room['foto'])) {
                unlink(FCPATH . 'uploads/kamar/' . $room['foto']);
            }
            return redirect()->to('/admin/kamar')->with('success', 'Kamar berhasil dihapus.');
        } else {
            return redirect()->to('/admin/kamar')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
