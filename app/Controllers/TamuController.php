<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\TamuModel;
use CodeIgniter\API\ResponseTrait;
use DateTime;

class TamuController extends BaseController
{
    use ResponseTrait;

    protected $kamarModel;
    protected $tamuModel;

    public function __construct()
    {
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
            ->where('r.status_pembayaran', 'settlement') // Hanya kamar yang lunas yang dianggap terisi
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
     * Menginisiasi pembayaran dan membuat transaksi di Midtrans.
     */
    public function initiatePayment()
    {
        // Muat library Midtrans secara manual
        require_once APPPATH . 'ThirdParty/midtrans-php/Midtrans.php';

        // PERBAIKAN: Kunci API ditetapkan langsung di dalam kode (hardcoded)
        \Midtrans\Config::$serverKey = "Mid-server-BZO_n2kDjkVcb_mFioJdRpyH";
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

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
        
        $room = $this->kamarModel->find($id_kamar);
        $tamu = $this->tamuModel->find($id_tamu);

        if (!$room || !$tamu) {
            return $this->failNotFound('Data kamar atau tamu tidak ditemukan.');
        }
        
        $harga_kamar = $room['harga_kamar'];
        if (!$id_kamar || !$tgl_masuk || !$tgl_keluar) {
            return $this->failValidationError('Data pemesanan tidak lengkap.');
        }

        $checkin = new DateTime($tgl_masuk);
        $checkout = new DateTime($tgl_keluar);
        $numberOfNights = $checkout->diff($checkin)->days;
        $total_harga_reservasi = $harga_kamar * $numberOfNights;

        $order_id = 'HOTEL10-' . time() . '-' . $id_kamar;

        $db = \Config\Database::connect();
        $db->transStart();

        $reservasiData = [
            'tgl_masuk'             => $tgl_masuk,
            'tgl_keluar'            => $tgl_keluar,
            'total_harga_reservasi' => $total_harga_reservasi,
            'id_tamu'               => $id_tamu,
            'status'                => 'Dibatalkan', // Status awal adalah Dibatalkan
            'status_pembayaran'     => 'pending',
            'midtrans_order_id'     => $order_id,
        ];
        $db->table('reservasi')->insert($reservasiData);
        $id_reservasi = $db->insertID();

        $detailReservasiKamarData = [
            'jumlah_malam_kamar' => $numberOfNights,
            'id_reservasi'       => $id_reservasi,
            'id_kamar'           => $id_kamar
        ];
        $db->table('detail_reservasi_kamar')->insert($detailReservasiKamarData);
        
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $total_harga_reservasi,
            ],
            'item_details' => [[
                'id' => $id_kamar,
                'price' => $harga_kamar,
                'quantity' => $numberOfNights,
                'name' => 'Menginap di ' . $room['tipe_kamar']
            ]],
            'customer_details' => [
                'first_name' => $tamu['nama_tamu'],
                'email' => $tamu['email'],
                'phone' => $tamu['no_hp_tamu'],
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $db->table('reservasi')->where('id_reservasi', $id_reservasi)->update(['snap_token' => $snapToken]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            log_message('error', 'Gagal membuat transaksi untuk Order ID: ' . $order_id);
            return $this->failServerError('Gagal memproses transaksi.');
        }

        return $this->respond(['status' => 'success', 'snap_token' => $snapToken]);
    }

    /**
     * Halaman tujuan setelah pembayaran, untuk memeriksa status secara manual.
     */
    public function paymentFinish()
    {
        // Muat library dan atur kunci
        require_once APPPATH . 'ThirdParty/midtrans-php/Midtrans.php';
        \Midtrans\Config::$serverKey = "Mid-server-BZO_n2kDjkVcb_mFioJdRpyH";
        \Midtrans\Config::$isProduction = false;

        // Ambil order_id dari URL
        $order_id = $this->request->getGet('order_id');
        if (!$order_id) {
            return redirect()->to('tamu/riwayat-reservasi')->with('error', 'Order ID tidak ditemukan.');
        }

        try {
            // Minta status transaksi ke Midtrans
            $status = \Midtrans\Transaction::status($order_id);

            $db = \Config\Database::connect();
            $transaction_status = $status->transaction_status;
            $fraud_status = $status->fraud_status ?? null;

            $updateData = [];

            // Logika update status reservasi DAN pembayaran
            if (($transaction_status == 'capture' && $fraud_status == 'accept') || $transaction_status == 'settlement') {
                $updateData['status_pembayaran'] = 'settlement';
                $updateData['status'] = 'Reserved'; // Pembayaran berhasil, status menjadi Reserved
            } else if ($transaction_status == 'pending') {
                $updateData['status_pembayaran'] = 'pending';
                $updateData['status'] = 'Dibatalkan'; // Tetap dibatalkan selagi pending
            } else if ($transaction_status == 'expire' || $transaction_status == 'cancel' || $transaction_status == 'deny') {
                $updateData['status_pembayaran'] = $transaction_status;
                $updateData['status'] = 'Dibatalkan'; // Pembayaran gagal, status tetap Dibatalkan
            }

            // Update database jika ada perubahan status
            if (!empty($updateData)) {
                $db->table('reservasi')->where('midtrans_order_id', $order_id)->update($updateData);
            }

            return redirect()->to('tamu/riwayat-reservasi')->with('success', 'Status pembayaran telah diperbarui.');

        } catch (\Exception $e) {
            log_message('error', 'Midtrans status check error: ' . $e->getMessage());
            return redirect()->to('tamu/riwayat-reservasi')->with('error', 'Gagal memeriksa status pembayaran: ' . $e->getMessage());
        }
    }
    
    public function riwayatReservasi()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return redirect()->to(base_url('login'));
        }
        $data['title'] = 'Riwayat Reservasi Saya';
        return view('riwayat-reservasi', $data);
    }

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
            $db = \Config\Database::connect();
            $data = $db->table('reservasi')
                         ->select('reservasi.*, kamar.tipe_kamar, kamar.nomor_kamar')
                         ->join('detail_reservasi_kamar', 'detail_reservasi_kamar.id_reservasi = reservasi.id_reservasi', 'left')
                         ->join('kamar', 'kamar.id_kamar = detail_reservasi_kamar.id_kamar', 'left')
                         ->where('reservasi.id_tamu', $id_tamu)
                         ->orderBy('reservasi.id_reservasi', 'DESC') // Urutkan berdasarkan ID terbaru
                         ->get()->getResultArray();

            return $this->respond($data);
        } catch (\Exception $e) {
            log_message('error', '[TamuController] Get API Riwayat Error: ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan pada server: ' . $e->getMessage());
        }
    }

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
