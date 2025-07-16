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


// == RUTE UNTUK TAMU (PERLU LOGIN) ==
$routes->get('tamu_dashboard', 'TamuController::index', ['filter' => 'auth']);


// == RUTE GRUP ADMIN ==
// Anda bisa mengaktifkan kembali filter dengan menambahkan ['filter' => ['auth', 'admin']]
$routes->group('admin', function($routes) {

    // Rute Halaman Utama Admin
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('checkin', 'AdminController::checkinPage');
    $routes->get('history', 'AdminController::history');

    // Rute untuk Aksi Reservasi
    $routes->get('reservasi/checkin/(:num)', 'AdminController::checkIn/$1');
    $routes->get('reservasi/selesaikan/(:num)', 'AdminController::selesaikanReservasi/$1');

    // Rute CRUD untuk Manajemen Kamar
    $routes->get('kamar', 'KamarController::index');
    $routes->get('kamar/create', 'KamarController::create');
    $routes->post('kamar/store', 'KamarController::store');
    $routes->get('kamar/edit/(:num)', 'KamarController::edit/$1');
    $routes->post('kamar/update/(:num)', 'KamarController::update/$1');
    $routes->get('kamar/delete/(:num)', 'KamarController::delete/$1');
    
});
