<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('pages/landing');
    }
    public function dashboard(): string
    {
        return view('pages/dashboard');
    }
    public function login(): string
    {
        return view('pages/login');
    }
    public function register(): string
    {
        return view('pages/register');
    }
    public function floating(): string
    {
        return view('pages/floating');
    }
    public function ciwidey(): string
    {
        return view('pages/ciwidey');
    }
    public function farmhouse(): string
    {
        return view('pages/farmhouse');
    }
    public function tangkuban(): string
    {
        return view('pages/tangkuban');
    }
    public function cukul(): string
    {
        return view('pages/cukul');
    }
    public function tsm(): string
    {
        return view('pages/tsm');
    }
    public function museum(): string
    {
        return view('pages/museum');
    }
    public function riwayat(): string
    {
        return view('riwayat-reservasi');
    }
}
