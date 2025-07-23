<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel; 
use App\Models\TamuModel; 

class AuthController extends BaseController
{
    protected $tamuModel;
    protected $adminModel;

    public function __construct()
    {
        $this->tamuModel = new TamuModel();
        $this->adminModel = new AdminModel(); 
        helper(['form', 'url', 'session']); 
    }

    public function login()
    {
        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation()
        ];
        return view('login', $data); 
    }

    public function processLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            log_message('debug', 'Login validation failed for email: ' . $this->request->getPost('email'));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = md5($this->request->getPost('password')); // Menggunakan md5() untuk login

        $admin = $this->adminModel->where('email', $email)->first();
        if ($admin) {
            if ($password === $admin['password']) { 
                $sessionData = [
                    'id'         => $admin['id_admin'],
                    'nama'       => $admin['nama_admin'],
                    'email'      => $admin['email'],
                    'isLoggedIn' => true,
                    'role'       => 'admin'
                ];
                session()->set($sessionData);
                log_message('debug', 'Admin login successful for email: ' . $email);
                return redirect()->to('/admin/dashboard');
            }
        }

        $tamu = $this->tamuModel->where('email', $email)->first();
        if ($tamu) {
            if ($password === $tamu['password']) {
                $sessionData = [
                    'id_tamu'    => $tamu['id_tamu'], 
                    'nama'       => $tamu['nama_tamu'],
                    'email'      => $tamu['email'],
                    'isLoggedIn' => true,
                    'role'       => 'tamu'
                ];
                session()->set($sessionData);
                log_message('debug', 'Tamu login successful for email: ' . $email);
                return redirect()->to('/tamu/dashboard')->with('success', 'Login berhasil!'); 
            }
        }

        log_message('debug', 'Login failed for email: ' . $email . '. Invalid credentials.');
        session()->setFlashdata('error', 'Email atau password yang Anda masukkan salah.');
        return redirect()->back()->withInput();
    }

    public function register()
    {
        $data = [
            'title' => 'Register',
            'validation' => \Config\Services::validation()
        ];
        return view('register', $data);
    }

    public function processRegister()
    {
        $rules = [
            'nama_tamu'        => 'required|alpha_space|min_length[3]',
            'no_hp_tamu'       => 'required|numeric|min_length[10]|max_length[15]',
            'email'            => 'required|valid_email|is_unique[tamu.email]|is_unique[admin.email]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            log_message('debug', 'Registration validation failed for email: ' . $this->request->getPost('email'));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_tamu'  => $this->request->getPost('nama_tamu'),
            'no_hp_tamu' => $this->request->getPost('no_hp_tamu'),
            'email'      => $this->request->getPost('email'),
            // DI SINI FUNGSI MD5 DIGUNAKAN SESUAI PERMINTAAN ANDA
            'password'   => md5($this->request->getPost('password')) 
        ];

        if ($this->tamuModel->save($data)) {
            log_message('debug', 'Tamu registration successful for email: ' . $this->request->getPost('email'));
            session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
            return redirect()->to('/login');
        } else {
            log_message('error', 'Tamu registration failed for email: ' . $this->request->getPost('email'));
            session()->setFlashdata('error', 'Registrasi gagal. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        log_message('debug', 'User logged out. Session destroyed.');
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout.');
    }
}
