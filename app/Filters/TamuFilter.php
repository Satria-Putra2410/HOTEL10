<?php 

namespace App\Filters; 

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TamuFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Log untuk melacak kapan filter ini dipicu dan untuk URI apa
        log_message('debug', 'TamuFilter: Metode before dipicu untuk URI: ' . current_url());
        // Log untuk menampilkan peran (role) yang saat ini ada di sesi
        log_message('debug', 'TamuFilter: Peran sesi saat ini: ' . session()->get('role'));
        // Log untuk menampilkan status login (isLoggedIn) dari sesi
        log_message('debug', 'TamuFilter: Status login: ' . (session()->get('isLoggedIn') ? 'true' : 'false'));

        // Memeriksa apakah peran (role) dalam sesi BUKAN 'tamu'
        if (session()->get('role') !== 'tamu') {
            // Jika peran bukan 'tamu', catat dalam log bahwa pengalihan akan dilakukan
            log_message('debug', 'TamuFilter: Mengalihkan ke halaman login karena peran bukan tamu.');
            // Mengalihkan pengguna ke halaman login dengan pesan error
            return redirect()->to('/login')->with('error', 'Akses terbatas untuk tamu.');
        }
        // Jika peran adalah 'tamu', catat dalam log bahwa permintaan akan dilanjutkan
        log_message('debug', 'TamuFilter: Pengguna adalah tamu. Melanjutkan permintaan.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu ada aksi setelah permintaan selesai untuk filter ini
    }
}
