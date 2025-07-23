<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReservasiModel;
use App\Models\KamarModel;
use App\Models\TamuModel;
use CodeIgniter\API\ResponseTrait;
use DateTime;

class TamuController extends BaseController
{
    use ResponseTrait;

    protected $reservasiModel;
    protected $kamarModel;
    protected $tamuModel;

    public function __construct()
    {
        $this->reservasiModel = new ReservasiModel();
        $this->kamarModel = new KamarModel();
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
        $reservedKamarIds = $db->table('reservasi r')
            ->select('drk.id_kamar')
            ->join('detail_reservasi_kamar drk', 'drk.id_reservasi = r.id_reservasi')
            ->where('r.status !=', 'selesai')
            ->groupStart()
                ->where('r.tgl_masuk <', $tgl_keluar)
                ->where('r.tgl_keluar >', $tgl_masuk)
            ->groupEnd()
            ->get()->getResultArray();

        $reservedIds = array_column($reservedKamarIds, 'id_kamar');

        $availableRooms = $this->kamarModel->where('jumlah_tamu >=', $jumlah_tamu);
            
        if (!empty($reservedIds)) {
            $availableRooms->whereNotIn('id_kamar', $reservedIds);
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
        $id_kamar = $input['id_kamar'] ?? null;
        $tgl_masuk = $input['tgl_masuk'] ?? null;
        $tgl_keluar = $input['tgl_keluar'] ?? null;
        
        // PERBAIKAN KUNCI: Ambil harga dari database, BUKAN dari input.
        $room = $this->kamarModel->find($id_kamar);
        if (!$room) {
            return $this->failNotFound('Kamar tidak ditemukan.');
        }
        // Harga yang valid dan aman diambil dari sini.
        $harga_kamar = $room['harga_kamar'];

        // Validasi bahwa semua data yang diperlukan ada.
        if (!$id_kamar || !$tgl_masuk || !$tgl_keluar || !$harga_kamar) {
            return $this->failValidationError('Data pemesanan tidak lengkap.');
        }

        $db = \Config\Database::connect();
        $isBooked = $db->table('reservasi r')
            ->join('detail_reservasi_kamar drk', 'drk.id_reservasi = r.id_reservasi')
            ->where('drk.id_kamar', $id_kamar) 
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

        // Total harga dihitung menggunakan harga dari database.
        $total_harga_reservasi = $harga_kamar * $numberOfNights;

        $reservasiData = [
            'tgl_masuk'             => $tgl_masuk,
            'tgl_keluar'            => $tgl_keluar,
            'total_harga_reservasi' => $total_harga_reservasi,
            'id_tamu'               => $id_tamu,
            'status'                => 'Reserved' 
        ];

        // Menggunakan metode transaksi otomatis dari CodeIgniter 4.
        $db->transStart();

        // 1. Insert ke tabel 'reservasi'
        $db->table('reservasi')->insert($reservasiData);
        $id_reservasi = $db->insertID();

        // 2. Insert ke tabel 'detail_reservasi_kamar'
        $detailReservasiKamarData = [
            'jumlah_malam_kamar' => $numberOfNights,
            'id_reservasi'       => $id_reservasi,
            'id_kamar'           => $id_kamar
        ];
        $db->table('detail_reservasi_kamar')->insert($detailReservasiKamarData);

        // Menyelesaikan transaksi. CodeIgniter akan otomatis commit jika berhasil,
        // atau rollback jika ada kesalahan.
        $db->transComplete();

        if ($db->transStatus() === false) {
            // Jika transaksi gagal, catat error dan kirim respons error.
            log_message('error', '[TamuController] Create Reservation Transaction Failed.');
            return $this->failServerError('Terjadi kesalahan saat memproses pemesanan. Transaksi database gagal.');
        } else {
            // Jika transaksi berhasil.
            return $this->respondCreated(['status' => 'success', 'message' => 'Pemesanan berhasil!', 'id_reservasi' => $id_reservasi]);
        }
    }
    
    /**
     * Menampilkan halaman Riwayat Reservasi untuk Tamu.
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
            $data = $this->reservasiModel
                         ->select('reservasi.*, kamar.tipe_kamar, kamar.nomor_kamar')
                         ->join('detail_reservasi_kamar', 'detail_reservasi_kamar.id_reservasi = reservasi.id_reservasi', 'left')
                         ->join('kamar', 'kamar.id_kamar = detail_reservasi_kamar.id_kamar', 'left')
                         ->where('reservasi.id_tamu', $id_tamu)
                         ->orderBy('reservasi.tgl_masuk', 'DESC')
                         ->findAll();

            return $this->respond($data);
        } catch (\Exception $e) {
            log_message('error', '[TamuController] Get API Riwayat Error: ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan pada server: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman edit profil tamu.
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
            'tamu'  => $userData,
            'validation' => \Config\Services::validation()
        ];
        return view('tamu_edit_profil', $data);
    }

    /**
     * Memproses update profil tamu.
     */
    public function updateProfil()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return redirect()->to(base_url('login'));
        }
        $id_tamu = session()->get('id_tamu');
        
        $rules = [
            'nama_tamu'  => 'required|alpha_space|min_length[3]',
            'no_hp_tamu' => 'required|numeric|min_length[10]|max_length[15]',
            'email'      => "required|valid_email|is_unique[tamu.email,id_tamu,{$id_tamu}]|is_unique[admin.email]",
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'required|min_length[8]';
            $rules['password_confirm'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $dataToUpdate = [
            'nama_tamu'  => $this->request->getPost('nama_tamu'),
            'no_hp_tamu' => $this->request->getPost('no_hp_tamu'),
            'email'      => $this->request->getPost('email'),
        ];
        
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $dataToUpdate['password'] = md5($password);
        }
        
        if ($this->tamuModel->update($id_tamu, $dataToUpdate) === false) {
            return redirect()->back()->withInput()->with('errors', $this->tamuModel->errors());
        }
        
        session()->set([
            'nama' => $dataToUpdate['nama_tamu'],
            'email' => $dataToUpdate['email'],
        ]);
        
        return redirect()->to(base_url('tamu/dashboard'))->with('success', 'Profil berhasil diperbarui.');
    }
}
