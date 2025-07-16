<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\DetailReservasiKamarModel; // Tambahkan ini

class KamarController extends BaseController
{
    public function index()
    {
        $kamarModel = new KamarModel();
        $data['rooms'] = $kamarModel->findAll();
        return view('kamar/index', $data);
    }

    public function create()
    {
        return view('kamar/create');
    }

    public function store()
    {
        $kamarModel = new KamarModel();
        
        if (!$this->validate($kamarModel->validationRules, $kamarModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $this->request->getFile('foto');
        $namaFoto = $foto->getRandomName();
        $foto->move('uploads/kamar', $namaFoto);

        $data = [
            'tipe_kamar'    => $this->request->getPost('tipe_kamar'),
            'harga_kamar'   => $this->request->getPost('harga_kamar'),
            'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
            'jumlah_tamu'   => $this->request->getPost('jumlah_tamu'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'foto'          => $namaFoto
        ];

        if ($kamarModel->save($data)) {
            return redirect()->to('/admin/kamar')->with('success', 'Data kamar berhasil ditambahkan.');
        } else {
            if (file_exists('uploads/kamar/' . $namaFoto)) {
                unlink('uploads/kamar/' . $namaFoto);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data ke database.');
        }
    }

    public function edit($id)
    {
        $kamarModel = new KamarModel();
        $data['room'] = $kamarModel->find($id);
        if (empty($data['room'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kamar tidak ditemukan: ' . $id);
        }
        return view('kamar/edit', $data);
    }

    public function update($id)
    {
        $kamarModel = new KamarModel();
        $rules = $kamarModel->validationRules;

        if (!$this->request->getFile('foto')->isValid()) {
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
        
        $foto = $this->request->getFile('foto');
        if ($foto->isValid() && !$foto->hasMoved()) {
            $kamarLama = $kamarModel->find($id);
            if ($kamarLama && file_exists('uploads/kamar/' . $kamarLama['foto'])) {
                unlink('uploads/kamar/' . $kamarLama['foto']);
            }
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/kamar', $namaFoto);
            $data['foto'] = $namaFoto;
        }

        if ($kamarModel->update($id, $data)) {
            return redirect()->to('/admin/kamar')->with('success', 'Data kamar berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * PERBAIKAN TOTAL: Fungsi delete sekarang memeriksa relasi sebelum menghapus.
     */
    public function delete($id)
    {
        $kamarModel = new KamarModel();
        $detailReservasiModel = new DetailReservasiKamarModel();

        // 1. Cek apakah kamar ini digunakan di tabel detail_reservasi_kamar
        $isUsed = $detailReservasiModel->where('id_kamar', $id)->first();

        if ($isUsed) {
            // Jika digunakan, jangan hapus. Beri pesan error.
            return redirect()->to('/admin/kamar')->with('error', 'Kamar tidak dapat dihapus karena sudah pernah digunakan dalam reservasi.');
        }

        // 2. Jika tidak digunakan, lanjutkan proses hapus
        $kamar = $kamarModel->find($id);
        if ($kamar && file_exists('uploads/kamar/' . $kamar['foto'])) {
            unlink('uploads/kamar/' . $kamar['foto']);
        }
        
        if ($kamarModel->delete($id)) {
            return redirect()->to('/admin/kamar')->with('success', 'Data kamar berhasil dihapus.');
        } else {
            return redirect()->to('/admin/kamar')->with('error', 'Gagal menghapus data.');
        }
    }
}