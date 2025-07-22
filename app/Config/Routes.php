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

// Grup Rute yang Memerlukan Login
$routes->group('', ['filter' => 'auth'], function($routes) {

    // Grup Rute Admin (Memerlukan peran 'admin')
    $routes->group('admin', ['filter' => 'admin'], function($routes) {

        $routes->get('dashboard', 'AdminController::index');
        $routes->get('checkin', 'AdminController::checkinPage');
        $routes->get('history', 'AdminController::history');
        $routes->get('reservasi/checkin/(:num)', 'AdminController::checkIn/$1');
        $routes->get('reservasi/selesaikan/(:num)', 'AdminController::selesaikanReservasi/$1');

        // PERBAIKAN: Menambahkan rute spesifik untuk create dan store SEBELUM resource
        $routes->get('kamar/create', 'KamarController::create');
        $routes->post('kamar/store', 'KamarController::store');
        $routes->get('kamar/edit/(:num)', 'KamarController::edit/$1');
        $routes->post('kamar/update/(:num)', 'KamarController::update/$1');
        $routes->get('kamar/(:num)/delete', 'KamarController::delete/$1');
        
        // PERBAIKAN: Menghapus semua metode yang sudah didefinisikan secara manual dari resource
        $routes->resource('kamar', [
            'controller' => 'KamarController', 
            'except' => ['new', 'create', 'edit', 'update', 'delete']
        ]);
        
        $routes->get('unit-kamar', 'UnitKamarController::index');
        $routes->post('unit-kamar/store', 'UnitKamarController::store');
        $routes->get('unit-kamar/delete/(:num)', 'UnitKamarController::delete/$1');
    });

    // Grup Rute Tamu (Memerlukan peran 'tamu')
    $routes->group('tamu', ['filter' => 'tamu'], function($routes) {
        $routes->get('dashboard', 'TamuController::index');
        
        // --- RUTE UNTUK RIWAYAT RESERVASI ---
        $routes->get('riwayat-reservasi', 'TamuController::riwayatReservasi');
        $routes->get('api/riwayat', 'TamuController::getApiRiwayat');
        
        // Proses Reservasi (API/AJAX)
        $routes->post('check-room-availability', 'TamuController::checkRoomAvailability');
        $routes->post('create-reservation', 'TamuController::createReservation');
        
        // Profil Tamu
        $routes->get('profil', 'TamuController::editProfil');
        $routes->post('profil', 'TamuController::updateProfil');

        // Form dan Proses Reservasi (Non-AJAX)
        $routes->get('reservasi-form', 'TamuController::reservasi_form');
        $routes->post('proses-reservasi', 'TamuController::proses_reservasi');
    });
});
