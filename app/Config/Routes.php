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
// Rute-rute ini dapat diakses oleh siapa saja.
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::processRegister');
$routes->get('logout', 'AuthController::logout');


// == GRUP RUTE YANG MEMERLUKAN LOGIN ==
// Semua rute di dalam grup ini akan dilindungi oleh filter 'auth'
// yang memastikan pengguna sudah login.
$routes->group('', ['filter' => 'auth'], function($routes) {

    // == RUTE GRUP ADMIN ==
    // Rute-rute ini memerlukan login DAN peran sebagai 'admin' (filter 'admin').
    $routes->group('admin', ['filter' => 'admin'], function($routes) {

        // Rute Halaman Utama Admin
        $routes->get('dashboard', 'AdminController::index');
        $routes->get('checkin', 'AdminController::checkinPage');
        $routes->get('history', 'AdminController::history');

        // Rute untuk Aksi Reservasi
        $routes->get('reservasi/check-in/(:num)', 'AdminController::checkIn/$1');
        $routes->get('reservasi/selesaikan/(:num)', 'AdminController::selesaikanReservasi/$1');

        // Rute CRUD untuk Manajemen Kamar
        // Menggunakan 'resource' untuk membuat rute CRUD (create, read, update, delete) secara otomatis.
        $routes->resource('kamar', ['controller' => 'KamarController']);
        
        // Rute CRUD untuk Manajemen Unit Kamar
        $routes->get('unit-kamar', 'UnitKamarController::index');
        $routes->post('unit-kamar/store', 'UnitKamarController::store');
        $routes->get('unit-kamar/delete/(:num)', 'UnitKamarController::delete/$1'); // Sebaiknya POST/DELETE
    });

    // == RUTE GRUP TAMU ==
    // Rute-rute ini memerlukan login DAN peran sebagai 'tamu' (filter 'tamu').
    $routes->group('tamu', ['filter' => 'tamu'], function($routes) {
        $routes->get('dashboard', 'TamuController::index');
        $routes->get('riwayat-reservasi', 'TamuController::riwayatReservasi');
        
        // Rute untuk proses reservasi (API/AJAX)
        $routes->post('check-room-availability', 'TamuController::checkRoomAvailability');
        $routes->post('create-reservation', 'TamuController::createReservation');
        
        // Rute untuk profil tamu (menampilkan form dan memproses update)
        // Ini adalah cara yang lebih rapi dan konsisten.
        $routes->get('profil', 'TamuController::editProfil');
        $routes->post('profil', 'TamuController::updateProfil');
    });
});
