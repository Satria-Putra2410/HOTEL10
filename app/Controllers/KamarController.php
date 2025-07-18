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
     * Menyimpan data kamar baru ke database.
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
            'tipe_kamar'    => $this->request->getPost('tipe_kamar'),
            'harga_kamar'   => $this->request->getPost('harga_kamar'),
            'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
            'jumlah_tamu'   => $this->request->getPost('jumlah_tamu'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'foto'          => $namaFoto,
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

    /**
     * Menampilkan detail satu kamar berdasarkan ID.
     * Ini adalah method 'show' yang hilang dan menyebabkan error 404.
     */
    public function show($id)
    {
        $kamarModel = new KamarModel();
        $room = $kamarModel->find($id);

        if (!$room) {
            // Jika kamar tidak ditemukan, lemparkan PageNotFoundException
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kamar dengan ID ' . $id . ' tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Kamar',
            'room'  => $room,
        ];
        // Anda perlu membuat file view ini di 'app/Views/kamar/show.php'
        return view('kamar/show', $data);
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
        ];
        return view('kamar/edit', $data);
    }

    /**
     * Memperbarui data kamar di database.
     */
    public function update($id)
    {
        $kamarModel = new KamarModel();
        
        $rules = $kamarModel->getValidationRules();

        // Jika ada file foto baru diupload, tambahkan aturan validasi foto
        if ($this->request->getFile('foto') && $this->request->getFile('foto')->isValid() && !$this->request->getFile('foto')->hasMoved()) {
            $rules['foto'] = 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,webp]';
        } else {
            // Jika tidak ada foto baru, hapus aturan 'uploaded' dari validasi
            unset($rules['foto']);
        }


        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'tipe_kamar'    => $this->request->getPost('tipe_kamar'),
            'harga_kamar'   => $this->request->getPost('harga_kamar'),
            'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
            'jumlah_tamu'   => $this->request->getPost('jumlah_tamu'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
        ];

        // Proses upload foto baru jika ada
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $oldRoom = $kamarModel->find($id);
            // Hapus foto lama jika ada
            if ($oldRoom && $oldRoom['foto'] && file_exists('uploads/kamar/' . $oldRoom['foto'])) {
                unlink('uploads/kamar/' . $oldRoom['foto']);
            }
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/kamar/', $namaFoto);
            $data['foto'] = $namaFoto; // Tambahkan nama foto baru ke data update
        }

        if ($kamarModel->update($id, $data)) {
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
        $detailReservasiModel = new DetailReservasiKamarModel();

        // Cek apakah kamar digunakan dalam reservasi
        $isUsed = $detailReservasiModel->where('id_kamar', $id)->first();
        if ($isUsed) {
            return redirect()->to('/admin/kamar')->with('error', 'Gagal! Kamar ini tidak dapat dihapus karena memiliki riwayat reservasi.');
        }

        // Ambil data kamar sebelum dihapus untuk mendapatkan nama foto
        $room = $kamarModel->find($id);

        if ($kamarModel->delete($id)) {
            // Hapus file foto terkait jika ada
            if ($room && $room['foto'] && file_exists('uploads/kamar/' . $room['foto'])) {
                unlink('uploads/kamar/' . $room['foto']);
            }
            return redirect()->to('/admin/kamar')->with('success', 'Data kamar berhasil dihapus.');
        } else {
            return redirect()->to('/admin/kamar')->with('error', 'Gagal menghapus data kamar.');
        }
    }
}
