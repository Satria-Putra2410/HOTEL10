<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TamuController extends BaseController
{
    public function index()
    {
        // Tampilkan view dashboard tamu yang sudah Anda buat
        return view('tamu_dashboard');
    }
}