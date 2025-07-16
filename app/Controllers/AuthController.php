<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\TamuModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('login'); // Pastikan nama view Anda login.php
    }

    public function processLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $hashedPassword = md5($password);

        $adminModel = new AdminModel();
        $tamuModel = new TamuModel();

        // Cek di tabel admin
        $admin = $adminModel->where('email', $email)->first();
        if ($admin) {
            if ($hashedPassword === $admin['password']) {
                // PERUBAHAN: Mengambil 'nama_admin' dari DB, tapi disimpan di session sebagai 'nama'
                $sessionData = [
                    'id'        => $admin['id_admin'],
                    'nama'      => $admin['nama_admin'], // Diubah
                    'email'     => $admin['email'],
                    'isLoggedIn'=> true,
                    'role'      => 'admin'
                ];
                session()->set($sessionData);
                return redirect()->to('/admin/dashboard');
            }
        }

        // Cek di tabel tamu
        $tamu = $tamuModel->where('email', $email)->first();
        if ($tamu) {
            if ($hashedPassword === $tamu['password']) {
                // PERUBAHAN: Mengambil 'nama_tamu' dari DB, tapi disimpan di session sebagai 'nama'
                $sessionData = [
                    'id'        => $tamu['id_tamu'],
                    'nama'      => $tamu['nama_tamu'], // Diubah
                    'email'     => $tamu['email'],
                    'isLoggedIn'=> true,
                    'role'      => 'tamu'
                ];
                session()->set($sessionData);
                return redirect()->to('/tamu_dashboard');
            }
        }

        session()->setFlashdata('error', 'Email atau password yang anda masukkan salah');
        return redirect()->back();
    }

    public function register()
    {
        return view('register');
    }

    public function processRegister()
    {
        // PERUBAHAN: Aturan validasi disesuaikan dengan nama input form baru
        $rules = [
            'nama_tamu' => 'required|alpha_space|min_length[3]',
            'no_hp_tamu' => 'required|numeric|min_length[10]|max_length[15]',
            'email' => 'required|valid_email|is_unique[tamu.email]|is_unique[admin.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tamuModel = new TamuModel();
        
        // PERUBAHAN: Array data disesuaikan dengan kolom dan input form baru
        $data = [
            'nama_tamu'     => $this->request->getPost('nama_tamu'),
            'no_hp_tamu'   => $this->request->getPost('no_hp_tamu'),
            'email'    => $this->request->getPost('email'),
            'password' => md5($this->request->getPost('password'))
        ];

        $tamuModel->save($data);

        session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
        return redirect()->to('/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
