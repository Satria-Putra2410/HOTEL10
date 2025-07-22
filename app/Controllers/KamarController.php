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
     * Termasuk validasi, upload foto, dan penyimpanan data kamar baru.
     */
    public function store()
    {
        $kamarModel = new KamarModel();
        // Gunakan aturan validasi default untuk operasi 'store' (yang menyertakan foto)
        $rules = $kamarModel->getValidationRules(); 
        $messages = $kamarModel->getValidationMessages();

        // 1. Validasi data yang masuk dari form
        if (!$this->validate($rules, $messages)) {
            log_message('error', 'Validasi gagal saat menyimpan kamar baru: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // 2. Proses upload file foto
        $foto = $this->request->getFile('foto');
        $namaFoto = $foto->getRandomName(); // Buat nama unik untuk foto
        $foto->move('uploads/kamar/', $namaFoto); // Pindahkan file ke direktori 'uploads/kamar/'

        // 3. Siapkan data untuk disimpan ke database
        $data = [
            'tipe_kamar'    => $this->request->getPost('tipe_kamar'),
            'harga_kamar'   => $this->request->getPost('harga_kamar'),
            'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
            'jumlah_tamu'   => $this->request->getPost('jumlah_tamu'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'foto'          => $namaFoto, // Simpan nama file foto
            'nomor_kamar'   => $this->request->getPost('nomor_kamar'), // Atribut nomor_kamar
        ];

        log_message('info', 'Data kamar baru yang akan disimpan: ' . json_encode($data));

        // 4. Simpan data ke database, lewati validasi Model karena sudah divalidasi di controller
        if ($kamarModel->skipValidation(true)->save($data)) {
            log_message('info', 'Kamar baru berhasil ditambahkan.');
            return redirect()->to('/admin/kamar')->with('success', 'Kamar baru berhasil ditambahkan.');
        } else {
            // Jika gagal menyimpan, hapus foto yang sudah terlanjur diupload
            if (file_exists('uploads/kamar/' . $namaFoto)) {
                unlink('uploads/kamar/' . $namaFoto);
                log_message('warning', 'Gagal menyimpan data kamar, foto yang diupload dihapus: ' . $namaFoto);
            }
            log_message('error', 'Gagal menyimpan data kamar baru ke database. Error Model: ' . json_encode($kamarModel->errors()));
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data ke database.');
        }
    }

    /**
     * Menampilkan detail satu kamar berdasarkan ID.
     */
    public function show($id)
    {
        $kamarModel = new KamarModel();
        $room = $kamarModel->find($id); // Cari kamar berdasarkan ID

        if (!$room) {
            log_message('warning', 'Kamar dengan ID ' . $id . ' tidak ditemukan saat mencoba menampilkan detail.');
            // Jika kamar tidak ditemukan, lemparkan PageNotFoundException (error 404)
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kamar dengan ID ' . $id . ' tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Kamar',
            'room'  => $room,
        ];
        log_message('info', 'Menampilkan detail kamar untuk ID: ' . $id);
        return view('kamar/show', $data);
    }

    /**
     * Menampilkan form untuk mengedit kamar yang sudah ada.
     */
    public function edit($id)
    {
        $kamarModel = new KamarModel();
        $room = $kamarModel->find($id); // Cari kamar berdasarkan ID

        if (!$room) {
            log_message('warning', 'Kamar dengan ID ' . $id . ' tidak ditemukan saat mencoba mengedit.');
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kamar dengan ID ' . $id . ' tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Kamar',
            'room'  => $room,
        ];
        log_message('info', 'Menampilkan form edit untuk kamar ID: ' . $id);
        return view('kamar/edit', $data);
    }

    /**
     * Memperbarui data kamar di database.
     * Termasuk validasi dan update data, foto tidak dapat diubah dari sini.
     */
    public function update($id)
    {
        $kamarModel = new KamarModel();
        
        // Ambil aturan validasi khusus untuk update (tanpa validasi foto)
        $rules = $kamarModel->getUpdateValidationRules();
        $messages = $kamarModel->getUpdateValidationMessages();

        // Ambil data kamar lama untuk perbandingan (terutama untuk foto)
        $oldRoom = $kamarModel->find($id);
        log_message('debug', 'Memulai update untuk kamar ID: ' . $id);
        log_message('debug', 'Data kamar lama: ' . json_encode($oldRoom));
        
        // Logika penghapusan aturan validasi foto di controller tidak lagi diperlukan
        // karena aturan 'foto' sudah tidak ada di getUpdateValidationRules()
        log_message('debug', 'Aturan validasi foto tidak ada di getUpdateValidationRules().');

        // Karena aturan is_unique untuk nomor_kamar sudah dihapus dari model,
        // tidak perlu ada penanganan khusus di sini.
        // Aturan 'required|alpha_numeric_punct|max_length[10]' akan tetap berlaku.
        log_message('debug', 'Aturan is_unique untuk nomor_kamar tidak diterapkan (dihapus dari model).');

        // Lakukan validasi dengan aturan dan pesan yang sudah disesuaikan
        if (!$this->validate($rules, $messages)) {
            log_message('error', 'Validasi gagal saat memperbarui kamar ID ' . $id . ': ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        log_message('info', 'Validasi berhasil untuk kamar ID: ' . $id);

        // Siapkan data untuk diperbarui
        $data = [
            'tipe_kamar'    => $this->request->getPost('tipe_kamar'),
            'harga_kamar'   => $this->request->getPost('harga_kamar'),
            'jenis_ranjang' => $this->request->getPost('jenis_ranjang'),
            'jumlah_tamu'   => $this->request->getPost('jumlah_tamu'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'nomor_kamar'   => $this->request->getPost('nomor_kamar'), // Atribut nomor_kamar
            'foto'          => $oldRoom['foto'], // Pertahankan foto lama, tidak bisa diubah
        ];

        log_message('info', 'Data final yang akan diperbarui untuk kamar ID ' . $id . ': ' . json_encode($data));
        // Perbarui data di database
        if ($kamarModel->update($id, $data)) {
            log_message('info', 'Data kamar ID ' . $id . ' berhasil diperbarui.');
            return redirect()->to('/admin/kamar')->with('success', 'Data kamar berhasil diperbarui.');
        } else {
            // Tangkap dan log error spesifik dari model
            log_message('error', 'Gagal memperbarui data kamar ID ' . $id . ' di database. Error Model: ' . json_encode($kamarModel->errors()));
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

        // Cek apakah kamar digunakan dalam reservasi sebelum dihapus
        $isUsed = $detailReservasiModel->where('id_kamar', $id)->first();
        if ($isUsed) {
            log_message('warning', 'Percobaan menghapus kamar ID ' . $id . ' gagal karena memiliki riwayat reservasi.');
            return redirect()->to('/admin/kamar')->with('error', 'Gagal! Kamar ini tidak dapat dihapus karena memiliki riwayat reservasi.');
        }

        // Ambil data kamar sebelum dihapus untuk mendapatkan nama file foto
        $room = $kamarModel->find($id);

        // Hapus data kamar dari database
        if ($kamarModel->delete($id)) {
            // Hapus file foto terkait jika ada dan file-nya eksis
            if ($room && $room['foto'] && file_exists('uploads/kamar/' . $room['foto'])) {
                unlink('uploads/kamar/' . $room['foto']);
                log_message('info', 'Kamar ID ' . $id . ' dan foto terkait dihapus.');
            }
            return redirect()->to('/admin/kamar')->with('success', 'Data kamar berhasil dihapus.');
        } else {
            log_message('error', 'Gagal menghapus data kamar ID ' . $id . ' dari database. Error Model: ' . json_encode($kamarModel->errors()));
            return redirect()->to('/admin/kamar')->with('error', 'Gagal menghapus data kamar.');
        }
    }
}
