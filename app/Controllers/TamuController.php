<?php

namespace App\Controllers;

use App\Models\ReservasiModel;
use App\Models\KamarModel;
use App\Models\UnitKamarModel;
use App\Models\TamuModel;
use CodeIgniter\API\ResponseTrait;

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
        helper(['form', 'url', 'session']); // Pastikan helper session dimuat
    }

    public function index()
    {
        // Log untuk melacak apakah controller ini dipanggil
        log_message('debug', 'TamuController: index method triggered.');

        // Pastikan tamu sudah login (menggunakan 'isLoggedIn' sesuai AuthController)
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            log_message('debug', 'TamuController: User not logged in or not tamu, redirecting to login.');
            return redirect()->to(base_url('login'));
        }
        $data['title'] = 'Dashboard Tamu';
        log_message('debug', 'TamuController: Attempting to load tamu_dashboard view.');
        return view('tamu_dashboard', $data);
    }

    /**
     * Memeriksa ketersediaan kamar berdasarkan tanggal dan jumlah tamu.
     * Mengembalikan daftar unit kamar yang tersedia dalam format JSON.
     */
    public function checkRoomAvailability()
    {
        log_message('debug', 'TamuController: checkRoomAvailability method triggered.');
        // Pastikan ini adalah AJAX request
        if (!$this->request->isAJAX()) {
            log_message('debug', 'TamuController: checkRoomAvailability - Not an AJAX request.');
            return $this->failUnauthorized('Akses tidak diizinkan.');
        }

        $input = $this->request->getJSON(true);
        $tgl_masuk = $input['tgl_masuk'] ?? null;
        $tgl_keluar = $input['tgl_keluar'] ?? null;
        $jumlah_tamu = $input['jumlah_tamu'] ?? null;

        log_message('debug', 'TamuController: checkRoomAvailability - Input: ' . json_encode($input));

        if (!$tgl_masuk || !$tgl_keluar || !$jumlah_tamu) {
            log_message('error', 'TamuController: checkRoomAvailability - Validation error: Missing date or guest count.');
            return $this->failValidationError('Data tanggal atau jumlah tamu tidak lengkap.');
        }

        // Validasi tanggal
        if (strtotime($tgl_masuk) >= strtotime($tgl_keluar)) {
            log_message('error', 'TamuController: checkRoomAvailability - Validation error: Checkout date before or same as checkin date.');
            return $this->failValidationError('Tanggal Check-out harus setelah Tanggal Check-in.');
        }
        if (strtotime($tgl_masuk) < strtotime(date('Y-m-d'))) {
             log_message('error', 'TamuController: checkRoomAvailability - Validation error: Checkin date in the past.');
             return $this->failValidationError('Tanggal Check-in tidak boleh di masa lalu.');
        }

        // Logika Ketersediaan Kamar
        // 1. Dapatkan semua id_unit_kamar yang sudah terreservasi pada rentang tanggal tersebut
        $db = \Config\Database::connect();
        $reservedUnitIds = $db->table('reservasi r')
            ->select('drk.id_kamar as id_unit_kamar') // id_kamar di detail_reservasi_kamar sebenarnya merujuk ke id_unit_kamar
            ->join('detail_reservasi_kamar drk', 'drk.id_reservasi = r.id_reservasi')
            ->where('r.status !=', 'selesai') // Jangan cek reservasi yang sudah selesai
            ->groupStart()
                ->where('r.tgl_masuk <', $tgl_keluar)
                ->where('r.tgl_keluar >', $tgl_masuk)
            ->groupEnd()
            ->get()
            ->getResultArray();

        $reservedIds = array_column($reservedUnitIds, 'id_unit_kamar');
        log_message('debug', 'TamuController: checkRoomAvailability - Reserved IDs: ' . json_encode($reservedIds));

        // 2. Dapatkan semua unit_kamar yang cocok dengan jumlah tamu
        //    dan TIDAK ADA di daftar unit yang sudah terreservasi
        $availableRooms = $this->unitKamarModel
            ->select('unit_kamar.id_unit_kamar, unit_kamar.nomor_kamar, kamar.id_kamar, kamar.tipe_kamar, kamar.harga_kamar, kamar.jenis_ranjang, kamar.jumlah_tamu, kamar.foto, kamar.deskripsi')
            ->join('kamar', 'kamar.id_kamar = unit_kamar.id_kamar')
            ->where('kamar.jumlah_tamu >=', $jumlah_tamu);
            
        if (!empty($reservedIds)) {
            $availableRooms->whereNotIn('unit_kamar.id_unit_kamar', $reservedIds);
        }
            
        $rooms = $availableRooms->findAll();
        log_message('debug', 'TamuController: checkRoomAvailability - Found rooms: ' . count($rooms));

        return $this->respond(['status' => 'success', 'rooms' => $rooms]);
    }

    /**
     * Memproses pembuatan reservasi baru.
     */
    public function createReservation()
    {
        log_message('debug', 'TamuController: createReservation method triggered.');
        // Pastikan ini adalah AJAX request
        if (!$this->request->isAJAX()) {
            log_message('debug', 'TamuController: createReservation - Not an AJAX request.');
            return $this->failUnauthorized('Akses tidak diizinkan.');
        }

        // Pastikan tamu sudah login
        $id_tamu = session()->get('id_tamu');
        if (!$id_tamu) {
            log_message('debug', 'TamuController: createReservation - User not logged in, failing unauthorized.');
            return $this->failUnauthorized('Anda harus login untuk membuat reservasi.');
        }

        $input = $this->request->getJSON(true);
        $id_unit_kamar = $input['id_unit_kamar'] ?? null;
        $tgl_masuk = $input['tgl_masuk'] ?? null;
        $tgl_keluar = $input['tgl_keluar'] ?? null;
        $harga_kamar = $input['harga_kamar'] ?? null; 

        log_message('debug', 'TamuController: createReservation - Input: ' . json_encode($input));

        if (!$id_unit_kamar || !$tgl_masuk || !$tgl_keluar || !$harga_kamar) {
            log_message('error', 'TamuController: createReservation - Validation error: Missing reservation data.');
            return $this->failValidationError('Data pemesanan tidak lengkap.');
        }

        $db = \Config\Database::connect();
        // --- Logika Pencegahan Double Booking (Race Condition) ---
        $isAvailable = $db->table('reservasi r')
            ->join('detail_reservasi_kamar drk', 'drk.id_reservasi = r.id_reservasi')
            ->where('drk.id_kamar', $id_unit_kamar) 
            ->where('r.status !=', 'selesai') 
            ->groupStart()
                ->where('r.tgl_masuk <', $tgl_keluar)
                ->where('r.tgl_keluar >', $tgl_masuk)
            ->groupEnd()
            ->countAllResults();

        if ($isAvailable > 0) {
            log_message('error', 'TamuController: createReservation - Conflict: Room already booked.');
            return $this->failConflict('Kamar ini baru saja dipesan. Silakan pilih kamar lain.');
        }
        // --- Akhir Logika Pencegahan Double Booking ---

        // Hitung total harga reservasi
        $checkinTimestamp = strtotime($tgl_masuk);
        $checkoutTimestamp = strtotime($tgl_keluar);
        $durationInSeconds = abs($checkoutTimestamp - $checkinTimestamp);
        $numberOfNights = round($durationInSeconds / (60 * 60 * 24)); 

        if ($numberOfNights <= 0) {
            log_message('error', 'TamuController: createReservation - Validation error: Invalid reservation duration.');
            return $this->failValidationError('Durasi reservasi tidak valid.');
        }

        $total_harga_reservasi = $harga_kamar * $numberOfNights;
        log_message('debug', 'TamuController: createReservation - Total price: ' . $total_harga_reservasi . ' for ' . $numberOfNights . ' nights.');

        // Buat reservasi baru
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
            log_message('debug', 'TamuController: createReservation - Reservation successful. ID: ' . $id_reservasi);
            return $this->respondCreated(['status' => 'success', 'message' => 'Pemesanan berhasil!', 'id_reservasi' => $id_reservasi]);

        } catch (\Exception $e) {
            $db->transRollback(); 
            log_message('error', 'TamuController: createReservation - Database transaction failed: ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan saat memproses pemesanan: ' . $e->getMessage());
        }
    }

    // Metode untuk menampilkan riwayat reservasi tamu
    public function riwayatReservasi()
    {
        log_message('debug', 'TamuController: riwayatReservasi method triggered.');
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            log_message('debug', 'TamuController: riwayatReservasi - User not logged in or not tamu, redirecting to login.');
            return redirect()->to(base_url('login'));
        }

        $id_tamu = session()->get('id_tamu');

        $reservations = $this->reservasiModel
            ->join('detail_reservasi_kamar drk', 'drk.id_reservasi = reservasi.id_reservasi', 'left')
            ->join('unit_kamar uk', 'uk.id_unit_kamar = drk.id_kamar', 'left') 
            ->join('kamar k', 'k.id_kamar = uk.id_kamar', 'left') 
            ->where('reservasi.id_tamu', $id_tamu)
            ->select('reservasi.*, k.tipe_kamar, uk.nomor_kamar, drk.jumlah_malam_kamar')
            ->orderBy('reservasi.tgl_masuk', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Riwayat Reservasi Anda',
            'reservations' => $reservations
        ];
        log_message('debug', 'TamuController: Loading tamu_riwayat view.');
        return view('tamu_riwayat', $data); 
    }

    /**
     * Menampilkan halaman form untuk mengedit profil tamu.
     */
    public function editProfil()
    {
        log_message('debug', 'TamuController: editProfil method triggered.');
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return redirect()->to(base_url('login'));
        }

        $id_tamu = session()->get('id_tamu');
        
        // Menggunakan model yang sudah diinisialisasi di constructor
        $userData = $this->tamuModel->find($id_tamu);

        if (!$userData) {
            session()->destroy();
            return redirect()->to(base_url('login'))->with('error', 'Data pengguna tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Profil',
            'tamu'  => $userData // Menggunakan 'tamu' agar konsisten dengan view Anda
        ];

        log_message('debug', 'TamuController: Loading tamu_edit_profil view.');
        // Ini akan memuat view app/Views/tamu_edit_profil.php
        return view('tamu_edit_profil', $data);
    }

    /**
     * Memproses data dari form edit profil dan mengupdate ke database.
     */
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
        } else {
            $this->tamuModel->validationRules['password'] = 'permit_empty|min_length[6]';
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
        // Cek apakah user sudah login
        if (!session()->get('logged_in') || session()->get('role') !== 'tamu') {
            return redirect()->to(base_url('login'));
        }

        // Ambil data dari query string
        $idUnitKamar = $this->request->getGet('id_unit_kamar');
        $tglMasuk = $this->request->getGet('tgl_masuk');
        $tglKeluar = $this->request->getGet('tgl_keluar');
        $jumlahTamu = $this->request->getGet('jumlah_tamu');
        $hargaKamar = $this->request->getGet('harga_kamar');

        // Validasi data yang diperlukan
        if (!$idUnitKamar || !$tglMasuk || !$tglKeluar || !$jumlahTamu) {
            session()->setFlashdata('error', 'Data reservasi tidak lengkap.');
            return redirect()->to(base_url('tamu/dashboard'));
        }

        // Ambil data tamu dari session
        $idTamu = session()->get('id_tamu');
        
        // Ambil detail tamu dari database
        $tamuModel = new \App\Models\TamuModel();
        $dataTamu = $tamuModel->find($idTamu);

        // Ambil detail kamar
        $unitKamarModel = new \App\Models\UnitKamarModel();
        $kamarModel = new \App\Models\KamarModel();
        
        $unitKamar = $unitKamarModel->find($idUnitKamar);
        $kamar = $kamarModel->find($unitKamar['id_kamar']);

        // Hitung total harga
        $checkin = new DateTime($tglMasuk);
        $checkout = new DateTime($tglKeluar);
        $interval = $checkin->diff($checkout);
        $jumlahHari = $interval->days;
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
        // Cek apakah user sudah login
        if (!session()->get('logged_in') || session()->get('role') !== 'tamu') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }

        $json = $this->request->getJSON(true);
        
        $idTamu = session()->get('id_tamu');
        $idUnitKamar = $json['id_unit_kamar'];
        $tglMasuk = $json['tgl_masuk'];
        $tglKeluar = $json['tgl_keluar'];
        $totalHarga = $json['total_harga'];
        $namaTamu = $json['nama_tamu'];
        $noHpTamu = $json['no_hp_tamu'];

        // Update data tamu jika ada perubahan
        if ($namaTamu || $noHpTamu) {
            $tamuModel = new \App\Models\TamuModel();
            $updateData = [];
            
            if ($namaTamu) $updateData['nama'] = $namaTamu;
            if ($noHpTamu) $updateData['no_hp'] = $noHpTamu;
            
            if (!empty($updateData)) {
                $tamuModel->update($idTamu, $updateData);
            }
        }

        try {
            // Buat reservasi
            $reservasiModel = new \App\Models\ReservasiModel();
            
            $reservasiData = [
                'id_tamu' => $idTamu,
                'id_unit_kamar' => $idUnitKamar,
                'tgl_reservasi' => date('Y-m-d H:i:s'),
                'tgl_masuk' => $tglMasuk,
                'tgl_keluar' => $tglKeluar,
                'total_harga' => $totalHarga,
                'status_reservasi' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $idReservasi = $reservasiModel->insert($reservasiData);

            if ($idReservasi) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Reservasi berhasil dibuat',
                    'id_reservasi' => $idReservasi
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal membuat reservasi'
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

}
