# 🔍 Kritik, Saran & Roadmap Pengembangan — Expedient Generation

> **Reviewer**: Deep Code Audit · **Tanggal**: 22 Mei 2026  
> **Cakupan**: Seluruh source code (`app/`, `public/`, `tests/`, `.github/`, `.env`)  
> **Metode**: Line-by-line review terhadap setiap Controller, Service, Model, Filter, View, dan config.

---

## 📊 Ringkasan Eksekutif

| Aspek | Skor | Catatan |
|-------|------|---------|
| Arsitektur | ⭐⭐⭐⭐ | Service layer sangat baik, tapi ada anti-pattern |
| Keamanan | ⭐⭐⚠️ | Ada 3 celah **kritis** yang harus segera diperbaiki |
| UI/UX | ⭐⭐⭐⭐⭐ | Desain sangat premium, micro-interactions luar biasa |
| Performa | ⭐⭐⭐ | Heavy front-end, perlu optimasi serius |
| Testing | ⭐⭐ | Coverage sangat rendah, CI ada tapi belum matang |
| Database | ⭐⭐⭐ | Fungsional tapi denormalisasi berlebihan |
| DevOps | ⭐⭐⭐ | CI/CD sudah ada framework-nya, deploy masih simulasi |

---

## 1. 🏗️ Arsitektur & Code Quality

### ✅ Yang Sudah Sangat Baik

1. **Service Layer yang konsisten** — 13 service class memisahkan business logic dari controller. Ini pola profesional yang jarang ditemukan di proyek CI4.
2. **DI sudah mulai digunakan** — `AuthController` dan `ProfileController` sudah menggunakan `service('authService')` alih-alih `new AuthService()`. Ini progress bagus.
3. **ActivityLogger sebagai audit trail** — Format log `[TIMESTAMP] [USER:ID] [ACTION] [IP]` sangat enterprise-grade.
4. **Cache invalidation yang eksplisit** — Setiap operasi write (register, update profil, update lokasi) menghapus cache terkait.
5. **GitHub Actions CI sudah ada** — `ci.yml` sudah setup MySQL service, migrations, dan PHPUnit.
6. **Deploy workflow sudah dipersiapkan** — `deploy.yml` dengan rsync pattern siap digunakan.

### ⚠️ Kritik Baru (Temuan dari Code Review)

#### 1.1 🔴 Duplikasi Logic Auto-Login Remember Me

Logic remember me token ada di **dua tempat** yang berbeda dan **tidak konsisten**:

```php
// AuthController::index() — LINE 26-40
// ❌ TIDAK cek expiry token!
$user = $userModel->where('remember_token', $token)->first();

// AuthFilter::before() — LINE 20-23
// ✅ Cek expiry token
$user = $userModel->where('remember_token', $token)
    ->where('remember_token_expires >', date('Y-m-d H:i:s'))
    ->first();
```

`AuthController::index()` **tidak mengecek** `remember_token_expires`, sehingga token yang sudah expired tetap bisa digunakan untuk auto-login saat user mengunjungi halaman login secara langsung. Ini adalah **security vulnerability**.

**Rekomendasi**: Pindahkan seluruh logic remember me ke satu tempat saja — idealnya hanya di `AuthFilter`. Hapus duplikasi dari `AuthController::index()`.

---

#### 1.2 🟡 Password Hashing Dilakukan di Controller, Bukan di Service

```php
// AuthController::register() — LINE 192
$dataUser['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
$this->authService->registerUser($dataUser);
```

Password di-hash **sebelum** dikirim ke `AuthService::registerUser()`. Ini melanggar prinsip bahwa business logic (termasuk password hashing) seharusnya ada di Service. Jika ada titik masuk registrasi lain di masa depan (misalnya admin invite), developer bisa lupa men-hash password.

**Rekomendasi**: Pindahkan `password_hash()` ke dalam `AuthService::registerUser()` dan kirimkan plain password ke service.

---

#### 1.3 🟡 Transaction Handling yang Salah

```php
// AuthController::register() — LINE 199-203
} catch (\Exception $e) { 
    if ($db->transStatus() === FALSE) {
        $db->transRollback();
    }
}
```

`$db->transStatus()` mengembalikan `FALSE` hanya jika transaksi sudah gagal. Tapi jika exception terjadi **sebelum** SQL gagal (misalnya pada email sending), status transaksi masih `TRUE` dan rollback **tidak akan terjadi** — data user sudah tersimpan meskipun ada error.

**Rekomendasi**: Selalu rollback di catch block tanpa cek kondisi:
```php
} catch (\Exception $e) {
    $db->transRollback(); // Selalu rollback jika ada exception
}
```

---

#### 1.4 🟡 Controller Menggunakan Raw DB Query Alih-Alih Model

`AdminController` menggunakan `$db->table('users')` secara langsung di banyak tempat (line 50, 110, 211) alih-alih menggunakan `UserModel`. Ini bypass semua model-level logic (`$allowedFields`, `$useTimestamps`, event callbacks).

```php
// AdminController::users() — LINE 50-52
$builder = $db->table('users')
    ->select('id, nama_lengkap, ...')  // ← bypasses UserModel
```

**Rekomendasi**: Gunakan `UserModel` dengan `->select()` chain. Jika perlu query kompleks, gunakan `$userModel->builder()`.

---

#### 1.5 🟡 `exit` di Controller

```php
// AdminController::exportCsv() — LINE 233
fclose($output);
exit;  // ← Menghentikan seluruh lifecycle CI4
```

`exit` dalam controller menghentikan execution CI4 secara kasar — `after` filters tidak berjalan (termasuk SecurityHeaders dan performance metrics). 

**Rekomendasi**: Gunakan CI4's `Response` download helper:
```php
return $this->response
    ->setHeader('Content-Type', 'text/csv; charset=utf-8')
    ->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"')
    ->setBody($csvContent);
```

---

#### 1.6 🟡 Dua Service Worker yang Konflik

Ada **dua** service worker file: `sw.js` dan `service-worker.js` di `public/`. Keduanya mengelola cache dengan nama berbeda dan strategy berbeda. Browser hanya bisa mendaftarkan satu scope — yang satunya menjadi dead code atau, lebih buruk, menyebabkan konflik caching.

**Rekomendasi**: Konsolidasi ke satu file. Hapus yang tidak dipakai.

---

#### 1.7 💡 Inkonsistensi Penamaan Flash Data

```php
// ProfileController — menggunakan 'pesan'
return redirect()->to('/profil')->with('pesan', 'Data berhasil diperbarui!');

// AuthController — menggunakan 'success' dan 'error'
return redirect()->to('/login')->with('success', 'Email berhasil diverifikasi!');

// Template.php — menangkap ketiganya
$flashMsg = session()->getFlashdata('pesan') ?? session()->getFlashdata('success') ?? session()->getFlashdata('error');
```

Template menangkap 3 key berbeda (`pesan`, `success`, `error`) tapi **tidak membedakan styling**. Semua ditampilkan sama. Harusnya `error` ditampilkan merah, `success` hijau.

---

## 2. 🔒 Keamanan

### ⚠️ Temuan Kritis Baru

#### 2.1 🔴 IDOR (Insecure Direct Object Reference) pada Public Endpoints

```php
// Routes.php — LINE 128-137 (endpoint PUBLIK, tanpa auth)
$routes->get('scan/(:num)', 'VaultController::scan_gateway/$1');
$routes->get('ar_hologram/(:num)', 'VaultController::ar_hologram/$1');
$routes->get('download_vcard/(:num)', 'VaultController::download_vcard/$1');
$routes->get('profil/(:num)', 'VaultController::profil/$1');
```

Semua endpoint ini menerima `$id` user sebagai **integer auto-increment**. Siapapun bisa menebak dan iterasi ID (1, 2, 3, ...) untuk:
- Melihat profil semua alumni
- Download vCard (nama, WA, email, alamat) semua alumni
- Mengakses data pribadi yang seharusnya hanya bisa diakses via QR scan

**Dampak**: **Data leak** seluruh anggota. Ini PII (Personally Identifiable Information) violation.

**Rekomendasi**: Gunakan UUID atau signed URL token alih-alih auto-increment ID:
```php
$routes->get('scan/(:alphanum)', 'VaultController::scan_gateway/$1');
// Di controller, lookup by public_token, bukan by id
```

---

#### 2.2 🔴 WebAuthn/FIDO2 — Verifikasi Palsu (Bypass Total)

```php
// BiometricApi::loginVerify() — LINE 71
if (isset($json->credential_id) && $json->credential_id === $user['webauthn_credential_id']) {
    // LOGIN BERHASIL ← Hanya cek credential_id string match!
}
```

Ini **BUKAN** implementasi WebAuthn yang valid. WebAuthn seharusnya:
1. Memverifikasi **digital signature** dari authenticator
2. Menggunakan **public key** untuk memvalidasi assertion
3. Menaikkan **sign_count** untuk mencegah replay attack

Implementasi saat ini hanya membandingkan string `credential_id` — siapapun yang tahu credential ID bisa login tanpa biometric. Credential ID **bukan rahasia** — ini seperti username.

**Dampak**: Seluruh sistem biometric authentication bisa di-bypass.

**Rekomendasi**: Gunakan library [web-auth/webauthn-lib](https://github.com/web-auth/webauthn-framework) untuk PHP, atau setidaknya:
1. Verifikasi signature dengan public key
2. Validasi challenge tidak expired
3. Increment sign_count

---

#### 2.3 🔴 `.env` File Ter-commit ke Git (Masih Aktif!)

`.env` file masih ada di repository (3261 bytes) meskipun sudah ada di `.gitignore` line 44. Ini berarti file **pernah di-commit sebelum** ditambahkan ke gitignore, dan masih tersimpan di Git history.

Kredensial yang ter-expose:
| Kredensial | Nilai | Risiko |
|-----------|-------|--------|
| SMTP Password | `todgczkssnngnmhg` | Email spoofing |
| Pusher Secret | `8e0776f18c208588b827` | Broadcast hijacking |
| Pusher Key | `15d2104c6127e1b85baa` | Channel subscription |
| DB Password | *(kosong)* | Root tanpa password |

> [!CAUTION]
> Kredensial ini harus dianggap **sudah kompromis**. Rotasi semua key dan password segera! Gunakan `git filter-branch` atau `git-filter-repo` untuk membersihkan dari Git history.

---

#### 2.4 🟡 Chat Image Upload — Tanpa Sanitasi Nama File

```php
// ChatController::send() — LINE 116-118
$newName = $imageFile->getRandomName();
$imageFile->move(FCPATH . 'uploads/chat/', $newName);
```

Meskipun nama file di-random, **tidak ada validasi bahwa file memang image** selain cek MIME type. MIME type bisa dipalsukan. Rekomendasi:
1. Gunakan `getimagesize()` untuk validasi riil
2. Re-encode gambar dengan GD/Imagick untuk strip metadata & payload
3. Serve gambar dari domain terpisah (anti XSS via image)

---

#### 2.5 🟡 Admin deleteContent Tanpa Audit Log

```php
// AdminController::deleteContent() — LINE 175-198
// Tidak ada ActivityLogger::log() — siapa yang menghapus apa tidak tercatat!
```

Admin bisa menghapus chat, majlis topic, syndicate entry, dan buku tamu **tanpa jejak audit**. Ini membuat accountability sulit.

---

#### 2.6 🟡 Beranda, Direktori, dan Galeri Tanpa Auth Filter

```php
// Routes.php — LINE 41-43
$routes->get('/beranda', 'BerandaController::index');  // ← Publik!
$routes->get('/galeri', 'GaleriController::index');     // ← Publik!
$routes->get('/direktori', 'DirektoriController::index'); // ← Publik!
```

Siapapun tanpa login bisa melihat:
- Seluruh daftar alumni (nama, foto, serial number) via `/direktori`
- Leaderboard prestise dengan nama dan foto
- Data buku tamu

Pertanyaan: Apakah ini **sengaja publik** atau kelupaan? Untuk platform alumni eksklusif, data anggota seharusnya dilindungi.

---

## 3. 🎨 UI/UX

### ✅ Yang Luar Biasa

Desain visual proyek ini **jauh di atas rata-rata** — glassmorphism yang proporsional, dual theme yang konsisten, tipografi premium, dan micro-interactions yang thoughtful (swipe-to-seal, haptic feedback, magnetic tilt cards). Ini bukan sekadar "proyek kampus" — ini **showcase-worthy**.

### ⚠️ Temuan Tambahan

#### 3.1 Inline Style Masif di beranda.php

Leaderboard section (line 148-168) menggunakan 15+ inline `style="..."` alih-alih CSS class. Ini membuat:
- Tidak bisa di-override oleh theme
- Tidak bisa di-cache terpisah
- Sulit di-maintain

Contoh terburuk — **satu div** dengan 5 properti inline + inline event handler:
```html
<div class="glass-panel reveal-up" 
     style="display:flex; align-items:center; justify-content:space-between; 
            padding:20px 30px; border-left:4px solid <?= ... ?>; transition:0.3s; cursor:default;" 
     onmouseover="this.style.transform='translateX(10px)'" 
     onmouseout="this.style.transform='translateX(0)'">
```

#### 3.2 Birthday Toast — Hanya Menampilkan User Pertama

```php
// beranda.php — LINE 271
<strong><?= esc($birthday_users[0]['nama_panggilan'] ...) ?></strong>
<?= count($birthday_users) > 1 ? ' dan ' . (count($birthday_users)-1) . ' entitas lainnya' : '' ?>
```

Jika ada 5 alumni berulang tahun, user hanya melihat nama orang pertama + "dan 4 entitas lainnya". Tidak ada cara melihat siapa saja tanpa klik ke halaman birthday. Pertimbangkan carousel atau expandable list di toast.

#### 3.3 Tidak Ada Empty State yang Baik

Beberapa view tidak menangani kondisi kosong dengan baik. Misalnya, jika tidak ada data leaderboard, section menghilang sepenuhnya tanpa pesan, yang bisa membingungkan.

---

## 4. ⚡ Performa

### Temuan Tambahan

#### 4.1 Beranda Memuat GSAP + ScrollTrigger untuk Semua User

```html
<!-- beranda.php — LINE 284-285 -->
<script src="/vendor/gsap/gsap.min.js"></script>
<script src="/vendor/gsap/ScrollTrigger.min.js"></script>
```

GSAP (~45KB) dan ScrollTrigger (~30KB) dimuat bahkan untuk halaman yang mungkin hanya punya 2 section. Tidak ada conditional loading.

#### 4.2 Face-api.js (~6MB Model Files) untuk Profil

Halaman profil memuat face-api.js plus model file (~6MB total) hanya untuk fitur "Biometric Gateway" yang bersifat gimmick (verifikasi visual, bukan keamanan nyata). Ini sangat berat untuk mobile.

#### 4.3 Pusher Script Dimuat Secara Global

```html
<!-- template.php — LINE 31 -->
<script src="/vendor/pusher/pusher.min.js"></script>
```

Pusher JS (~30KB) dimuat di **setiap halaman** meskipun hanya digunakan di chat dan notifikasi. Gunakan lazy-loading: muat hanya saat dibutuhkan.

---

## 5. 🗄️ Database

### Temuan Tambahan

#### 5.1 `allowedFields` Terlalu Permisif

```php
// UserModel.php — LINE 20-34
protected $allowedFields = [
    ..., 'role', 'is_active', 'prestise_points', ...
];
```

`role`, `is_active`, dan `prestise_points` ada di `$allowedFields` — artinya **mass assignment** bisa mengubah role user menjadi admin jika ada endpoint yang menerima input user secara langsung ke model. Meskipun saat ini tidak terjadi karena controller secara eksplisit memilih field, ini adalah **time bomb**.

**Rekomendasi**: Hapus field sensitif (`role`, `is_active`, `prestise_points`) dari `$allowedFields`. Gunakan `$userModel->update($id, ['role' => ...])` secara eksplisit saat perlu.

#### 5.2 Kolom Redundan

Tabel `users` memiliki:
- `tempat_lahir` (VARCHAR) 
- `tanggal_lahir` (DATE)
- `tempat_tanggal_lahir` (VARCHAR) — **gabungan string** dari keduanya!

```php
// AuthController::register() — LINE 178
'tempat_tanggal_lahir' => $this->request->getPost('tempat_lahir') . ', ' . 
    date('d F Y', strtotime($this->request->getPost('tanggal_lahir'))),
```

Kolom `tempat_tanggal_lahir` 100% redundan — bisa di-generate on-the-fly dari `tempat_lahir` dan `tanggal_lahir`. Ini melanggar normalisasi dan menambah risiko data tidak sinkron.

---

## 6. 🧪 Testing & CI/CD

### ✅ Yang Sudah Ada

- CI workflow (`ci.yml`) dengan MySQL service, migrations, dan PHPUnit ✓
- Deploy workflow template (`deploy.yml`) dengan rsync ✓
- 2 unit test file (AuthService, ProfileService) ✓

### ⚠️ Kritik

#### 6.1 Coverage Hanya ~4%

Dari 13 service dan 31 controller, hanya 2 service yang di-test. Nol controller test, nol integration test, nol API test.

#### 6.2 Deploy Workflow Masih Simulasi

```yaml
# deploy.yml — LINE 35-36
echo "Memulai deployment via Rsync..."
# Skrip asli dikomentari
echo "Deployment selesai!"
```

Deploy hanya echo "selesai" tanpa melakukan apa-apa. Ini memberi false confidence.

#### 6.3 Tidak Ada Lint Step di CI

CI workflow langsung run tests tanpa PHP CS Fixer atau PHPStan. Code style dan static analysis tidak divalidasi.

---

## 7. 💡 Roadmap Pengembangan

### Fase 1: 🚨 Critical Fixes (1-2 Minggu)

| # | Item | Prioritas | Detail |
|---|------|-----------|--------|
| 1 | **Rotasi semua kredensial** | 🔴 P0 | Gmail app password, Pusher key/secret. Bersihkan Git history dengan `git-filter-repo` |
| 2 | **Fix IDOR pada public endpoints** | 🔴 P0 | Ganti `(:num)` dengan UUID/token pada `scan/`, `ar_hologram/`, `profil/`, `download_vcard/` |
| 3 | **Fix WebAuthn bypass** | 🔴 P0 | Implementasi verifikasi signature, atau nonaktifkan fitur sampai benar-benar aman |
| 4 | **Tambah CSRF pada radar/update-location** | 🔴 P0 | Hapus exception di `Filters.php` line 81 |
| 5 | **Fix remember me di AuthController::index()** | 🟡 P1 | Tambah cek `remember_token_expires` atau delegasikan ke AuthFilter saja |
| 6 | **Hapus field sensitif dari $allowedFields** | 🟡 P1 | `role`, `is_active`, `prestise_points` |
| 7 | **Hapus TestEmail.php** | 🟡 P1 | File test controller masih ada di production |
| 8 | **Tambah audit log pada admin deleteContent** | 🟡 P1 | ActivityLogger::log() untuk setiap penghapusan |

---

### Fase 2: 🔧 Technical Debt (2-4 Minggu)

| # | Item | Detail |
|---|------|--------|
| 9 | **Konsolidasi Service Worker** | Gabungkan `sw.js` dan `service-worker.js` menjadi satu |
| 10 | **Pindahkan password hashing ke AuthService** | Single source of truth untuk password logic |
| 11 | **Fix transaction handling** | Selalu rollback di catch block |
| 12 | **Konsistensikan flash data naming** | Gunakan `success`/`error`/`warning` saja, hapus `pesan` |
| 13 | **Hapus inline style dari beranda.php** | Buat class CSS untuk leaderboard, birthday toast, buku tamu |
| 14 | **Hapus redundant column** | Drop `tempat_tanggal_lahir`, generate on-the-fly |
| 15 | **Lazy load Pusher.js** | Muat hanya saat user logged in DAN ada fitur realtime |
| 16 | **Ganti exit dengan Response di exportCsv** | Gunakan CI4 response object |
| 17 | **Admin query via Model** | Ganti `$db->table('users')` dengan `UserModel` di AdminController |

---

### Fase 3: ✨ Fitur Baru (1-3 Bulan)

| # | Fitur | Deskripsi | Kompleksitas |
|---|-------|-----------|-------------|
| 18 | **Implementasi Modul Placeholder** | 6 modul masih placeholder — prioritaskan **Wasiat** (pesan terenkripsi) dan **Baitul Maal** (keuangan angkatan) | 🔴 Tinggi |
| 19 | **Sistem Polling Real-time di Majlis** | Polling langsung di halaman Majlis Syura menggunakan Pusher Presence Channel (sudah ada dasar dari conversation sebelumnya) | 🟡 Sedang |
| 20 | **Album Foto Kolaboratif** | Alumni bisa upload foto kenangan sendiri ke galeri, dengan moderasi admin. Ganti foto Unsplash di Lorong Kenangan | 🟡 Sedang |
| 21 | **Sistem Notifikasi Push (FCM)** | PWA sudah ada manifest — tambahkan Firebase Cloud Messaging untuk push notification ulang tahun, event, dan chat | 🟡 Sedang |
| 22 | **Alumni Story/Status** | Mirip Instagram Story — alumni bisa posting status singkat yang expire setelah 24 jam. Cocok untuk kabar terbaru | 🟡 Sedang |
| 23 | **Job Board Alumni** | Papan lowongan kerja internal — alumni bisa posting lowongan di perusahaan mereka, yang lain bisa apply | 🟡 Sedang |
| 24 | **Export Kartu ID sebagai PDF/PNG** | Sovereign ID 3D sudah keren — tambahkan tombol export sebagai file gambar untuk dicetak | 🟢 Rendah |
| 25 | **QR Code Scanner In-App** | Scan QR code KTA alumni langsung dari PWA tanpa perlu app kamera terpisah | 🟢 Rendah |
| 26 | **Dark/Light Mode Schedule** | Auto switch tema berdasarkan waktu (siang/malam) atau system preference | 🟢 Rendah |
| 27 | **Recap Tahunan** | Fitur "Wrapped" ala Spotify — statistik personal alumni selama setahun (login, chat, prestise, dll) | 🟡 Sedang |

---

### Fase 4: 🏗️ Infrastruktur & Skalabilitas (3-6 Bulan)

| # | Item | Detail |
|---|------|--------|
| 28 | **Tingkatkan Test Coverage ke 60%+** | Prioritas: semua Service class, semua API endpoint, AuthFilter, AdminFilter |
| 29 | **Implementasi PHPStan Level 5** | Static analysis untuk deteksi bug sebelum runtime |
| 30 | **Database Migration Cleanup** | Konsolidasi 20 migrasi menjadi 5-7 migrasi bersih |
| 31 | **Image CDN / Storage** | Pindahkan foto profil dan galeri ke cloud storage (S3/Cloudflare R2) untuk performa |
| 32 | **Rate Limiting yang Lebih Ketat** | Implementasi rate limiting di semua POST endpoint, bukan hanya login dan API |
| 33 | **Real Deployment Pipeline** | Aktifkan rsync di `deploy.yml`, tambahkan staging environment |
| 34 | **Monitoring & Error Tracking** | Integrasikan Sentry atau Bugsnag untuk error tracking di production |
| 35 | **Enkripsi face_data dan biometric data** | Enkripsi at-rest menggunakan `sodium_crypto_secretbox` |

---

## 8. 🏆 Kesimpulan

### Apa yang membuat proyek ini istimewa:

Proyek ini **bukan** sekadar CRUD alumni biasa. Ini adalah platform digital dengan ambisi tinggi yang berhasil mengimplementasikan:
- 3D rendering (Three.js) untuk kartu ID dan hologram
- Globe visualization (Globe.gl) untuk persebaran alumni
- Real-time communication (Pusher/Soketi) untuk chat dan notifikasi
- Biometric authentication (WebAuthn) untuk login tanpa password
- Gamification system dengan poin prestise dan leaderboard
- Progressive Web App dengan offline support

Secara **arsitektur**, proyek ini sudah jauh melampaui ekspektasi — service layer yang bersih, audit logging, cache strategy, dan CI/CD pipeline. Ini menunjukkan pemahaman software engineering yang matang.

### Apa yang perlu segera diperbaiki:

Tiga kelemahan **fundamental** yang harus ditangani sebelum production:

1. **IDOR vulnerability** — Data alumni bisa diakses siapapun dengan iterasi ID
2. **WebAuthn bypass** — Fitur biometric bisa di-bypass karena verifikasi hanya string match
3. **Kredensial ter-expose** — Semua API key dan password harus dirotasi

### Rekomendasi prioritas 1 minggu ke depan:

```
1. Rotasi semua kredensial (30 menit)
2. Fix IDOR dengan UUID/token (2 jam)  
3. Disable biometric login sampai implementasi benar (30 menit)
4. Tambah CSRF pada radar endpoint (15 menit)
5. Hapus TestEmail.php (5 menit)
```

Setelah 5 fix di atas selesai, proyek ini layak untuk **soft-launch** ke kalangan internal dengan percaya diri. 🚀
