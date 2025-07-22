<?php

namespace App\Controllers;

use App\Models\ReservasiModel;
use App\Models\KamarModel;
use App\Models\UnitKamarModel;
use App\Models\TamuModel;
use CodeIgniter\API\ResponseTrait;
use DateTime; // Pastikan class DateTime di-import

class TamuController extends BaseController
{
    use ResponseTrait;

    protected $reservasiModel;
    protected $kamarModel;
    protected $unitKamarModel;
    protected $tamuModel;

    public function __construct()
    {
        $this->reservasiModel = new ReservasiModel();
        $this->kamarModel = new KamarModel();
        $this->unitKamarModel = new UnitKamarModel();
        $this->tamuModel = new TamuModel();
        helper(['form', 'url', 'session']);
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return redirect()->to(base_url('login'));
        }
        $data['title'] = 'Dashboard Tamu';
        return view('tamu_dashboard', $data);
    }

    /**
     * Memeriksa ketersediaan kamar berdasarkan tanggal dan jumlah tamu (via AJAX).
     */
    public function checkRoomAvailability()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak diizinkan.');
        }

        $input = $this->request->getJSON(true);
        $tgl_masuk = $input['tgl_masuk'] ?? null;
        $tgl_keluar = $input['tgl_keluar'] ?? null;
        $jumlah_tamu = $input['jumlah_tamu'] ?? null;

        if (!$tgl_masuk || !$tgl_keluar || !$jumlah_tamu) {
            return $this->failValidationError('Data tanggal atau jumlah tamu tidak lengkap.');
        }
        if (strtotime($tgl_masuk) >= strtotime($tgl_keluar) || strtotime($tgl_masuk) < strtotime(date('Y-m-d'))) {
            return $this->failValidationError('Rentang tanggal tidak valid.');
        }

        $db = \Config\Database::connect();
        $reservedUnitIds = $db->table('reservasi r')
            ->select('drk.id_kamar as id_unit_kamar')
            ->join('detail_reservasi_kamar drk', 'drk.id_reservasi = r.id_reservasi')
            ->where('r.status !=', 'selesai')
            ->groupStart()
                ->where('r.tgl_masuk <', $tgl_keluar)
                ->where('r.tgl_keluar >', $tgl_masuk)
            ->groupEnd()
            ->get()->getResultArray();

        $reservedIds = array_column($reservedUnitIds, 'id_unit_kamar');

        $availableRooms = $this->unitKamarModel
            ->select('unit_kamar.id_unit_kamar, unit_kamar.nomor_kamar, kamar.id_kamar, kamar.tipe_kamar, kamar.harga_kamar, kamar.jenis_ranjang, kamar.jumlah_tamu, kamar.foto, kamar.deskripsi')
            ->join('kamar', 'kamar.id_kamar = unit_kamar.id_kamar')
            ->where('kamar.jumlah_tamu >=', $jumlah_tamu);
            
        if (!empty($reservedIds)) {
            $availableRooms->whereNotIn('unit_kamar.id_unit_kamar', $reservedIds);
        }
            
        $rooms = $availableRooms->findAll();
        return $this->respond(['status' => 'success', 'rooms' => $rooms]);
    }

    /**
     * Memproses pembuatan reservasi baru (via AJAX).
     */
    public function createReservation()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak diizinkan.');
        }

        $id_tamu = session()->get('id_tamu');
        if (!$id_tamu) {
            return $this->failUnauthorized('Anda harus login untuk membuat reservasi.');
        }

        $input = $this->request->getJSON(true);
        $id_unit_kamar = $input['id_unit_kamar'] ?? null;
        $tgl_masuk = $input['tgl_masuk'] ?? null;
        $tgl_keluar = $input['tgl_keluar'] ?? null;
        $harga_kamar = $input['harga_kamar'] ?? null;

        if (!$id_unit_kamar || !$tgl_masuk || !$tgl_keluar || !$harga_kamar) {
            return $this->failValidationError('Data pemesanan tidak lengkap.');
        }

        $db = \Config\Database::connect();
        $isBooked = $db->table('reservasi r')
            ->join('detail_reservasi_kamar drk', 'drk.id_reservasi = r.id_reservasi')
            ->where('drk.id_kamar', $id_unit_kamar) 
            ->where('r.status !=', 'selesai') 
            ->groupStart()
                ->where('r.tgl_masuk <', $tgl_keluar)
                ->where('r.tgl_keluar >', $tgl_masuk)
            ->groupEnd()
            ->countAllResults();

        if ($isBooked > 0) {
            return $this->failConflict('Kamar ini baru saja dipesan. Silakan pilih kamar lain.');
        }

        $checkin = new DateTime($tgl_masuk);
        $checkout = new DateTime($tgl_keluar);
        $numberOfNights = $checkout->diff($checkin)->days;

        if ($numberOfNights <= 0) {
            return $this->failValidationError('Durasi reservasi tidak valid.');
        }

        $total_harga_reservasi = $harga_kamar * $numberOfNights;

        $reservasiData = [
            'tgl_masuk' => $tgl_masuk,
            'tgl_keluar' => $tgl_keluar,
            'total_harga_reservasi' => $total_harga_reservasi,
            'id_tamu' => $id_tamu,
            'status' => 'Reserved' 
        ];

        $db->transBegin();
        try {
            $this->reservasiModel->insert($reservasiData);
            $id_reservasi = $this->reservasiModel->getInsertID();

            $detailReservasiKamarData = [
                'jumlah_malam_kamar' => $numberOfNights,
                'id_reservasi' => $id_reservasi,
                'id_kamar' => $id_unit_kamar 
            ];
            $db->table('detail_reservasi_kamar')->insert($detailReservasiKamarData);

            $db->transCommit(); 
            return $this->respondCreated(['status' => 'success', 'message' => 'Pemesanan berhasil!', 'id_reservasi' => $id_reservasi]);

        } catch (\Exception $e) {
            $db->transRollback(); 
            return $this->failServerError('Terjadi kesalahan saat memproses pemesanan: ' . $e->getMessage());
        }
    }

    /**
     * ====================================================================
     * FUNGSI UNTUK RIWAYAT RESERVASI
     * ====================================================================
     */

    /**
     * Menampilkan halaman Riwayat Reservasi untuk Tamu.
     * Fungsi ini hanya memuat view, data akan diambil oleh JavaScript.
     */
    public function riwayatReservasi()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return redirect()->to(base_url('login'));
        }
        $data['title'] = 'Riwayat Reservasi Saya';
        return view('riwayat-reservasi', $data);
    }

    /**
     * Menyediakan data riwayat reservasi sebagai API untuk JavaScript.
     */
    public function getApiRiwayat()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return $this->failUnauthorized('Akses ditolak.');
        }

        $id_tamu = session()->get('id_tamu'); 
        if (!$id_tamu) {
            return $this->fail('ID Tamu tidak ditemukan di session.', 400);
        }

        try {
            $data = $this->reservasiModel->getRiwayatByTamu($id_tamu);
            return $this->respond($data);
        } catch (\Exception $e) {
            log_message('error', '[TamuController] Get API Riwayat Error: ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan pada server: ' . $e->getMessage());
        }
    }

    /**
     * ====================================================================
     * FUNGSI LAINNYA
     * ====================================================================
     */

    public function editProfil()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return redirect()->to(base_url('login'));
        }
        $id_tamu = session()->get('id_tamu');
        $userData = $this->tamuModel->find($id_tamu);
        if (!$userData) {
            session()->destroy();
            return redirect()->to(base_url('login'))->with('error', 'Data pengguna tidak ditemukan.');
        }
        $data = [
            'title' => 'Edit Profil',
            'tamu'  => $userData
        ];
        return view('tamu_edit_profil', $data);
    }

    public function updateProfil()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return redirect()->to(base_url('login'));
        }
        $id_tamu = session()->get('id_tamu');
        $dataToUpdate = [
            'id_tamu'    => $id_tamu,
            'nama_tamu'  => $this->request->getPost('nama_tamu'),
            'no_hp_tamu' => $this->request->getPost('no_hp_tamu'),
            'email'      => $this->request->getPost('email'),
        ];
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $dataToUpdate['password'] = $password;
        }
        if ($this->tamuModel->save($dataToUpdate) === false) {
            return redirect()->back()->withInput()->with('errors', $this->tamuModel->errors());
        }
        session()->set([
            'nama' => $dataToUpdate['nama_tamu'],
            'email' => $dataToUpdate['email'],
        ]);
        return redirect()->to(base_url('tamu/dashboard'))->with('success', 'Profil berhasil diperbarui.');
    }

    public function reservasi_form()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return redirect()->to(base_url('login'));
        }

        $idUnitKamar = $this->request->getGet('id_unit_kamar');
        $tglMasuk = $this->request->getGet('tgl_masuk');
        $tglKeluar = $this->request->getGet('tgl_keluar');
        $jumlahTamu = $this->request->getGet('jumlah_tamu');
        $hargaKamar = $this->request->getGet('harga_kamar');

        if (!$idUnitKamar || !$tglMasuk || !$tglKeluar || !$jumlahTamu) {
            session()->setFlashdata('error', 'Data reservasi tidak lengkap.');
            return redirect()->to(base_url('tamu/dashboard'));
        }

        $idTamu = session()->get('id_tamu');
        $dataTamu = $this->tamuModel->find($idTamu);
        $unitKamar = $this->unitKamarModel->find($idUnitKamar);
        $kamar = $this->kamarModel->find($unitKamar['id_kamar']);

        $checkin = new DateTime($tglMasuk);
        $checkout = new DateTime($tglKeluar);
        $jumlahHari = $checkout->diff($checkin)->days;
        $totalHarga = $jumlahHari * $hargaKamar;

        $data = [
            'title' => 'Form Reservasi Kamar',
            'tamu' => $dataTamu,
            'kamar' => $kamar,
            'unit_kamar' => $unitKamar,
            'tgl_masuk' => $tglMasuk,
            'tgl_keluar' => $tglKeluar,
            'jumlah_tamu' => $jumlahTamu,
            'jumlah_hari' => $jumlahHari,
            'harga_kamar' => $hargaKamar,
            'total_harga' => $totalHarga
        ];
        return view('tamu/reservasi_form', $data);
    }

    public function proses_reservasi()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return $this->failUnauthorized('Akses tidak diizinkan.');
        }

        $json = $this->request->getJSON(true);
        $idTamu = session()->get('id_tamu');
        $idUnitKamar = $json['id_unit_kamar'];
        $tglMasuk = $json['tgl_masuk'];
        $tglKeluar = $json['tgl_keluar'];
        $totalHarga = $json['total_harga'];
        $namaTamu = $json['nama_tamu'];
        $noHpTamu = $json['no_hp_tamu'];

        if ($namaTamu || $noHpTamu) {
            $updateData = [];
            if ($namaTamu) $updateData['nama'] = $namaTamu;
            if ($noHpTamu) $updateData['no_hp'] = $noHpTamu;
            if (!empty($updateData)) {
                $this->tamuModel->update($idTamu, $updateData);
            }
        }

        try {
            $reservasiData = [
                'id_tamu' => $idTamu,
                'tgl_reservasi' => date('Y-m-d H:i:s'),
                'tgl_masuk' => $tglMasuk,
                'tgl_keluar' => $tglKeluar,
                'total_harga' => $totalHarga,
                'status_reservasi' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $idReservasi = $this->reservasiModel->insert($reservasiData);

            if ($idReservasi) {
                // Anda mungkin perlu menambahkan data ke detail_reservasi_kamar di sini juga
                return $this->respondCreated(['status' => 'success', 'message' => 'Reservasi berhasil dibuat', 'id_reservasi' => $idReservasi]);
            } else {
                return $this->fail('Gagal membuat reservasi');
            }
        } catch (\Exception $e) {
            return $this->failServerError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
