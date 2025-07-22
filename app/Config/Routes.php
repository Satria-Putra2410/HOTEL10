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
$routes->get('riwayat-reservasi', 'home::riwayat');

// Grup Rute yang Memerlukan Login
$routes->group('', ['filter' => 'auth'], function($routes) {

    // Grup Rute Admin (Memerlukan peran 'admin')
    $routes->group('admin', ['filter' => 'admin'], function($routes) {

        // Dashboard Admin
        $routes->get('dashboard', 'AdminController::index');
        $routes->get('checkin', 'AdminController::checkinPage');
        $routes->get('history', 'AdminController::history');

        // Aksi Reservasi
        $routes->get('reservasi/checkin/(:num)', 'AdminController::checkIn/$1');
        $routes->get('reservasi/selesaikan/(:num)', 'AdminController::selesaikanReservasi/$1');

        // Manajemen Kamar (Rute spesifik harus di atas resource)
        $routes->get('kamar/create', 'KamarController::create');
        $routes->post('kamar/store', 'KamarController::store');
        $routes->get('kamar/edit/(:num)', 'KamarController::edit/$1');
        $routes->post('kamar/update/(:num)', 'KamarController::update/$1');
        $routes->get('kamar/delete/(:num)', 'KamarController::delete/$1');

        // Resource Kamar (untuk index dan show)
        $routes->resource('kamar', ['controller' => 'KamarController', 'except' => ['new', 'create', 'edit', 'update', 'delete']]);
        
        // Manajemen Unit Kamar
        $routes->get('unit-kamar', 'UnitKamarController::index');
        $routes->post('unit-kamar/store', 'UnitKamarController::store');
        $routes->get('unit-kamar/delete/(:num)', 'UnitKamarController::delete/$1');
    });

    // Grup Rute Tamu (Memerlukan peran 'tamu')
    $routes->group('tamu', ['filter' => 'tamu'], function($routes) {
        $routes->get('dashboard', 'TamuController::index');
        $routes->get('riwayat-reservasi', 'TamuController::riwayatReservasi');
        
        // Proses Reservasi (API/AJAX)
        $routes->post('check-room-availability', 'TamuController::checkRoomAvailability');
        $routes->post('create-reservation', 'TamuController::createReservation');
        
        // Profil Tamu
        $routes->get('profil', 'TamuController::editProfil');
        $routes->post('profil', 'TamuController::updateProfil');
    });

        // Route untuk halaman form reservasi
        $routes->get('tamu/reservasi-form', 'TamuController::reservasi_form');

        // Route untuk memproses reservasi
        $routes->post('tamu/proses-reservasi', 'TamuController::proses_reservasi');

});
