<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('login'); // Menampilkan form login
    }

    public function doLogin()
    {
        $email = $this->request->getPost('email');
        $password = md5($this->request->getPost('password'));

        $userModel = new UserModel();

        $user = $userModel->where('email', $email)
            ->where('password', $password)
            ->first();

        if ($user) {
            // Simpan session
            session()->set([
                'email' => $user['email'],
                'role' => $user['role'],
                'logged_in' => true
            ]);

            // Redirect berdasarkan role
            if ($user['role'] == 'admin') {
                return view('homeadmin');
            } elseif ($user['role'] == 'tamu') {
                return view('hometamu');
            } else {
                return "Role tidak dikenal!";
            }
        } else {
            return redirect()->to(base_url('hotel10/public/login'))
                ->with('error', 'Email atau password salah!');
        }
    }

    public function register()
    {
        return view('register'); // Tampilkan form register
    }

    public function doRegister()
    {
        $email = $this->request->getPost('email');
        $password = md5($this->request->getPost('password'));
        $nama = $this->request->getPost('nama');

        $userModel = new UserModel();

        $userModel->save([
            'email' => $email,
            'password' => $password,
            'nama' => $nama,
            'role' => 'tamu' // default tamu
        ]);

        return redirect()->to(base_url('login'))->with('success', 'Registrasi berhasil, silakan login!');
    }
}
