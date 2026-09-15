<?php

use CodeIgniter\Router\RouteCollection;
use Config\Filters;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// DASHBOARD UTAMA — Redirect otomatis sesuai role
// Sekpel -> SekpelController::dashboard, Sekum -> SekumController::dashboard
// ============================================================
$routes->get('/', 'Admin::index', ['filter' => 'profile_completion']);

// ============================================================
// SEKPEL — Manajemen Surat Kepanitiaan
// ============================================================
$routes->group('sekpel', ['filter' => ['role:Sekpel,sekpel', 'profile_completion']], function ($routes) {
    $routes->get('/', 'SekpelController::dashboard');
    
    // Surat
    $routes->get('surat/create', 'SekpelController::createSurat');
    $routes->post('surat/store', 'SekpelController::storeSurat');
    $routes->post('surat/preview', 'SekpelController::previewHtml');
    $routes->get('surat/edit/(:segment)', 'SekpelController::editSurat/$1');
    $routes->post('surat/update/(:segment)', 'SekpelController::updateSurat/$1');
    $routes->post('surat/submit/(:segment)', 'SekpelController::submitSurat/$1');
    $routes->get('surat/print/(:segment)', 'SekpelController::printSurat/$1');
    $routes->get('surat/download/(:segment)', 'SekpelController::downloadPdf/$1');
    $routes->post('surat/upload-arsip', 'SekpelController::uploadArsipSurat');

    // Profil & Biodata
    $routes->get('profil/edit', 'ProfileController::edit');
    $routes->post('profil/update', 'ProfileController::updateEdit');
});

// Lengkapi Biodata (Pertama Kali) — dipisah agar tidak kena loop filter ProfileCompletion
$routes->group('sekpel/profil', ['filter' => 'role:Sekpel,sekpel'], function ($routes) {
    $routes->get('lengkapi', 'ProfileController::index');
    $routes->post('simpan', 'ProfileController::update');
});

// ============================================================
// SEKUM — Review, Approval & Administrasi Sistem
// ============================================================
$routes->group('sekum', ['filter' => ['role:Sekum,sekum', 'profile_completion']], function ($routes) {
    $routes->get('/', 'SekumController::dashboard');
    
    // User Management (dipindahkan dari Admin.php ke SekumController)
    $routes->get('users', 'SekumController::userList');
    $routes->get('users/create', 'SekumController::addUser');
    $routes->post('users/store', 'SekumController::register');
    $routes->get('users/edit/(:num)', 'SekumController::editUser/$1');
    $routes->post('users/update/(:num)', 'SekumController::updateUser/$1');
    $routes->delete('users/delete/(:num)', 'SekumController::deleteUser/$1');

    // Review Surat
    $routes->get('surat/review/(:segment)', 'SekumController::review/$1');
    $routes->get('surat/print/(:segment)', 'SekumController::printSurat/$1');
    $routes->post('surat/approve/(:segment)', 'SekumController::approve/$1');
    $routes->post('surat/reject/(:segment)', 'SekumController::reject/$1');

    // Laci Kepanitiaan
    $routes->get('kepanitiaan/(:segment)', 'SekumController::detailProker/$1');

    // Pengaturan Sistem
    $routes->get('pengaturan', 'SekumController::identitas');
    $routes->post('pengaturan/kop', 'SekumController::updateKop');
    $routes->post('pengaturan/pejabat', 'SekumController::updatePejabat');
    $routes->post('pengaturan/upload-ttd', 'SekumController::uploadTtd');

    // Demisioner & Manajemen Kepengurusan
    $routes->post('demisioner', 'SekumController::demisionerKepengurusan');
    $routes->post('penerus/sekum', 'SekumController::buatSekumPenerus');

    // Log Aktivitas
    $routes->get('aktivitas', 'SekumController::aktivitas');
});

// Lengkapi Biodata Sekum Baru — dipisah agar tidak kena loop filter ProfileCompletion
$routes->group('sekum/profil', ['filter' => 'role:Sekum,sekum'], function ($routes) {
    $routes->get('lengkapi', 'SekumProfileController::index');
    $routes->post('simpan', 'SekumProfileController::simpan');
});

// ============================================================
// NOTIFIKASI — API endpoint (Sekum & Sekpel)
// ============================================================
$routes->group('notifikasi', ['filter' => ['login', 'profile_completion']], function ($routes) {
    $routes->get('list', 'NotificationController::list');
    $routes->post('baca/(:num)', 'NotificationController::markRead/$1');
    $routes->post('baca-semua', 'NotificationController::markAllRead');
});
