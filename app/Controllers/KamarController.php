<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\DetailReservasiKamarModel;

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
        ];
        return view('kamar/create', $data);
    }

    /**
     * KODE PRODUKSI FINAL
     */
    public function store()
    {
        $kamarModel = new KamarModel();
        $rules = $kamarModel->getValidationRules();

        // 1. Validasi di Controller (sudah terbukti berhasil)
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // 2. Proses Pindah File
        $foto = $this->request->getFile('foto');
        $namaFoto = $foto->getRandomName();
        $foto->move('uploads/kamar/', $namaFoto);

        // 3. Siapkan Data
        $data = [
            'tipe_kamar'  => $this->request->getPost('tipe_kamar'),
            'harga_kamar' => $this->request->getPost('harga_kamar'),
            'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
            'jumlah_tamu' => $this->request->getPost('jumlah_tamu'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'foto'        => $namaFoto,
        ];

        // 4. Simpan ke Database, lewati validasi Model
        if ($kamarModel->skipValidation(true)->save($data)) {
            return redirect()->to('/admin/kamar')->with('success', 'Kamar baru berhasil ditambahkan.');
        } else {
            // Jika gagal simpan, hapus foto yang sudah terlanjur diupload
            if (file_exists('uploads/kamar/' . $namaFoto)) {
                unlink('uploads/kamar/' . $namaFoto);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data ke database.');
        }
    }

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
        ];
        return view('kamar/edit', $data);
    }

    public function update($id)
    {
        $kamarModel = new KamarModel();
        
        $rules = $kamarModel->getValidationRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // LOGIKA PROSES FOTO DIHAPUS

        $data = [
            'tipe_kamar'  => $this->request->getPost('tipe_kamar'),
            'harga_kamar' => $this->request->getPost('harga_kamar'),
            'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
            'jumlah_tamu' => $this->request->getPost('jumlah_tamu'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
        ];

        if ($kamarModel->update($id, $data)) {
            return redirect()->to('/admin/kamar')->with('success', 'Data kamar berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data.');
        }
    }

    public function delete($id)
    {
        $kamarModel = new KamarModel();
        $detailReservasiModel = new DetailReservasiKamarModel();

        $isUsed = $detailReservasiModel->where('id_kamar', $id)->first();
        if ($isUsed) {
            return redirect()->to('/admin/kamar')->with('error', 'Gagal! Kamar ini tidak dapat dihapus karena memiliki riwayat reservasi.');
        }

        // LOGIKA HAPUS FOTO DIHAPUS
        
        if ($kamarModel->delete($id)) {
            return redirect()->to('/admin/kamar')->with('success', 'Data kamar berhasil dihapus.');
        } else {
            return redirect()->to('/admin/kamar')->with('error', 'Gagal menghapus data kamar.');
        }
    }
}
