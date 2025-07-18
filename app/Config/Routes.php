<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// == RUTE HALAMAN STATIS & UTAMA ==
$routes->get('/', 'Home::index');
$routes->get('ciwidey', 'Home::ciwidey');
$routes->get('farmhouse', 'Home::farmhouse');
$routes->get('tangkuban', 'Home::tangkuban');
$routes->get('cukul', 'Home::cukul');
$routes->get('tsm', 'Home::tsm');
$routes->get('museum', 'Home::museum');
$routes->get('floating', 'Home::floating');


// == RUTE OTENTIKASI (PUBLIK) ==
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::processRegister');
$routes->get('logout', 'AuthController::logout');


// Filter untuk pengguna yang sudah login (auth)
// Semua rute di dalam grup ini akan memerlukan filter 'auth'
$routes->group('/', ['filter' => 'auth'], function($routes) {

    // == RUTE GRUP ADMIN ==
    // Rute admin juga akan dilindungi oleh filter 'admin'
    $routes->group('admin', ['filter' => 'admin'], function($routes) {

        // Rute Halaman Utama Admin
        $routes->get('dashboard', 'AdminController::index');
        $routes->get('checkin', 'AdminController::checkinPage');
        $routes->get('history', 'AdminController::history');

        // Rute untuk Aksi Reservasi
        // Dipertahankan sesuai nama yang umum digunakan di CodeIgniter
        $routes->get('reservasi/check-in/(:num)', 'AdminController::checkIn/$1');
        $routes->get('reservasi/selesaikan/(:num)', 'AdminController::selesaikanReservasi/$1');

        // Rute CRUD untuk Manajemen Kamar
        $routes->get('kamar', 'KamarController::index');
        $routes->get('kamar/create', 'KamarController::create');
        $routes->post('kamar/store', 'KamarController::store');
        $routes->get('kamar/edit/(:num)', 'KamarController::edit/$1');
        $routes->post('kamar/update/(:num)', 'KamarController::update/$1');
        $routes->get('kamar/delete/(:num)', 'KamarController::delete/$1');
        
        // Tambahan: Rute CRUD untuk Manajemen Unit Kamar (jika ada)
        $routes->get('unit-kamar', 'UnitKamarController::index');
        $routes->post('unit-kamar/store', 'UnitKamarController::store');
        $routes->post('unit-kamar/delete/(:num)', 'UnitKamarController::delete/$1');
    });

    // == RUTE UNTUK TAMU (PERLU LOGIN) ==
    // Ini adalah perubahan utama untuk rute tamu
    $routes->group('tamu', ['filter' => 'tamu'], function($routes) {
        $routes->get('dashboard', 'TamuController::index'); // Rute dashboard tamu
        $routes->post('check-room-availability', 'TamuController::checkRoomAvailability');
        $routes->post('create-reservation', 'TamuController::createReservation');
        $routes->get('riwayat-reservasi', 'TamuController::riwayatReservasi');
        $routes->get('edit-profil', 'TamuController::editProfil');
        $routes->post('update-profil', 'TamuController::updateProfil');
    });
});

