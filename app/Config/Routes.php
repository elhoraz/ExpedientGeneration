<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==========================================================
// 1. RUTE DEFAULT
// ==========================================================
// Landing page publik — redirect ke beranda jika sudah login
$routes->get('/', 'LandingController::index');

// ==========================================================
// 2. RUTE OTENTIKASI (LOGIN, REGISTER, VERIFIKASI)
// ==========================================================
// Halaman Login
$routes->get('/login', 'AuthController::index');
$routes->get('/auth/login', 'AuthController::index'); // Alias agar link 'Kembali' berfungsi
$routes->post('/auth/login', 'AuthController::processLogin', ['filter' => 'throttleauth']); // Proses cek password

// Halaman Register
$routes->get('/auth/register', 'AuthController::registerView'); // Menampilkan view
$routes->post('/auth/register', 'AuthController::register', ['filter' => 'throttleauth']);    // Memproses data submit

// Verifikasi Email & Logout
$routes->get('/auth/verify/(:segment)', 'AuthController::verifyEmail/$1'); 
$routes->get('/logout', 'AuthController::logout');

// Forgot Password (Reset Kata Sandi)
$routes->get('/auth/forgot-password', 'AuthController::forgotPasswordView');
$routes->post('/auth/forgot-password/send', 'AuthController::sendResetCode');
$routes->post('/auth/forgot-password/verify', 'AuthController::verifyResetCode');
$routes->post('/auth/forgot-password/reset', 'AuthController::resetPassword');

// ==========================================================
// 3. RUTE DASHBOARD / BERANDA
// ==========================================================
// Halaman PUBLIK (bisa diakses tanpa login)
$routes->get('/beranda', 'BerandaController::index');
$routes->get('/galeri', 'GaleriController::index');
$routes->get('/direktori', 'DirektoriController::index');

// Interaksi ANGGOTA (tetap butuh login)
$routes->post('/beranda/simpan_pesan', 'BerandaController::simpan_pesan', ['filter' => 'auth']);
$routes->get('/buku-tamu', 'BukuTamuController::index', ['filter' => 'auth']);
$routes->get('/fitur', 'FiturController::index', ['filter' => 'auth']);
$routes->get('/oracle', 'OracleController::index', ['filter' => 'auth']);
$routes->post('/oracle/store', 'OracleController::store', ['filter' => 'auth']);
$routes->post('/oracle/unlock/(:num)', 'OracleController::unlock/$1', ['filter' => 'auth']);
$routes->get('/enigma', 'EnigmaController::index', ['filter' => 'auth']);
$routes->post('/enigma/verify', 'EnigmaController::verify', ['filter' => 'auth']);
$routes->get('/enigma/reset', 'EnigmaController::reset', ['filter' => 'auth']);
$routes->get('/genesis', 'GenesisController::index', ['filter' => 'auth']);
$routes->post('/genesis/log', 'GenesisController::log', ['filter' => 'auth']);
$routes->get('/celestial', 'CelestialController::index', ['filter' => 'auth']);
$routes->get('/majlis', 'MajlisController::index', ['filter' => 'auth']);
$routes->post('/majlis/store', 'MajlisController::store', ['filter' => 'auth']);
$routes->post('/majlis/vote/(:num)', 'MajlisController::vote/$1', ['filter' => 'auth']);
$routes->post('/majlis/close/(:num)', 'MajlisController::close/$1', ['filter' => 'auth']);
$routes->get('/majlis/state', 'MajlisController::getState', ['filter' => 'auth']);
$routes->post('/majlis/raise_hand', 'MajlisController::raiseHand', ['filter' => 'auth']);
$routes->post('/majlis/approve_speaker', 'MajlisController::approveSpeaker', ['filter' => 'auth']);
$routes->post('/majlis/stop_speaker', 'MajlisController::stopSpeaker', ['filter' => 'auth']);
$routes->post('/pusher/auth', 'MajlisController::pusherAuth', ['filter' => 'auth']);
$routes->get('/event', 'EventController::index', ['filter' => 'auth']);
$routes->post('/event/store', 'EventController::store', ['filter' => 'auth']);
$routes->post('/event/rsvp/(:num)', 'EventController::rsvp/$1', ['filter' => 'auth']);
$routes->get('/tarbiyah', 'TarbiyahController::index', ['filter' => 'auth']);
$routes->post('/tarbiyah/request', 'TarbiyahController::request', ['filter' => 'auth']);
$routes->get('/baitul-maal', 'BaitulMaalController::index', ['filter' => 'auth']);
$routes->post('/baitul-maal/store', 'BaitulMaalController::store', ['filter' => 'auth']);
$routes->get('/wasiat', 'WasiatController::index', ['filter' => 'auth']);
$routes->post('/wasiat/store', 'WasiatController::store', ['filter' => 'auth']);
$routes->post('/wasiat/unlock/(:num)', 'WasiatController::unlock/$1', ['filter' => 'auth']);
$routes->get('/multazam', 'MultazamController::index', ['filter' => 'auth']);
$routes->post('/multazam/store', 'MultazamController::store', ['filter' => 'auth']);
// ================= FASILITAS: GLOBAL RADAR / PETA ALUMNI =================
// Menampilkan halaman Peta 3D
$routes->get('/radar', 'RadarController::index', ['filter' => 'auth']);
$routes->get('/radar/flat', 'RadarController::flatMap', ['filter' => 'auth']);
$routes->get('/radar/satellite', 'RadarController::satelliteMap', ['filter' => 'auth']);
$routes->get('/radar/terrain', 'RadarController::terrainMap', ['filter' => 'auth']);
$routes->get('/radar/dark', 'RadarController::darkMap', ['filter' => 'auth']);
$routes->get('/radar/watercolor', 'RadarController::watercolorMap', ['filter' => 'auth']);
$routes->get('/radar/classic', 'RadarController::classicMap', ['filter' => 'auth']);
$routes->get('/radar/natgeo', 'RadarController::natgeoMap', ['filter' => 'auth']);
$routes->get('/radar/voyager', 'RadarController::voyagerMap', ['filter' => 'auth']);
$routes->get('/radar/hybrid', 'RadarController::hybridMap', ['filter' => 'auth']);
$routes->get('/radar/graycanvas', 'RadarController::graycanvasMap', ['filter' => 'auth']);
$routes->get('/radar/hot', 'RadarController::hotMap', ['filter' => 'auth']);
$routes->get('/radar/googleterrain', 'RadarController::googleterrainMap', ['filter' => 'auth']);
$routes->get('/radar/esriclarity', 'RadarController::esriclarityMap', ['filter' => 'auth']);
$routes->get('/radar/nightnav', 'RadarController::nightnavMap', ['filter' => 'auth']);
$routes->get('/radar/googletransit', 'RadarController::googletransitMap', ['filter' => 'auth']);
$routes->get('/radar/physical', 'RadarController::physicalMap', ['filter' => 'auth']);
$routes->get('/radar/nasamarble', 'RadarController::nasamarbleMap', ['filter' => 'auth']);
$routes->get('/radar/googletraffic', 'RadarController::googletrafficMap', ['filter' => 'auth']);
$routes->get('/radar/navigation', 'RadarController::navigationMap', ['filter' => 'auth']);
$routes->get('/radar/esristreet', 'RadarController::esristreetMap', ['filter' => 'auth']);
$routes->get('/radar/toner', 'RadarController::tonerMap', ['filter' => 'auth']);
// Endpoint API (AJAX) untuk menerima dan menyimpan koordinat GPS dari HP User
$routes->post('/radar/update-location', 'RadarController::updateLocation', ['filter' => 'auth']);
$routes->get('/kontemplasi', 'KontemplasiController::index', ['filter' => 'auth']);
$routes->post('/kontemplasi/store', 'KontemplasiController::store', ['filter' => 'auth']);
// ================= FASILITAS: THE SYNDICATE =================
// Menampilkan galeri kartu VIP
$routes->get('/syndicate', 'SyndicateController::index', ['filter' => 'auth']);

// Menampilkan formulir pendaftaran Black Card (VIP Access)
$routes->get('/syndicate/create', 'SyndicateController::create', ['filter' => 'auth']);

// Endpoint POST untuk menyimpan data formulir ke database
$routes->post('/syndicate/store', 'SyndicateController::store', ['filter' => 'auth']);

// CRUD Syndicate: Edit, Update, Delete
$routes->get('/syndicate/edit/(:num)', 'SyndicateController::edit/$1', ['filter' => 'auth']);
$routes->post('/syndicate/update/(:num)', 'SyndicateController::update/$1', ['filter' => 'auth']);
$routes->post('/syndicate/delete/(:num)', 'SyndicateController::delete/$1', ['filter' => 'auth']);
// ================= FASILITAS: COMMAND CENTER (PROFIL) =================
// Menampilkan halaman profil dan pengaturan biometrik agen
$routes->get('/profil', 'ProfileController::index', ['filter' => 'auth']);
$routes->post('/profil/update', 'ProfileController::updateProfile', ['filter' => 'auth']);
$routes->post('/profil/change-password', 'ProfileController::changePassword', ['filter' => 'auth']);
$routes->post('/profil/delete-account', 'ProfileController::deleteAccount', ['filter' => 'auth']);
// ==========================================================
// 4. RUTE BIOMETRIK (DISELARASKAN DENGAN Api\BiometricApi)
// ==========================================================
// Proses Login Biometrik (Dari halaman depan)
$routes->get('/biometric/login-options', 'Api\BiometricApi::loginOptions', ['filter' => 'throttle']);
$routes->post('/biometric/login-verify', 'Api\BiometricApi::loginVerify', ['filter' => 'throttle']);

// Proses Mendaftarkan Biometrik Baru (Dari dalam dashboard/settings)
$routes->get('/biometric/register-options', 'Api\BiometricApi::registerOptions', ['filter' => 'auth']);
$routes->post('/biometric/register-verify', 'Api\BiometricApi::registerVerify', ['filter' => 'auth']);
// ====================================================================
// JALUR EKSKLUSIF: THE SOVEREIGN VAULT
// ====================================================================

// Sovereign ID Card 5D (Butuh login)
$routes->get('/sovereign', 'SovereignController::index', ['filter' => 'auth']);

// Gateway saat KTA di-scan (Publik — menggunakan public_token, bukan ID)
$routes->get('scan/(:alphanum)', 'VaultController::scan_gateway/$1');

// Opsi 1: Hologram AR (Publik)
$routes->get('ar_hologram/(:alphanum)', 'VaultController::ar_hologram/$1');

// Opsi 2: Download vCard Eksekutif (Publik)
$routes->get('download_vcard/(:alphanum)', 'VaultController::download_vcard/$1');

// Halaman Profil Kaca 3D / Dossier Publik
$routes->get('profil/(:alphanum)', 'VaultController::profil/$1');

// In-App QR Scanner
$routes->get('scanner', 'VaultController::scanner');

// ====================================================================
// P3: NOTIFICATION & CHAT
// ====================================================================
$routes->get('/notifications/unread', 'NotificationController::getUnread', ['filter' => 'auth']);
$routes->post('/notifications/read/(:num)', 'NotificationController::markAsRead/$1', ['filter' => 'auth']);

// Web Push API Subscriptions
$routes->get('/push/public-key', 'PushController::publicKey', ['filter' => 'auth']);
$routes->post('/push/subscribe', 'PushController::subscribe', ['filter' => 'auth']);

$routes->get('/chat', 'ChatController::index', ['filter' => 'auth']);
$routes->get('/chat/lounge', 'ChatController::lounge', ['filter' => 'auth']);
$routes->get('/chat/personal/(:num)', 'ChatController::personal/$1', ['filter' => 'auth']);
$routes->get('/chat/unread', 'ChatController::getUnreadCount', ['filter' => 'auth']);
$routes->post('/chat/read/(:num)', 'ChatController::markAsRead/$1', ['filter' => 'auth']);
$routes->post('/chat/send', 'ChatController::send', ['filter' => 'auth']);
$routes->post('/chat/delete/(:num)', 'ChatController::deleteMessage/$1', ['filter' => 'auth']);
$routes->get('/chat/load-more', 'ChatController::loadMore', ['filter' => 'auth']);

$routes->get('/nexus', 'NexusController::index', ['filter' => 'auth']);
$routes->get('/nexus/calculate', 'NexusController::calculateMatches', ['filter' => 'auth']);

$routes->get('/birthday', 'BirthdayController::index', ['filter' => 'auth']);
$routes->get('/birthday/(:num)', 'BirthdayController::show/$1', ['filter' => 'auth']);

// Expedient Wrapped (Recap Tahunan)
$routes->get('/wrapped', 'WrappedController::index', ['filter' => 'auth']);

// ====================================================================
// P4: ADMIN & OFFLINE
// ====================================================================
$routes->get('/offline', function() { return view('offline'); });

// Admin Dashboard Unlock (Harus login, tapi tidak kena filter admin)
$routes->get('/admin/unlock', 'Admin\UnlockController::index', ['filter' => 'auth']);
$routes->post('/admin/unlock', 'Admin\UnlockController::process', ['filter' => 'auth']);

$routes->get('/admin/dashboard', 'AdminController::index', ['filter' => 'admin']);

// Admin: User Management
$routes->get('/admin/users', 'AdminController::users', ['filter' => 'admin']);
$routes->post('/admin/users/role/(:num)', 'AdminController::updateRole/$1', ['filter' => 'admin']);
$routes->post('/admin/users/toggle/(:num)', 'AdminController::toggleActive/$1', ['filter' => 'admin']);

// Admin: Content Moderation
$routes->get('/admin/moderation', 'AdminController::moderation', ['filter' => 'admin']);
$routes->post('/admin/delete/(:segment)/(:num)', 'AdminController::deleteContent/$1/$2', ['filter' => 'admin']);

// Admin: Export CSV
$routes->get('/admin/export-csv', 'AdminController::exportCsv', ['filter' => 'admin']);

// Admin: CRUD Pengumuman
$routes->get('/admin/announcements', 'Admin\AnnouncementController::index', ['filter' => 'admin']);
$routes->get('/admin/announcements/create', 'Admin\AnnouncementController::create', ['filter' => 'admin']);
$routes->post('/admin/announcements/store', 'Admin\AnnouncementController::store', ['filter' => 'admin']);
$routes->get('/admin/announcements/edit/(:num)', 'Admin\AnnouncementController::edit/$1', ['filter' => 'admin']);
$routes->post('/admin/announcements/update/(:num)', 'Admin\AnnouncementController::update/$1', ['filter' => 'admin']);
$routes->post('/admin/announcements/delete/(:num)', 'Admin\AnnouncementController::delete/$1', ['filter' => 'admin']);

// Admin: CMS Manager
$routes->get('/admin/cms', 'Admin\CmsController::index', ['filter' => 'admin']);
$routes->post('/admin/cms/update', 'Admin\CmsController::update', ['filter' => 'admin']);
$routes->post('/admin/cms/save', 'Admin\CmsController::save_content', ['filter' => 'admin']);
$routes->post('/admin/cms/gallery/add', 'Admin\CmsController::addGallery', ['filter' => 'admin']);
$routes->post('/admin/cms/gallery/delete/(:num)', 'Admin\CmsController::deleteGallery/$1', ['filter' => 'admin']);
