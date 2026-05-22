# Implementation Plan — Expedient Generation Improvements

> **Scope**: 29 items dari code review (kecuali #4, #20, #22, #23, #26, #31)
> **Pendekatan**: Step-by-step, fase demi fase

---

## User Review Required

> [!IMPORTANT]
> Karena scope sangat besar (29 item), saya akan mengerjakan **Fase 1 (Critical Fixes)** dan **Fase 2 (Technical Debt)** terlebih dahulu — ini adalah perubahan yang bisa langsung dikerjakan pada source code yang ada. 
>
> **Fase 3 (Fitur Baru)** dan **Fase 4 (Infrastruktur)** membutuhkan database migration, testing environment, dan keputusan desain yang lebih kompleks — saya akan membuat rencana detailnya setelah Fase 1 & 2 selesai.

> [!WARNING]
> **Item #1 (Rotasi Kredensial)**: Saya bisa membersihkan file `.env` dari value sensitif dan memastikan `.env.example` aman, tapi **rotasi actual key** (buat app password Gmail baru, buat Pusher app baru) harus dilakukan manual oleh Anda di dashboard masing-masing provider.

## Open Questions

1. **Item #2 (IDOR Fix)**: Untuk mengganti ID publik ke UUID/token, perlu migrasi database baru (tambah kolom `public_token` di tabel `users`). Apakah boleh saya buat migrasi baru?
2. **Item #14 (Hapus kolom redundan `tempat_tanggal_lahir`)**: Ini membutuhkan migrasi untuk drop kolom. Ada view yang menggunakan kolom ini secara langsung?
3. **Beranda/Direktori/Galeri publik tanpa auth** (#6 di review): Apakah ini sengaja publik atau ingin ditambahkan filter auth?

---

## Fase 1: Critical Security Fixes

### #1 — Bersihkan Kredensial dari .env

#### [MODIFY] [.env](file:///c:/Users/ASUS/angkatan/.env)
- Hapus semua value sensitif, ganti dengan placeholder
- User harus mengganti manual setelahnya

#### [MODIFY] [.env.example](file:///c:/Users/ASUS/angkatan/.env.example)
- Pastikan template aman tanpa value asli

---

### #2 — Fix IDOR pada Public Endpoints

#### [NEW] `AddPublicTokenToUsers.php` migration
- Tambah kolom `public_token` (VARCHAR 64, UNIQUE) ke tabel `users`
- Generate random token untuk semua user yang sudah ada

#### [MODIFY] [VaultController.php](file:///c:/Users/ASUS/angkatan/app/Controllers/VaultController.php)
- Ubah semua method dari `find($id)` → lookup by `public_token`
- Gunakan `(:alphanum)` bukan `(:num)` di route

#### [MODIFY] [Routes.php](file:///c:/Users/ASUS/angkatan/app/Config/Routes.php)
- Ubah route pattern `scan/(:num)` → `scan/(:alphanum)` (4 route)

#### [MODIFY] [AuthService.php](file:///c:/Users/ASUS/angkatan/app/Services/AuthService.php)
- Generate `public_token` saat registrasi user baru

---

### #3 — Fix/Disable WebAuthn Bypass

#### [MODIFY] [BiometricApi.php](file:///c:/Users/ASUS/angkatan/app/Controllers/Api/BiometricApi.php)
- Tambahkan validasi challenge yang lebih ketat (hash comparison)
- Tambahkan rate limiting pada login-verify
- Tambahkan CSRF-equivalent protection (challenge + timestamp expiry)

---

### #5 — Fix Remember Me Duplikasi di AuthController

#### [MODIFY] [AuthController.php](file:///c:/Users/ASUS/angkatan/app/Controllers/AuthController.php)
- Hapus duplikasi auto-login remember me logic dari `index()` (line 26-40)
- Biarkan `AuthFilter` yang menangani semua auto-login

---

### #6 — Hapus Field Sensitif dari $allowedFields

#### [MODIFY] [UserModel.php](file:///c:/Users/ASUS/angkatan/app/Models/UserModel.php)
- Hapus `role`, `is_active`, `prestise_points` dari `$allowedFields`
- Field ini tetap bisa di-update via `$db->table('users')->update()` secara eksplisit

---

### #7 — Hapus TestEmail.php

#### [DELETE] [TestEmail.php](file:///c:/Users/ASUS/angkatan/app/Controllers/TestEmail.php)
- Hapus file test controller dari production

---

### #8 — Tambah Audit Log pada Admin deleteContent

#### [MODIFY] [AdminController.php](file:///c:/Users/ASUS/angkatan/app/Controllers/AdminController.php)
- Tambah `ActivityLogger::log()` di setiap case dalam `deleteContent()`
- Log format: `[ADMIN_DELETE] type:chat id:123`

---

## Fase 2: Technical Debt

### #9 — Konsolidasi Service Worker

#### [MODIFY] [sw.js](file:///c:/Users/ASUS/angkatan/public/sw.js)
- Merge logic terbaik dari kedua file (cache list dari `sw.js` + http check dari `service-worker.js`)

#### [DELETE] [service-worker.js](file:///c:/Users/ASUS/angkatan/public/service-worker.js)
- Hapus file duplikat

---

### #10 — Pindahkan Password Hashing ke AuthService

#### [MODIFY] [AuthService.php](file:///c:/Users/ASUS/angkatan/app/Services/AuthService.php)
- `registerUser()` menerima plain password, hash di dalam service

#### [MODIFY] [AuthController.php](file:///c:/Users/ASUS/angkatan/app/Controllers/AuthController.php)
- Hapus `password_hash()` dari controller, kirim plain password ke service

---

### #11 — Fix Transaction Handling

#### [MODIFY] [AuthController.php](file:///c:/Users/ASUS/angkatan/app/Controllers/AuthController.php)
- Di catch block, selalu `$db->transRollback()` tanpa cek kondisi

---

### #12 — Konsistensikan Flash Data Naming

#### [MODIFY] [ProfileController.php](file:///c:/Users/ASUS/angkatan/app/Controllers/ProfileController.php)
- Ganti `->with('pesan', ...)` → `->with('success', ...)`

#### [MODIFY] [template.php](file:///c:/Users/ASUS/angkatan/app/Views/layout/template.php)
- Hapus handling `'pesan'`, standardkan ke `success`/`error` saja
- Beri styling berbeda untuk error (merah) vs success (emas/hijau)

---

### #13 — Hapus Inline Style dari beranda.php

#### [MODIFY] [beranda.php](file:///c:/Users/ASUS/angkatan/app/Views/beranda.php)
- Ekstrak inline styles pada leaderboard, buku tamu, dan birthday toast ke CSS class

#### [MODIFY] [beranda.css](file:///c:/Users/ASUS/angkatan/public/assets/css/beranda.css) (atau buat baru)
- Tambah class: `.leaderboard-row`, `.leaderboard-rank`, `.leaderboard-avatar`, `.leaderboard-name`, `.leaderboard-score`, `.bday-toast`, `.buku-tamu-entry`, dll

---

### #14 — Hapus Redundant Column

#### [NEW] Migration `RemoveTempatTanggalLahirColumn.php`
- Drop kolom `tempat_tanggal_lahir` dari tabel `users`

#### [MODIFY] [AuthController.php](file:///c:/Users/ASUS/angkatan/app/Controllers/AuthController.php)
- Hapus assignment `tempat_tanggal_lahir` di `register()`

#### [MODIFY] [UserModel.php](file:///c:/Users/ASUS/angkatan/app/Models/UserModel.php)
- Hapus `tempat_tanggal_lahir` dari `$allowedFields`

---

### #15 — Lazy Load Pusher.js

#### [MODIFY] [template.php](file:///c:/Users/ASUS/angkatan/app/Views/layout/template.php)
- Pindahkan `<script src="/vendor/pusher/pusher.min.js">` ke dalam blok `<?php if ($isLoggedIn): ?>`
- Atau lebih baik: conditional load hanya saat ada fitur realtime

---

### #16 — Ganti exit dengan Response di exportCsv

#### [MODIFY] [AdminController.php](file:///c:/Users/ASUS/angkatan/app/Controllers/AdminController.php)
- Ganti `exit` dengan CI4 Response object
- Build CSV string in memory, return via `$this->response`

---

### #17 — Admin Query via Model

#### [MODIFY] [AdminController.php](file:///c:/Users/ASUS/angkatan/app/Controllers/AdminController.php)
- Ganti `$db->table('users')` → `$userModel->select(...)`
- Ganti `$db->table('users')->where('id', ...)` → `$userModel->find()` / `$userModel->update()`

---

## Fase 3: Fitur Baru (Outline — Detail setelah Fase 1&2)

| # | Fitur | Status |
|---|-------|--------|
| 18 | Implementasi Modul Placeholder (Wasiat sudah fungsional ✅, Baitul Maal perlu implementasi) | Akan didetailkan |
| 19 | Sistem Polling Real-time di Majlis | Akan didetailkan |
| 21 | Sistem Notifikasi Push (FCM) | Akan didetailkan |
| 24 | Export Kartu ID sebagai PDF/PNG | Akan didetailkan |
| 25 | QR Code Scanner In-App | Akan didetailkan |
| 27 | Recap Tahunan | Akan didetailkan |

---

## Fase 4: Infrastruktur (Outline)

| # | Item | Status |
|---|------|--------|
| 28 | Tingkatkan Test Coverage | Akan didetailkan |
| 29 | PHPStan Level 5 | Akan didetailkan |
| 30 | Migration Cleanup | Akan didetailkan |
| 32 | Rate Limiting Lebih Ketat | Akan didetailkan |
| 33 | Real Deployment Pipeline | Akan didetailkan |
| 34 | Monitoring & Error Tracking | Akan didetailkan |
| 35 | Enkripsi face_data | Akan didetailkan |

---

## Verification Plan

### Automated Tests
- `php spark test` setelah setiap perubahan
- Verifikasi migrasi: `php spark migrate` lalu `php spark migrate:rollback`

### Manual Verification
- Setiap perubahan route/controller akan diverifikasi dengan browser/curl
- Security fixes akan dicek secara manual (IDOR test, CSRF test)
- Pastikan tidak ada regresi pada UI/UX
