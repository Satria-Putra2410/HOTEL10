<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute Halaman Statis & Utama
$routes->get('/', 'Home::index');
$routes->get('ciwidey', 'Home::ciwidey');
$routes->get('farmhouse', 'Home::farmhouse');
$routes->get('tangkuban', 'Home::tangkuban');
$routes->get('cukul', 'Home::cukul');
$routes->get('tsm', 'Home::tsm');
$routes->get('museum', 'Home::museum');
$routes->get('floating', 'Home::floating');

// Rute Otentikasi (Publik)
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::processRegister');
$routes->get('logout', 'AuthController::logout');

// HAPUS RUTE NOTIFIKASI MIDTRANS
// $routes->post('midtrans/notification', 'TamuController::handleNotification');

// Grup Rute yang Memerlukan Login
$routes->group('', ['filter' => 'auth'], function($routes) {

    // Grup Rute Admin (Memerlukan peran 'admin')
    $routes->group('admin', ['filter' => 'admin'], function($routes) {
        $routes->get('dashboard', 'AdminController::index');
        $routes->get('checkin', 'AdminController::checkinPage');
        $routes->get('history', 'AdminController::history');
        $routes->get('reservasi/checkin/(:num)', 'AdminController::checkIn/$1');
        $routes->get('reservasi/selesaikan/(:num)', 'AdminController::selesaikanReservasi/$1');

        // Manajemen Kamar
        $routes->get('kamar', 'KamarController::index');
        $routes->get('kamar/create', 'KamarController::create');
        $routes->post('kamar/store', 'KamarController::store');
        $routes->get('kamar/edit/(:num)', 'KamarController::edit/$1');
        $routes->post('kamar/update/(:num)', 'KamarController::update/$1');
        $routes->get('kamar/delete/(:num)', 'KamarController::delete/$1');
    });

    // Grup Rute Tamu (Memerlukan peran 'tamu')
    $routes->group('tamu', ['filter' => 'tamu'], function($routes) {
        $routes->get('dashboard', 'TamuController::index');
        
        // Riwayat Reservasi
        $routes->get('riwayat-reservasi', 'TamuController::riwayatReservasi');
        $routes->get('api/riwayat', 'TamuController::getApiRiwayat');
        
        // Proses Reservasi & Pembayaran (API/AJAX)
        $routes->post('check-room-availability', 'TamuController::checkRoomAvailability');
        $routes->post('initiate-payment', 'TamuController::initiatePayment');
        
        // TAMBAHKAN RUTE BARU UNTUK HALAMAN FINISH PEMBAYARAN
        $routes->get('payment-finish', 'TamuController::paymentFinish');

        // Profil Tamu
        $routes->get('profil', 'TamuController::editProfil');
        $routes->post('profil', 'TamuController::updateProfil');
    });
});
