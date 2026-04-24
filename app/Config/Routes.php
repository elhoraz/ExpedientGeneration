<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==========================================================
// 1. RUTE DEFAULT
// ==========================================================
// Jika buka domain utama, langsung paksa ke halaman login
$routes->get('/', function() {
    return redirect()->to('/login');
});

// ==========================================================
// 2. RUTE OTENTIKASI (LOGIN, REGISTER, VERIFIKASI)
// ==========================================================
// Halaman Login
$routes->get('/login', 'AuthController::index');
$routes->get('/auth/login', 'AuthController::index'); // Alias agar link 'Kembali' berfungsi
$routes->post('/auth/login', 'AuthController::processLogin'); // Proses cek password

// Halaman Register
$routes->get('/auth/register', 'AuthController::registerView'); // Menampilkan view
$routes->post('/auth/register', 'AuthController::register');    // Memproses data submit

// Verifikasi Email & Logout
$routes->get('/auth/verify/(:segment)', 'AuthController::verifyEmail/$1'); 
$routes->get('/logout', 'AuthController::logout');

// ==========================================================
// 3. RUTE DASHBOARD / BERANDA
// ==========================================================
// Filter 'auth' memastikan tidak ada yang bisa buka /beranda kalau belum login
$routes->get('/beranda', 'BerandaController::index', ['filter' => 'auth']);
$routes->get('/direktori', 'DirektoriController::index', ['filter' => 'auth']);
$routes->get('/galeri', 'GaleriController::index', ['filter' => 'auth']);
$routes->get('/fitur', 'FiturController::index', ['filter' => 'auth']);
// ================= FASILITAS: GLOBAL RADAR =================
// Menampilkan halaman Peta 3D
$routes->get('/radar', 'RadarController::index', ['filter' => 'auth']);
$routes->get('/radar/flat', 'RadarController::flatMap', ['filter' => 'auth']);
// Endpoint API (AJAX) untuk menerima dan menyimpan koordinat GPS dari HP User
$routes->post('/radar/update-location', 'RadarController::updateLocation', ['filter' => 'auth']);
// ================= FASILITAS: THE SYNDICATE =================
// Menampilkan galeri kartu VIP
$routes->get('/syndicate', 'SyndicateController::index', ['filter' => 'auth']);

// Menampilkan formulir pendaftaran Black Card (VIP Access)
$routes->get('/syndicate/create', 'SyndicateController::create', ['filter' => 'auth']);

// Endpoint POST untuk menyimpan data formulir ke database
$routes->post('/syndicate/store', 'SyndicateController::store', ['filter' => 'auth']);
// ================= FASILITAS: COMMAND CENTER (PROFIL) =================
// Menampilkan halaman profil dan pengaturan biometrik agen
$routes->get('/profil', 'ProfileController::index', ['filter' => 'auth']);
// ==========================================================
// 4. RUTE BIOMETRIK (SUDAH DISELARASKAN DENGAN CONTROLLER)
// ==========================================================
// Proses Login Biometrik (Dari halaman depan)
$routes->get('/biometric/login-options', 'BiometricController::getLoginOptions');
$routes->post('/biometric/login-verify', 'BiometricController::loginVerify');

// Proses Mendaftarkan Biometrik Baru (Dari dalam dashboard/settings)
$routes->get('/biometric/register-options', 'BiometricController::getRegisterOptions');
$routes->post('/biometric/register-verify', 'BiometricController::registerVerify');
// ====================================================================
// JALUR EKSKLUSIF: THE SOVEREIGN VAULT
// ====================================================================



// 2. Sinyal Rahasia (AJAX) - Jabat tangan antara Face ID dan Server
$routes->post('/fitur/unlock', 'FiturController::unlockVaultSession');

// 3. Panggung Mahakarya: Sovereign ID Card 5D
$routes->get('/sovereign', 'SovereignController::index');

// 4. Ruang Kendali: Profil Entitas & Manajemen Kunci Keamanan
$routes->get('/profil', 'ProfilController::index');

// --- SOVEREIGN VAULT ROUTES ---

// 1. Halaman Registrasi (Form Input Data)
$routes->get('register', 'Vault::register');
$routes->post('register/simpan', 'Vault::simpan_register');

// 2. Gateway saat KTA di-scan (Muncul Pop-up 2 Opsi)
$routes->get('scan/(:num)', 'Vault::scan_gateway/$1');

// 3. Opsi 1: Hologram AR
$routes->get('ar_hologram/(:num)', 'Vault::ar_hologram/$1');

// 4. Opsi 2: Download vCard Eksekutif
$routes->get('download_vcard/(:num)', 'Vault::download_vcard/$1');

// 5. Halaman Profil Kaca 3D (Tujuan Akhir)
$routes->get('profil/(:num)', 'Vault::profil/$1');