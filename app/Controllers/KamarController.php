<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\UnitKamarModel;
use App\Models\DetailReservasiKamarModel;

class KamarController extends BaseController
{
    /**
     * Menampilkan halaman daftar semua kamar.
     */
    public function index()
    {
        $unitKamarModel = new UnitKamarModel();
        
        $data = [
            'title' => 'Manajemen Kamar',
            'rooms' => $unitKamarModel
                ->select('unit_kamar.id_unit_kamar, unit_kamar.nomor_kamar, kamar.*')
                ->join('kamar', 'kamar.id_kamar = unit_kamar.id_kamar', 'left')
                ->findAll(),
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
     * ====================================================================
     * FUNGSI STORE YANG DIPERBARUI DENGAN LOGIKA YANG LEBIH AMAN
     * ====================================================================
     */
    public function store()
    {
        $kamarModel = new KamarModel();
        $unitKamarModel = new UnitKamarModel();
        
        // PERBAIKAN: Validasi semua input dari form terlebih dahulu
        $rules = $kamarModel->getValidationRules(); 
        // Tambahkan aturan untuk nomor_kamar secara manual jika tidak ada di model Kamar
        $rules['nomor_kamar'] = 'required|alpha_numeric_punct|max_length[20]';

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $foto = $this->request->getFile('foto');
        $namaFoto = $foto->getRandomName();
        
        if (!$foto->move('uploads/kamar/', $namaFoto)) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupload file foto. Pastikan folder `uploads/kamar` dapat ditulis.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Siapkan data HANYA untuk tabel 'kamar'
            $kamarData = [
                'tipe_kamar'    => $this->request->getPost('tipe_kamar'),
                'harga_kamar'   => $this->request->getPost('harga_kamar'),
                'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
                'jumlah_tamu'   => $this->request->getPost('jumlah_tamu'),
                'deskripsi'     => $this->request->getPost('deskripsi'),
                'foto'          => $namaFoto,
            ];
            
            // PERBAIKAN: Lewati validasi model saat insert, karena sudah divalidasi di controller
            $id_kamar_baru = $kamarModel->skipValidation(true)->insert($kamarData);

            if ($id_kamar_baru === false) {
                // Ambil error dari model jika ada
                $errors = $kamarModel->errors();
                throw new \Exception('Gagal menyimpan data tipe kamar: ' . implode(', ', $errors));
            }

            // Siapkan dan simpan data untuk tabel 'unit_kamar'
            $unitKamarData = [
                'id_kamar'    => $id_kamar_baru,
                'nomor_kamar' => $this->request->getPost('nomor_kamar'),
            ];
            $unitKamarModel->insert($unitKamarData);

            $db->transCommit();

            return redirect()->to('/admin/kamar')->with('success', 'Kamar baru dan unitnya berhasil ditambahkan.');

        } catch (\Exception $e) {
            $db->transRollback();

            if (file_exists('uploads/kamar/' . $namaFoto)) {
                unlink('uploads/kamar/' . $namaFoto);
            }

            log_message('error', 'Gagal menyimpan kamar baru: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data ke database: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail satu kamar berdasarkan ID.
     */
    public function show($id)
    {
        $kamarModel = new KamarModel();
        $room = $kamarModel->find($id);

        if (!$room) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kamar dengan ID ' . $id . ' tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Kamar',
            'room'  => $room,
        ];
        return view('kamar/show', $data);
    }

    /**
     * Menampilkan form untuk mengedit kamar yang sudah ada.
     */
    public function edit($id)
    {
        $kamarModel = new KamarModel();
        $unitKamarModel = new UnitKamarModel();

        $room = $kamarModel->find($id);
        if (!$room) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Tipe Kamar dengan ID ' . $id . ' tidak ditemukan.');
        }

        $unit = $unitKamarModel->where('id_kamar', $id)->first();
        $room['nomor_kamar'] = $unit ? $unit['nomor_kamar'] : 'N/A';
        $room['id_unit_kamar'] = $unit ? $unit['id_unit_kamar'] : null;

        $data = [
            'title' => 'Edit Kamar',
            'room'  => $room,
        ];
        return view('kamar/edit', $data);
    }

    /**
     * ====================================================================
     * FUNGSI UPDATE YANG DIPERBARUI
     * ====================================================================
     * Memperbarui data tipe kamar dan unit kamar terkait.
     */
    public function update($id) // $id di sini adalah id_kamar
    {
        $kamarModel = new KamarModel();
        $unitKamarModel = new UnitKamarModel();

        $rules = $kamarModel->getValidationRules(['except' => ['foto']]);
        $rules['nomor_kamar'] = 'required|alpha_numeric_punct|max_length[20]';

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Update data di tabel 'kamar'
            $kamarData = [
                'tipe_kamar'    => $this->request->getPost('tipe_kamar'),
                'harga_kamar'   => $this->request->getPost('harga_kamar'),
                'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
                'jumlah_tamu'   => $this->request->getPost('jumlah_tamu'),
                'deskripsi'     => $this->request->getPost('deskripsi'),
            ];
            $kamarModel->skipValidation(true)->update($id, $kamarData);

            // 2. Update data di tabel 'unit_kamar'
            $id_unit_kamar = $this->request->getPost('id_unit_kamar');
            if ($id_unit_kamar) {
                $unitKamarData = [
                    'nomor_kamar' => $this->request->getPost('nomor_kamar'),
                ];
                $unitKamarModel->update($id_unit_kamar, $unitKamarData);
            }

            $db->transCommit();
            return redirect()->to('/admin/kamar')->with('success', 'Data kamar berhasil diperbarui.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Gagal memperbarui kamar: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * Menghapus data TIPE kamar dan SEMUA UNIT kamar yang terkait.
     */
    public function delete($id)
    {
        $kamarModel = new KamarModel();
        $unitKamarModel = new UnitKamarModel();
        $detailReservasiKamarModel = new DetailReservasiKamarModel();

        $unitsToDelete = $unitKamarModel->where('id_kamar', $id)->findAll();
        $unitIds = array_column($unitsToDelete, 'id_unit_kamar');

        if (!empty($unitIds)) {
            $isUsed = $detailReservasiKamarModel->whereIn('id_kamar', $unitIds)->first();
            if ($isUsed) {
                return redirect()->to('/admin/kamar')->with('error', 'Gagal! Tipe kamar ini tidak dapat dihapus karena salah satu unitnya memiliki riwayat reservasi.');
            }
        }

        $room = $kamarModel->find($id);
        if (!$room) {
             throw new \CodeIgniter\Exceptions\PageNotFoundException('Tipe Kamar dengan ID ' . $id . ' tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            if (!empty($unitIds)) {
                $unitKamarModel->delete($unitIds);
            }
            $kamarModel->delete($id);
            $db->transCommit();

            if ($room['foto'] && file_exists('uploads/kamar/' . $room['foto'])) {
                unlink('uploads/kamar/' . $room['foto']);
            }
            return redirect()->to('/admin/kamar')->with('success', 'Tipe kamar dan semua unitnya berhasil dihapus.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/admin/kamar')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
